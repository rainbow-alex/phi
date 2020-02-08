<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions;

use Phi\Exception\ValidationException;
use Phi\Nodes\Expression;
use Phi\Nodes\Generated\GeneratedAnonymousFunctionExpression;
use Phi\Nodes\Helpers\Parameter;
use PhpParser\Node\Expr\Closure;

class AnonymousFunctionExpression extends Expression
{
    use GeneratedAnonymousFunctionExpression;

    protected function extraValidation(int $flags): void
    {
        foreach (\array_slice($this->getParameters()->getItems(), 0, -1) as $parameter)
        {
            /** @var Parameter $parameter */
            if ($unpack = $parameter->getUnpack())
            {
                throw ValidationException::invalidSyntax($unpack);
            }
        }
    }

    public function convertToPhpParserNode()
    {
        $use = $this->getUse();
        $returnType = $this->getReturnType();
        return new Closure([
            "static" => $this->hasStaticModifier(),
            "byRef" => $this->hasByReference(),
            "params" => $this->getParameters()->convertToPhpParserNode(),
            "uses" => $use ? $use->getBindings()->convertToPhpParserNode() : [],
            "returnType" => $returnType ? $returnType->getType()->convertToPhpParserNode() : null,
            "stmts" => $this->getBody()->convertToPhpParserNode(),
        ]);
    }
}
