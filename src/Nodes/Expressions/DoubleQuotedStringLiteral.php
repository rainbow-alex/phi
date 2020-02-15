<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions;

use Phi\Nodes\Generated\GeneratedDoubleQuotedStringLiteral;
use PhpParser\Node\Scalar\Encapsed;
use PhpParser\Node\Scalar\EncapsedStringPart;
use PhpParser\Node\Scalar\String_;

class DoubleQuotedStringLiteral extends InterpolatedStringLiteral
{
	use GeneratedDoubleQuotedStringLiteral;

	public function convertToPhpParser()
	{
		$parts = $this->getParts()->convertToPhpParser();

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
