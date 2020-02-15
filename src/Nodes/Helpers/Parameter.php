<?php

declare(strict_types=1);

namespace Phi\Nodes\Helpers;

use Phi\Exception\ValidationException;
use Phi\Nodes\Base\CompoundNode;
use Phi\Nodes\Generated\GeneratedParameter;
use Phi\Nodes\Types\NamedType;
use Phi\Nodes\Types\NullableType;
use Phi\Nodes\Types\SpecialType;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Param;

class Parameter extends CompoundNode
{
	use GeneratedParameter;

	protected function extraValidation(int $flags): void
	{
		if ($this->hasUnpack())
		{
			if ($default = $this->getDefault())
			{
				throw ValidationException::invalidSyntax($default);
			}
		}

		if ($type = $this->getType())
		{
			$type = $type->unwrapNullable();
			if ($type instanceof SpecialType && $type->isStatic())
			{
				throw ValidationException::invalidNameInContext($type->getName());
			}
			else if ($type instanceof NamedType && !$type->getName()->isUsableAsParameterType())
			{
				throw ValidationException::invalidNameInContext($type->getName());
			}
		}
	}

	public function convertToPhpParser()
	{
		$type = $this->getType();
		$default = $this->getDefault();
		return new Param(
			new Variable(substr($this->getVariable()->getSource(), 1)),
			$default ? $default->convertToPhpParser() : null,
			$type ? $type->convertToPhpParser() : null,
			$this->hasByReference(),
			$this->hasUnpack()
		);
	}
}
