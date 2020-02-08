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

trait GeneratedExpressionStatement
{
    /**
     * @var \Phi\Nodes\Expression|null
     */
    private $expression;

    /**
     * @var \Phi\Token|null
     */
    private $semiColon;

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
     * @param \Phi\Token|null $semiColon
     * @return self
     */
    public static function __instantiateUnchecked($expression, $semiColon)
    {
        $instance = new self;
        $instance->expression = $expression;
        $expression->parent = $instance;
        $instance->semiColon = $semiColon;
        if ($semiColon) $semiColon->parent = $instance;
        return $instance;
    }

    public function getChildNodes(): array
    {
        return \array_values(\array_filter([
            $this->expression,
            $this->semiColon,
        ]));
    }

    protected function &getChildRef(Node $childToDetach): Node
    {
        if ($this->expression === $childToDetach)
        {
            return $this->expression;
        }
        if ($this->semiColon === $childToDetach)
        {
            return $this->semiColon;
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

    public function getSemiColon(): ?\Phi\Token
    {
        return $this->semiColon;
    }

    public function hasSemiColon(): bool
    {
        return $this->semiColon !== null;
    }

    /**
     * @param \Phi\Token|\Phi\Node|string|null $semiColon
     */
    public function setSemiColon($semiColon): void
    {
        if ($semiColon !== null)
        {
            /** @var \Phi\Token $semiColon */
            $semiColon = NodeCoercer::coerce($semiColon, \Phi\Token::class, $this->getPhpVersion());
            $semiColon->detach();
            $semiColon->parent = $this;
        }
        if ($this->semiColon !== null)
        {
            $this->semiColon->detach();
        }
        $this->semiColon = $semiColon;
    }

    public function _validate(int $flags): void
    {
        if ($this->expression === null)
            throw ValidationException::missingChild($this, "expression");
        if ($this->semiColon)
        if (!\in_array($this->semiColon->getType(), [114, 143], true))
            throw ValidationException::invalidSyntax($this->semiColon, [114, 143]);


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
