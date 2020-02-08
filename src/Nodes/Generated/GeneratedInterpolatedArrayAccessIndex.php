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

trait GeneratedInterpolatedArrayAccessIndex
{
    /**
     * @var \Phi\Token|null
     */
    private $minus;

    /**
     * @var \Phi\Token|null
     */
    private $value;

    /**
     * @param \Phi\Token|\Phi\Node|string|null $value
     */
    public function __construct($value = null)
    {
        if ($value !== null)
        {
            $this->setValue($value);
        }
    }

    /**
     * @param \Phi\Token|null $minus
     * @param \Phi\Token $value
     * @return self
     */
    public static function __instantiateUnchecked($minus, $value)
    {
        $instance = new self;
        $instance->minus = $minus;
        if ($minus) $minus->parent = $instance;
        $instance->value = $value;
        $value->parent = $instance;
        return $instance;
    }

    public function getChildNodes(): array
    {
        return \array_values(\array_filter([
            $this->minus,
            $this->value,
        ]));
    }

    protected function &getChildRef(Node $childToDetach): Node
    {
        if ($this->minus === $childToDetach)
        {
            return $this->minus;
        }
        if ($this->value === $childToDetach)
        {
            return $this->value;
        }
        throw new \LogicException();
    }

    public function getMinus(): ?\Phi\Token
    {
        return $this->minus;
    }

    public function hasMinus(): bool
    {
        return $this->minus !== null;
    }

    /**
     * @param \Phi\Token|\Phi\Node|string|null $minus
     */
    public function setMinus($minus): void
    {
        if ($minus !== null)
        {
            /** @var \Phi\Token $minus */
            $minus = NodeCoercer::coerce($minus, \Phi\Token::class, $this->getPhpVersion());
            $minus->detach();
            $minus->parent = $this;
        }
        if ($this->minus !== null)
        {
            $this->minus->detach();
        }
        $this->minus = $minus;
    }

    public function getValue(): \Phi\Token
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
     * @param \Phi\Token|\Phi\Node|string|null $value
     */
    public function setValue($value): void
    {
        if ($value !== null)
        {
            /** @var \Phi\Token $value */
            $value = NodeCoercer::coerce($value, \Phi\Token::class, $this->getPhpVersion());
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
        if ($this->value === null)
            throw ValidationException::missingChild($this, "value");
        if ($this->minus)
        if ($this->minus->getType() !== 110)
            throw ValidationException::invalidSyntax($this->minus, [110]);
        if (!\in_array($this->value->getType(), [220, 243, 255], true))
            throw ValidationException::invalidSyntax($this->value, [220, 243, 255]);


        $this->extraValidation($flags);

    }

    public function _autocorrect(): void
    {

        $this->extraAutocorrect();
    }
}
