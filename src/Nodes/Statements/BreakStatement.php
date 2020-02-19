<?php

declare(strict_types=1);

namespace Phi\Nodes\Statements;

use Phi\Nodes\Generated\GeneratedBreakStatement;
use PhpParser\Node\Stmt\Break_;

class BreakStatement extends LoopFlowStatement
{
	use GeneratedBreakStatement;

	public function convertToPhpParser()
	{
		$levels = $this->getLevels();
		return new Break_($levels ? $levels->convertToPhpParser() : null);
	}
}
