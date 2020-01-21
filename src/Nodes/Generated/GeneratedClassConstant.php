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

abstract class GeneratedClassConstant extends CompoundNode implements Nodes\ClassLikeMember
{
    /** @var Specification[] */
    private static $specifications;
    protected static function getSpecifications(): array
    {
        return self::$specifications ?? self::$specifications = [
            new ValidCompoundNode([
                'modifiers' => new EachItem(new IsToken(\T_PUBLIC, \T_PROTECTED, \T_PRIVATE)),
                'keyword' => new IsToken(\T_CONST),
                'name' => new IsToken(\T_STRING),
                'equals' => new IsToken('='),
                'value' => new Any,
                'semiColon' => new IsToken(';'),
            ]),
        ];
    }

    /**
     * @var NodesList|Token[]
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
        parent::__construct();
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
     * @param mixed[] $modifiers
     * @param Token|null $keyword
     * @param Token|null $name
     * @param Token|null $equals
     * @param Nodes\Expression|null $value
     * @param Token|null $semiColon
     * @return static
     */
    public static function __instantiateUnchecked($modifiers, $keyword, $name, $equals, $value, $semiColon)
    {
        $instance = new static();
        $instance->modifiers->__initUnchecked($modifiers);
        $instance->keyword = $keyword;
        $instance->name = $name;
        $instance->equals = $equals;
        $instance->value = $value;
        $instance->semiColon = $semiColon;
        return $instance;
    }

    public function &_getNodeRefs(): array
    {
        $refs = [
            'modifiers' => &$this->modifiers,
            'keyword' => &$this->keyword,
            'name' => &$this->name,
            'equals' => &$this->equals,
            'value' => &$this->value,
            'semiColon' => &$this->semiColon,
        ];
        return $refs;
    }

    /**
     * @return NodesList|Token[]
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
        $modifier = NodeConverter::convert($modifier, Token::class);
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
            $keyword = NodeConverter::convert($keyword, Token::class, $this->_phpVersion);
            $keyword->_attachTo($this);
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
            $name = NodeConverter::convert($name, Token::class, $this->_phpVersion);
            $name->_attachTo($this);
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
            $equals = NodeConverter::convert($equals, Token::class, $this->_phpVersion);
            $equals->_attachTo($this);
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
            $value = NodeConverter::convert($value, Nodes\Expression::class, $this->_phpVersion);
            $value->_attachTo($this);
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
            $semiColon = NodeConverter::convert($semiColon, Token::class, $this->_phpVersion);
            $semiColon->_attachTo($this);
        }
        if ($this->semiColon !== null)
        {
            $this->semiColon->detach();
        }
        $this->semiColon = $semiColon;
    }
}
