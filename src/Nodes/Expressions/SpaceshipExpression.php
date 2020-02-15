<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions;

use Phi\Nodes\Generated\GeneratedSpaceshipExpression;
use Phi\Nodes\ValidationTraits\NonAssocBinopExpression;
use PhpParser\Node\Expr\BinaryOp\Spaceship;

class SpaceshipExpression extends BinopExpression
{
	use GeneratedSpaceshipExpression;
	use NonAssocBinopExpression;

	protected function getPrecedence(): int
	{
		return self::PRECEDENCE_COMPARISON1;
	}

	public function convertToPhpParser()
	{
		return new Spaceship($this->getLeft()->convertToPhpParser(), $this->getRight()->convertToPhpParser());
	}
}
