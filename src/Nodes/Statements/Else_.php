<?php

declare(strict_types=1);

namespace Phi\Nodes\Statements;

use Phi\Nodes\Base\CompoundNode;
use Phi\Nodes\Generated\GeneratedElse;

class Else_ extends CompoundNode
{
	use GeneratedElse;

	public function convertToPhpParser()
	{
		return new \PhpParser\Node\Stmt\Else_(
			$this->getBlock()->convertToPhpParser()
		);
	}
}
