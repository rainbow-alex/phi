<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions\MagicConstant;

use Phi\Nodes\Expressions\MagicConstant;
use Phi\Nodes\Generated\GeneratedFileMagicConstant;
use PhpParser\Node\Scalar\MagicConst\File;

class FileMagicConstant extends MagicConstant
{
	use GeneratedFileMagicConstant;

	public function convertToPhpParser()
	{
		return new File();
	}
}
