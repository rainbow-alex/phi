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

abstract class GeneratedTraitUseAs extends Nodes\TraitUseModification
{
    /**
     * @var Nodes\Name|null
     */
    private $trait;

    /**
     * @var Token|null
     */
    private $doubleColon;

    /**
     * @var Token|null
     */
    private $member;

    /**
     * @var Token|null
     */
    private $as;

    /**
     * @var Token|null
     */
    private $alias;

    /**
     */
    public function __construct()
    {
    }

    /**
     * @param int $phpVersion
     * @param Nodes\Name|null $trait
     * @param Token|null $doubleColon
     * @param Token|null $member
     * @param Token|null $as
     * @param Token|null $alias
     * @return static
     */
    public static function __instantiateUnchecked($phpVersion, $trait, $doubleColon, $member, $as, $alias)
    {
        $instance = new static;
        $instance->phpVersion = $phpVersion;
        $instance->trait = $trait;
        $instance->trait->parent = $instance;
        $instance->doubleColon = $doubleColon;
        $instance->doubleColon->parent = $instance;
        $instance->member = $member;
        $instance->member->parent = $instance;
        $instance->as = $as;
        $instance->as->parent = $instance;
        $instance->alias = $alias;
        $instance->alias->parent = $instance;
        return $instance;
    }

    protected function &_getNodeRefs(): array
    {
        $refs = [
            'trait' => &$this->trait,
            'doubleColon' => &$this->doubleColon,
            'member' => &$this->member,
            'as' => &$this->as,
            'alias' => &$this->alias,
        ];
        return $refs;
    }

    public function getTrait(): Nodes\Name
    {
        if ($this->trait === null)
        {
            throw new MissingNodeException($this, __FUNCTION__);
        }
        return $this->trait;
    }

    public function hasTrait(): bool
    {
        return $this->trait !== null;
    }

    /**
     * @param Nodes\Name|Node|string|null $trait
     */
    public function setTrait($trait): void
    {
        if ($trait !== null)
        {
            /** @var Nodes\Name $trait */
            $trait = NodeConverter::convert($trait, Nodes\Name::class, $this->phpVersion);
            $trait->detach();
            $trait->parent = $this;
        }
        if ($this->trait !== null)
        {
            $this->trait->detach();
        }
        $this->trait = $trait;
    }

    public function getDoubleColon(): Token
    {
        if ($this->doubleColon === null)
        {
            throw new MissingNodeException($this, __FUNCTION__);
        }
        return $this->doubleColon;
    }

    public function hasDoubleColon(): bool
    {
        return $this->doubleColon !== null;
    }

    /**
     * @param Token|Node|string|null $doubleColon
     */
    public function setDoubleColon($doubleColon): void
    {
        if ($doubleColon !== null)
        {
            /** @var Token $doubleColon */
            $doubleColon = NodeConverter::convert($doubleColon, Token::class, $this->phpVersion);
            $doubleColon->detach();
            $doubleColon->parent = $this;
        }
        if ($this->doubleColon !== null)
        {
            $this->doubleColon->detach();
        }
        $this->doubleColon = $doubleColon;
    }

    public function getMember(): Token
    {
        if ($this->member === null)
        {
            throw new MissingNodeException($this, __FUNCTION__);
        }
        return $this->member;
    }

    public function hasMember(): bool
    {
        return $this->member !== null;
    }

    /**
     * @param Token|Node|string|null $member
     */
    public function setMember($member): void
    {
        if ($member !== null)
        {
            /** @var Token $member */
            $member = NodeConverter::convert($member, Token::class, $this->phpVersion);
            $member->detach();
            $member->parent = $this;
        }
        if ($this->member !== null)
        {
            $this->member->detach();
        }
        $this->member = $member;
    }

    public function getAs(): Token
    {
        if ($this->as === null)
        {
            throw new MissingNodeException($this, __FUNCTION__);
        }
        return $this->as;
    }

    public function hasAs(): bool
    {
        return $this->as !== null;
    }

    /**
     * @param Token|Node|string|null $as
     */
    public function setAs($as): void
    {
        if ($as !== null)
        {
            /** @var Token $as */
            $as = NodeConverter::convert($as, Token::class, $this->phpVersion);
            $as->detach();
            $as->parent = $this;
        }
        if ($this->as !== null)
        {
            $this->as->detach();
        }
        $this->as = $as;
    }

    public function getAlias(): Token
    {
        if ($this->alias === null)
        {
            throw new MissingNodeException($this, __FUNCTION__);
        }
        return $this->alias;
    }

    public function hasAlias(): bool
    {
        return $this->alias !== null;
    }

    /**
     * @param Token|Node|string|null $alias
     */
    public function setAlias($alias): void
    {
        if ($alias !== null)
        {
            /** @var Token $alias */
            $alias = NodeConverter::convert($alias, Token::class, $this->phpVersion);
            $alias->detach();
            $alias->parent = $this;
        }
        if ($this->alias !== null)
        {
            $this->alias->detach();
        }
        $this->alias = $alias;
    }

    protected function _validate(int $flags): void
    {
        if ($flags & self::VALIDATE_TYPES)
        {
            if ($this->trait === null) throw ValidationException::childRequired($this, 'trait');
            if ($this->doubleColon === null) throw ValidationException::childRequired($this, 'doubleColon');
            if ($this->member === null) throw ValidationException::childRequired($this, 'member');
            if ($this->as === null) throw ValidationException::childRequired($this, 'as');
            if ($this->alias === null) throw ValidationException::childRequired($this, 'alias');
        }
        if ($flags & self::VALIDATE_EXPRESSION_CONTEXT)
        {
        }
        if ($flags & self::VALIDATE_TOKENS)
        {
        }
        $this->trait->_validate($flags);
    }
}
