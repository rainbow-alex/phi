<?php

declare(strict_types=1);

namespace Phi\Nodes\Statements;

use Phi\Nodes\Generated\GeneratedLabelStatement;
use Phi\Nodes\Statement;
use PhpParser\Node\Stmt\Label;

class LabelStatement extends Statement
{
	use GeneratedLabelStatement;

	public function convertToPhpParser()
	{
		return new Label($this->getLabel()->getSource());
	}
}
