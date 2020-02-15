<?php

declare(strict_types=1);

namespace Phi\Nodes\Types;

use Phi\Nodes\Generated\GeneratedSpecialType;
use Phi\Nodes\Type;
use Phi\TokenType;

class SpecialType extends Type
{
	use GeneratedSpecialType;

	public function isStatic(): bool
	{
		return $this->getName()->getType() === TokenType::T_STATIC;
	}
}
