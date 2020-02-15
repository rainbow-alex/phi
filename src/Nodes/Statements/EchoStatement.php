<?php

declare(strict_types=1);

namespace Phi\Nodes\Statements;

use Phi\Nodes\Generated\GeneratedEchoStatement;
use Phi\Nodes\Statement;
use PhpParser\Node\Stmt\Echo_;

class EchoStatement extends Statement
{
	use GeneratedEchoStatement;

	public function convertToPhpParser()
	{
		return new Echo_($this->getExpressions()->convertToPhpParser());
	}
}
