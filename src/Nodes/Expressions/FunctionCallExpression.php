<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions;

use Phi\Exception\ValidationException;
use Phi\Nodes\Expression;
use Phi\Nodes\Generated\GeneratedFunctionCallExpression;
use PhpParser\Node\Expr\FuncCall;

class FunctionCallExpression extends Expression
{
    use GeneratedFunctionCallExpression;

    protected function extraValidation(int $flags): void
    {
        if (
            $this->getCallable() instanceof AnonymousFunctionExpression
            || $this->getCallable() instanceof EmptyExpression
            || $this->getCallable() instanceof EvalExpression
            || $this->getCallable() instanceof ExecExpression
            || $this->getCallable() instanceof ExitExpression
            || $this->getCallable() instanceof IssetExpression
            || $this->getCallable() instanceof MagicConstant
            || $this->getCallable() instanceof NumberLiteral
            // note: the parser can't generate some of these combinations, but they're still not valid
            // their semantics would be different from the code they generate
            || $this->getCallable() instanceof NewExpression
            || $this->getCallable() instanceof PropertyAccessExpression
            || $this->getCallable() instanceof StaticPropertyAccessExpression
            || $this->getCallable() instanceof ConstantAccessExpression
            || $this->getCallable() instanceof ClassNameResolutionExpression
        )
        {
            throw ValidationException::invalidSyntax($this->getLeftParenthesis());
        }
    }

    public function convertToPhpParserNode()
    {
        $callable = $this->getCallable();
        if ($callable instanceof NameExpression)
        {
            $callable = $callable->getName();
        }
        return new FuncCall($callable->convertToPhpParserNode(), $this->getArguments()->convertToPhpParserNode());
    }
}