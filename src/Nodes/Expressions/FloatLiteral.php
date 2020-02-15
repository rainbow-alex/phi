<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions;

use Phi\LiteralParser;
use Phi\Nodes\Generated\GeneratedFloatLiteral;
use PhpParser\Node\Scalar\DNumber;

class FloatLiteral extends NumberLiteral
{
	use GeneratedFloatLiteral;

	public function getValue(): float
	{
		return (new LiteralParser($this->getPhpVersion()))->parseFloatLiteral($this->getToken()->getSource());
	}

	public function convertToPhpParser()
	{
		return new DNumber($this->getValue());
	}
}
