<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions;

use Phi\Nodes\Generated\GeneratedIncludeExpression;
use PhpParser\Node\Expr\Include_;

class IncludeExpression extends IncludeLikeExpression
{
	use GeneratedIncludeExpression;

	public function convertToPhpParser()
	{
		return new Include_($this->getExpression()->convertToPhpParser(), Include_::TYPE_INCLUDE);
	}
}
