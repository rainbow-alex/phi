<?php

namespace Phi\Nodes\Generated;

use Phi\Node;
use Phi\Token;
use Phi\Nodes\Base\CompoundNode;
use Phi\Nodes\Base\NodesList;
use Phi\Nodes\Base\SeparatedNodesList;
use Phi\Exception\MissingNodeException;
use Phi\NodeConverter;
use Phi\Specification;
use Phi\Optional;
use Phi\Specifications\And_;
use Phi\Specifications\Any;
use Phi\Specifications\IsToken;
use Phi\Specifications\IsInstanceOf;
use Phi\Specifications\ValidCompoundNode;
use Phi\Specifications\EachItem;
use Phi\Specifications\EachSeparator;
use Phi\Nodes as Nodes;
use Phi\Specifications as Specs;

abstract class GeneratedDeclareDirective extends CompoundNode
{
    /** @var Specification[] */
    private static $specifications;
    protected static function getSpecifications(): array
    {
        return self::$specifications ?? self::$specifications = [
            new ValidCompoundNode([
                'key' => new IsToken(\T_STRING),
                'equals' => new IsToken('='),
                'value' => new Any,
            ]),
        ];
    }

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
        parent::__construct();
    }

    /**
     * @param Token|null $key
     * @param Token|null $equals
     * @param Nodes\Expression|null $value
     * @return static
     */
    public static function __instantiateUnchecked($key, $equals, $value)
    {
        $instance = new static();
        $instance->key = $key;
        $instance->equals = $equals;
        $instance->value = $value;
        return $instance;
    }

    public function &_getNodeRefs(): array
    {
        $refs = [
            'key' => &$this->key,
            'equals' => &$this->equals,
            'value' => &$this->value,
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
            $key = NodeConverter::convert($key, Token::class, $this->_phpVersion);
            $key->_attachTo($this);
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
            $equals = NodeConverter::convert($equals, Token::class, $this->_phpVersion);
            $equals->_attachTo($this);
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
            $value = NodeConverter::convert($value, Nodes\Expression::class, $this->_phpVersion);
            $value->_attachTo($this);
        }
        if ($this->value !== null)
        {
            $this->value->detach();
        }
        $this->value = $value;
    }
}
