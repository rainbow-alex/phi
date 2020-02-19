<?php

declare(strict_types=1);

namespace Phi\Nodes\Helpers;

use Phi\Nodes\Base\CompoundNode;
use Phi\Nodes\Generated\GeneratedName;
use Phi\Nodes\Oop\OopDeclaration;
use Phi\TokenType;
use PhpParser\Node\Name as PPName;
use PhpParser\Node\Name\FullyQualified;

// TODO clean up
class Name extends CompoundNode
{
	use GeneratedName;

	public function isAbsolute(): bool
	{
		$parts = $this->getParts()->getItems();
		return $parts && $parts[0]->getType() === TokenType::T_NS_SEPARATOR;
	}

	public function isUsableAsUse(): bool
	{
		return !$this->endsInSpecialClass() && !$this->endsInReservedWord();
	}

	public function isStaticAccessible(): bool
	{
		return !$this->isNsSpecialClass();
	}

	public function isUsableAsReturnType(): bool
	{
		return ($this->isNonNsSpecialClass() && $this->hasClassScope())
			|| $this->isSpecialType()
			|| (!$this->endsInSpecialClass() && !$this->endsInReservedWord());
	}

	public function isUsableAsParameterType(): bool
	{
		return ($this->isNonNsSpecialClass() && $this->hasClassScope())
			|| ($this->isSpecialType() && !$this->isVoid())
			|| (!$this->endsInSpecialClass() && !$this->endsInReservedWord());
	}

	public function isUsableAsTraitUse(): bool
	{
		return !$this->isSpecialClass();
	}

	public function isNewable(): bool
	{
		return !$this->isNsSpecialClass();
	}

	private function isSpecialClass(): bool
	{
		$parts = $this->getParts()->getItems();
		return \count($parts) <= 2 && TokenType::isSpecialClass(\end($parts));
	}

	private function isNonNsSpecialClass(): bool
	{
		$parts = $this->getParts()->getItems();
		return \count($parts) === 1 && TokenType::isSpecialClass($parts[0]);
	}

	private function isNsSpecialClass(): bool
	{
		$parts = $this->getParts()->getItems();
		return \count($parts) === 2 && TokenType::isSpecialClass(\end($parts));
	}

	private function hasClassScope(): bool
	{
		for ($node = $this; $node; $node = $node->getParent())
		{
			if ($node instanceof OopDeclaration)
			{
				return true;
			}
		}
		return false;
	}

	public function isSpecialType(): bool
	{
		$parts = $this->getParts()->getItems();
		return \count($parts) === 1 && TokenType::isSpecialType($parts[0]);
	}

	private function isVoid(): bool
	{
		$parts = $this->getParts()->getItems();
		return \count($parts) === 1 && \strcasecmp($parts[0]->getSource(), 'void') === 0;
	}

	private function endsInSpecialClass(): bool
	{
		$parts = $this->getParts()->getItems();
		return TokenType::isSpecialClass(\end($parts));
	}

	private function endsInReservedWord(): bool
	{
		$parts = $this->getParts()->getItems();
		return TokenType::isReservedWord(\end($parts));
	}

	// TODO param wtf
	public function convertToPhpParser(bool $forceNotAbsolute = false)
	{
		$parts = [];
		foreach ($this->getParts() as $part)
		{
			if ($part->getType() !== TokenType::T_NS_SEPARATOR)
			{
				$parts[] = $part->getSource();
			}
		}

		if (!$forceNotAbsolute && $this->isAbsolute())
		{
			return new FullyQualified($parts);
		}
		else
		{
			return new PPName($parts);
		}
	}
}
