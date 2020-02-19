<?php

declare(strict_types=1);

namespace Phi\Nodes\Types;

use Phi\Nodes\Generated\GeneratedSpecialType;
use Phi\Nodes\Type;
use Phi\TokenType;
use PhpParser\Node\Identifier;

class SpecialType extends Type
{
	use GeneratedSpecialType;

	public function isStatic(): bool
	{
		return $this->getName()->getType() === TokenType::T_STATIC;
	}

	public function convertToPhpParser()
	{
		return new Identifier($this->getName()->getSource());
	}
}
