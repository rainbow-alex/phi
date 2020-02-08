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

trait GeneratedDefault
{
    /**
     * @var \Phi\Token|null
     */
    private $equals;

    /**
     * @var \Phi\Nodes\Expression|null
     */
    private $value;

    /**
     * @param \Phi\Nodes\Expression|\Phi\Node|string|null $value
     */
    public function __construct($value = null)
    {
        if ($value !== null)
        {
            $this->setValue($value);
        }
    }

    /**
     * @param \Phi\Token $equals
     * @param \Phi\Nodes\Expression $value
     * @return self
     */
    public static function __instantiateUnchecked($equals, $value)
    {
        $instance = new self;
        $instance->equals = $equals;
        $equals->parent = $instance;
        $instance->value = $value;
        $value->parent = $instance;
        return $instance;
    }

    public function getChildNodes(): array
    {
        return \array_values(\array_filter([
            $this->equals,
            $this->value,
        ]));
    }

    protected function &getChildRef(Node $childToDetach): Node
    {
        if ($this->equals === $childToDetach)
        {
            return $this->equals;
        }
        if ($this->value === $childToDetach)
        {
            return $this->value;
        }
        throw new \LogicException();
    }

    public function getEquals(): \Phi\Token
    {
        if ($this->equals === null)
        {
            throw TreeException::missingNode($this, "equals");
        }
        return $this->equals;
    }

    public function hasEquals(): bool
    {
        return $this->equals !== null;
    }

    /**
     * @param \Phi\Token|\Phi\Node|string|null $equals
     */
    public function setEquals($equals): void
    {
        if ($equals !== null)
        {
            /** @var \Phi\Token $equals */
            $equals = NodeCoercer::coerce($equals, \Phi\Token::class, $this->getPhpVersion());
            $equals->detach();
            $equals->parent = $this;
        }
        if ($this->equals !== null)
        {
            $this->equals->detach();
        }
        $this->equals = $equals;
    }

    public function getValue(): \Phi\Nodes\Expression
    {
        if ($this->value === null)
        {
            throw TreeException::missingNode($this, "value");
        }
        return $this->value;
    }

    public function hasValue(): bool
    {
        return $this->value !== null;
    }

    /**
     * @param \Phi\Nodes\Expression|\Phi\Node|string|null $value
     */
    public function setValue($value): void
    {
        if ($value !== null)
        {
            /** @var \Phi\Nodes\Expression $value */
            $value = NodeCoercer::coerce($value, \Phi\Nodes\Expression::class, $this->getPhpVersion());
            $value->detach();
            $value->parent = $this;
        }
        if ($this->value !== null)
        {
            $this->value->detach();
        }
        $this->value = $value;
    }

    public function _validate(int $flags): void
    {
        if ($this->equals === null)
            throw ValidationException::missingChild($this, "equals");
        if ($this->value === null)
            throw ValidationException::missingChild($this, "value");
        if ($this->equals->getType() !== 116)
            throw ValidationException::invalidSyntax($this->equals, [116]);


        $this->extraValidation($flags);

        $this->value->_validate(1);
    }

    public function _autocorrect(): void
    {
        if ($this->value)
            $this->value->_autocorrect();

        $this->extraAutocorrect();
    }
}
