<?php

declare(strict_types=1);

namespace Phi\Nodes\Oop;

use Phi\Exception\ValidationException;
use Phi\Nodes\Generated\GeneratedTraitUse;

class TraitUse extends OopMember
{
	use GeneratedTraitUse;

	protected function extraValidation(int $flags): void
	{
		foreach ($this->getTraits()->getItems() as $name)
		{
			if (!$name->isUsableAsTraitUse())
			{
				throw ValidationException::invalidNameInContext($name);
			}
		}
	}

	public function convertToPhpParser()
	{
		return new \PhpParser\Node\Stmt\TraitUse(
			$this->getTraits()->convertToPhpParser()
		);
		// TODO more
	}
}
