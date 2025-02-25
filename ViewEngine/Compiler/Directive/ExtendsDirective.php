<?php

namespace Forge\Modules\ViewEngine\Compiler\Directive;

use Forge\Modules\ViewEngine\Contracts\DirectiveInterface;

class ExtendsDirective implements DirectiveInterface
{
    public function handle(string $content): string
    {
        return preg_replace_callback(
            '/@extends\([\'"](.+?)[\'"]\)/',
            function ($matches) {
                return "<?php \$__parent = '{$matches[1]}'; ?>";
            },
            $content
        );
    }
}
