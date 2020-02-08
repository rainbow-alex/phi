<?php

declare(strict_types=1);

namespace Phi\Nodes\Statements;

use Phi\Nodes\Generated\GeneratedInlineHtmlStatement;
use Phi\Nodes\Statement;
use PhpParser\Node\Stmt\InlineHTML;

class InlineHtmlStatement extends Statement
{
    use GeneratedInlineHtmlStatement;

    public function convertToPhpParserNode()
    {
        $content = $this->getContent();
        return new InlineHTML($content ? $content->getSource() : "");
    }
}
