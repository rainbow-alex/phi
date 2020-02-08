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

trait GeneratedArrayItem
{
    /**
     * @var \Phi\Nodes\Helpers\Key|null
     */
    private $key;

    /**
     * @var \Phi\Token|null
     */
    private $byReference;

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
     * @param \Phi\Nodes\Helpers\Key|null $key
     * @param \Phi\Token|null $byReference
     * @param \Phi\Nodes\Expression|null $value
     * @return self
     */
    public static function __instantiateUnchecked($key, $byReference, $value)
    {
        $instance = new self;
        $instance->key = $key;
        if ($key) $key->parent = $instance;
        $instance->byReference = $byReference;
        if ($byReference) $byReference->parent = $instance;
        $instance->value = $value;
        if ($value) $value->parent = $instance;
        return $instance;
    }

    public function getChildNodes(): array
    {
        return \array_values(\array_filter([
            $this->key,
            $this->byReference,
            $this->value,
        ]));
    }

    protected function &getChildRef(Node $childToDetach): Node
    {
        if ($this->key === $childToDetach)
        {
            return $this->key;
        }
        if ($this->byReference === $childToDetach)
        {
            return $this->byReference;
        }
        if ($this->value === $childToDetach)
        {
            return $this->value;
        }
        throw new \LogicException();
    }

    public function getKey(): ?\Phi\Nodes\Helpers\Key
    {
        return $this->key;
    }

    public function hasKey(): bool
    {
        return $this->key !== null;
    }

    /**
     * @param \Phi\Nodes\Helpers\Key|\Phi\Node|string|null $key
     */
    public function setKey($key): void
    {
        if ($key !== null)
        {
            /** @var \Phi\Nodes\Helpers\Key $key */
            $key = NodeCoercer::coerce($key, \Phi\Nodes\Helpers\Key::class, $this->getPhpVersion());
            $key->detach();
            $key->parent = $this;
        }
        if ($this->key !== null)
        {
            $this->key->detach();
        }
        $this->key = $key;
    }

    public function getByReference(): ?\Phi\Token
    {
        return $this->byReference;
    }

    public function hasByReference(): bool
    {
        return $this->byReference !== null;
    }

    /**
     * @param \Phi\Token|\Phi\Node|string|null $byReference
     */
    public function setByReference($byReference): void
    {
        if ($byReference !== null)
        {
            /** @var \Phi\Token $byReference */
            $byReference = NodeCoercer::coerce($byReference, \Phi\Token::class, $this->getPhpVersion());
            $byReference->detach();
            $byReference->parent = $this;
        }
        if ($this->byReference !== null)
        {
            $this->byReference->detach();
        }
        $this->byReference = $byReference;
    }

    public function getValue(): ?\Phi\Nodes\Expression
    {
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
        if ($this->byReference)
        if ($this->byReference->getType() !== 104)
            throw ValidationException::invalidSyntax($this->byReference, [104]);


        $this->extraValidation($flags);

        if ($this->key)
            $this->key->_validate(1);
        if ($this->value)
            $this->value->_validate($flags);
    }

    public function _autocorrect(): void
    {
        if ($this->key)
            $this->key->_autocorrect();
        if ($this->value)
            $this->value->_autocorrect();

        $this->extraAutocorrect();
    }
}
