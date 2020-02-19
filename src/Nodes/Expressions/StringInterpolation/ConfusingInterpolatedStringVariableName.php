<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions\StringInterpolation;

use Phi\Nodes\Expressions\VariableExpression;
use Phi\Nodes\Generated\GeneratedConfusingInterpolatedStringVariableName;
use PhpParser\Node\Expr\Variable;

class ConfusingInterpolatedStringVariableName extends VariableExpression
{
	use GeneratedConfusingInterpolatedStringVariableName;

	public function convertToPhpParser()
	{
		return new Variable($this->getName()->getSource());
	}
}
