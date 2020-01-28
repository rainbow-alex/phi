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

abstract class GeneratedArgument extends CompoundNode
{
    /**
     * @var Token|null
     */
    private $unpack;

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
     * @param Token|null $unpack
     * @param Nodes\Expression $expression
     * @return static
     */
    public static function __instantiateUnchecked($phpVersion, $unpack, $expression)
    {
        $instance = new static;
        $instance->phpVersion = $phpVersion;
        $instance->unpack = $unpack;
        if ($unpack) $unpack->parent = $instance;
        $instance->expression = $expression;
        $expression->parent = $instance;
        return $instance;
    }

    protected function &_getNodeRefs(): array
    {
        $refs = [
            "unpack" => &$this->unpack,
            "expression" => &$this->expression,
        ];
        return $refs;
    }

    public function getUnpack(): ?Token
    {
        return $this->unpack;
    }

    public function hasUnpack(): bool
    {
        return $this->unpack !== null;
    }

    /**
     * @param Token|Node|string|null $unpack
     */
    public function setUnpack($unpack): void
    {
        if ($unpack !== null)
        {
            /** @var Token $unpack */
            $unpack = NodeConverter::convert($unpack, Token::class, $this->phpVersion);
            $unpack->detach();
            $unpack->parent = $this;
        }
        if ($this->unpack !== null)
        {
            $this->unpack->detach();
        }
        $this->unpack = $unpack;
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
