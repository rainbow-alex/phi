<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions;

use Phi\Nodes\Expression;
use Phi\Nodes\Generated\GeneratedIncludeLikeExpression;
use Phi\TokenType;
use PhpParser\Node\Expr\Include_;

// TODO split
class IncludeLikeExpression extends Expression
{
	use GeneratedIncludeLikeExpression;

	public function convertToPhpParser()
	{
		$type = [
			TokenType::T_INCLUDE => Include_::TYPE_INCLUDE,
			TokenType::T_INCLUDE_ONCE => Include_::TYPE_INCLUDE_ONCE,
			TokenType::T_REQUIRE => Include_::TYPE_REQUIRE,
			TokenType::T_REQUIRE_ONCE => Include_::TYPE_REQUIRE_ONCE,
		][$this->getKeyword()->getType()];
		return new Include_($this->getExpression()->convertToPhpParser(), $type);
	}
}
