<?php

namespace Phi\Nodes\Expressions;

use Phi\Nodes\Expression;
use Phi\Nodes\Generated\GeneratedExecExpression;
use PhpParser\Node\Expr\ShellExec;
use PhpParser\Node\Scalar\EncapsedStringPart;

// TODO support multiple parts
class ExecExpression extends Expression
{
    use GeneratedExecExpression;

    public function convertToPhpParserNode()
    {
        return new ShellExec([
            new EncapsedStringPart($this->getCommand()->getSource())
        ]);
    }
}
