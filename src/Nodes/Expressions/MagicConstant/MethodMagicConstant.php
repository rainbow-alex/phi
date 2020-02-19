<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions\MagicConstant;

use Phi\Nodes\Expressions\MagicConstant;
use Phi\Nodes\Generated\GeneratedMethodMagicConstant;
use PhpParser\Node\Scalar\MagicConst\Method;

class MethodMagicConstant extends MagicConstant
{
	use GeneratedMethodMagicConstant;

	public function convertToPhpParser()
	{
		return new Method();
	}
}
