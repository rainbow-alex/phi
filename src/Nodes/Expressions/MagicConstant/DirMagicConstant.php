<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions\MagicConstant;

use Phi\Nodes\Expressions\MagicConstant;
use Phi\Nodes\Generated\GeneratedDirMagicConstant;
use PhpParser\Node\Scalar\MagicConst\Dir;

class DirMagicConstant extends MagicConstant
{
	use GeneratedDirMagicConstant;

	public function convertToPhpParser()
	{
		return new Dir();
	}
}
