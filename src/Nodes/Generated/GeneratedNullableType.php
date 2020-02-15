<?php

declare(strict_types=1);

/**
 * This code is generated.
 * @see meta/
 */

namespace Phi\Nodes\Generated;

use Phi\Node;
use Phi\Token;
use Phi\Exception\TreeException;
use Phi\NodeCoercer;
use Phi\Exception\ValidationException;

trait GeneratedNullableType
{
	/**
	 * @var \Phi\Token|null
	 */
	private $questionMark;

	/**
	 * @var \Phi\Nodes\Type|null
	 */
	private $type;

	/**
	 * @param \Phi\Nodes\Type|\Phi\Node|string|null $type
	 */
	public function __construct($type = null)
	{
		if ($type !== null)
		{
			$this->setType($type);
		}
	}

	/**
	 * @param \Phi\Token $questionMark
	 * @param \Phi\Nodes\Type $type
	 * @return self
	 */
	public static function __instantiateUnchecked($questionMark, $type)
	{
		$instance = new self;
	$instance->setQuestionMark($questionMark);
	$instance->setType($type);
		return $instance;
	}

	public function getChildNodes(): array
	{
		return \array_values(\array_filter([
			$this->questionMark,
			$this->type,
		]));
	}

	protected function &getChildRef(Node $childToDetach): Node
	{
		if ($this->questionMark === $childToDetach)
			return $this->questionMark;
		if ($this->type === $childToDetach)
			return $this->type;
		throw new \LogicException();
	}

	public function getQuestionMark(): \Phi\Token
	{
		if ($this->questionMark === null)
		{
			throw TreeException::missingNode($this, "questionMark");
		}
		return $this->questionMark;
	}

	public function hasQuestionMark(): bool
	{
		return $this->questionMark !== null;
	}

	/**
	 * @param \Phi\Token|\Phi\Node|string|null $questionMark
	 */
	public function setQuestionMark($questionMark): void
	{
		if ($questionMark !== null)
		{
			/** @var \Phi\Token $questionMark */
			$questionMark = NodeCoercer::coerce($questionMark, \Phi\Token::class, $this->getPhpVersion());
			$questionMark->detach();
			$questionMark->parent = $this;
		}
		if ($this->questionMark !== null)
		{
			$this->questionMark->detach();
		}
		$this->questionMark = $questionMark;
	}

	public function getType(): \Phi\Nodes\Type
	{
		if ($this->type === null)
		{
			throw TreeException::missingNode($this, "type");
		}
		return $this->type;
	}

	public function hasType(): bool
	{
		return $this->type !== null;
	}

	/**
	 * @param \Phi\Nodes\Type|\Phi\Node|string|null $type
	 */
	public function setType($type): void
	{
		if ($type !== null)
		{
			/** @var \Phi\Nodes\Type $type */
			$type = NodeCoercer::coerce($type, \Phi\Nodes\Type::class, $this->getPhpVersion());
			$type->detach();
			$type->parent = $this;
		}
		if ($this->type !== null)
		{
			$this->type->detach();
		}
		$this->type = $type;
	}

	public function _validate(int $flags): void
	{
		if ($this->questionMark === null)
			throw ValidationException::missingChild($this, "questionMark");
		if ($this->type === null)
			throw ValidationException::missingChild($this, "type");
		if ($this->questionMark->getType() !== 118)
			throw ValidationException::invalidSyntax($this->questionMark, [118]);


		$this->extraValidation($flags);

		$this->type->_validate(0);
	}

	public function _autocorrect(): void
	{
		if (!$this->questionMark)
			$this->setQuestionMark(new Token(118, '?'));
		if ($this->type)
			$this->type->_autocorrect();

		$this->extraAutocorrect();
	}
}
