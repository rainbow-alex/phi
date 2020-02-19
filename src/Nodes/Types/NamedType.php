<?php

declare(strict_types=1);

namespace Phi\Nodes\Types;

use Phi\Nodes\Generated\GeneratedNamedType;
use Phi\Nodes\Type;
use PhpParser\Node\Identifier;

class NamedType extends Type
{
	use GeneratedNamedType;

	public function convertToPhpParser()
	{
		if ($this->getName()->isSpecialType())
		{
			return new Identifier($this->getName()->getParts()->getItems()[0]->getSource());
		}
		else
		{
			return $this->getName()->convertToPhpParser();
		}
	}
}
