<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions;

use Phi\Nodes\Generated\GeneratedIncludeOnceExpression;
use PhpParser\Node\Expr\Include_;

class IncludeOnceExpression extends IncludeLikeExpression
{
	use GeneratedIncludeOnceExpression;

	public function convertToPhpParser()
	{
		return new Include_($this->getExpression()->convertToPhpParser(), Include_::TYPE_INCLUDE_ONCE);
	}
}
