<?php

namespace Forge\Modules\Database\Adapters;

use Forge\Modules\Database\Contracts\DatabaseInterface;
use PDO;
use PDOException;

class SqliteAdapter implements DatabaseInterface
{
    private ?PDO $pdo = null;

    public function connect(array $config): void
    {
        $dsn = "sqlite:" . $config['database'];
        try {
            $this->pdo = new PDO($dsn, null, null, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_PERSISTENT => true,
            ]);
        } catch (PDOException $e) {
            throw new \RuntimeException("Database connection failed: " . $e->getMessage(), 0, $e);
        }
    }

    public function query(string $sql, array $params = []): array
    {
        $this->ensureConnected();
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function execute(string $sql, array $params = []): int
    {
        $this->ensureConnected();
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->rowCount();
    }

    public function beginTransaction(): void
    {
        $this->ensureConnected();
        $this->pdo->beginTransaction();
    }

    public function commit(): void
    {
        $this->ensureConnected();
        $this->pdo->commit();
    }

    public function rollback(): void
    {
        $this->ensureConnected();
        $this->pdo->rollBack();
    }

    public function lastInsertId(): string
    {
        $this->ensureConnected();
        return $this->pdo->lastInsertId();
    }

    private function ensureConnected(): void
    {
        if (!$this->pdo) {
            throw new \RuntimeException("Database connection not established. Call connect() first.");
        }
    }
}