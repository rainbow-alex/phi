<?php

namespace Phi\Nodes\Generated;

use Phi\Node;
use Phi\Token;
use Phi\Nodes\Base\CompoundNode;
use Phi\Nodes\Base\NodesList;
use Phi\Nodes\Base\SeparatedNodesList;
use Phi\Exception\MissingNodeException;
use Phi\NodeConverter;
use Phi\Exception\ValidationException;
use Phi\Nodes as Nodes;

abstract class GeneratedYieldExpression extends Nodes\Expression
{
    /**
     * @var Token|null
     */
    private $keyword;

    /**
     * @var Nodes\Key|null
     */
    private $key;

    /**
     * @var Nodes\Expression|null
     */
    private $expression;

    /**
     * @param Nodes\Expression|Node|string|null $expression
     */
    public function __construct($expression = null)
    {
        if ($expression !== null)
        {
            $this->setExpression($expression);
        }
    }

    /**
     * @param int $phpVersion
     * @param Token|null $keyword
     * @param Nodes\Key|null $key
     * @param Nodes\Expression|null $expression
     * @return static
     */
    public static function __instantiateUnchecked($phpVersion, $keyword, $key, $expression)
    {
        $instance = new static;
        $instance->phpVersion = $phpVersion;
        $instance->keyword = $keyword;
        $instance->keyword->parent = $instance;
        $instance->key = $key;
        if ($key)
        {
            $instance->key->parent = $instance;
        }
        $instance->expression = $expression;
        $instance->expression->parent = $instance;
        return $instance;
    }

    protected function &_getNodeRefs(): array
    {
        $refs = [
            "keyword" => &$this->keyword,
            "key" => &$this->key,
            "expression" => &$this->expression,
        ];
        return $refs;
    }

    public function getKeyword(): Token
    {
        if ($this->keyword === null)
        {
            throw new MissingNodeException($this, __FUNCTION__);
        }
        return $this->keyword;
    }

    public function hasKeyword(): bool
    {
        return $this->keyword !== null;
    }

    /**
     * @param Token|Node|string|null $keyword
     */
    public function setKeyword($keyword): void
    {
        if ($keyword !== null)
        {
            /** @var Token $keyword */
            $keyword = NodeConverter::convert($keyword, Token::class, $this->phpVersion);
            $keyword->detach();
            $keyword->parent = $this;
        }
        if ($this->keyword !== null)
        {
            $this->keyword->detach();
        }
        $this->keyword = $keyword;
    }

    public function getKey(): ?Nodes\Key
    {
        return $this->key;
    }

    public function hasKey(): bool
    {
        return $this->key !== null;
    }

    /**
     * @param Nodes\Key|Node|string|null $key
     */
    public function setKey($key): void
    {
        if ($key !== null)
        {
            /** @var Nodes\Key $key */
            $key = NodeConverter::convert($key, Nodes\Key::class, $this->phpVersion);
            $key->detach();
            $key->parent = $this;
        }
        if ($this->key !== null)
        {
            $this->key->detach();
        }
        $this->key = $key;
    }

    public function getExpression(): Nodes\Expression
    {
        if ($this->expression === null)
        {
            throw new MissingNodeException($this, __FUNCTION__);
        }
        return $this->expression;
    }

    public function hasExpression(): bool
    {
        return $this->expression !== null;
    }

    /**
     * @param Nodes\Expression|Node|string|null $expression
     */
    public function setExpression($expression): void
    {
        if ($expression !== null)
        {
            /** @var Nodes\Expression $expression */
            $expression = NodeConverter::convert($expression, Nodes\Expression::class, $this->phpVersion);
            $expression->detach();
            $expression->parent = $this;
        }
        if ($this->expression !== null)
        {
            $this->expression->detach();
        }
        $this->expression = $expression;
    }

    protected function _validate(int $flags): void
    {
        if ($flags & self::VALIDATE_TYPES)
        {
            if ($this->keyword === null) throw ValidationException::childRequired($this, "keyword");
            if ($this->expression === null) throw ValidationException::childRequired($this, "expression");
        }
        if ($flags & self::VALIDATE_EXPRESSION_CONTEXT)
        {
        }
        if ($flags & self::VALIDATE_TOKENS)
        {
        }
        if ($this->key)
        {
            $this->key->_validate($flags);
        }
        $this->expression->_validate($flags);
    }
}
