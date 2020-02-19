<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions;

use Phi\Exception\ValidationException;
use Phi\Nodes\Expression;
use Phi\Nodes\Generated\GeneratedFunctionCallExpression;
use Phi\Nodes\ValidationTraits\ForbidTrailingSeparator;
use PhpParser\Node\Expr\FuncCall;

class FunctionCallExpression extends Expression
{
	use GeneratedFunctionCallExpression;
	use ForbidTrailingSeparator;

	public function isTemporary(): bool
	{
		return false;
	}

	protected function extraValidation(int $flags): void
	{
		if (
			$this->getCallable() instanceof NormalAnonymousFunctionExpression
			|| $this->getCallable() instanceof EmptyExpression
			|| $this->getCallable() instanceof EvalExpression
			|| $this->getCallable() instanceof ExececutionExpression
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
			|| $this->getCallable() instanceof CloneExpression
		)
		{
			throw ValidationException::invalidSyntax($this->getLeftParenthesis());
		}

		self::forbidTrailingSeparator($this->getArguments());
	}

	public function convertToPhpParser()
	{
		$callable = $this->getCallable();
		if ($callable instanceof NameExpression)
		{
			$callable = $callable->getName();
		}
		return new FuncCall($callable->convertToPhpParser(), $this->getArguments()->convertToPhpParser());
	}
}
