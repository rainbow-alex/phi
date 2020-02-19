<?php

declare(strict_types=1);

namespace Phi\Nodes\Statements;

use Phi\Nodes\Generated\GeneratedReturnStatement;
use Phi\Nodes\Statement;
use PhpParser\Node\Stmt\Return_;

class ReturnStatement extends Statement
{
	use GeneratedReturnStatement;

	public function convertToPhpParser()
	{
		$expression = $this->getExpression();
		return new Return_(
			$expression ? $expression->convertToPhpParser() : null
		);
	}
}
