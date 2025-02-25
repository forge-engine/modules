<?php

namespace Forge\Modules\ForgeOrm\Migrations;

use Forge\Core\Helpers\App;
use Forge\Core\Traits\OutputHelper;
use Forge\Modules\Database\Contracts\DatabaseInterface;
use Forge\Modules\ForgeOrm\Schema\Blueprint;
use Forge\Modules\ForgeOrm\Schema\Schema;

class MigrationManager
{
    use OutputHelper;

    private const MIGRATION_TABLE = 'forge_migrations';

    private DatabaseInterface $db;
    private Schema $schema;

    public function __construct(DatabaseInterface $db, Schema $schema)
    {
        $this->db = $db;
        $this->schema = $schema;
        $this->ensureMigrationTableExists();
    }

    public function runMigrations(): void
    {
        $env = App::env('APP_ENV');
        if ($env === 'production') {
            $this->error('Migration cant run in production');
            exit(0);
        }
        $this->db->beginTransaction();

        try {
            foreach ($this->getPendingMigrations() as $migrationDetails) {
                $this->runMigration($migrationDetails);
            }
            $this->db->commit();
        } catch (\Throwable $e) {
            $this->db->rollback();
            throw $e;
        }
    }

    public function rollbackLastMigration(): void
    {
        $env = App::env('APP_ENV');
        if ($env === 'production') {
            $this->error('Migration cant run in production');
            exit(0);
        }

        $this->db->beginTransaction();
        try {
            $lastMigrationName = $this->getLastMigration();
            if ($lastMigrationName) {
                $allMigrations = $this->getAllMigrations();
                if (isset($allMigrations[$lastMigrationName])) {
                    $lastMigrationPath = $allMigrations[$lastMigrationName];
                    $migrationDetails = ['name' => $lastMigrationName, 'path' => $lastMigrationPath];
                    $this->rollbackMigration($migrationDetails);
                }
            }
            $this->db->commit();
        } catch (\Throwable $e) {
            $this->db->rollback();
            throw $e;
        }
    }

    private function ensureMigrationTableExists(): void
    {
        if (!$this->schema->hasTable(self::MIGRATION_TABLE)) {
            $this->schema->create(self::MIGRATION_TABLE, function (Blueprint $table) {
                $table->integer('id', true);
                $table->integer('batch');
                $table->timestamps();
            });
        }
    }

    private function getPendingMigrations(): array
    {
        $ranMigrations = $this->getRanMigrationNames(); // Get just names of ran migrations
        $allMigrations = $this->getAllMigrations();

        $pendingMigrations = [];
        foreach ($allMigrations as $migrationName => $migrationInfo) { // Loop through all migrations by name
            if (!in_array($migrationName, $ranMigrations)) { // Check if name is in ran migrations
                $pendingMigrations[$migrationName] = $migrationInfo; // Keep the full info array
            }
        }
        return $pendingMigrations;
    }

    private function getRanMigrationNames(): array // Get only names of ran migrations
    {
        $results = $this->db->query(
            "SELECT id FROM " . self::MIGRATION_TABLE
        );
        return array_column($results, 'id'); // Extract just the 'id' (which is migration name) column
    }


    private function getAllMigrations(): array
    {
        return array_merge(
            $this->discoverModuleMigrations(),
            $this->discoverAppMigrations()
        );
    }

    private function discoverModuleMigrations(): array
    {
        $migrations = [];
        $modulesPath = BASE_PATH . '/modules/';

        foreach (glob($modulesPath . '*/Database/Migrations/*.php') as $file) {
            $migrationName = $this->getMigrationNameFromFilePath($file);
            $this->log($migrationName);
            $namespace = $this->getMigrationNamespaceFromFilePath($file, 'Modules');
            $this->log($namespace);
            $className = $this->getClassName($migrationName, $namespace);
            $this->log($className);
            $migrations[$migrationName] = ['name' => $migrationName, 'path' => $file, 'class' => $className, 'namespace' => $namespace];
            $this->printArray($migrations[$migrationName]);
        }
        return $migrations;
    }

    private function discoverAppMigrations(): array
    {
        $migrations = [];
        $appsPath = BASE_PATH . '/apps/';

        foreach (glob($appsPath . '*/Database/Migration/*.php') as $file) {
            $migrationName = $this->getMigrationNameFromFilePath($file);
            $namespace = $this->getMigrationNamespaceFromFilePath($file, 'Apps');
            $className = $this->getClassName($migrationName, $namespace);
            $migrations[$migrationName] = ['name' => $migrationName, 'path' => $file, 'class' => $className, 'namespace' => $namespace];
        }
        return $migrations;
    }

    private function getMigrationNameFromFilePath(string $path): string
    {
        return basename($path, '.php');
    }

    private function getMigrationNamespaceFromFilePath(string $filePath, string $type): string
    {
        $segments = explode(DIRECTORY_SEPARATOR, $filePath);
        $moduleOrAppName = $segments[array_search('modules', $segments) + 1] ?? $segments[array_search('apps', $segments) + 1] ?? '';
        return "{$type}\\{$moduleOrAppName}\\Database\\Migrations";
    }


    private function getClassName(string $filename, string $namespace): string
    {
        $className = preg_replace("/^\d+_/", "", $filename);
        $className = str_replace("_", "", ucwords($className, "_"));
        return "{$namespace}\\{$className}";
    }

    private function normalizePath(string $path): string
    {
        return rtrim(
            preg_replace("/[\/\\\\]+/", DIRECTORY_SEPARATOR, $path),
            DIRECTORY_SEPARATOR
        );
    }

    private function runMigration(array $migration): void
    {
        $this->debug("Running migration: " . $migration['name']);
        $this->debug("Migration File Path: " . $migration['path']);
        $this->debug("Class Name: " . $migration['class']); // Debug: Full Class Name

        if (!file_exists($migration['path'])) {
            $this->error("Error: Migration file not found at path: " . $migration['path']);
            return;
        }

        require_once $migration['path'];

        // Force autoloader to try and load the class again after require_once
        try {
            \Forge\Core\Bootstrap\Autoloader::load($migration['class']); // Explicitly trigger autoloader
            $this->debug("Autoloader::load() called explicitly for: " . $migration['class']); // Debug autoloader call
        } catch (\Throwable $autoloaderException) {
            $this->error("Autoloader::load() Exception: " . $autoloaderException->getMessage()); // Debug autoloader exception
        }


        if (!class_exists($migration['class'])) { // Use full class name from $migration['class']
            $this->error("Error: Class '{$migration['class']}' does NOT exist after require_once and Autoloader::load().");
            return;
        } else {
            $this->success("Success: Class '{$migration['class']}' DOES exist after require_once and Autoloader::load().");
        }

        $instance = new $migration['class'](); // Instantiate using full class name
        $instance->up();
        $this->recordMigration($migration['name']);
    }

    private function rollbackMigration(array $migration): void
    {
        $migrationFile = $migration['path'];
        require_once $migrationFile;
        $className = $migration['class']; // Use full class name from $migration array

        $instance = new $className();
        $instance->down();
        $this->removeMigrationRecord($migration['name']);
    }


    private function recordMigration(string $migration): void
    {
        $this->db->execute(
            "INSERT INTO " . self::MIGRATION_TABLE . " (id, batch, created_at) VALUES (?, ?, ?)",
            [$migration, $this->getNextBatchNumber(), date('Y-m-d H:i:s')]
        );
    }

    private function removeMigrationRecord(string $migration): void
    {
        $this->db->execute(
            "DELETE FROM " . self::MIGRATION_TABLE . " WHERE id = ?",
            [$migration]
        );
    }

    private function getLastMigration(): ?string
    {
        $result = $this->db->query(
            "SELECT id FROM " . self::MIGRATION_TABLE . " ORDER BY created_at DESC LIMIT 1"
        );

        return $result[0]['id'] ?? null;
    }

    private function getNextBatchNumber(): int
    {
        $result = $this->db->query(
            "SELECT MAX(batch) as max_batch FROM " . self::MIGRATION_TABLE
        );

        return ($result[0]['max_batch'] ?? 0) + 1;
    }
}