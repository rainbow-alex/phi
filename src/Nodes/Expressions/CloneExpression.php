<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions;

use Phi\Nodes\Expression;
use Phi\Nodes\Generated\GeneratedCloneExpression;
use Phi\Nodes\ValidationTraits\UnaryOpExpression;
use PhpParser\Node\Expr\Clone_;

class CloneExpression extends Expression
{
	use GeneratedCloneExpression;
	use UnaryOpExpression;

	public function convertToPhpParser()
	{
		return new Clone_($this->getExpression()->convertToPhpParser());
	}
}
