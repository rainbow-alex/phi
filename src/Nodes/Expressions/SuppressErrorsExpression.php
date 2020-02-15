<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions;

use Phi\Nodes\Expression;
use Phi\Nodes\Generated\GeneratedSuppressErrorsExpression;
use PhpParser\Node\Expr\ErrorSuppress;

class SuppressErrorsExpression extends Expression
{
	use GeneratedSuppressErrorsExpression;

	public function convertToPhpParser()
	{
		return new ErrorSuppress($this->getExpression()->convertToPhpParser());
	}
}
