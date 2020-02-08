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

trait GeneratedStaticVariable
{
    /**
     * @var \Phi\Token|null
     */
    private $variable;

    /**
     * @var \Phi\Nodes\Helpers\Default_|null
     */
    private $default;

    /**
     */
    public function __construct()
    {
    }

    /**
     * @param \Phi\Token $variable
     * @param \Phi\Nodes\Helpers\Default_|null $default
     * @return self
     */
    public static function __instantiateUnchecked($variable, $default)
    {
        $instance = new self;
        $instance->variable = $variable;
        $variable->parent = $instance;
        $instance->default = $default;
        if ($default) $default->parent = $instance;
        return $instance;
    }

    public function getChildNodes(): array
    {
        return \array_values(\array_filter([
            $this->variable,
            $this->default,
        ]));
    }

    protected function &getChildRef(Node $childToDetach): Node
    {
        if ($this->variable === $childToDetach)
        {
            return $this->variable;
        }
        if ($this->default === $childToDetach)
        {
            return $this->default;
        }
        throw new \LogicException();
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

    public function getDefault(): ?\Phi\Nodes\Helpers\Default_
    {
        return $this->default;
    }

    public function hasDefault(): bool
    {
        return $this->default !== null;
    }

    /**
     * @param \Phi\Nodes\Helpers\Default_|\Phi\Node|string|null $default
     */
    public function setDefault($default): void
    {
        if ($default !== null)
        {
            /** @var \Phi\Nodes\Helpers\Default_ $default */
            $default = NodeCoercer::coerce($default, \Phi\Nodes\Helpers\Default_::class, $this->getPhpVersion());
            $default->detach();
            $default->parent = $this;
        }
        if ($this->default !== null)
        {
            $this->default->detach();
        }
        $this->default = $default;
    }

    public function _validate(int $flags): void
    {
        if ($this->variable === null)
            throw ValidationException::missingChild($this, "variable");
        if ($this->variable->getType() !== 255)
            throw ValidationException::invalidSyntax($this->variable, [255]);


        $this->extraValidation($flags);

        if ($this->default)
            $this->default->_validate(0);
    }

    public function _autocorrect(): void
    {
        if ($this->default)
            $this->default->_autocorrect();

        $this->extraAutocorrect();
    }
}
