<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions\MagicConstant;

use Phi\Nodes\Expressions\MagicConstant;
use Phi\Nodes\Generated\GeneratedClassMagicConstant;
use PhpParser\Node\Scalar\MagicConst\Class_;

class ClassMagicConstant extends MagicConstant
{
	use GeneratedClassMagicConstant;

	public function convertToPhpParser()
	{
		return new Class_();
	}
}
