<?php

declare(strict_types=1);

namespace Phi\Nodes\Base;

use Phi\Exception\ValidationException;
use Phi\Node;
use Phi\Nodes\Expression;
use Phi\Nodes\Expressions\ArrayAccessExpression;
use Phi\Nodes\Expressions\ParenthesizedExpression;
use Phi\Nodes\Oop\OopMember;
use Phi\Nodes\RootNode;
use Phi\Nodes\Statement;
use Phi\Util\Console;

abstract class CompoundNode extends Node
{
	abstract protected function &getChildRef(Node $child): Node;

	protected function detachChild(Node $child, Node $replacement = null): void
	{
		assert(!($child instanceof NodesList));
		assert(!($child instanceof SeparatedNodesList));
		$ref =& $this->getChildRef($child);
		$ref = $replacement;
	}

	public function iterTokens(): iterable
	{
		foreach ($this->getChildNodes() as $child)
		{
			yield from $child->iterTokens();
		}
	}

	public function clone(): Node
	{
		$clone = parent::clone();
		assert($clone instanceof self);
		foreach ($this->getChildNodes() as $child)
		{
			$ref =& $clone->getChildRef($child);
			$ref = $child->clone();
			$ref->parent = $clone;
		}
		return $clone;
	}

	/**
	 * @throws ValidationException
	 */
	final public function validate(): void
	{
		$this->_validate(0);
		parent::validate();
	}

	// usually _validate is given just one of these flags (exclusively)
	// but occasionally a combination is required, e.g.:
	// expr++ -> READ|WRITE
	// [&expr] -> READ|ALIAS_WRITE
	/** @internal */
	public const CTX_READ = 0x01;
	/** @internal */
	public const CTX_WRITE = 0x02;
	/** @internal */
	public const CTX_ALIAS_WRITE = 0x04;
	/** @internal */
	public const CTX_ALIAS_READ = 0x08;

	/**
	 * this is *added* to CTX_READ in a few rare cases,
	 * used to allow [] in 'read' context
	 * e.g. foo($a[]), $a[] += 4
	 * @see ArrayAccessExpression::extraValidation()
	 * @internal
	 */
	public const CTX_LENIENT_READ = 0x10;

	/**
	 * @throws ValidationException
	 * @internal
	 */
	abstract public function _validate(int $flags): void;

	protected function extraValidation(int $flags): void
	{
		// nop
	}

	public function autocorrect(): void
	{
		$this->_autocorrect();
		parent::autocorrect();
	}

	abstract public function _autocorrect(): void;

	protected function extraAutocorrect(): void
	{
		// nop
	}

	public function toPhp(): string
	{
		$php = "";
		foreach ($this->getChildNodes() as $child)
		{
			$php .= $child->toPhp();
		}
		return $php;
	}

	public function debugDump(string $indent = ""): void
	{
		$important = ($this instanceof RootNode || $this instanceof Statement || $this instanceof Expression || $this instanceof OopMember);
		echo ($important ? Console::bold($this->repr()) : $this->repr()) . "\n";

		$children = [];
		foreach ((array) $this as $key => $child)
		{
			$key = \explode("\0", $key);
			$key = \end($key);
			assert($key !== false);

			if ($key === "_phpVersion" || $key === "parent")
			{
				continue;
			}

			$children[$key] = $child;
		}

		foreach ($children as $name => $child)
		{
			$lastChild = ($child === \end($children));

			echo $indent . Console::faint($lastChild ? " └─ " : " ├─ ") . Console::blue($name) . ": ";

			if ($child instanceof Node)
			{
				$child->debugDump($indent . Console::faint($lastChild ? "    " : " │  "));
			}
			else
			{
				echo "~\n";
			}
		}
	}
}
