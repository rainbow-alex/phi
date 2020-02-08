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

trait GeneratedKey
{
    /**
     * @var \Phi\Nodes\Expression|null
     */
    private $expression;

    /**
     * @var \Phi\Token|null
     */
    private $arrow;

    /**
     * @param \Phi\Nodes\Expression|\Phi\Node|string|null $expression
     */
    public function __construct($expression = null)
    {
        if ($expression !== null)
        {
            $this->setExpression($expression);
        }
    }

    /**
     * @param \Phi\Nodes\Expression $expression
     * @param \Phi\Token $arrow
     * @return self
     */
    public static function __instantiateUnchecked($expression, $arrow)
    {
        $instance = new self;
        $instance->expression = $expression;
        $expression->parent = $instance;
        $instance->arrow = $arrow;
        $arrow->parent = $instance;
        return $instance;
    }

    public function getChildNodes(): array
    {
        return \array_values(\array_filter([
            $this->expression,
            $this->arrow,
        ]));
    }

    protected function &getChildRef(Node $childToDetach): Node
    {
        if ($this->expression === $childToDetach)
        {
            return $this->expression;
        }
        if ($this->arrow === $childToDetach)
        {
            return $this->arrow;
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

    public function getArrow(): \Phi\Token
    {
        if ($this->arrow === null)
        {
            throw TreeException::missingNode($this, "arrow");
        }
        return $this->arrow;
    }

    public function hasArrow(): bool
    {
        return $this->arrow !== null;
    }

    /**
     * @param \Phi\Token|\Phi\Node|string|null $arrow
     */
    public function setArrow($arrow): void
    {
        if ($arrow !== null)
        {
            /** @var \Phi\Token $arrow */
            $arrow = NodeCoercer::coerce($arrow, \Phi\Token::class, $this->getPhpVersion());
            $arrow->detach();
            $arrow->parent = $this;
        }
        if ($this->arrow !== null)
        {
            $this->arrow->detach();
        }
        $this->arrow = $arrow;
    }

    public function _validate(int $flags): void
    {
        if ($this->expression === null)
            throw ValidationException::missingChild($this, "expression");
        if ($this->arrow === null)
            throw ValidationException::missingChild($this, "arrow");
        if ($this->arrow->getType() !== 160)
            throw ValidationException::invalidSyntax($this->arrow, [160]);


        $this->extraValidation($flags);

        $this->expression->_validate($flags);
    }

    public function _autocorrect(): void
    {
        if ($this->expression)
            $this->expression->_autocorrect();

        $this->extraAutocorrect();
    }
}
