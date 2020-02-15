<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions;

use Phi\Nodes\Expression;
use Phi\Nodes\Generated\GeneratedExitExpression;
use PhpParser\Node\Expr\Exit_;

class ExitExpression extends Expression
{
	use GeneratedExitExpression;

	public function convertToPhpParser()
	{
		$expr = $this->getExpression();
		return new Exit_($expr ? $expr->convertToPhpParser() : null);
	}
}
