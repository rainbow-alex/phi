<?php

declare(strict_types=1);

namespace Phi\Nodes\Helpers;

use Phi\Exception\ValidationException;
use Phi\Nodes\Base\CompoundNode;
use Phi\Nodes\Generated\GeneratedDefault;

class Default_ extends CompoundNode
{
	use GeneratedDefault;

	protected function extraValidation(int $flags): void
	{
		if (!$this->getValue()->isConstant())
		{
			throw ValidationException::invalidExpressionInContext($this->getValue());
		}
	}

	public function convertToPhpParser()
	{
		return $this->getValue()->convertToPhpParser();
	}
}
