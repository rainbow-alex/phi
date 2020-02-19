<?php

declare(strict_types=1);

namespace Phi\Tests\Testing;

use Phi\Node;
use Phi\Nodes;
use Phi\Nodes\Base\CompoundNode;
use Phi\Nodes\Base\NodesList;
use Phi\Nodes\Base\SeparatedNodesList;
use Phi\Token;
use Phi\TokenType;

trait NodeAssertions
{
	private static function assertNodeTreeIsCorrect(Node $node): void
	{
		foreach ($node->getChildNodes() as $child)
		{
			self::assertSame($node, $child->getParent());
			self::assertNodeTreeIsCorrect($child);
		}
	}

	private static function assertNodeStructEquals(Node $node1, Node $node2): void
	{
		$repr1 = self::reprNode($node1);
		$repr2 = self::reprNode($node2);
		self::assertEquals($repr1, $repr2,
			"Nodes don't match\n"
			. $repr1 . "\n"
			. $repr2
		);
	}

	public static function reprNode(Node $node): string
	{
		if ($node instanceof Nodes\Expressions\NumberLiteral)
		{
			return $node->getToken()->getSource();
		}
		else if ($node instanceof CompoundNode)
		{
			$children = [];
			foreach ($node->getChildNodes() as $child)
			{
				if ($child)
				{
					$children[] = self::reprNode($child);
				}
			}
			return $node->repr() . "(" . implode(", ", $children) . ")";
		}
		else if ($node instanceof NodesList || $node instanceof SeparatedNodesList)
		{
			$items = [];
			foreach ($node->getChildNodes() as $item)
			{
				$items[] = self::reprNode($item);
			}
			return "[" . implode(", ", $items) . "]";
		}
		else if ($node instanceof Token)
		{
			if (in_array($node->getType(), [TokenType::S_LEFT_PARENTHESIS, TokenType::S_RIGHT_PARENTHESIS], true))
			{
				return "`" . $node->getSource() . "`";
			}
			return $node->getSource();
		}
		else
		{
			throw new \RuntimeException(\get_class($node));
		}
	}
}
