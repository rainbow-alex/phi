<?php

declare(strict_types=1);

namespace Phi\Nodes\Statements;

use Phi\Exception\ValidationException;
use Phi\Nodes\Base\CompoundNode;
use Phi\Nodes\Generated\GeneratedUseDeclaration;
use PhpParser\Node\Stmt\UseUse;

class UseDeclaration extends CompoundNode
{
	use GeneratedUseDeclaration;

	protected function extraValidation(int $flags): void
	{
		$name = $this->getName();
		if (!$name->isUsableAsUse())
		{
			throw ValidationException::invalidNameInContext($name);
		}
	}

	public function convertToPhpParser()
	{
		$alias = $this->getAlias();
		return new UseUse(
			$this->getName()->convertToPhpParser(true),
			$alias ? $alias->getName()->getSource() : null
		);
	}
}
