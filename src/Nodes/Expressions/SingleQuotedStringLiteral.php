<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions;

use Phi\Nodes\Generated\GeneratedSingleQuotedStringLiteral;
use PhpParser\Node\Scalar\String_;

class SingleQuotedStringLiteral extends ConstantStringLiteral
{
	use GeneratedSingleQuotedStringLiteral;

	public function convertToPhpParser()
	{
		return new String_(String_::parse($this->getToken()->getSource(), true));
	}
}
