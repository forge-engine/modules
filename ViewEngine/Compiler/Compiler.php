<?php

namespace Forge\Modules\ViewEngine\Compiler;

use Forge\Modules\ViewEngine\Compiler\Directive\ExtendsDirective;
use Forge\Modules\ViewEngine\Compiler\Directive\SectionDirective;
use Forge\Modules\ViewEngine\Compiler\Directive\YieldDirective;

class Compiler
{
    protected array $directives = [
        'section' => SectionDirective::class,
        'yield' => YieldDirective::class,
        'extends' => ExtendsDirective::class,
    ];

    public function compile(string $content): string
    {
        $directives = $this->directives;
        krsort($directives);

        try {
            foreach ($directives as $name => $directive) {
                $content = (new $directive)->handle($content);
            }
            return $content;
        } catch (\Exception $e) {
            return "Compilation Error: " . $e->getMessage();
        }
    }
}
