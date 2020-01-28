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

abstract class GeneratedArrayItem extends CompoundNode
{
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
        if ($value !== null)
        {
            $this->setValue($value);
        }
    }

    /**
     * @param int $phpVersion
     * @param Nodes\Key|null $key
     * @param Token|null $byReference
     * @param Nodes\Expression|null $value
     * @return static
     */
    public static function __instantiateUnchecked($phpVersion, $key, $byReference, $value)
    {
        $instance = new static;
        $instance->phpVersion = $phpVersion;
        $instance->key = $key;
        if ($key) $key->parent = $instance;
        $instance->byReference = $byReference;
        if ($byReference) $byReference->parent = $instance;
        $instance->value = $value;
        if ($value) $value->parent = $instance;
        return $instance;
    }

    protected function &_getNodeRefs(): array
    {
        $refs = [
            "key" => &$this->key,
            "byReference" => &$this->byReference,
            "value" => &$this->value,
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
            $byReference = NodeConverter::convert($byReference, Token::class, $this->phpVersion);
            $byReference->detach();
            $byReference->parent = $this;
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
        if ($this->value)
        {
            $this->value->_validate($flags);
        }
    }
}
