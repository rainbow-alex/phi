<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions\StringInterpolation;

use Phi\Exception\LiteralParsingException;
use Phi\Exception\ValidationException;
use Phi\LiteralParser;
use Phi\Nodes\Expressions\DoubleQuotedStringLiteral;
use Phi\Nodes\Expressions\StringLiteral;
use Phi\Nodes\Generated\GeneratedConstantInterpolatedStringPart;
use PhpParser\Node\Scalar\EncapsedStringPart;
use PhpParser\Node\Scalar\String_;

class ConstantInterpolatedStringPart extends InterpolatedStringPart
{
	use GeneratedConstantInterpolatedStringPart;

	private function getStringLiteral(): ?StringLiteral
	{
		$parent = $this->getParent();
		$parent = $parent ? $parent->getParent() : null;
		return $parent instanceof StringLiteral ? $parent : null;
	}

	protected function extraValidation(int $flags): void
	{
		try
		{
			(new LiteralParser($this->getPhpVersion()))->parseStringContentEscapes(
				$this->getContent()->getSource(),
				$this->getStringLiteral() instanceof DoubleQuotedStringLiteral
			);
		}
		catch (LiteralParsingException $e)
		{
			throw ValidationException::invalidSyntax($this);
		}
	}

	public function convertToPhpParser()
	{
		return new EncapsedStringPart(String_::parseEscapeSequences(
			$this->getContent()->getSource(),
			$this->getStringLiteral() instanceof DoubleQuotedStringLiteral ? '"' : null,
			true
		));
	}
}
