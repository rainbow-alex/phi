<?php

namespace Phi\Nodes\Expressions;

use Phi\Nodes\Expression;
use Phi\Nodes\Generated\GeneratedExececutionExpression;
use PhpParser\Node\Expr\ShellExec;
use PhpParser\Node\Scalar\EncapsedStringPart;

// TODO support multiple parts
class ExececutionExpression extends Expression
{
	use GeneratedExececutionExpression;

	public function convertToPhpParser()
	{
		return new ShellExec([
			new EncapsedStringPart($this->getCommand()->getSource())
		]);
	}
}
