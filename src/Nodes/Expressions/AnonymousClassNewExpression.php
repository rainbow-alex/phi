<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions;

use Phi\Nodes\Generated\GeneratedAnonymousClassNewExpression;
use PhpParser\Node\Expr\New_;
use PhpParser\Node\Stmt\Class_;

class AnonymousClassNewExpression extends NewExpression
{
	use GeneratedAnonymousClassNewExpression;

	public function convertToPhpParser()
	{
		$extends = $this->getExtends();
		$implements = $this->getImplements();
		return new New_(
			new Class_(
				null,
				[
					'extends' => $extends ? $extends->convertToPhpParser() : null,
					'implements' => $implements ? $implements->convertToPhpParser() : null,
					'stmts' => $this->members->convertToPhpParser(),
				]
			),
			$this->getArguments()->convertToPhpParser()
		);
	}
}
