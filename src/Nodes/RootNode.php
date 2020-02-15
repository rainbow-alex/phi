<?php

declare(strict_types=1);

namespace Phi\Nodes;

use Phi\Nodes\Base\CompoundNode;
use Phi\Nodes\Generated\GeneratedRootNode;
use Phi\Nodes\Statements\BlockStatement;
use Phi\Nodes\Statements\InlineHtmlStatement;

class RootNode extends CompoundNode
{
	use GeneratedRootNode;

	public function convertToPhpParser()
	{
		$statements = $this->getStatements()->getItems();
		$statements = BlockStatement::flatten($statements);

		// drop the initial <?php node if it is empty
		if ($statements)
		{
			if ($statements[0] instanceof InlineHtmlStatement)
			{
				$content = $statements[0]->getContent();
				if (!$content || $content->getSource() === "")
				{
					\array_shift($statements);
				}
			}
		}

		return \array_map(function (Statement $s) { return $s->convertToPhpParser(); }, $statements);
	}
}
