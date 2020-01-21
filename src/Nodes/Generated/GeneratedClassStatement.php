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

abstract class GeneratedClassStatement extends CompoundNode implements Nodes\ClassLikeStatement
{
    /** @var Specification[] */
    private static $specifications;
    protected static function getSpecifications(): array
    {
        return self::$specifications ?? self::$specifications = [
            new ValidCompoundNode([
                'modifiers' => new EachItem(new IsToken(\T_ABSTRACT, \T_FINAL)),
                'keyword' => new IsToken(\T_CLASS),
                'name' => new IsToken(\T_STRING),
                'extends' => new Optional(new Any),
                'implements' => new Optional(new Any),
                'leftBrace' => new IsToken('{'),
                'members' => new EachItem(new IsInstanceOf(Nodes\ClassLikeMember::class)),
                'rightBrace' => new IsToken('}'),
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
     * @var Nodes\Extends_|null
     */
    private $extends;
    /**
     * @var Nodes\Implements_|null
     */
    private $implements;
    /**
     * @var Token|null
     */
    private $leftBrace;
    /**
     * @var NodesList|Nodes\ClassLikeMember[]
     */
    private $members;
    /**
     * @var Token|null
     */
    private $rightBrace;

    /**
     * @param Token|Node|string|null $name
     */
    public function __construct($name = null)
    {
        parent::__construct();
        $this->modifiers = new NodesList();
        if ($name !== null)
        {
            $this->setName($name);
        }
        $this->members = new NodesList();
    }

    /**
     * @param mixed[] $modifiers
     * @param Token|null $keyword
     * @param Token|null $name
     * @param Nodes\Extends_|null $extends
     * @param Nodes\Implements_|null $implements
     * @param Token|null $leftBrace
     * @param mixed[] $members
     * @param Token|null $rightBrace
     * @return static
     */
    public static function __instantiateUnchecked($modifiers, $keyword, $name, $extends, $implements, $leftBrace, $members, $rightBrace)
    {
        $instance = new static();
        $instance->modifiers->__initUnchecked($modifiers);
        $instance->keyword = $keyword;
        $instance->name = $name;
        $instance->extends = $extends;
        $instance->implements = $implements;
        $instance->leftBrace = $leftBrace;
        $instance->members->__initUnchecked($members);
        $instance->rightBrace = $rightBrace;
        return $instance;
    }

    public function &_getNodeRefs(): array
    {
        $refs = [
            'modifiers' => &$this->modifiers,
            'keyword' => &$this->keyword,
            'name' => &$this->name,
            'extends' => &$this->extends,
            'implements' => &$this->implements,
            'leftBrace' => &$this->leftBrace,
            'members' => &$this->members,
            'rightBrace' => &$this->rightBrace,
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

    public function getExtends(): ?Nodes\Extends_
    {
        return $this->extends;
    }

    public function hasExtends(): bool
    {
        return $this->extends !== null;
    }

    /**
     * @param Nodes\Extends_|Node|string|null $extends
     */
    public function setExtends($extends): void
    {
        if ($extends !== null)
        {
            /** @var Nodes\Extends_ $extends */
            $extends = NodeConverter::convert($extends, Nodes\Extends_::class, $this->_phpVersion);
            $extends->_attachTo($this);
        }
        if ($this->extends !== null)
        {
            $this->extends->detach();
        }
        $this->extends = $extends;
    }

    public function getImplements(): ?Nodes\Implements_
    {
        return $this->implements;
    }

    public function hasImplements(): bool
    {
        return $this->implements !== null;
    }

    /**
     * @param Nodes\Implements_|Node|string|null $implements
     */
    public function setImplements($implements): void
    {
        if ($implements !== null)
        {
            /** @var Nodes\Implements_ $implements */
            $implements = NodeConverter::convert($implements, Nodes\Implements_::class, $this->_phpVersion);
            $implements->_attachTo($this);
        }
        if ($this->implements !== null)
        {
            $this->implements->detach();
        }
        $this->implements = $implements;
    }

    public function getLeftBrace(): Token
    {
        if ($this->leftBrace === null)
        {
            throw new MissingNodeException($this, __FUNCTION__);
        }
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
            $leftBrace = NodeConverter::convert($leftBrace, Token::class, $this->_phpVersion);
            $leftBrace->_attachTo($this);
        }
        if ($this->leftBrace !== null)
        {
            $this->leftBrace->detach();
        }
        $this->leftBrace = $leftBrace;
    }

    /**
     * @return NodesList|Nodes\ClassLikeMember[]
     */
    public function getMembers(): NodesList
    {
        return $this->members;
    }

    /**
     * @param Nodes\ClassLikeMember $member
     */
    public function addMember($member): void
    {
        /** @var Nodes\ClassLikeMember $member */
        $member = NodeConverter::convert($member, Nodes\ClassLikeMember::class);
        $this->members->add($member);
    }

    public function getRightBrace(): Token
    {
        if ($this->rightBrace === null)
        {
            throw new MissingNodeException($this, __FUNCTION__);
        }
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
            $rightBrace = NodeConverter::convert($rightBrace, Token::class, $this->_phpVersion);
            $rightBrace->_attachTo($this);
        }
        if ($this->rightBrace !== null)
        {
            $this->rightBrace->detach();
        }
        $this->rightBrace = $rightBrace;
    }
}
