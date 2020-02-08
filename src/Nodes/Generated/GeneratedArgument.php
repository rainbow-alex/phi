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

trait GeneratedArgument
{
    /**
     * @var \Phi\Token|null
     */
    private $unpack;

    /**
     * @var \Phi\Nodes\Expression|null
     */
    private $expression;

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
     * @param \Phi\Token|null $unpack
     * @param \Phi\Nodes\Expression $expression
     * @return self
     */
    public static function __instantiateUnchecked($unpack, $expression)
    {
        $instance = new self;
        $instance->unpack = $unpack;
        if ($unpack) $unpack->parent = $instance;
        $instance->expression = $expression;
        $expression->parent = $instance;
        return $instance;
    }

    public function getChildNodes(): array
    {
        return \array_values(\array_filter([
            $this->unpack,
            $this->expression,
        ]));
    }

    protected function &getChildRef(Node $childToDetach): Node
    {
        if ($this->unpack === $childToDetach)
        {
            return $this->unpack;
        }
        if ($this->expression === $childToDetach)
        {
            return $this->expression;
        }
        throw new \LogicException();
    }

    public function getUnpack(): ?\Phi\Token
    {
        return $this->unpack;
    }

    public function hasUnpack(): bool
    {
        return $this->unpack !== null;
    }

    /**
     * @param \Phi\Token|\Phi\Node|string|null $unpack
     */
    public function setUnpack($unpack): void
    {
        if ($unpack !== null)
        {
            /** @var \Phi\Token $unpack */
            $unpack = NodeCoercer::coerce($unpack, \Phi\Token::class, $this->getPhpVersion());
            $unpack->detach();
            $unpack->parent = $this;
        }
        if ($this->unpack !== null)
        {
            $this->unpack->detach();
        }
        $this->unpack = $unpack;
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

    public function _validate(int $flags): void
    {
        if ($this->expression === null)
            throw ValidationException::missingChild($this, "expression");
        if ($this->unpack)
        if ($this->unpack->getType() !== 164)
            throw ValidationException::invalidSyntax($this->unpack, [164]);


        $this->extraValidation($flags);

        $this->expression->_validate($this->unpack ? self::CTX_READ : self::CTX_READ|self::CTX_READ_IMPLICIT_BY_REF);
    }

    public function _autocorrect(): void
    {
        if ($this->expression)
            $this->expression->_autocorrect();

        $this->extraAutocorrect();
    }
}
