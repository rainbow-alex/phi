<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions;

use Phi\Nodes\Generated\GeneratedPostDecrementExpression;
use PhpParser\Node\Expr\PostDec;

class PostDecrementExpression extends CrementExpression
{
	use GeneratedPostDecrementExpression;

	public function convertToPhpParser()
	{
		return new PostDec($this->getExpression()->convertToPhpParser());
	}
}
