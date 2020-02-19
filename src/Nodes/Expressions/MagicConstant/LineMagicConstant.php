<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions\MagicConstant;

use Phi\Nodes\Expressions\MagicConstant;
use Phi\Nodes\Generated\GeneratedLineMagicConstant;
use PhpParser\Node\Scalar\MagicConst\Line;

class LineMagicConstant extends MagicConstant
{
	use GeneratedLineMagicConstant;

	public function convertToPhpParser()
	{
		return new Line();
	}
}
