<?php

namespace Forge\Modules\ForgeStaticHtml;

use Forge\Core\Helpers\App;
use Forge\Http\Request;
use Forge\Modules\ForgeRouter\BasicRouter;

class StaticGenerator
{
    private BasicRouter $router;
    private string $outputDir;
    private array $config;

    public function __construct(array $config)
    {
        $this->router = App::router();
        $this->config = $config;
        $this->outputDir = BASE_PATH . '/' . $config['output_dir'];
    }

    public function generate(): void
    {
        if ($this->config['clean_build']) {
            $this->cleanOutputDir();
        }

        $this->generateRoutes();

        if ($this->config['copy_assets']) {
            $this->copyAssets();
        }
    }

    private function generateRoutes(): void
    {
        $routes = $this->router->getRoutes();

        foreach ($routes as $route) {
            if ($this->shouldGenerateRoute($route)) {
                $this->generateRouteOutput($route);
            } else {

            }
        }
        echo "Route generation completed.\n"; // Debug: Route generation complete message
    }

    private function shouldGenerateRoute(array $route): bool
    {
        $isGet = $route['method'] === 'GET';
        $isStatic = $this->isStaticRoute($route);
        $matchesPatterns = $this->matchesIncludePatterns($route['uri']);

        return $isGet && $isStatic && $matchesPatterns;
    }

    private function matchesIncludePatterns(string $uri): bool
    {
        $patterns = $this->config['include_paths'] ?? ['/'];

        // Special case: include everything
        if (in_array('/', $patterns, true)) {
            return true;
        }

        foreach ($patterns as $pattern) {
            $normalizedPattern = rtrim($pattern, '*') . '*';
            if (fnmatch($normalizedPattern, $uri) || $uri === $pattern) {
                return true;
            }
        }

        return false;
    }

    private function isStaticRoute(array $route): bool
    {
        $isStatic = !preg_match('/\{.*?\}/', $route['uri']) && strpos($route['uri'], '/_') !== 0;
        return $isStatic;
    }

    private function generateRouteOutput(array $route): void
    {
        $request = $this->createMockRequest($route['uri']);
        $response = $this->router->handleRequest($request);

        if ((int)$response->getStatusCode() === 200) {
            $html = $response->getContent();

            $filePath = $this->getOutputPath($route['uri']);
            $outputDir = dirname($filePath);

            if (!is_dir($outputDir)) {
                if (!mkdir($outputDir, 0755, true)) {
                    echo "  Error: Failed to create output directory: " . $outputDir . "\n";
                    return; // Stop processing this route if directory creation fails
                } else {
                }
            }

            file_put_contents($filePath, $html);


        } else {
            echo "  Warning: Non-200 status code (" . $response->getStatusCode() . ") for route: " . $route['uri'] . ". Skipping HTML save.\n";
        }
    }

    private function createMockRequest(string $uri): Request
    {
        $baseUrl = parse_url($this->config['base_url']);
        $host = $baseUrl['host'] ?? 'localhost';
        $scheme = $baseUrl['scheme'] ?? 'http';
        $port = $baseUrl['port'] ?? ($scheme === 'https' ? 443 : 80);

        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['REQUEST_URI'] = $uri;
        $_SERVER['HTTP_HOST'] = $host;
        $_SERVER['SERVER_NAME'] = $host;
        $_SERVER['SERVER_PORT'] = $port;
        $_SERVER['HTTPS'] = $scheme === 'https' ? 'on' : 'off';

        return Request::createFromGlobals();
    }

    private function getOutputPath(string $uri): string
    {
        $path = trim($uri, '/');
        $filename = $path ?: 'index';
        return "{$this->outputDir}/{$filename}/index.html";
    }

    private function cleanOutputDir(): void
    {
        if (is_dir($this->outputDir)) {
            $this->deleteDirectory($this->outputDir);
        }
        mkdir($this->outputDir, 0755, true);
    }

    private function copyAssets(): void
    {
        foreach ($this->config['asset_dirs'] as $assetDir) {
            $source = BASE_PATH . '/' . $assetDir;
            $dest = $this->outputDir . '/' . basename($assetDir);

            if (is_dir($source)) {
                $this->copyDirectory($source, $dest);
            }
        }
    }

    private function copyDirectory(string $src, string $dst): void
    {
        $dir = opendir($src);
        @mkdir($dst, 0755);

        while (($file = readdir($dir)) !== false) {
            if ($file != '.' && $file != '..') {
                $srcFile = "$src/$file";
                $destFile = "$dst/$file";

                if (is_dir($srcFile)) {
                    $this->copyDirectory($srcFile, $destFile);
                } else {
                    copy($srcFile, $destFile);
                }
            }
        }
        closedir($dir);
    }

    private function deleteDirectory(string $dir): void
    {
        $files = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($dir, \FilesystemIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::CHILD_FIRST
        );

        foreach ($files as $file) {
            if ($file->isDir()) {
                rmdir($file->getRealPath());
            } else {
                unlink($file->getRealPath());
            }
        }
        rmdir($dir);
    }
}