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

trait GeneratedYieldExpression
{
    /**
     * @var \Phi\Token|null
     */
    private $keyword;

    /**
     * @var \Phi\Nodes\Helpers\Key|null
     */
    private $key;

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
     * @param \Phi\Token $keyword
     * @param \Phi\Nodes\Helpers\Key|null $key
     * @param \Phi\Nodes\Expression|null $expression
     * @return self
     */
    public static function __instantiateUnchecked($keyword, $key, $expression)
    {
        $instance = new self;
        $instance->keyword = $keyword;
        $keyword->parent = $instance;
        $instance->key = $key;
        if ($key) $key->parent = $instance;
        $instance->expression = $expression;
        if ($expression) $expression->parent = $instance;
        return $instance;
    }

    public function getChildNodes(): array
    {
        return \array_values(\array_filter([
            $this->keyword,
            $this->key,
            $this->expression,
        ]));
    }

    protected function &getChildRef(Node $childToDetach): Node
    {
        if ($this->keyword === $childToDetach)
        {
            return $this->keyword;
        }
        if ($this->key === $childToDetach)
        {
            return $this->key;
        }
        if ($this->expression === $childToDetach)
        {
            return $this->expression;
        }
        throw new \LogicException();
    }

    public function getKeyword(): \Phi\Token
    {
        if ($this->keyword === null)
        {
            throw TreeException::missingNode($this, "keyword");
        }
        return $this->keyword;
    }

    public function hasKeyword(): bool
    {
        return $this->keyword !== null;
    }

    /**
     * @param \Phi\Token|\Phi\Node|string|null $keyword
     */
    public function setKeyword($keyword): void
    {
        if ($keyword !== null)
        {
            /** @var \Phi\Token $keyword */
            $keyword = NodeCoercer::coerce($keyword, \Phi\Token::class, $this->getPhpVersion());
            $keyword->detach();
            $keyword->parent = $this;
        }
        if ($this->keyword !== null)
        {
            $this->keyword->detach();
        }
        $this->keyword = $keyword;
    }

    public function getKey(): ?\Phi\Nodes\Helpers\Key
    {
        return $this->key;
    }

    public function hasKey(): bool
    {
        return $this->key !== null;
    }

    /**
     * @param \Phi\Nodes\Helpers\Key|\Phi\Node|string|null $key
     */
    public function setKey($key): void
    {
        if ($key !== null)
        {
            /** @var \Phi\Nodes\Helpers\Key $key */
            $key = NodeCoercer::coerce($key, \Phi\Nodes\Helpers\Key::class, $this->getPhpVersion());
            $key->detach();
            $key->parent = $this;
        }
        if ($this->key !== null)
        {
            $this->key->detach();
        }
        $this->key = $key;
    }

    public function getExpression(): ?\Phi\Nodes\Expression
    {
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
        if ($this->keyword === null)
            throw ValidationException::missingChild($this, "keyword");
        if ($this->keyword->getType() !== 259)
            throw ValidationException::invalidSyntax($this->keyword, [259]);

        if ($flags & 14)
            throw ValidationException::invalidExpressionInContext($this);

        $this->extraValidation($flags);

        if ($this->key)
            $this->key->_validate(1);
        if ($this->expression)
            $this->expression->_validate(1);
    }

    public function _autocorrect(): void
    {
        if ($this->key)
            $this->key->_autocorrect();
        if ($this->expression)
            $this->expression->_autocorrect();

        $this->extraAutocorrect();
    }
}
