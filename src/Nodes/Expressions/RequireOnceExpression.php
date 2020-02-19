<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions;

use Phi\Nodes\Generated\GeneratedRequireOnceExpression;
use PhpParser\Node\Expr\Include_;

class RequireOnceExpression extends IncludeLikeExpression
{
	use GeneratedRequireOnceExpression;

	public function convertToPhpParser()
	{
		return new Include_($this->getExpression()->convertToPhpParser(), Include_::TYPE_REQUIRE_ONCE);
	}
}
