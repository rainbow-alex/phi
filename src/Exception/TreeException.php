<?php

declare(strict_types=1);

namespace Phi\Exception;

use Phi\Node;

class TreeException extends PhiException
{
	public static function missingNode(Node $node, string $childName): self
	{
		return new self($node->repr() . " is missing '" . $childName . "'", $node);
	}

	public static function cantDetachList(Node $node): self
	{
		return new self("List nodes can't be detached", $node);
	}
}
