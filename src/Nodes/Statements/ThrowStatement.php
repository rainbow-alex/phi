<?php

declare(strict_types=1);

namespace Phi\Nodes\Statements;

use Phi\Nodes\Generated\GeneratedThrowStatement;
use Phi\Nodes\Statement;
use PhpParser\Node\Stmt\Throw_;

class ThrowStatement extends Statement
{
	use GeneratedThrowStatement;

	public function convertToPhpParser()
	{
		return new Throw_($this->getExpression()->convertToPhpParser());
	}
}
