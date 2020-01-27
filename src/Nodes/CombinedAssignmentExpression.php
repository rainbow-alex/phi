<?php

namespace Phi\Nodes;

use Phi\Exception\ValidationException;
use Phi\ExpressionClassification;
use Phi\Nodes\Generated\GeneratedCombinedAssignmentExpression;

class CombinedAssignmentExpression extends GeneratedCombinedAssignmentExpression
{
    public function validateContext(int $flags): void
    {
        $never = self::CTX_WRITE|self::CTX_ALIAS;
        if ($flags & $never)
        {
            throw ValidationException::expressionContext($flags & $never, $this);
        }

        $this->getLvalue()->validateContext(self::CTX_READ_OR_IMPLICIT_ALIAS_READ);

        if (ExpressionClassification::isTemporary($this->getLvalue()))
        {
            throw new ValidationException(__METHOD__, $this);
        }

        $this->getValue()->validateContext(self::CTX_READ);
    }
}
