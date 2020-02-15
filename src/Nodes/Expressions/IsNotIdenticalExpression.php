<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions;

use Phi\Nodes\Generated\GeneratedIsNotIdenticalExpression;
use Phi\Nodes\ValidationTraits\NonAssocBinopExpression;
use PhpParser\Node\Expr\BinaryOp\NotIdentical;

class IsNotIdenticalExpression extends BinopExpression
{
	use GeneratedIsNotIdenticalExpression;
	use NonAssocBinopExpression;

	protected function getPrecedence(): int
	{
		return self::PRECEDENCE_COMPARISON1;
	}

	public function convertToPhpParser()
	{
		return new NotIdentical($this->getLeft()->convertToPhpParser(), $this->getRight()->convertToPhpParser());
	}
}
