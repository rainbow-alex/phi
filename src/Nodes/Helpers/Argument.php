<?php

declare(strict_types=1);

namespace Phi\Nodes\Helpers;

use Phi\Nodes\Base\CompoundNode;
use Phi\Nodes\Generated\GeneratedArgument;
use PhpParser\Node\Arg;

class Argument extends CompoundNode
{
	use GeneratedArgument;

	protected function extraValidation(int $flags): void
	{
		$this->getExpression()->_validate($this->hasUnpack() ? self::CTX_READ : self::CTX_READ|self::CTX_LENIENT_READ);
	}

	public function convertToPhpParser()
	{
		return new Arg($this->getExpression()->convertToPhpParser(), false, $this->hasUnpack());
	}
}
