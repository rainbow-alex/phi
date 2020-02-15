<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions;

use Phi\Exception\ValidationException;
use Phi\Nodes\Expression;
use Phi\Nodes\Generated\GeneratedInstanceofExpression;
use Phi\Nodes\ValidationTraits\IsNewableHelper;
use Phi\PhpVersion;
use PhpParser\Node\Expr\Instanceof_;

class InstanceofExpression extends Expression
{
	use GeneratedInstanceofExpression;
	use IsNewableHelper;

	public function getLeftPrecedence(): int
	{
		return self::PRECEDENCE_INSTANCEOF;
	}

	protected function extraValidation(int $flags): void
	{
		$phpVersion = $this->getPhpVersion();

		$expression = $this->getExpression();
		$class = $this->getClass();

		if ($phpVersion < PhpVersion::PHP_7_3 && (
			$expression instanceof ClassNameResolutionExpression
			|| $expression instanceof ExitExpression
			|| ($expression instanceof ArrayExpression && $expression->isConstant())
			|| $expression instanceof MagicConstant
			|| $expression instanceof StringLiteral
			|| $expression instanceof NumberLiteral
		))
		{
			throw ValidationException::invalidExpressionInContext($expression);
		}

		if ($expression->getRightPrecedence() < $this->getLeftPrecedence())
		{
			throw ValidationException::badPrecedence($expression);
		}

		if (!self::isNewable($class))
		{
			throw ValidationException::invalidExpressionInContext($class);
		}

		if ($class->getLeftPrecedence() < $this->getRightPrecedence())
		{
			throw ValidationException::badPrecedence($class);
		}
	}

	protected function extraAutocorrect(): void
	{
		$expression = $this->getExpression();
		if ($expression->getRightPrecedence() < $this->getLeftPrecedence())
		{
			$expression = $expression->wrapIn(new ParenthesizedExpression());
			$expression->_autocorrect();
		}

		// wrapping class doesn't help, parentheses aren't newable
	}

	public function convertToPhpParser()
	{
		$class = $this->getClass();
		if ($class instanceof NameExpression)
		{
			$class = $class->getName();
		}
		return new Instanceof_($this->getExpression()->convertToPhpParser(), $class->convertToPhpParser());
	}
}
