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

abstract class GeneratedKey extends CompoundNode
{
    /**
     * @var Nodes\Expression|null
     */
    private $value;

    /**
     * @var Token|null
     */
    private $arrow;

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
     * @param Nodes\Expression|null $value
     * @param Token|null $arrow
     * @return static
     */
    public static function __instantiateUnchecked($phpVersion, $value, $arrow)
    {
        $instance = new static;
        $instance->phpVersion = $phpVersion;
        $instance->value = $value;
        $instance->value->parent = $instance;
        $instance->arrow = $arrow;
        $instance->arrow->parent = $instance;
        return $instance;
    }

    protected function &_getNodeRefs(): array
    {
        $refs = [
            "value" => &$this->value,
            "arrow" => &$this->arrow,
        ];
        return $refs;
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

    public function getArrow(): Token
    {
        if ($this->arrow === null)
        {
            throw new MissingNodeException($this, __FUNCTION__);
        }
        return $this->arrow;
    }

    public function hasArrow(): bool
    {
        return $this->arrow !== null;
    }

    /**
     * @param Token|Node|string|null $arrow
     */
    public function setArrow($arrow): void
    {
        if ($arrow !== null)
        {
            /** @var Token $arrow */
            $arrow = NodeConverter::convert($arrow, Token::class, $this->phpVersion);
            $arrow->detach();
            $arrow->parent = $this;
        }
        if ($this->arrow !== null)
        {
            $this->arrow->detach();
        }
        $this->arrow = $arrow;
    }

    protected function _validate(int $flags): void
    {
        if ($flags & self::VALIDATE_TYPES)
        {
            if ($this->value === null) throw ValidationException::childRequired($this, "value");
            if ($this->arrow === null) throw ValidationException::childRequired($this, "arrow");
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
