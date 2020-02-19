<?php

declare(strict_types=1);

namespace Phi\Nodes\Statements;

use Phi\Nodes\Generated\GeneratedUseStatement;
use Phi\Nodes\Statement;
use PhpParser\Node\Stmt\Use_;

class UseStatement extends Statement
{
	use GeneratedUseStatement;

	public function convertToPhpParser()
	{
		return new Use_(
			$this->getDeclarations()->convertToPhpParser()
		);
	}
}
