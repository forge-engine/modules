<?php

namespace Forge\Modules\PackageManager\Src\Services;

use Forge\Core\Traits\OutputHelper;

class GitDownloader
{
    use OutputHelper;

    private const TMP_DIR = BASE_PATH . '/storage/framework/tmp/';
    private const CACHE_DIR = BASE_PATH . '/storage/framework/modules/cache/';
    private const GITHUB_API = 'https://api.github.com/repos/%s/contents/';
    private int $cacheTtl;

    public function __construct(int $cacheTtl = 3600)
    {
        $this->cacheTtl = $cacheTtl;
        $this->ensureDirExists(self::CACHE_DIR);
        $this->ensureDirExists(self::TMP_DIR);
    }

    public function getRepoModules(string $repoUrl): array
    {
        $repoPath = $this->downloadRepo($repoUrl);
        return $this->discoverModules($repoPath);
    }

    private function downloadRepo(string $repoUrl): string
    {
        $cacheKey = $this->getCacheKey($repoUrl);
        $cachedPath = $this->getCachedPath($cacheKey);

        if ($this->isCacheValid($cachedPath)) {
            $this->info("Using cached repository: " . basename($cachedPath));
            return $cachedPath;
        }

        $tempDir = $this->createTempDir();
        $this->downloadAndCache($repoUrl, $tempDir, $cachedPath);

        return $cachedPath;
    }

    private function getCacheKey(string $repoUrl): string
    {
        return hash('sha256', $repoUrl);
    }

    private function getCachedPath(string $cacheKey): string
    {
        return self::CACHE_DIR . $cacheKey . '/';
    }

    private function isCacheValid(string $path): bool
    {
        if (!file_exists($path)) {
            return false;
        }

        $lastUpdated = filemtime($path);
        return (time() - $lastUpdated) < $this->cacheTtl;
    }

    private function createTempDir(): string
    {
        $tempDir = self::TMP_DIR . uniqid('repo_', true);
        mkdir($tempDir, 0755, true);
        return $tempDir;
    }

    private function downloadAndCache(string $repoUrl, string $tempDir, string $cachedPath): void
    {
        try {
            // Download and extract
            $zipUrl = $this->getZipUrl($repoUrl);
            $zipPath = $tempDir . '/repo.zip';
            $this->downloadFile($zipUrl, $zipPath);
            $this->extractZip($zipPath, $tempDir);

            // Get extracted contents
            $extractedDir = $this->getExtractedDir($tempDir);

            // Update cache
            $this->removeDirectory($cachedPath);
            rename($extractedDir, $cachedPath);
            touch($cachedPath);

            $this->info("Cached repository: " . basename($cachedPath));
        } finally {
            $this->removeDirectory($tempDir);
        }
    }

    private function getExtractedDir(string $tempDir): string
    {
        $dirs = glob($tempDir . '/*', GLOB_ONLYDIR);
        if (empty($dirs)) {
            throw new \RuntimeException("No directories found in extracted zip");
        }
        return $dirs[0];
    }

    private function removeDirectory(string $path): void
    {
        if (!file_exists($path)) return;

        $files = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($path, \RecursiveDirectoryIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::CHILD_FIRST
        );

        foreach ($files as $file) {
            if ($file->isDir()) {
                rmdir($file->getRealPath());
            } else {
                unlink($file->getRealPath());
            }
        }

        rmdir($path);
    }

    private function ensureDirExists(string $path): void
    {
        if (!is_dir($path) && !mkdir($path, 0755, true)) {
            throw new \RuntimeException("Failed to create directory: $path");
        }
    }

    private function getZipUrl(string $repoUrl): string
    {
        if (strpos($repoUrl, 'github.com') !== false) {
            $repoPath = parse_url($repoUrl, PHP_URL_PATH);
            return "https://github.com$repoPath/archive/refs/heads/main.zip";
        }

        throw new \RuntimeException("Unsupported repository host");
    }

    private function downloadFile(string $url, string $destination): void
    {
        $context = stream_context_create([
            'http' => ['header' => "User-Agent: Forge-Package-Manager\r\n"]
        ]);

        if (!copy($url, $destination, $context)) {
            throw new \RuntimeException("Failed to download repository");
        }
    }

    private function extractZip(string $zipPath, string $extractPath): void
    {
        $zip = new \ZipArchive();
        $zip->open($zipPath);
        $zip->extractTo($extractPath);
        $zip->close();
    }

    private function discoverModules(string $repoPath): array
    {
        $modules = [];
        $dirIterator = new \RecursiveDirectoryIterator(
            $repoPath,
            \FilesystemIterator::SKIP_DOTS
        );

        foreach (new \RecursiveIteratorIterator($dirIterator) as $file) {
            if ($file->getFilename() === 'forge.json') {
                $manifest = json_decode(file_get_contents($file), true);
                $modulePath = dirname($file->getRealPath());
                $modules[$manifest['name']] = [
                    'path' => $modulePath,
                    'manifest' => $manifest
                ];
            }
        }

        return $modules;
    }
}