<?php

namespace Phi\Nodes;

use Phi\Nodes\Generated\GeneratedRootNode;
use PhpParser\Node\Stmt\InlineHTML;

class RootNode extends GeneratedRootNode
{
    public function convertToPhpParserNode()
    {
        $statements = $this->getStatements()->convertToPhpParserNode();

        if (isset($statements[0]) && $statements[0] instanceof InlineHTML && $statements[0]->value === "")
        {
            \array_shift($statements);
        }

        return $statements;
    }
}
