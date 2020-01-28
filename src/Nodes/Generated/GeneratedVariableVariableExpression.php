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

abstract class GeneratedVariableVariableExpression extends Nodes\Variable
{
    /**
     * @var Token|null
     */
    private $dollar;

    /**
     * @var Token|null
     */
    private $leftBrace;

    /**
     * @var Nodes\Expression|null
     */
    private $name;

    /**
     * @var Token|null
     */
    private $rightBrace;


    /**
     * @param Nodes\Expression|Node|string|null $name
     */
    public function __construct($name = null)
    {
        if ($name !== null)
        {
            $this->setName($name);
        }
    }

    /**
     * @param int $phpVersion
     * @param Token $dollar
     * @param Token|null $leftBrace
     * @param Nodes\Expression $name
     * @param Token|null $rightBrace
     * @return static
     */
    public static function __instantiateUnchecked($phpVersion, $dollar, $leftBrace, $name, $rightBrace)
    {
        $instance = new static;
        $instance->phpVersion = $phpVersion;
        $instance->dollar = $dollar;
        $dollar->parent = $instance;
        $instance->leftBrace = $leftBrace;
        if ($leftBrace) $leftBrace->parent = $instance;
        $instance->name = $name;
        $name->parent = $instance;
        $instance->rightBrace = $rightBrace;
        if ($rightBrace) $rightBrace->parent = $instance;
        return $instance;
    }

    protected function &_getNodeRefs(): array
    {
        $refs = [
            "dollar" => &$this->dollar,
            "leftBrace" => &$this->leftBrace,
            "name" => &$this->name,
            "rightBrace" => &$this->rightBrace,
        ];
        return $refs;
    }

    public function getDollar(): Token
    {
        if ($this->dollar === null)
        {
            throw new MissingNodeException($this, __FUNCTION__);
        }
        return $this->dollar;
    }

    public function hasDollar(): bool
    {
        return $this->dollar !== null;
    }

    /**
     * @param Token|Node|string|null $dollar
     */
    public function setDollar($dollar): void
    {
        if ($dollar !== null)
        {
            /** @var Token $dollar */
            $dollar = NodeConverter::convert($dollar, Token::class, $this->phpVersion);
            $dollar->detach();
            $dollar->parent = $this;
        }
        if ($this->dollar !== null)
        {
            $this->dollar->detach();
        }
        $this->dollar = $dollar;
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

    public function getName(): Nodes\Expression
    {
        if ($this->name === null)
        {
            throw new MissingNodeException($this, __FUNCTION__);
        }
        return $this->name;
    }

    public function hasName(): bool
    {
        return $this->name !== null;
    }

    /**
     * @param Nodes\Expression|Node|string|null $name
     */
    public function setName($name): void
    {
        if ($name !== null)
        {
            /** @var Nodes\Expression $name */
            $name = NodeConverter::convert($name, Nodes\Expression::class, $this->phpVersion);
            $name->detach();
            $name->parent = $this;
        }
        if ($this->name !== null)
        {
            $this->name->detach();
        }
        $this->name = $name;
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
        if ($this->dollar === null) throw ValidationException::childRequired($this, "dollar");
        if ($this->name === null) throw ValidationException::childRequired($this, "name");
        if ($flags & self::VALIDATE_TYPES)
        {
        }
        if ($flags & self::VALIDATE_EXPRESSION_CONTEXT)
        {
        }
        if ($flags & self::VALIDATE_TOKENS)
        {
        }
        $this->name->_validate($flags);
    }
}
