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

trait GeneratedPropertyAccessInterpolatedStringVariable
{
    /**
     * @var \Phi\Nodes\Expressions\StringInterpolation\NormalInterpolatedStringVariable|null
     */
    private $variable;

    /**
     * @var \Phi\Token|null
     */
    private $operator;

    /**
     * @var \Phi\Token|null
     */
    private $name;

    /**
     * @param \Phi\Nodes\Expressions\StringInterpolation\NormalInterpolatedStringVariable|\Phi\Node|string|null $variable
     * @param \Phi\Token|\Phi\Node|string|null $name
     */
    public function __construct($variable = null, $name = null)
    {
        if ($variable !== null)
        {
            $this->setVariable($variable);
        }
        if ($name !== null)
        {
            $this->setName($name);
        }
    }

    /**
     * @param \Phi\Nodes\Expressions\StringInterpolation\NormalInterpolatedStringVariable $variable
     * @param \Phi\Token $operator
     * @param \Phi\Token $name
     * @return self
     */
    public static function __instantiateUnchecked($variable, $operator, $name)
    {
        $instance = new self;
        $instance->variable = $variable;
        $variable->parent = $instance;
        $instance->operator = $operator;
        $operator->parent = $instance;
        $instance->name = $name;
        $name->parent = $instance;
        return $instance;
    }

    public function getChildNodes(): array
    {
        return \array_values(\array_filter([
            $this->variable,
            $this->operator,
            $this->name,
        ]));
    }

    protected function &getChildRef(Node $childToDetach): Node
    {
        if ($this->variable === $childToDetach)
        {
            return $this->variable;
        }
        if ($this->operator === $childToDetach)
        {
            return $this->operator;
        }
        if ($this->name === $childToDetach)
        {
            return $this->name;
        }
        throw new \LogicException();
    }

    public function getVariable(): \Phi\Nodes\Expressions\StringInterpolation\NormalInterpolatedStringVariable
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
     * @param \Phi\Nodes\Expressions\StringInterpolation\NormalInterpolatedStringVariable|\Phi\Node|string|null $variable
     */
    public function setVariable($variable): void
    {
        if ($variable !== null)
        {
            /** @var \Phi\Nodes\Expressions\StringInterpolation\NormalInterpolatedStringVariable $variable */
            $variable = NodeCoercer::coerce($variable, \Phi\Nodes\Expressions\StringInterpolation\NormalInterpolatedStringVariable::class, $this->getPhpVersion());
            $variable->detach();
            $variable->parent = $this;
        }
        if ($this->variable !== null)
        {
            $this->variable->detach();
        }
        $this->variable = $variable;
    }

    public function getOperator(): \Phi\Token
    {
        if ($this->operator === null)
        {
            throw TreeException::missingNode($this, "operator");
        }
        return $this->operator;
    }

    public function hasOperator(): bool
    {
        return $this->operator !== null;
    }

    /**
     * @param \Phi\Token|\Phi\Node|string|null $operator
     */
    public function setOperator($operator): void
    {
        if ($operator !== null)
        {
            /** @var \Phi\Token $operator */
            $operator = NodeCoercer::coerce($operator, \Phi\Token::class, $this->getPhpVersion());
            $operator->detach();
            $operator->parent = $this;
        }
        if ($this->operator !== null)
        {
            $this->operator->detach();
        }
        $this->operator = $operator;
    }

    public function getName(): \Phi\Token
    {
        if ($this->name === null)
        {
            throw TreeException::missingNode($this, "name");
        }
        return $this->name;
    }

    public function hasName(): bool
    {
        return $this->name !== null;
    }

    /**
     * @param \Phi\Token|\Phi\Node|string|null $name
     */
    public function setName($name): void
    {
        if ($name !== null)
        {
            /** @var \Phi\Token $name */
            $name = NodeCoercer::coerce($name, \Phi\Token::class, $this->getPhpVersion());
            $name->detach();
            $name->parent = $this;
        }
        if ($this->name !== null)
        {
            $this->name->detach();
        }
        $this->name = $name;
    }

    public function _validate(int $flags): void
    {
        if ($this->variable === null)
            throw ValidationException::missingChild($this, "variable");
        if ($this->operator === null)
            throw ValidationException::missingChild($this, "operator");
        if ($this->name === null)
            throw ValidationException::missingChild($this, "name");
        if ($this->operator->getType() !== 222)
            throw ValidationException::invalidSyntax($this->operator, [222]);
        if ($this->name->getType() !== 243)
            throw ValidationException::invalidSyntax($this->name, [243]);


        $this->extraValidation($flags);

        $this->variable->_validate(0);
    }

    public function _autocorrect(): void
    {
        if ($this->variable)
            $this->variable->_autocorrect();

        $this->extraAutocorrect();
    }
}
