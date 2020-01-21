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

abstract class GeneratedNullableType extends CompoundNode implements Nodes\Type
{
    /** @var Specification[] */
    private static $specifications;
    protected static function getSpecifications(): array
    {
        return self::$specifications ?? self::$specifications = [
            new ValidCompoundNode([
                'symbol' => new IsToken('?'),
                'type' => new Any,
            ]),
        ];
    }

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
        parent::__construct();
        if ($type !== null)
        {
            $this->setType($type);
        }
    }

    /**
     * @param Token|null $symbol
     * @param Nodes\Type|null $type
     * @return static
     */
    public static function __instantiateUnchecked($symbol, $type)
    {
        $instance = new static();
        $instance->symbol = $symbol;
        $instance->type = $type;
        return $instance;
    }

    public function &_getNodeRefs(): array
    {
        $refs = [
            'symbol' => &$this->symbol,
            'type' => &$this->type,
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
            $symbol = NodeConverter::convert($symbol, Token::class, $this->_phpVersion);
            $symbol->_attachTo($this);
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
            $type = NodeConverter::convert($type, Nodes\Type::class, $this->_phpVersion);
            $type->_attachTo($this);
        }
        if ($this->type !== null)
        {
            $this->type->detach();
        }
        $this->type = $type;
    }
}
