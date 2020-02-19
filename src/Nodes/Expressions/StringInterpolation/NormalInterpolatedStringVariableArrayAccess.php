<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions\StringInterpolation;

use Phi\Exception\ValidationException;
use Phi\Nodes\Expression;
use Phi\Nodes\Generated\GeneratedNormalInterpolatedStringVariableArrayAccess;
use Phi\TokenType;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Scalar\LNumber;
use PhpParser\Node\Scalar\String_;

/**
 * This is not the same as ArrayAccess!
 * @see notes.md
 */
class NormalInterpolatedStringVariableArrayAccess extends Expression
{
	use GeneratedNormalInterpolatedStringVariableArrayAccess;

	protected function extraValidation(int $flags): void
	{
		$index = $this->getIndex();
		if ($this->hasMinus() && $index->getType() !== TokenType::T_NUM_STRING)
		{
			throw ValidationException::invalidSyntax($index);
		}
	}

	public function convertToPhpParser()
	{
		$index = $this->getIndex();
		if ($index->getType() === TokenType::T_STRING)
		{
			$dim = new String_($index->getSource());
		}
		else if ($index->getType() === TokenType::T_VARIABLE)
		{
			$dim = new Variable(\substr($index->getSource(), 1));
		}
		else
		{
			// note: alternative int notations like e.g. 0x0 are not considered T_NUM_STRING
			$dim = new LNumber(($this->hasMinus() ? -1 : 1) * (int) $index->getSource());
		}

		return new ArrayDimFetch($this->getVariable()->convertToPhpParser(), $dim);
	}
}
