<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions;

use Phi\Nodes\Generated\GeneratedRequireExpression;
use PhpParser\Node\Expr\Include_;

class RequireExpression extends IncludeLikeExpression
{
	use GeneratedRequireExpression;

	public function convertToPhpParser()
	{
		return new Include_($this->getExpression()->convertToPhpParser(), Include_::TYPE_REQUIRE);
	}
}
