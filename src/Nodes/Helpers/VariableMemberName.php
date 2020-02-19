<?php

declare(strict_types=1);

namespace Phi\Nodes\Helpers;

use Phi\Nodes\Generated\GeneratedVariableMemberName;

class VariableMemberName extends MemberName
{
	use GeneratedVariableMemberName;

	public function convertToPhpParser()
	{
		return $this->getExpression()->convertToPhpParser();
	}
}
