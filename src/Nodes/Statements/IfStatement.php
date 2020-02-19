<?php

declare(strict_types=1);

namespace Phi\Nodes\Statements;

use Phi\Nodes\Generated\GeneratedIfStatement;
use Phi\Nodes\Statement;
use PhpParser\Node\Stmt\If_;

class IfStatement extends Statement
{
	use GeneratedIfStatement;

	public function convertToPhpParser()
	{
		$else = $this->getElseClause();
		return new If_(
			$this->getCondition()->convertToPhpParser(),
			[
				'stmts' => $this->getBlock()->convertToPhpParser(),
				'elseifs' => $this->getElseifClauses()->convertToPhpParser(),
				'else' => $else ? $else->convertToPhpParser() : null,
			]
		);
	}
}
