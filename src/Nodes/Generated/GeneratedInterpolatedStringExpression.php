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

trait GeneratedInterpolatedStringExpression
{
    /**
     * @var \Phi\Token|null
     */
    private $leftDelimiter;

    /**
     * @var \Phi\Nodes\Expression|null
     */
    private $expression;

    /**
     * @var \Phi\Token|null
     */
    private $rightDelimiter;

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
     * @param \Phi\Token $leftDelimiter
     * @param \Phi\Nodes\Expression $expression
     * @param \Phi\Token $rightDelimiter
     * @return self
     */
    public static function __instantiateUnchecked($leftDelimiter, $expression, $rightDelimiter)
    {
        $instance = new self;
        $instance->leftDelimiter = $leftDelimiter;
        $leftDelimiter->parent = $instance;
        $instance->expression = $expression;
        $expression->parent = $instance;
        $instance->rightDelimiter = $rightDelimiter;
        $rightDelimiter->parent = $instance;
        return $instance;
    }

    public function getChildNodes(): array
    {
        return \array_values(\array_filter([
            $this->leftDelimiter,
            $this->expression,
            $this->rightDelimiter,
        ]));
    }

    protected function &getChildRef(Node $childToDetach): Node
    {
        if ($this->leftDelimiter === $childToDetach)
        {
            return $this->leftDelimiter;
        }
        if ($this->expression === $childToDetach)
        {
            return $this->expression;
        }
        if ($this->rightDelimiter === $childToDetach)
        {
            return $this->rightDelimiter;
        }
        throw new \LogicException();
    }

    public function getLeftDelimiter(): \Phi\Token
    {
        if ($this->leftDelimiter === null)
        {
            throw TreeException::missingNode($this, "leftDelimiter");
        }
        return $this->leftDelimiter;
    }

    public function hasLeftDelimiter(): bool
    {
        return $this->leftDelimiter !== null;
    }

    /**
     * @param \Phi\Token|\Phi\Node|string|null $leftDelimiter
     */
    public function setLeftDelimiter($leftDelimiter): void
    {
        if ($leftDelimiter !== null)
        {
            /** @var \Phi\Token $leftDelimiter */
            $leftDelimiter = NodeCoercer::coerce($leftDelimiter, \Phi\Token::class, $this->getPhpVersion());
            $leftDelimiter->detach();
            $leftDelimiter->parent = $this;
        }
        if ($this->leftDelimiter !== null)
        {
            $this->leftDelimiter->detach();
        }
        $this->leftDelimiter = $leftDelimiter;
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

    public function getRightDelimiter(): \Phi\Token
    {
        if ($this->rightDelimiter === null)
        {
            throw TreeException::missingNode($this, "rightDelimiter");
        }
        return $this->rightDelimiter;
    }

    public function hasRightDelimiter(): bool
    {
        return $this->rightDelimiter !== null;
    }

    /**
     * @param \Phi\Token|\Phi\Node|string|null $rightDelimiter
     */
    public function setRightDelimiter($rightDelimiter): void
    {
        if ($rightDelimiter !== null)
        {
            /** @var \Phi\Token $rightDelimiter */
            $rightDelimiter = NodeCoercer::coerce($rightDelimiter, \Phi\Token::class, $this->getPhpVersion());
            $rightDelimiter->detach();
            $rightDelimiter->parent = $this;
        }
        if ($this->rightDelimiter !== null)
        {
            $this->rightDelimiter->detach();
        }
        $this->rightDelimiter = $rightDelimiter;
    }

    public function _validate(int $flags): void
    {
        if ($this->leftDelimiter === null)
            throw ValidationException::missingChild($this, "leftDelimiter");
        if ($this->expression === null)
            throw ValidationException::missingChild($this, "expression");
        if ($this->rightDelimiter === null)
            throw ValidationException::missingChild($this, "rightDelimiter");
        if ($this->leftDelimiter->getType() !== 124)
            throw ValidationException::invalidSyntax($this->leftDelimiter, [124]);
        if ($this->rightDelimiter->getType() !== 126)
            throw ValidationException::invalidSyntax($this->rightDelimiter, [126]);


        $this->extraValidation($flags);

        $this->expression->_validate(1);
    }

    public function _autocorrect(): void
    {
        if ($this->expression)
            $this->expression->_autocorrect();

        $this->extraAutocorrect();
    }
}
