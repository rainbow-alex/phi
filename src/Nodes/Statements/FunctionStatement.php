<?php

declare(strict_types=1);

namespace Phi\Nodes\Statements;

use Phi\Nodes\Generated\GeneratedFunctionStatement;
use Phi\Nodes\Statement;
use Phi\Nodes\ValidationTraits\ForbidTrailingSeparator;

class FunctionStatement extends Statement
{
	use GeneratedFunctionStatement;
	use ForbidTrailingSeparator;

	protected function extraValidation(int $flags): void
	{
		self::forbidTrailingSeparator($this->getParameters());
	}
}
