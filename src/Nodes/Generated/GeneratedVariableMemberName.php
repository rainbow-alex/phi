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

abstract class GeneratedVariableMemberName extends Nodes\MemberName
{
    /**
     * @var Token|null
     */
    private $leftBrace;

    /**
     * @var Nodes\Expression|null
     */
    private $expression;

    /**
     * @var Token|null
     */
    private $rightBrace;


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
     * @param Token|null $leftBrace
     * @param Nodes\Expression $expression
     * @param Token|null $rightBrace
     * @return static
     */
    public static function __instantiateUnchecked($phpVersion, $leftBrace, $expression, $rightBrace)
    {
        $instance = new static;
        $instance->phpVersion = $phpVersion;
        $instance->leftBrace = $leftBrace;
        if ($leftBrace) $leftBrace->parent = $instance;
        $instance->expression = $expression;
        $expression->parent = $instance;
        $instance->rightBrace = $rightBrace;
        if ($rightBrace) $rightBrace->parent = $instance;
        return $instance;
    }

    protected function &_getNodeRefs(): array
    {
        $refs = [
            "leftBrace" => &$this->leftBrace,
            "expression" => &$this->expression,
            "rightBrace" => &$this->rightBrace,
        ];
        return $refs;
    }

    public function getLeftBrace(): ?Token
    {
        return $this->leftBrace;
    }

    public function hasLeftBrace(): bool
    {
        return $this->leftBrace !== null;
    }

    /**
     * @param Token|Node|string|null $leftBrace
     */
    public function setLeftBrace($leftBrace): void
    {
        if ($leftBrace !== null)
        {
            /** @var Token $leftBrace */
            $leftBrace = NodeConverter::convert($leftBrace, Token::class, $this->phpVersion);
            $leftBrace->detach();
            $leftBrace->parent = $this;
        }
        if ($this->leftBrace !== null)
        {
            $this->leftBrace->detach();
        }
        $this->leftBrace = $leftBrace;
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

    public function getRightBrace(): ?Token
    {
        return $this->rightBrace;
    }

    public function hasRightBrace(): bool
    {
        return $this->rightBrace !== null;
    }

    /**
     * @param Token|Node|string|null $rightBrace
     */
    public function setRightBrace($rightBrace): void
    {
        if ($rightBrace !== null)
        {
            /** @var Token $rightBrace */
            $rightBrace = NodeConverter::convert($rightBrace, Token::class, $this->phpVersion);
            $rightBrace->detach();
            $rightBrace->parent = $this;
        }
        if ($this->rightBrace !== null)
        {
            $this->rightBrace->detach();
        }
        $this->rightBrace = $rightBrace;
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
