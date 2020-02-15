<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions;

use Phi\Nodes\Expression;
use Phi\Nodes\Generated\GeneratedPrintExpression;
use PhpParser\Node\Expr\Print_;

class PrintExpression extends Expression
{
	use GeneratedPrintExpression;

	public function convertToPhpParser()
	{
		return new Print_($this->getExpression()->convertToPhpParser());
	}
}
