<?php

declare(strict_types=1);

namespace Phi\Nodes\Statements;

use Phi\Nodes\Generated\GeneratedExpressionStatement;
use Phi\Nodes\Statement;
use PhpParser\Node\Stmt\Expression as PPExpressionStatement;

class ExpressionStatement extends Statement
{
	use GeneratedExpressionStatement;

	public function convertToPhpParser()
	{
		return new PPExpressionStatement($this->getExpression()->convertToPhpParser());
	}
}
