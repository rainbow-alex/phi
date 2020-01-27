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

abstract class GeneratedNullableType extends Nodes\Type
{
    /**
     * @var Token|null
     */
    private $symbol;

    /**
     * @var Nodes\Type|null
     */
    private $type;

    /**
     * @param Nodes\Type|Node|string|null $type
     */
    public function __construct($type = null)
    {
        if ($type !== null)
        {
            $this->setType($type);
        }
    }

    /**
     * @param int $phpVersion
     * @param Token|null $symbol
     * @param Nodes\Type|null $type
     * @return static
     */
    public static function __instantiateUnchecked($phpVersion, $symbol, $type)
    {
        $instance = new static;
        $instance->phpVersion = $phpVersion;
        $instance->symbol = $symbol;
        $instance->symbol->parent = $instance;
        $instance->type = $type;
        $instance->type->parent = $instance;
        return $instance;
    }

    protected function &_getNodeRefs(): array
    {
        $refs = [
            "symbol" => &$this->symbol,
            "type" => &$this->type,
        ];
        return $refs;
    }

    public function getSymbol(): Token
    {
        if ($this->symbol === null)
        {
            throw new MissingNodeException($this, __FUNCTION__);
        }
        return $this->symbol;
    }

    public function hasSymbol(): bool
    {
        return $this->symbol !== null;
    }

    /**
     * @param Token|Node|string|null $symbol
     */
    public function setSymbol($symbol): void
    {
        if ($symbol !== null)
        {
            /** @var Token $symbol */
            $symbol = NodeConverter::convert($symbol, Token::class, $this->phpVersion);
            $symbol->detach();
            $symbol->parent = $this;
        }
        if ($this->symbol !== null)
        {
            $this->symbol->detach();
        }
        $this->symbol = $symbol;
    }

    public function getType(): Nodes\Type
    {
        if ($this->type === null)
        {
            throw new MissingNodeException($this, __FUNCTION__);
        }
        return $this->type;
    }

    public function hasType(): bool
    {
        return $this->type !== null;
    }

    /**
     * @param Nodes\Type|Node|string|null $type
     */
    public function setType($type): void
    {
        if ($type !== null)
        {
            /** @var Nodes\Type $type */
            $type = NodeConverter::convert($type, Nodes\Type::class, $this->phpVersion);
            $type->detach();
            $type->parent = $this;
        }
        if ($this->type !== null)
        {
            $this->type->detach();
        }
        $this->type = $type;
    }

    protected function _validate(int $flags): void
    {
        if ($flags & self::VALIDATE_TYPES)
        {
            if ($this->symbol === null) throw ValidationException::childRequired($this, "symbol");
            if ($this->type === null) throw ValidationException::childRequired($this, "type");
        }
        if ($flags & self::VALIDATE_EXPRESSION_CONTEXT)
        {
        }
        if ($flags & self::VALIDATE_TOKENS)
        {
        }
        $this->type->_validate($flags);
    }
}
