<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions;

use Phi\Nodes\Expression;
use Phi\Nodes\Generated\GeneratedNotExpression;
use Phi\Nodes\ValidationTraits\UnaryOpExpression;
use PhpParser\Node\Expr\BooleanNot;

class NotExpression extends Expression
{
	use GeneratedNotExpression;
	use UnaryOpExpression;

	public function isConstant(): bool
	{
		return $this->getExpression()->isConstant();
	}

	public function getRightPrecedence(): int
	{
		return self::PRECEDENCE_BOOLEAN_NOT;
	}

	public function convertToPhpParser()
	{
		return new BooleanNot($this->getExpression()->convertToPhpParser());
	}
}
