<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions\StringInterpolation;

use Phi\Nodes\Generated\GeneratedConfusingInterpolatedStringVariable;

/**
 * @see notes.md
 */
class ConfusingInterpolatedStringVariable extends InterpolatedStringVariable
{
	use GeneratedConfusingInterpolatedStringVariable;

	public function convertToPhpParser()
	{
		return $this->getVariable()->convertToPhpParser();
	}
}
