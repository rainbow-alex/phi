<?php

declare(strict_types=1);

namespace Phi\Nodes\Statements;

use Phi\Nodes\Generated\GeneratedForStatement;
use PhpParser\Node\Stmt\For_;

class ForStatement extends LoopStatement
{
	use GeneratedForStatement;

	public function convertToPhpParser()
	{
		return new For_([
			'init' => $this->getInitExpressions()->convertToPhpParser(),
			'cond' => $this->getConditionExpressions()->convertToPhpParser(),
			'loop' => $this->getStepExpressions()->convertToPhpParser(),
			'stmts' => $this->getBlock()->convertToPhpParser(),
		]);
	}
}
