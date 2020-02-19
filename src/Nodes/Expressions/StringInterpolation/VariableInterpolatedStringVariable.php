<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions\StringInterpolation;

use Phi\Nodes\Generated\GeneratedVariableInterpolatedStringVariable;
use PhpParser\Node\Expr\Variable;

class VariableInterpolatedStringVariable extends InterpolatedStringVariable
{
	use GeneratedVariableInterpolatedStringVariable;

	public function convertToPhpParser()
	{
		return new Variable($this->getName()->convertToPhpParser());
	}
}
