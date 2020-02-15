<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions;

use Phi\Nodes\Expression;
use Phi\Nodes\Generated\GeneratedEmptyExpression;
use PhpParser\Node\Expr\Empty_;

class EmptyExpression extends Expression
{
	use GeneratedEmptyExpression;

	public function convertToPhpParser()
	{
		return new Empty_(
			$this->getExpression()->convertToPhpParser()
		);
	}
}
