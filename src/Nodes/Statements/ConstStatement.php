<?php

declare(strict_types=1);

namespace Phi\Nodes\Statements;

use Phi\Exception\ValidationException;
use Phi\Nodes\Generated\GeneratedConstStatement;
use Phi\Nodes\Statement;

class ConstStatement extends Statement
{
    use GeneratedConstStatement;

    protected function extraValidation(int $flags): void
    {
        if (!$this->getValue()->isConstant())
        {
            throw ValidationException::invalidExpressionInContext($this->getValue());
        }
    }
}
