<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions;

use Phi\Nodes\Generated\GeneratedPowerExpression;
use Phi\Nodes\ValidationTraits\RightAssocBinopExpression;
use PhpParser\Node\Expr\BinaryOp\Pow;

class PowerExpression extends BinopExpression
{
	use GeneratedPowerExpression;
	use RightAssocBinopExpression;

	public function getPrecedence(): int
	{
		return self::PRECEDENCE_POW;
	}

	public function convertToPhpParser()
	{
		return new Pow($this->getLeft()->convertToPhpParser(), $this->getRight()->convertToPhpParser());
	}
}
