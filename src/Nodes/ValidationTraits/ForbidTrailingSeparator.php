<?php

declare(strict_types=1);

namespace Phi\Nodes\ValidationTraits;

use Phi\Exception\ValidationException;
use Phi\Nodes\Base\SeparatedNodesList;

trait ForbidTrailingSeparator
{
	/**
	 * @template N of \Phi\Node
	 * @phpstan-param SeparatedNodesList<N> $list
	 */
	private static function forbidTrailingSeparator(SeparatedNodesList $list): void
	{
		$separators = $list->getSeparators();
		if ($trailingSeparator = \end($separators))
		{
			throw ValidationException::invalidSyntax($trailingSeparator);
		}
	}
}
