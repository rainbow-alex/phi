<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions;

use Phi\Nodes\Generated\GeneratedHeredocStringLiteral;
use Phi\Nodes\ValidationTraits\DocStringLiteral;
use PhpParser\Node\Scalar\Encapsed;
use PhpParser\Node\Scalar\EncapsedStringPart;
use PhpParser\Node\Scalar\String_;

class HeredocStringLiteral extends InterpolatedStringLiteral
{
	use GeneratedHeredocStringLiteral;
	use DocStringLiteral;

	protected function extraValidation(int $flags): void
	{
		$this->validateEndDelimiter();
	}

	public function convertToPhpParser()
	{
		$parts = $this->getParts()->convertToPhpParser();

		$parts = \array_values(\array_filter($parts, function ($part): bool
		{
			return !($part instanceof EncapsedStringPart) || $part->value !== "";
		}));

		// trim one newline, dropping the entire string part if it becomes empty
		$lastPart = \end($parts);
		if ($lastPart instanceof EncapsedStringPart)
		{
			$lastPart->value = \substr($lastPart->value, 0, -1);
			if ($lastPart->value === "")
			{
				\array_pop($parts);
			}
		}

		if (count($parts) === 0)
		{
			return new String_("");
		}
		else if (count($parts) === 1 && $parts[0] instanceof EncapsedStringPart)
		{
			return new String_($parts[0]->value);
		}
		else
		{
			return new Encapsed($parts);
		}
	}
}
