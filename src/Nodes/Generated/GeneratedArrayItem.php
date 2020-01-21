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

abstract class GeneratedArrayItem extends CompoundNode
{
    /** @var Specification[] */
    private static $specifications;
    protected static function getSpecifications(): array
    {
        return self::$specifications ?? self::$specifications = [
            new ValidCompoundNode([
                'key' => new Optional(new Any),
                'byReference' => new Optional(new IsToken('&')),
                'value' => new Optional(new Any),
            ]),
        ];
    }

    /**
     * @var Nodes\Key|null
     */
    private $key;
    /**
     * @var Token|null
     */
    private $byReference;
    /**
     * @var Nodes\Expression|null
     */
    private $value;

    /**
     * @param Nodes\Expression|Node|string|null $value
     */
    public function __construct($value = null)
    {
        parent::__construct();
        if ($value !== null)
        {
            $this->setValue($value);
        }
    }

    /**
     * @param Nodes\Key|null $key
     * @param Token|null $byReference
     * @param Nodes\Expression|null $value
     * @return static
     */
    public static function __instantiateUnchecked($key, $byReference, $value)
    {
        $instance = new static();
        $instance->key = $key;
        $instance->byReference = $byReference;
        $instance->value = $value;
        return $instance;
    }

    public function &_getNodeRefs(): array
    {
        $refs = [
            'key' => &$this->key,
            'byReference' => &$this->byReference,
            'value' => &$this->value,
        ];
        return $refs;
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
            $key = NodeConverter::convert($key, Nodes\Key::class, $this->_phpVersion);
            $key->_attachTo($this);
        }
        if ($this->key !== null)
        {
            $this->key->detach();
        }
        $this->key = $key;
    }

    public function getByReference(): ?Token
    {
        return $this->byReference;
    }

    public function hasByReference(): bool
    {
        return $this->byReference !== null;
    }

    /**
     * @param Token|Node|string|null $byReference
     */
    public function setByReference($byReference): void
    {
        if ($byReference !== null)
        {
            /** @var Token $byReference */
            $byReference = NodeConverter::convert($byReference, Token::class, $this->_phpVersion);
            $byReference->_attachTo($this);
        }
        if ($this->byReference !== null)
        {
            $this->byReference->detach();
        }
        $this->byReference = $byReference;
    }

    public function getValue(): ?Nodes\Expression
    {
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
