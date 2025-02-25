<?php

namespace Forge\Modules\ViewEngine\Compiler\Directive;

use Forge\Modules\ViewEngine\Contracts\DirectiveInterface;

class YieldDirective implements DirectiveInterface
{
    public function handle(string $content): string
    {
        return preg_replace_callback(
            '/@yield\([\'"](.+?)[\'"]\)/',
            function ($matches) {
                return "<?= \$__sections['{$matches[1]}'] ?? '' ?>";
            },
            $content
        );
    }
}
