<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions\MagicConstant;

use Phi\Nodes\Expressions\MagicConstant;
use Phi\Nodes\Generated\GeneratedFunctionMagicConstant;
use PhpParser\Node\Scalar\MagicConst\Function_;

class FunctionMagicConstant extends MagicConstant
{
	use GeneratedFunctionMagicConstant;

	public function convertToPhpParser()
	{
		return new Function_();
	}
}
