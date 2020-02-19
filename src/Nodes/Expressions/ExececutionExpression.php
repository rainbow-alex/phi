<?php

namespace Phi\Nodes\Expressions;

use Phi\Nodes\Expression;
use Phi\Nodes\Generated\GeneratedExececutionExpression;
use PhpParser\Node\Expr\ShellExec;

class ExececutionExpression extends Expression
{
	use GeneratedExececutionExpression;

	public function convertToPhpParser()
	{
		// TODO test coverage
		return new ShellExec($this->getParts()->convertToPhpParser());
	}
}
