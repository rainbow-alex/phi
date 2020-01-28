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

abstract class GeneratedClassConstant extends Nodes\ClassLikeMember
{
    /**
     * @var NodesList|Token[]
     * @phpstan-var NodesList<\Phi\Token>
     */
    private $modifiers;

    /**
     * @var Token|null
     */
    private $keyword;

    /**
     * @var Token|null
     */
    private $name;

    /**
     * @var Token|null
     */
    private $equals;

    /**
     * @var Nodes\Expression|null
     */
    private $value;

    /**
     * @var Token|null
     */
    private $semiColon;


    /**
     * @param Token|Node|string|null $name
     * @param Nodes\Expression|Node|string|null $value
     */
    public function __construct($name = null, $value = null)
    {
        $this->modifiers = new NodesList();
        if ($name !== null)
        {
            $this->setName($name);
        }
        if ($value !== null)
        {
            $this->setValue($value);
        }
    }

    /**
     * @param int $phpVersion
     * @param mixed[] $modifiers
     * @param Token $keyword
     * @param Token $name
     * @param Token $equals
     * @param Nodes\Expression $value
     * @param Token $semiColon
     * @return static
     */
    public static function __instantiateUnchecked($phpVersion, $modifiers, $keyword, $name, $equals, $value, $semiColon)
    {
        $instance = new static;
        $instance->phpVersion = $phpVersion;
        $instance->modifiers->__initUnchecked($modifiers);
        $instance->modifiers->parent = $instance;
        $instance->keyword = $keyword;
        $keyword->parent = $instance;
        $instance->name = $name;
        $name->parent = $instance;
        $instance->equals = $equals;
        $equals->parent = $instance;
        $instance->value = $value;
        $value->parent = $instance;
        $instance->semiColon = $semiColon;
        $semiColon->parent = $instance;
        return $instance;
    }

    protected function &_getNodeRefs(): array
    {
        $refs = [
            "modifiers" => &$this->modifiers,
            "keyword" => &$this->keyword,
            "name" => &$this->name,
            "equals" => &$this->equals,
            "value" => &$this->value,
            "semiColon" => &$this->semiColon,
        ];
        return $refs;
    }

    /**
     * @return NodesList|Token[]
     * @phpstan-return NodesList<\Phi\Token>
     */
    public function getModifiers(): NodesList
    {
        return $this->modifiers;
    }

    /**
     * @param Token $modifier
     */
    public function addModifier($modifier): void
    {
        /** @var Token $modifier */
        $modifier = NodeConverter::convert($modifier, Token::class, $this->phpVersion);
        $this->modifiers->add($modifier);
    }

    public function getKeyword(): Token
    {
        if ($this->keyword === null)
        {
            throw new MissingNodeException($this, __FUNCTION__);
        }
        return $this->keyword;
    }

    public function hasKeyword(): bool
    {
        return $this->keyword !== null;
    }

    /**
     * @param Token|Node|string|null $keyword
     */
    public function setKeyword($keyword): void
    {
        if ($keyword !== null)
        {
            /** @var Token $keyword */
            $keyword = NodeConverter::convert($keyword, Token::class, $this->phpVersion);
            $keyword->detach();
            $keyword->parent = $this;
        }
        if ($this->keyword !== null)
        {
            $this->keyword->detach();
        }
        $this->keyword = $keyword;
    }

    public function getName(): Token
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
     * @param Token|Node|string|null $name
     */
    public function setName($name): void
    {
        if ($name !== null)
        {
            /** @var Token $name */
            $name = NodeConverter::convert($name, Token::class, $this->phpVersion);
            $name->detach();
            $name->parent = $this;
        }
        if ($this->name !== null)
        {
            $this->name->detach();
        }
        $this->name = $name;
    }

    public function getEquals(): Token
    {
        if ($this->equals === null)
        {
            throw new MissingNodeException($this, __FUNCTION__);
        }
        return $this->equals;
    }

    public function hasEquals(): bool
    {
        return $this->equals !== null;
    }

    /**
     * @param Token|Node|string|null $equals
     */
    public function setEquals($equals): void
    {
        if ($equals !== null)
        {
            /** @var Token $equals */
            $equals = NodeConverter::convert($equals, Token::class, $this->phpVersion);
            $equals->detach();
            $equals->parent = $this;
        }
        if ($this->equals !== null)
        {
            $this->equals->detach();
        }
        $this->equals = $equals;
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
            $value = NodeConverter::convert($value, Nodes\Expression::class, $this->phpVersion);
            $value->detach();
            $value->parent = $this;
        }
        if ($this->value !== null)
        {
            $this->value->detach();
        }
        $this->value = $value;
    }

    public function getSemiColon(): Token
    {
        if ($this->semiColon === null)
        {
            throw new MissingNodeException($this, __FUNCTION__);
        }
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
        if ($this->keyword === null) throw ValidationException::childRequired($this, "keyword");
        if ($this->name === null) throw ValidationException::childRequired($this, "name");
        if ($this->equals === null) throw ValidationException::childRequired($this, "equals");
        if ($this->value === null) throw ValidationException::childRequired($this, "value");
        if ($this->semiColon === null) throw ValidationException::childRequired($this, "semiColon");
        if ($flags & self::VALIDATE_TYPES)
        {
        }
        if ($flags & self::VALIDATE_EXPRESSION_CONTEXT)
        {
        }
        if ($flags & self::VALIDATE_TOKENS)
        {
        }
        $this->value->_validate($flags);
    }
}
