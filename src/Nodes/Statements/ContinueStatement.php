<?php

declare(strict_types=1);

namespace Phi\Nodes\Statements;

use Phi\Nodes\Generated\GeneratedContinueStatement;
use PhpParser\Node\Stmt\Continue_;

class ContinueStatement extends LoopFlowStatement
{
	use GeneratedContinueStatement;

	public function convertToPhpParser()
	{
		$levels = $this->getLevels();
		return new Continue_($levels ? $levels->convertToPhpParser() : null);
	}
}
