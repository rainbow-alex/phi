<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions;

use Phi\Exception\LiteralParsingException;
use Phi\Exception\ValidationException;
use Phi\LiteralParser;
use Phi\Nodes\Generated\GeneratedIntegerLiteral;
use PhpParser\Node\Scalar\LNumber;

class IntegerLiteral extends NumberLiteral
{
	use GeneratedIntegerLiteral;

	protected function extraValidation(int $flags): void
	{
		try
		{
			(new LiteralParser($this->getPhpVersion()))->validateIntegerLiteral($this->getToken()->getSource());
		}
		catch (LiteralParsingException $e)
		{
			throw ValidationException::invalidSyntax($this);
		}
	}

	/**
	 * @return int|float
	 */
	public function getValue()
	{
		return (new LiteralParser($this->getPhpVersion()))->parseIntegerLiteral($this->getToken()->getSource());
	}

	public function convertToPhpParser()
	{
		$value = $this->getValue();
		if (\is_float($value))
		{
			throw new \LogicException(); // tODO dedicated exception
		}
		return new LNumber($value);
	}
}
