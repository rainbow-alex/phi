<?php

namespace Phi\Nodes;

use Phi\Nodes\Generated\GeneratedInlineHtmlStatement;
use PhpParser\Node\Stmt\InlineHTML;

class InlineHtmlStatement extends GeneratedInlineHtmlStatement
{
    public function convertToPhpParserNode()
    {
        return new InlineHTML(($content = $this->getContent()) ? $content->getSource() : "");
    }
}
