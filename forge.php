#!/usr/bin/env php
<?php

declare(strict_types=1);

// Command handling
$command = $_SERVER['argv'][1] ?? 'help';
$arguments = array_slice($_SERVER['argv'], 2);

switch ($command) {
    case 'publish':
        handlePublishCommand($arguments);
        break;
    case 'help':
    default:
        displayHelp();
        break;
}

function displayHelp(): void
{
    echo "Forge Module Publisher\n";
    echo "Usage: php forge.php <command> [arguments]\n\n";
    echo "Commands:\n";
    echo "  publish <module-name>@<version>  Package and publish a module version.\n";
    echo "  help                              Display this help message.\n";
}

function handlePublishCommand(array $arguments): void
{
    if (count($arguments) !== 1) {
        echo "Error: 'publish' command requires one argument: <module-name>@<version>\n";
        displayHelp();
        exit(1);
    }

    $moduleVersionString = $arguments[0];
    if (!preg_match('/^([a-z0-9\-]+)@([0-9\.]+)/', $moduleVersionString, $matches)) {
        echo "Error: Invalid module version format. Use <module-name>@<version> (e.g., forge-router@1.0.0)\n";
        exit(1);
    }

    $moduleName = $matches[1];
    $version = $matches[2];

    echo "Publishing module: {$moduleName} version {$version}\n";

    $moduleVersionPath = __DIR__ . '/modules/' . $moduleName . '/' . $version;
    $zipFilePath = $moduleVersionPath . '/' . $version . '.zip';

    if (!is_dir($moduleVersionPath)) {
        echo "Error: Module version path not found: {$moduleVersionPath}\n";
        exit(1);
    }

    echo "Creating zip file: {$zipFilePath}\n";
    if (createModuleZip($moduleVersionPath, $zipFilePath)) {
        echo "Zip file created successfully.\n";

        // Git operations
        echo "Staging zip file with git...\n";
        if (gitAdd($zipFilePath)) {
            echo "Zip file staged.\n";

            echo "Committing changes with git...\n";
            if (gitCommit("Add zip for {$moduleName}@{$version}")) {
                echo "Changes committed.\n";

                echo "Pushing changes to git repository...\n";
                if (gitPush()) {
                    echo "Changes pushed successfully.\n";
                    echo "Module {$moduleName} version {$version} published!\n";
                    exit(0);
                } else {
                    echo "Error: Git push failed.\n";
                    exit(1);
                }
            } else {
                echo "Error: Git commit failed.\n";
                exit(1);
            }
        } else {
            echo "Error: Git add failed.\n";
            exit(1);
        }


    } else {
        echo "Error: Failed to create zip file.\n";
        exit(1);
    }
}


function createModuleZip(string $sourceDir, string $zipFilePath): bool
{
    $zip = new ZipArchive();
    if ($zip->open($zipFilePath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
        return false;
    }

    $files = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($sourceDir),
        RecursiveIteratorIterator::LEAVES_ONLY
    );

    foreach ($files as $name => $file) {
        if (!$file->isDir()) {
            $filePath = $file->getRealPath();
            $relativePath = substr($filePath, strlen($sourceDir) + 1);
            $zip->addFile($filePath, $relativePath);
        }
    }

    return $zip->close();
}


function gitAdd(string $filePath): bool
{
    $command = "git add " . escapeshellarg($filePath);
    exec($command, $output, $returnVar);
    return $returnVar === 0;
}

function gitCommit(string $message): bool
{
    $command = "git commit -m " . escapeshellarg($message);
    exec($command, $output, $returnVar);
    return $returnVar === 0;
}

function gitPush(): bool
{
    $command = "git push";
    exec($command, $output, $returnVar);
    return $returnVar === 0;
}