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

trait GeneratedInstanceofExpression
{
    /**
     * @var \Phi\Nodes\Expression|null
     */
    private $expression;

    /**
     * @var \Phi\Token|null
     */
    private $operator;

    /**
     * @var \Phi\Nodes\Expression|null
     */
    private $class;

    /**
     * @param \Phi\Nodes\Expression|\Phi\Node|string|null $expression
     * @param \Phi\Nodes\Expression|\Phi\Node|string|null $class
     */
    public function __construct($expression = null, $class = null)
    {
        if ($expression !== null)
        {
            $this->setExpression($expression);
        }
        if ($class !== null)
        {
            $this->setClass($class);
        }
    }

    /**
     * @param \Phi\Nodes\Expression $expression
     * @param \Phi\Token $operator
     * @param \Phi\Nodes\Expression $class
     * @return self
     */
    public static function __instantiateUnchecked($expression, $operator, $class)
    {
        $instance = new self;
        $instance->expression = $expression;
        $expression->parent = $instance;
        $instance->operator = $operator;
        $operator->parent = $instance;
        $instance->class = $class;
        $class->parent = $instance;
        return $instance;
    }

    public function getChildNodes(): array
    {
        return \array_values(\array_filter([
            $this->expression,
            $this->operator,
            $this->class,
        ]));
    }

    protected function &getChildRef(Node $childToDetach): Node
    {
        if ($this->expression === $childToDetach)
        {
            return $this->expression;
        }
        if ($this->operator === $childToDetach)
        {
            return $this->operator;
        }
        if ($this->class === $childToDetach)
        {
            return $this->class;
        }
        throw new \LogicException();
    }

    public function getExpression(): \Phi\Nodes\Expression
    {
        if ($this->expression === null)
        {
            throw TreeException::missingNode($this, "expression");
        }
        return $this->expression;
    }

    public function hasExpression(): bool
    {
        return $this->expression !== null;
    }

    /**
     * @param \Phi\Nodes\Expression|\Phi\Node|string|null $expression
     */
    public function setExpression($expression): void
    {
        if ($expression !== null)
        {
            /** @var \Phi\Nodes\Expression $expression */
            $expression = NodeCoercer::coerce($expression, \Phi\Nodes\Expression::class, $this->getPhpVersion());
            $expression->detach();
            $expression->parent = $this;
        }
        if ($this->expression !== null)
        {
            $this->expression->detach();
        }
        $this->expression = $expression;
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

    public function _validate(int $flags): void
    {
        if ($this->expression === null)
            throw ValidationException::missingChild($this, "expression");
        if ($this->operator === null)
            throw ValidationException::missingChild($this, "operator");
        if ($this->class === null)
            throw ValidationException::missingChild($this, "class");
        if ($this->operator->getType() !== 195)
            throw ValidationException::invalidSyntax($this->operator, [195]);

        if ($flags & 14)
            throw ValidationException::invalidExpressionInContext($this);

        $this->extraValidation($flags);

        $this->expression->_validate(1);
        $this->class->_validate(1);
    }

    public function _autocorrect(): void
    {
        if ($this->expression)
            $this->expression->_autocorrect();
        if ($this->class)
            $this->class->_autocorrect();

        $this->extraAutocorrect();
    }
}
