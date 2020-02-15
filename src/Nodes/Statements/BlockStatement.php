<?php

declare(strict_types=1);

namespace Phi\Nodes\Statements;

use Phi\Nodes\Generated\GeneratedBlockStatement;
use Phi\Nodes\Statement;

class BlockStatement extends Statement
{
	use GeneratedBlockStatement;

	/**
	 * @param Statement[] $statements
	 * @return Statement[]
	 */
	public static function flatten(array $statements): array
	{
		for ($i = 0; $i < count($statements); $i++)
		{
			$stmt = $statements[$i];

			// flatten blocks
			while ($stmt instanceof BlockStatement)
			{
				\array_splice($statements, $i, 1, \iterator_to_array($stmt->getBlock()->getStatements()));
			}

			$statements[$i] = $stmt;
		}

		return $statements;
	}
}
