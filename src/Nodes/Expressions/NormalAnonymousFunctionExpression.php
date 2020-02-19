<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions;

use Phi\Nodes\Generated\GeneratedNormalAnonymousFunctionExpression;
use PhpParser\Node\Expr\Closure;

class NormalAnonymousFunctionExpression extends AnonymousFunctionExpression
{
	use GeneratedNormalAnonymousFunctionExpression;

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
