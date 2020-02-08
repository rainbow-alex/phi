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

trait GeneratedParenthesizedExpression
{
    /**
     * @var \Phi\Token|null
     */
    private $leftParenthesis;

    /**
     * @var \Phi\Nodes\Expression|null
     */
    private $expression;

    /**
     * @var \Phi\Token|null
     */
    private $rightParenthesis;

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
     * @param \Phi\Token $leftParenthesis
     * @param \Phi\Nodes\Expression $expression
     * @param \Phi\Token $rightParenthesis
     * @return self
     */
    public static function __instantiateUnchecked($leftParenthesis, $expression, $rightParenthesis)
    {
        $instance = new self;
        $instance->leftParenthesis = $leftParenthesis;
        $leftParenthesis->parent = $instance;
        $instance->expression = $expression;
        $expression->parent = $instance;
        $instance->rightParenthesis = $rightParenthesis;
        $rightParenthesis->parent = $instance;
        return $instance;
    }

    public function getChildNodes(): array
    {
        return \array_values(\array_filter([
            $this->leftParenthesis,
            $this->expression,
            $this->rightParenthesis,
        ]));
    }

    protected function &getChildRef(Node $childToDetach): Node
    {
        if ($this->leftParenthesis === $childToDetach)
        {
            return $this->leftParenthesis;
        }
        if ($this->expression === $childToDetach)
        {
            return $this->expression;
        }
        if ($this->rightParenthesis === $childToDetach)
        {
            return $this->rightParenthesis;
        }
        throw new \LogicException();
    }

    public function getLeftParenthesis(): \Phi\Token
    {
        if ($this->leftParenthesis === null)
        {
            throw TreeException::missingNode($this, "leftParenthesis");
        }
        return $this->leftParenthesis;
    }

    public function hasLeftParenthesis(): bool
    {
        return $this->leftParenthesis !== null;
    }

    /**
     * @param \Phi\Token|\Phi\Node|string|null $leftParenthesis
     */
    public function setLeftParenthesis($leftParenthesis): void
    {
        if ($leftParenthesis !== null)
        {
            /** @var \Phi\Token $leftParenthesis */
            $leftParenthesis = NodeCoercer::coerce($leftParenthesis, \Phi\Token::class, $this->getPhpVersion());
            $leftParenthesis->detach();
            $leftParenthesis->parent = $this;
        }
        if ($this->leftParenthesis !== null)
        {
            $this->leftParenthesis->detach();
        }
        $this->leftParenthesis = $leftParenthesis;
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

    public function getRightParenthesis(): \Phi\Token
    {
        if ($this->rightParenthesis === null)
        {
            throw TreeException::missingNode($this, "rightParenthesis");
        }
        return $this->rightParenthesis;
    }

    public function hasRightParenthesis(): bool
    {
        return $this->rightParenthesis !== null;
    }

    /**
     * @param \Phi\Token|\Phi\Node|string|null $rightParenthesis
     */
    public function setRightParenthesis($rightParenthesis): void
    {
        if ($rightParenthesis !== null)
        {
            /** @var \Phi\Token $rightParenthesis */
            $rightParenthesis = NodeCoercer::coerce($rightParenthesis, \Phi\Token::class, $this->getPhpVersion());
            $rightParenthesis->detach();
            $rightParenthesis->parent = $this;
        }
        if ($this->rightParenthesis !== null)
        {
            $this->rightParenthesis->detach();
        }
        $this->rightParenthesis = $rightParenthesis;
    }

    public function _validate(int $flags): void
    {
        if ($this->leftParenthesis === null)
            throw ValidationException::missingChild($this, "leftParenthesis");
        if ($this->expression === null)
            throw ValidationException::missingChild($this, "expression");
        if ($this->rightParenthesis === null)
            throw ValidationException::missingChild($this, "rightParenthesis");
        if ($this->leftParenthesis->getType() !== 105)
            throw ValidationException::invalidSyntax($this->leftParenthesis, [105]);
        if ($this->rightParenthesis->getType() !== 106)
            throw ValidationException::invalidSyntax($this->rightParenthesis, [106]);

        if ($flags & 12)
            throw ValidationException::invalidExpressionInContext($this);

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
