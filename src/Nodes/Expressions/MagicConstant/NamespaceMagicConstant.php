<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions\MagicConstant;

use Phi\Nodes\Expressions\MagicConstant;
use Phi\Nodes\Generated\GeneratedNamespaceMagicConstant;
use PhpParser\Node\Scalar\MagicConst\Namespace_;

class NamespaceMagicConstant extends MagicConstant
{
	use GeneratedNamespaceMagicConstant;

	public function convertToPhpParser()
	{
		return new Namespace_();
	}
}
