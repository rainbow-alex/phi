<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions\StringInterpolation;

use Phi\Exception\LiteralParsingException;
use Phi\Exception\ValidationException;
use Phi\LiteralParser;
use Phi\Nodes\Expressions\DoubleQuotedStringLiteral;
use Phi\Nodes\Expressions\ExececutionExpression;
use Phi\Nodes\Generated\GeneratedConstantInterpolatedStringPart;
use PhpParser\Node\Scalar\EncapsedStringPart;
use PhpParser\Node\Scalar\String_;

class ConstantInterpolatedStringPart extends InterpolatedStringPart
{
	use GeneratedConstantInterpolatedStringPart;

	private function getQuoteType(): ?string
	{
		$grandParent = $this->getGrandParent();
		if ($grandParent instanceof DoubleQuotedStringLiteral)
		{
			return '"';
		}
		else if ($grandParent instanceof ExececutionExpression)
		{
			return '`';
		}
		else
		{
			return null;
		}
	}

	protected function extraValidation(int $flags): void
	{

		try
		{
			(new LiteralParser($this->getPhpVersion()))->parseStringContentEscapes(
				$this->getContent()->getSource(),
				$this->getQuoteType()
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
			$this->getQuoteType(),
			true
		));
	}
}
