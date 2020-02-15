<?php

declare(strict_types=1);

namespace Phi\Nodes\Helpers;

use Phi\Exception\ValidationException;
use Phi\Nodes\Base\CompoundNode;
use Phi\Nodes\Generated\GeneratedReturnType;
use Phi\Nodes\Types\NamedType;
use Phi\Nodes\Types\NullableType;
use Phi\Nodes\Types\SpecialType;

class ReturnType extends CompoundNode
{
	use GeneratedReturnType;

	protected function extraValidation(int $flags): void
	{
		$type = $this->getType()->unwrapNullable();
		if ($type instanceof SpecialType && $type->isStatic())
		{
			throw ValidationException::invalidSyntax($type);
		}
		else if ($type instanceof NamedType && !$type->getName()->isUsableAsReturnType())
		{
			throw ValidationException::invalidSyntax($type);
		}
	}
}
