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

abstract class GeneratedDefault extends CompoundNode
{
    /** @var Specification[] */
    private static $specifications;
    protected static function getSpecifications(): array
    {
        return self::$specifications ?? self::$specifications = [
            new ValidCompoundNode([
                'symbol' => new IsToken('='),
                'value' => new Any,
            ]),
        ];
    }

    /**
     * @var Token|null
     */
    private $symbol;
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
     * @param Token|null $symbol
     * @param Nodes\Expression|null $value
     * @return static
     */
    public static function __instantiateUnchecked($symbol, $value)
    {
        $instance = new static();
        $instance->symbol = $symbol;
        $instance->value = $value;
        return $instance;
    }

    public function &_getNodeRefs(): array
    {
        $refs = [
            'symbol' => &$this->symbol,
            'value' => &$this->value,
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
