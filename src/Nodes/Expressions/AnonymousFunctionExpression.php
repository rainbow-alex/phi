<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions;

use Phi\Exception\ValidationException;
use Phi\Nodes\Base\SeparatedNodesList;
use Phi\Nodes\Expression;
use Phi\Nodes\Helpers\Parameter;
use Phi\Nodes\ValidationTraits\ForbidTrailingSeparator;

abstract class AnonymousFunctionExpression extends Expression
{
	use ForbidTrailingSeparator;

	/**
	 * @return \Phi\Nodes\Base\SeparatedNodesList|\Phi\Nodes\Helpers\Parameter[]
	 * @phpstan-return \Phi\Nodes\Base\SeparatedNodesList<\Phi\Nodes\Helpers\Parameter>
	 */
	abstract public function getParameters(): SeparatedNodesList;

	protected function extraValidation(int $flags): void
	{
		foreach (\array_slice($this->getParameters()->getItems(), 0, -1) as $parameter)
		{
			/** @var Parameter $parameter */
			if ($unpack = $parameter->getUnpack())
			{
				throw ValidationException::invalidSyntax($unpack);
			}
		}

		self::forbidTrailingSeparator($this->getParameters());
	}
}
