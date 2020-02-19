<?php

declare(strict_types=1);

namespace Phi\Nodes\Statements;

use Phi\Nodes\Base\CompoundNode;
use Phi\Nodes\Generated\GeneratedElseif;

class Elseif_ extends CompoundNode
{
	use GeneratedElseif;

	public function convertToPhpParser()
	{
		return new \PhpParser\Node\Stmt\ElseIf_(
			$this->getCondition()->convertToPhpParser(),
			$this->getBlock()->convertToPhpParser()
		);
	}
}
