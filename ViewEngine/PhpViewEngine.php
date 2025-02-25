<?php

namespace Forge\Modules\ViewEngine;

use Forge\Http\Response;
use Forge\Modules\ViewEngine\Compiler\Compiler;
use Forge\Core\Contracts\Modules\ViewEngineInterface;

class PhpViewEngine implements ViewEngineInterface
{
    private ViewFinder $finder;
    private Compiler $compiler;
    private string $compiledPath;

    /**
     * @param array<int,mixed> $config
     */
    public function __construct(
        private array  $config,
        private string $basePath
    )
    {
        $this->finder = new ViewFinder(
            $this->config['paths'] ?? [],
            $this->basePath
        );

        $this->compiler = new Compiler();
        $this->compiledPath = $basePath . '/' . $config['cache'];
    }

    public function render(string $view, array $data = [], bool $render_as_string = false): Response
    {
        $path = $this->finder->find($view);

        // Inject view into DebugBar if installed and enabled
        if (class_exists('\Forge\Modules\DebugBar\Collectors\ViewCollector')) {
            \Forge\Modules\DebugBar\Collectors\ViewCollector::instance()->addView($path, $data);
        }

        //$compiled = $this->compile($path);
        extract($data);
        if ($render_as_string) {
            ob_start();
            require_once $path;
            $content = ob_get_clean();
            return (new Response())->html($content);
        } else {
            include $path;
            $response = new Response();
            $response->setHeader('Content-Type', 'text/html');
            return $response;
        }
    }

    public function exists(string $view): bool
    {
        return $this->finder->find($view) !== null;
    }

    private function compile(string $path): string
    {
        $hash = md5_file($path);
        $compiled = "{$this->compiledPath}/{$hash}.php";

        if (!file_exists($compiled)) {
            $content = file_get_contents($path);
            $compiledContent = $this->compiler->compile($content);
            file_put_contents($compiled, $compiledContent);
        }

        return $compiled;
    }
}
