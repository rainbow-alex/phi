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

abstract class GeneratedUnaryMinusExpression extends Nodes\Expression
{
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
        if ($expression !== null)
        {
            $this->setExpression($expression);
        }
    }

    /**
     * @param int $phpVersion
     * @param Token $symbol
     * @param Nodes\Expression $expression
     * @return static
     */
    public static function __instantiateUnchecked($phpVersion, $symbol, $expression)
    {
        $instance = new static;
        $instance->phpVersion = $phpVersion;
        $instance->symbol = $symbol;
        $symbol->parent = $instance;
        $instance->expression = $expression;
        $expression->parent = $instance;
        return $instance;
    }

    protected function &_getNodeRefs(): array
    {
        $refs = [
            "symbol" => &$this->symbol,
            "expression" => &$this->expression,
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
        if ($this->symbol === null) throw ValidationException::childRequired($this, "symbol");
        if ($this->expression === null) throw ValidationException::childRequired($this, "expression");
        if ($flags & self::VALIDATE_TYPES)
        {
        }
        if ($flags & self::VALIDATE_EXPRESSION_CONTEXT)
        {
        }
        if ($flags & self::VALIDATE_TOKENS)
        {
        }
        $this->expression->_validate($flags);
    }
}
