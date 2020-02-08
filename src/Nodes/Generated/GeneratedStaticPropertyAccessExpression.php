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

trait GeneratedStaticPropertyAccessExpression
{
    /**
     * @var \Phi\Nodes\Expression|null
     */
    private $class;

    /**
     * @var \Phi\Token|null
     */
    private $operator;

    /**
     * @var \Phi\Nodes\Expressions\VariableExpression|null
     */
    private $name;

    /**
     * @param \Phi\Nodes\Expression|\Phi\Node|string|null $class
     * @param \Phi\Nodes\Expressions\VariableExpression|\Phi\Node|string|null $name
     */
    public function __construct($class = null, $name = null)
    {
        if ($class !== null)
        {
            $this->setClass($class);
        }
        if ($name !== null)
        {
            $this->setName($name);
        }
    }

    /**
     * @param \Phi\Nodes\Expression $class
     * @param \Phi\Token $operator
     * @param \Phi\Nodes\Expressions\VariableExpression $name
     * @return self
     */
    public static function __instantiateUnchecked($class, $operator, $name)
    {
        $instance = new self;
        $instance->class = $class;
        $class->parent = $instance;
        $instance->operator = $operator;
        $operator->parent = $instance;
        $instance->name = $name;
        $name->parent = $instance;
        return $instance;
    }

    public function getChildNodes(): array
    {
        return \array_values(\array_filter([
            $this->class,
            $this->operator,
            $this->name,
        ]));
    }

    protected function &getChildRef(Node $childToDetach): Node
    {
        if ($this->class === $childToDetach)
        {
            return $this->class;
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

    public function getClass(): \Phi\Nodes\Expression
    {
        if ($this->class === null)
        {
            throw TreeException::missingNode($this, "class");
        }
        return $this->class;
    }

    public function hasClass(): bool
    {
        return $this->class !== null;
    }

    /**
     * @param \Phi\Nodes\Expression|\Phi\Node|string|null $class
     */
    public function setClass($class): void
    {
        if ($class !== null)
        {
            /** @var \Phi\Nodes\Expression $class */
            $class = NodeCoercer::coerce($class, \Phi\Nodes\Expression::class, $this->getPhpVersion());
            $class->detach();
            $class->parent = $this;
        }
        if ($this->class !== null)
        {
            $this->class->detach();
        }
        $this->class = $class;
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

    public function getName(): \Phi\Nodes\Expressions\VariableExpression
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
     * @param \Phi\Nodes\Expressions\VariableExpression|\Phi\Node|string|null $name
     */
    public function setName($name): void
    {
        if ($name !== null)
        {
            /** @var \Phi\Nodes\Expressions\VariableExpression $name */
            $name = NodeCoercer::coerce($name, \Phi\Nodes\Expressions\VariableExpression::class, $this->getPhpVersion());
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
        if ($this->class === null)
            throw ValidationException::missingChild($this, "class");
        if ($this->operator === null)
            throw ValidationException::missingChild($this, "operator");
        if ($this->name === null)
            throw ValidationException::missingChild($this, "name");
        if ($this->operator->getType() !== 162)
            throw ValidationException::invalidSyntax($this->operator, [162]);


        $this->extraValidation($flags);

        $this->class->_validate(1);
        $this->name->_validate(0);
    }

    public function _autocorrect(): void
    {
        if ($this->class)
            $this->class->_autocorrect();
        if ($this->name)
            $this->name->_autocorrect();

        $this->extraAutocorrect();
    }
}
