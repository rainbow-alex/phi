<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions;

use Phi\Nodes\Expression;
use Phi\Nodes\Generated\GeneratedNameExpression;
use PhpParser\Node\Expr\ConstFetch;

class NameExpression extends Expression
{
	use GeneratedNameExpression;

	public function isConstant(): bool
	{
		return true;
	}

	public function convertToPhpParser()
	{
		return new ConstFetch($this->getName()->convertToPhpParser());
	}
}
