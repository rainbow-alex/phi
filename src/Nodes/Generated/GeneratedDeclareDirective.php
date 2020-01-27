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

abstract class GeneratedDeclareDirective extends CompoundNode
{
    /**
     * @var Token|null
     */
    private $key;

    /**
     * @var Token|null
     */
    private $equals;

    /**
     * @var Nodes\Expression|null
     */
    private $value;

    /**
     */
    public function __construct()
    {
    }

    /**
     * @param int $phpVersion
     * @param Token|null $key
     * @param Token|null $equals
     * @param Nodes\Expression|null $value
     * @return static
     */
    public static function __instantiateUnchecked($phpVersion, $key, $equals, $value)
    {
        $instance = new static;
        $instance->phpVersion = $phpVersion;
        $instance->key = $key;
        $instance->key->parent = $instance;
        $instance->equals = $equals;
        $instance->equals->parent = $instance;
        $instance->value = $value;
        $instance->value->parent = $instance;
        return $instance;
    }

    protected function &_getNodeRefs(): array
    {
        $refs = [
            "key" => &$this->key,
            "equals" => &$this->equals,
            "value" => &$this->value,
        ];
        return $refs;
    }

    public function getKey(): Token
    {
        if ($this->key === null)
        {
            throw new MissingNodeException($this, __FUNCTION__);
        }
        return $this->key;
    }

    public function hasKey(): bool
    {
        return $this->key !== null;
    }

    /**
     * @param Token|Node|string|null $key
     */
    public function setKey($key): void
    {
        if ($key !== null)
        {
            /** @var Token $key */
            $key = NodeConverter::convert($key, Token::class, $this->phpVersion);
            $key->detach();
            $key->parent = $this;
        }
        if ($this->key !== null)
        {
            $this->key->detach();
        }
        $this->key = $key;
    }

    public function getEquals(): Token
    {
        if ($this->equals === null)
        {
            throw new MissingNodeException($this, __FUNCTION__);
        }
        return $this->equals;
    }

    public function hasEquals(): bool
    {
        return $this->equals !== null;
    }

    /**
     * @param Token|Node|string|null $equals
     */
    public function setEquals($equals): void
    {
        if ($equals !== null)
        {
            /** @var Token $equals */
            $equals = NodeConverter::convert($equals, Token::class, $this->phpVersion);
            $equals->detach();
            $equals->parent = $this;
        }
        if ($this->equals !== null)
        {
            $this->equals->detach();
        }
        $this->equals = $equals;
    }

    public function getValue(): Nodes\Expression
    {
        if ($this->value === null)
        {
            throw new MissingNodeException($this, __FUNCTION__);
        }
        return $this->value;
    }

    public function hasValue(): bool
    {
        return $this->value !== null;
    }

    /**
     * @param Nodes\Expression|Node|string|null $value
     */
    public function setValue($value): void
    {
        if ($value !== null)
        {
            /** @var Nodes\Expression $value */
            $value = NodeConverter::convert($value, Nodes\Expression::class, $this->phpVersion);
            $value->detach();
            $value->parent = $this;
        }
        if ($this->value !== null)
        {
            $this->value->detach();
        }
        $this->value = $value;
    }

    protected function _validate(int $flags): void
    {
        if ($flags & self::VALIDATE_TYPES)
        {
            if ($this->key === null) throw ValidationException::childRequired($this, "key");
            if ($this->equals === null) throw ValidationException::childRequired($this, "equals");
            if ($this->value === null) throw ValidationException::childRequired($this, "value");
        }
        if ($flags & self::VALIDATE_EXPRESSION_CONTEXT)
        {
        }
        if ($flags & self::VALIDATE_TOKENS)
        {
        }
        $this->value->_validate($flags);
    }
}
