<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions;

use Phi\Exception\ValidationException;
use Phi\Nodes\Expression;
use Phi\Nodes\Generated\GeneratedArrayAccessExpression;
use PhpParser\Node\Expr\ArrayDimFetch;

class ArrayAccessExpression extends Expression
{
	use GeneratedArrayAccessExpression;

	public function isConstant(): bool
	{
		$index = $this->getIndex();
		return $this->getExpression()->isConstant() && $index && $index->isConstant();
	}

	public function isTemporary(): bool
	{
		return $this->getExpression()->isTemporary();
	}

	protected function extraValidation(int $flags): void
	{
		$expression = $this->getExpression();
		if (
			$expression instanceof NormalNewExpression
			|| $expression instanceof ExitExpression
			|| $expression instanceof EmptyExpression
			|| $expression instanceof EvalExpression
			|| $expression instanceof ExececutionExpression
			|| $expression instanceof IssetExpression
			|| $expression instanceof CloneExpression
			|| $expression instanceof MagicConstant
			|| $expression instanceof NormalAnonymousFunctionExpression
			|| $expression instanceof NewExpression
			|| $expression instanceof NumberLiteral
		)
		{
			throw ValidationException::invalidSyntax($this->getLeftBracket());
		}

		if ($flags & self::CTX_WRITE && $expression->isTemporary())
		{
			throw ValidationException::invalidExpression($this, $this->getLeftBracket());
		}

		if (!$this->index)
		{
			if ($flags & self::CTX_READ)
			{
				// there are some exceptions where $a[] is allowed even though it isn't usually considered read
				// e.g. foo($a[]), $a[]++
				if (!($flags & self::CTX_LENIENT_READ))
				{
					throw ValidationException::invalidExpressionInContext($this, $this->leftBracket);
				}
			}
		}
	}

	public function convertToPhpParser()
	{
		$index = $this->getIndex();
		return new ArrayDimFetch(
			$this->getExpression()->convertToPhpParser(),
			$index ? $index->convertToPhpParser() : null
		);
	}
}
