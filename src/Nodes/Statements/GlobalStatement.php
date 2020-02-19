<?php

declare(strict_types=1);

namespace Phi\Nodes\Statements;

use Phi\Nodes\Generated\GeneratedGlobalStatement;
use Phi\Nodes\Statement;
use PhpParser\Node\Stmt\Global_;

class GlobalStatement extends Statement
{
	use GeneratedGlobalStatement;

	public function convertToPhpParser()
	{
		return new Global_(
			$this->getVariables()->convertToPhpParser()
		);
	}
}
