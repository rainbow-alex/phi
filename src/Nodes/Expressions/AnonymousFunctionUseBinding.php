<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions;

use Phi\Nodes\Base\CompoundNode;
use Phi\Nodes\Generated\GeneratedAnonymousFunctionUseBinding;
use PhpParser\Node\Expr\ClosureUse;
use PhpParser\Node\Expr\Variable;

class AnonymousFunctionUseBinding extends CompoundNode
{
	use GeneratedAnonymousFunctionUseBinding;

	public function convertToPhpParser()
	{
		return new ClosureUse(
			new Variable(\substr($this->getVariable()->getSource(), 1)),
			$this->hasByReference()
		);
	}
}
