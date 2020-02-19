<?php

declare(strict_types=1);

namespace Phi\Nodes\Statements;

use Phi\Nodes\Generated\GeneratedWhileStatement;
use PhpParser\Node\Stmt\While_;

class WhileStatement extends LoopStatement
{
	use GeneratedWhileStatement;

	public function convertToPhpParser()
	{
		return new While_(
			$this->getCondition()->convertToPhpParser(),
			$this->getBlock()->convertToPhpParser()
		);
	}
}
