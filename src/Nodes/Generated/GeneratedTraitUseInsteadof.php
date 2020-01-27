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

abstract class GeneratedTraitUseInsteadof extends Nodes\TraitUseModification
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
    private $insteadof;

    /**
     * @var Nodes\Name|null
     */
    private $excluded;

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
     * @param Token|null $insteadof
     * @param Nodes\Name|null $excluded
     * @return static
     */
    public static function __instantiateUnchecked($phpVersion, $trait, $doubleColon, $member, $insteadof, $excluded)
    {
        $instance = new static;
        $instance->phpVersion = $phpVersion;
        $instance->trait = $trait;
        $instance->trait->parent = $instance;
        $instance->doubleColon = $doubleColon;
        $instance->doubleColon->parent = $instance;
        $instance->member = $member;
        $instance->member->parent = $instance;
        $instance->insteadof = $insteadof;
        $instance->insteadof->parent = $instance;
        $instance->excluded = $excluded;
        $instance->excluded->parent = $instance;
        return $instance;
    }

    protected function &_getNodeRefs(): array
    {
        $refs = [
            "trait" => &$this->trait,
            "doubleColon" => &$this->doubleColon,
            "member" => &$this->member,
            "insteadof" => &$this->insteadof,
            "excluded" => &$this->excluded,
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

    public function getInsteadof(): Token
    {
        if ($this->insteadof === null)
        {
            throw new MissingNodeException($this, __FUNCTION__);
        }
        return $this->insteadof;
    }

    public function hasInsteadof(): bool
    {
        return $this->insteadof !== null;
    }

    /**
     * @param Token|Node|string|null $insteadof
     */
    public function setInsteadof($insteadof): void
    {
        if ($insteadof !== null)
        {
            /** @var Token $insteadof */
            $insteadof = NodeConverter::convert($insteadof, Token::class, $this->phpVersion);
            $insteadof->detach();
            $insteadof->parent = $this;
        }
        if ($this->insteadof !== null)
        {
            $this->insteadof->detach();
        }
        $this->insteadof = $insteadof;
    }

    public function getExcluded(): Nodes\Name
    {
        if ($this->excluded === null)
        {
            throw new MissingNodeException($this, __FUNCTION__);
        }
        return $this->excluded;
    }

    public function hasExcluded(): bool
    {
        return $this->excluded !== null;
    }

    /**
     * @param Nodes\Name|Node|string|null $excluded
     */
    public function setExcluded($excluded): void
    {
        if ($excluded !== null)
        {
            /** @var Nodes\Name $excluded */
            $excluded = NodeConverter::convert($excluded, Nodes\Name::class, $this->phpVersion);
            $excluded->detach();
            $excluded->parent = $this;
        }
        if ($this->excluded !== null)
        {
            $this->excluded->detach();
        }
        $this->excluded = $excluded;
    }

    protected function _validate(int $flags): void
    {
        if ($flags & self::VALIDATE_TYPES)
        {
            if ($this->trait === null) throw ValidationException::childRequired($this, "trait");
            if ($this->doubleColon === null) throw ValidationException::childRequired($this, "doubleColon");
            if ($this->member === null) throw ValidationException::childRequired($this, "member");
            if ($this->insteadof === null) throw ValidationException::childRequired($this, "insteadof");
            if ($this->excluded === null) throw ValidationException::childRequired($this, "excluded");
        }
        if ($flags & self::VALIDATE_EXPRESSION_CONTEXT)
        {
        }
        if ($flags & self::VALIDATE_TOKENS)
        {
        }
        $this->trait->_validate($flags);
        $this->excluded->_validate($flags);
    }
}
