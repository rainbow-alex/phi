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

trait GeneratedLabelStatement
{
    /**
     * @var \Phi\Token|null
     */
    private $label;

    /**
     * @var \Phi\Token|null
     */
    private $colon;

    /**
     * @param \Phi\Token|\Phi\Node|string|null $label
     */
    public function __construct($label = null)
    {
        if ($label !== null)
        {
            $this->setLabel($label);
        }
    }

    /**
     * @param \Phi\Token $label
     * @param \Phi\Token $colon
     * @return self
     */
    public static function __instantiateUnchecked($label, $colon)
    {
        $instance = new self;
        $instance->label = $label;
        $label->parent = $instance;
        $instance->colon = $colon;
        $colon->parent = $instance;
        return $instance;
    }

    public function getChildNodes(): array
    {
        return \array_values(\array_filter([
            $this->label,
            $this->colon,
        ]));
    }

    protected function &getChildRef(Node $childToDetach): Node
    {
        if ($this->label === $childToDetach)
        {
            return $this->label;
        }
        if ($this->colon === $childToDetach)
        {
            return $this->colon;
        }
        throw new \LogicException();
    }

    public function getLabel(): \Phi\Token
    {
        if ($this->label === null)
        {
            throw TreeException::missingNode($this, "label");
        }
        return $this->label;
    }

    public function hasLabel(): bool
    {
        return $this->label !== null;
    }

    /**
     * @param \Phi\Token|\Phi\Node|string|null $label
     */
    public function setLabel($label): void
    {
        if ($label !== null)
        {
            /** @var \Phi\Token $label */
            $label = NodeCoercer::coerce($label, \Phi\Token::class, $this->getPhpVersion());
            $label->detach();
            $label->parent = $this;
        }
        if ($this->label !== null)
        {
            $this->label->detach();
        }
        $this->label = $label;
    }

    public function getColon(): \Phi\Token
    {
        if ($this->colon === null)
        {
            throw TreeException::missingNode($this, "colon");
        }
        return $this->colon;
    }

    public function hasColon(): bool
    {
        return $this->colon !== null;
    }

    /**
     * @param \Phi\Token|\Phi\Node|string|null $colon
     */
    public function setColon($colon): void
    {
        if ($colon !== null)
        {
            /** @var \Phi\Token $colon */
            $colon = NodeCoercer::coerce($colon, \Phi\Token::class, $this->getPhpVersion());
            $colon->detach();
            $colon->parent = $this;
        }
        if ($this->colon !== null)
        {
            $this->colon->detach();
        }
        $this->colon = $colon;
    }

    public function _validate(int $flags): void
    {
        if ($this->label === null)
            throw ValidationException::missingChild($this, "label");
        if ($this->colon === null)
            throw ValidationException::missingChild($this, "colon");
        if ($this->label->getType() !== 243)
            throw ValidationException::invalidSyntax($this->label, [243]);
        if ($this->colon->getType() !== 113)
            throw ValidationException::invalidSyntax($this->colon, [113]);


        $this->extraValidation($flags);

    }

    public function _autocorrect(): void
    {

        $this->extraAutocorrect();
    }
}
