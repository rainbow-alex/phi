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

abstract class GeneratedExpressionStatement extends Nodes\Statement
{
    /**
     * @var Nodes\Expression|null
     */
    private $expression;

    /**
     * @var Token|null
     */
    private $semiColon;

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
     * @param Nodes\Expression|null $expression
     * @param Token|null $semiColon
     * @return static
     */
    public static function __instantiateUnchecked($phpVersion, $expression, $semiColon)
    {
        $instance = new static;
        $instance->phpVersion = $phpVersion;
        $instance->expression = $expression;
        $instance->expression->parent = $instance;
        $instance->semiColon = $semiColon;
        if ($semiColon)
        {
            $instance->semiColon->parent = $instance;
        }
        return $instance;
    }

    protected function &_getNodeRefs(): array
    {
        $refs = [
            "expression" => &$this->expression,
            "semiColon" => &$this->semiColon,
        ];
        return $refs;
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

    public function getSemiColon(): ?Token
    {
        return $this->semiColon;
    }

    public function hasSemiColon(): bool
    {
        return $this->semiColon !== null;
    }

    /**
     * @param Token|Node|string|null $semiColon
     */
    public function setSemiColon($semiColon): void
    {
        if ($semiColon !== null)
        {
            /** @var Token $semiColon */
            $semiColon = NodeConverter::convert($semiColon, Token::class, $this->phpVersion);
            $semiColon->detach();
            $semiColon->parent = $this;
        }
        if ($this->semiColon !== null)
        {
            $this->semiColon->detach();
        }
        $this->semiColon = $semiColon;
    }

    protected function _validate(int $flags): void
    {
        if ($flags & self::VALIDATE_TYPES)
        {
            if ($this->expression === null) throw ValidationException::childRequired($this, "expression");
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
