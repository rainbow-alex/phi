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

abstract class GeneratedInterfaceStatement extends Nodes\ClassLikeStatement
{
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
        if ($name !== null)
        {
            $this->setName($name);
        }
        $this->members = new NodesList();
    }

    /**
     * @param int $phpVersion
     * @param Token|null $keyword
     * @param Token|null $name
     * @param Nodes\Extends_|null $extends
     * @param Token|null $leftBrace
     * @param mixed[] $members
     * @param Token|null $rightBrace
     * @return static
     */
    public static function __instantiateUnchecked($phpVersion, $keyword, $name, $extends, $leftBrace, $members, $rightBrace)
    {
        $instance = new static;
        $instance->phpVersion = $phpVersion;
        $instance->keyword = $keyword;
        $instance->keyword->parent = $instance;
        $instance->name = $name;
        $instance->name->parent = $instance;
        $instance->extends = $extends;
        if ($extends)
        {
            $instance->extends->parent = $instance;
        }
        $instance->leftBrace = $leftBrace;
        $instance->leftBrace->parent = $instance;
        $instance->members->__initUnchecked($members);
        $instance->members->parent = $instance;
        $instance->rightBrace = $rightBrace;
        $instance->rightBrace->parent = $instance;
        return $instance;
    }

    protected function &_getNodeRefs(): array
    {
        $refs = [
            'keyword' => &$this->keyword,
            'name' => &$this->name,
            'extends' => &$this->extends,
            'leftBrace' => &$this->leftBrace,
            'members' => &$this->members,
            'rightBrace' => &$this->rightBrace,
        ];
        return $refs;
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
            $extends = NodeConverter::convert($extends, Nodes\Extends_::class, $this->phpVersion);
            $extends->detach();
            $extends->parent = $this;
        }
        if ($this->extends !== null)
        {
            $this->extends->detach();
        }
        $this->extends = $extends;
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
        $member = NodeConverter::convert($member, Nodes\ClassLikeMember::class, $this->phpVersion);
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
        if ($flags & self::VALIDATE_TYPES)
        {
            if ($this->keyword === null) throw ValidationException::childRequired($this, 'keyword');
            if ($this->name === null) throw ValidationException::childRequired($this, 'name');
            if ($this->leftBrace === null) throw ValidationException::childRequired($this, 'leftBrace');
            if ($this->rightBrace === null) throw ValidationException::childRequired($this, 'rightBrace');
        }
        if ($flags & self::VALIDATE_EXPRESSION_CONTEXT)
        {
        }
        if ($flags & self::VALIDATE_TOKENS)
        {
        }
        if ($this->extends)
        {
            $this->extends->_validate($flags);
        }
        $this->members->_validate($flags);
    }
}
