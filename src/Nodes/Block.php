<?php

declare(strict_types=1);

namespace Phi\Nodes;

use Phi\Nodes\Base\CompoundNode;
use Phi\Nodes\Statements\BlockStatement;
use Phi\Util\Util;

abstract class Block extends CompoundNode
{
	/**
	 * @return iterable<Statement>
	 * @phpstan-return iterable<Statement>
	 */
	abstract public function getStatements();

	public function convertToPhpParser()
	{
		$statements = Util::iterableToArray($this->getStatements());
		$statements = BlockStatement::flatten($statements);
		return \array_map(function (Statement $s) { return $s->convertToPhpParser(); }, $statements);
	}
}
