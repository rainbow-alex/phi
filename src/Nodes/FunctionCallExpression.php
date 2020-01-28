<?php

namespace Phi\Nodes;

use Phi\Exception\ValidationException;
use Phi\ExpressionClassification;
use Phi\Nodes\Generated\GeneratedFunctionCallExpression;
use PhpParser\Node\Expr\FuncCall;

class FunctionCallExpression extends GeneratedFunctionCallExpression
{
    public function validateContext(int $flags): void
    {
        $never = self::CTX_WRITE|self::CTX_ALIAS_WRITE;
        if ($flags & $never)
        {
            throw ValidationException::expressionContext($flags & $never, $this);
        }

        $this->getCallee()->validateContext(self::CTX_READ);

        if (!ExpressionClassification::isCallable($this->getCallee()))
        {
            throw new ValidationException(__METHOD__, $this); // TODO
        }

        foreach ($this->getArguments() as $argument)
        {
            $argument->validateContext();
        }
    }

    public function convertToPhpParserNode()
    {
        $callee = $this->getCallee();
        if ($callee instanceof NameExpression)
        {
            $callee = $callee->getName();
        }
        return new FuncCall($callee->convertToPhpParserNode(), $this->getArguments()->convertToPhpParserNode());
    }
}
