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

abstract class GeneratedNegationExpression extends CompoundNode implements Nodes\Expression
{
    /** @var Specification[] */
    private static $specifications;
    protected static function getSpecifications(): array
    {
        return self::$specifications ?? self::$specifications = [
            new ValidCompoundNode([
                'symbol' => new IsToken('-'),
                'expression' => new Specs\IsReadExpression,
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
    private $expression;

    /**
     * @param Nodes\Expression|Node|string|null $expression
     */
    public function __construct($expression = null)
    {
        parent::__construct();
        if ($expression !== null)
        {
            $this->setExpression($expression);
        }
    }

    /**
     * @param Token|null $symbol
     * @param Nodes\Expression|null $expression
     * @return static
     */
    public static function __instantiateUnchecked($symbol, $expression)
    {
        $instance = new static();
        $instance->symbol = $symbol;
        $instance->expression = $expression;
        return $instance;
    }

    public function &_getNodeRefs(): array
    {
        $refs = [
            'symbol' => &$this->symbol,
            'expression' => &$this->expression,
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
            $expression = NodeConverter::convert($expression, Nodes\Expression::class, $this->_phpVersion);
            $expression->_attachTo($this);
        }
        if ($this->expression !== null)
        {
            $this->expression->detach();
        }
        $this->expression = $expression;
    }
}
