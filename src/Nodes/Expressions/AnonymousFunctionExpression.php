<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions;

use Phi\Exception\ValidationException;
use Phi\Nodes\Expression;
use Phi\Nodes\Generated\GeneratedAnonymousFunctionExpression;
use Phi\Nodes\Helpers\Parameter;
use Phi\Nodes\ValidationTraits\ForbidTrailingSeparator;
use PhpParser\Node\Expr\Closure;

class AnonymousFunctionExpression extends Expression
{
	use GeneratedAnonymousFunctionExpression;
	use ForbidTrailingSeparator;

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

	public function convertToPhpParser()
	{
		$use = $this->getUse();
		$returnType = $this->getReturnType();
		return new Closure([
			"static" => $this->hasStaticModifier(),
			"byRef" => $this->hasByReference(),
			"params" => $this->getParameters()->convertToPhpParser(),
			"uses" => $use ? $use->getBindings()->convertToPhpParser() : [],
			"returnType" => $returnType ? $returnType->getType()->convertToPhpParser() : null,
			"stmts" => $this->getBody()->convertToPhpParser(),
		]);
	}
}
