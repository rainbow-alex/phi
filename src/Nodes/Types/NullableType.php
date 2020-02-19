<?php

declare(strict_types=1);

namespace Phi\Nodes\Types;

use Phi\Nodes\Generated\GeneratedNullableType;
use Phi\Nodes\Type;

class NullableType extends Type
{
	use GeneratedNullableType;

	public function unwrapNullable(): Type
	{
		return $this->getType();
	}

	public function convertToPhpParser()
	{
		return new \PhpParser\Node\NullableType($this->getType()->convertToPhpParser());
	}
}
