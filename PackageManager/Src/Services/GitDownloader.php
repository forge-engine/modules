<?php

namespace Forge\Modules\PackageManager\Src\Services;

use Forge\Core\Traits\OutputHelper;

class GitDownloader
{
    use OutputHelper;

    private const TMP_DIR = __DIR__ . '/../../tmp/';
    private const GITHUB_API = 'https://api.github.com/repos/%s/contents/';

    public function getRepoModules(string $repoUrl): array
    {
        $repoPath = $this->downloadRepo($repoUrl);
        return $this->discoverModules($repoPath);
    }

    private function downloadRepo(string $repoUrl): string
    {
        $tempDir = self::TMP_DIR . uniqid('repo_', true);
        mkdir($tempDir, 0755, true);

        $zipUrl = $this->getZipUrl($repoUrl);
        $zipPath = $tempDir . '/repo.zip';

        $this->downloadFile($zipUrl, $zipPath);
        $this->extractZip($zipPath, $tempDir);

        return $tempDir;
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