<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions;

use Phi\Nodes\Expression;
use Phi\Nodes\Generated\GeneratedEvalExpression;
use PhpParser\Node\Expr\Eval_;

class EvalExpression extends Expression
{
	use GeneratedEvalExpression;

	public function convertToPhpParser()
	{
		return new Eval_($this->getExpression()->convertToPhpParser());
	}
}
