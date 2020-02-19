<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions;

use Phi\Nodes\Generated\GeneratedArrowFunctionExpression;
use PhpParser\Node\Expr\ArrowFunction;

class ArrowFunctionExpression extends AnonymousFunctionExpression
{
	use GeneratedArrowFunctionExpression;

	public function convertToPhpParser()
	{
		$returnType = $this->getReturnType();
		return new ArrowFunction([
			'static' => $this->hasStaticKeyword(),
			'byRef' => $this->hasByReference(),
			'params' => $this->getParameters()->convertToPhpParser(),
			'returnType' => $returnType ? $returnType->convertToPhpParser() : null,
			'expr' => $this->getBody()->convertToPhpParser(),
		]);
	}
}
