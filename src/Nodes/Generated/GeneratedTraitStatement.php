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

abstract class GeneratedTraitStatement extends Nodes\ClassLikeStatement
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
     * @var Token|null
     */
    private $leftBrace;

    /**
     * @var NodesList|Nodes\ClassLikeMember[]
     * @phpstan-var NodesList<\Phi\Nodes\ClassLikeMember>
     */
    private $members;

    /**
     * @var Token|null
     */
    private $rightBrace;


    /**
     */
    public function __construct()
    {
        $this->members = new NodesList();
    }

    /**
     * @param int $phpVersion
     * @param Token $keyword
     * @param Token $name
     * @param Token $leftBrace
     * @param mixed[] $members
     * @param Token $rightBrace
     * @return static
     */
    public static function __instantiateUnchecked($phpVersion, $keyword, $name, $leftBrace, $members, $rightBrace)
    {
        $instance = new static;
        $instance->phpVersion = $phpVersion;
        $instance->keyword = $keyword;
        $keyword->parent = $instance;
        $instance->name = $name;
        $name->parent = $instance;
        $instance->leftBrace = $leftBrace;
        $leftBrace->parent = $instance;
        $instance->members->__initUnchecked($members);
        $instance->members->parent = $instance;
        $instance->rightBrace = $rightBrace;
        $rightBrace->parent = $instance;
        return $instance;
    }

    protected function &_getNodeRefs(): array
    {
        $refs = [
            "keyword" => &$this->keyword,
            "name" => &$this->name,
            "leftBrace" => &$this->leftBrace,
            "members" => &$this->members,
            "rightBrace" => &$this->rightBrace,
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
     * @phpstan-return NodesList<\Phi\Nodes\ClassLikeMember>
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
        if ($this->keyword === null) throw ValidationException::childRequired($this, "keyword");
        if ($this->name === null) throw ValidationException::childRequired($this, "name");
        if ($this->leftBrace === null) throw ValidationException::childRequired($this, "leftBrace");
        if ($this->rightBrace === null) throw ValidationException::childRequired($this, "rightBrace");
        if ($flags & self::VALIDATE_TYPES)
        {
        }
        if ($flags & self::VALIDATE_EXPRESSION_CONTEXT)
        {
        }
        if ($flags & self::VALIDATE_TOKENS)
        {
        }
        $this->members->_validate($flags);
    }
}
