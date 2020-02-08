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

trait GeneratedAnonymousFunctionUseBinding
{
    /**
     * @var \Phi\Token|null
     */
    private $byReference;

    /**
     * @var \Phi\Token|null
     */
    private $variable;

    /**
     */
    public function __construct()
    {
    }

    /**
     * @param \Phi\Token|null $byReference
     * @param \Phi\Token $variable
     * @return self
     */
    public static function __instantiateUnchecked($byReference, $variable)
    {
        $instance = new self;
        $instance->byReference = $byReference;
        if ($byReference) $byReference->parent = $instance;
        $instance->variable = $variable;
        $variable->parent = $instance;
        return $instance;
    }

    public function getChildNodes(): array
    {
        return \array_values(\array_filter([
            $this->byReference,
            $this->variable,
        ]));
    }

    protected function &getChildRef(Node $childToDetach): Node
    {
        if ($this->byReference === $childToDetach)
        {
            return $this->byReference;
        }
        if ($this->variable === $childToDetach)
        {
            return $this->variable;
        }
        throw new \LogicException();
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

    public function getVariable(): \Phi\Token
    {
        if ($this->variable === null)
        {
            throw TreeException::missingNode($this, "variable");
        }
        return $this->variable;
    }

    public function hasVariable(): bool
    {
        return $this->variable !== null;
    }

    /**
     * @param \Phi\Token|\Phi\Node|string|null $variable
     */
    public function setVariable($variable): void
    {
        if ($variable !== null)
        {
            /** @var \Phi\Token $variable */
            $variable = NodeCoercer::coerce($variable, \Phi\Token::class, $this->getPhpVersion());
            $variable->detach();
            $variable->parent = $this;
        }
        if ($this->variable !== null)
        {
            $this->variable->detach();
        }
        $this->variable = $variable;
    }

    public function _validate(int $flags): void
    {
        if ($this->variable === null)
            throw ValidationException::missingChild($this, "variable");
        if ($this->byReference)
        if ($this->byReference->getType() !== 104)
            throw ValidationException::invalidSyntax($this->byReference, [104]);
        if ($this->variable->getType() !== 255)
            throw ValidationException::invalidSyntax($this->variable, [255]);


        $this->extraValidation($flags);

    }

    public function _autocorrect(): void
    {

        $this->extraAutocorrect();
    }
}
