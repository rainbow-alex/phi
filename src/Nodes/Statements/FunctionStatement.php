<?php

declare(strict_types=1);

namespace Phi\Nodes\Statements;

use Phi\Nodes\Generated\GeneratedFunctionStatement;
use Phi\Nodes\Statement;
use Phi\Nodes\ValidationTraits\ForbidTrailingSeparator;
use PhpParser\Node\Stmt\Function_;

class FunctionStatement extends Statement
{
	use GeneratedFunctionStatement;
	use ForbidTrailingSeparator;

	protected function extraValidation(int $flags): void
	{
		self::forbidTrailingSeparator($this->getParameters());
	}

	public function convertToPhpParser()
	{
		$returnType = $this->getReturnType();
		return new Function_(
			$this->getName()->getSource(),
			[
				'byRef' => $this->hasByReference(),
				'params' => $this->getParameters()->convertToPhpParser(),
				'returnType' => $returnType ? $returnType->convertToPhpParser() : null,
				'stmts' => $this->getBody()->convertToPhpParser(),
			]
		);
	}
}
