<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions\MagicConstant;

use Phi\Nodes\Expressions\MagicConstant;
use Phi\Nodes\Generated\GeneratedTraitMagicConstant;
use PhpParser\Node\Scalar\MagicConst\Trait_;

class TraitMagicConstant extends MagicConstant
{
	use GeneratedTraitMagicConstant;

	public function convertToPhpParser()
	{
		return new Trait_();
	}
}
