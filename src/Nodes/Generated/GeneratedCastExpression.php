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

abstract class GeneratedCastExpression extends Nodes\Expression
{
    /**
     * @var Token|null
     */
    private $cast;

    /**
     * @var Nodes\Expression|null
     */
    private $expression;


    /**
     * @param Token|Node|string|null $cast
     * @param Nodes\Expression|Node|string|null $expression
     */
    public function __construct($cast = null, $expression = null)
    {
        if ($cast !== null)
        {
            $this->setCast($cast);
        }
        if ($expression !== null)
        {
            $this->setExpression($expression);
        }
    }

    /**
     * @param int $phpVersion
     * @param Token $cast
     * @param Nodes\Expression $expression
     * @return static
     */
    public static function __instantiateUnchecked($phpVersion, $cast, $expression)
    {
        $instance = new static;
        $instance->phpVersion = $phpVersion;
        $instance->cast = $cast;
        $cast->parent = $instance;
        $instance->expression = $expression;
        $expression->parent = $instance;
        return $instance;
    }

    protected function &_getNodeRefs(): array
    {
        $refs = [
            "cast" => &$this->cast,
            "expression" => &$this->expression,
        ];
        return $refs;
    }

    public function getCast(): Token
    {
        if ($this->cast === null)
        {
            throw new MissingNodeException($this, __FUNCTION__);
        }
        return $this->cast;
    }

    public function hasCast(): bool
    {
        return $this->cast !== null;
    }

    /**
     * @param Token|Node|string|null $cast
     */
    public function setCast($cast): void
    {
        if ($cast !== null)
        {
            /** @var Token $cast */
            $cast = NodeConverter::convert($cast, Token::class, $this->phpVersion);
            $cast->detach();
            $cast->parent = $this;
        }
        if ($this->cast !== null)
        {
            $this->cast->detach();
        }
        $this->cast = $cast;
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
        if ($this->cast === null) throw ValidationException::childRequired($this, "cast");
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
