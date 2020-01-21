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

abstract class GeneratedTraitUseInsteadof extends CompoundNode implements Nodes\TraitUseModification
{
    /** @var Specification[] */
    private static $specifications;
    protected static function getSpecifications(): array
    {
        return self::$specifications ?? self::$specifications = [
            new ValidCompoundNode([
                'trait' => new Any,
                'doubleColon' => new IsToken(\T_DOUBLE_COLON),
                'member' => new IsToken(\T_STRING),
                'insteadof' => new IsToken(\T_INSTEADOF),
                'excluded' => new Any,
            ]),
        ];
    }

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
        parent::__construct();
    }

    /**
     * @param Nodes\Name|null $trait
     * @param Token|null $doubleColon
     * @param Token|null $member
     * @param Token|null $insteadof
     * @param Nodes\Name|null $excluded
     * @return static
     */
    public static function __instantiateUnchecked($trait, $doubleColon, $member, $insteadof, $excluded)
    {
        $instance = new static();
        $instance->trait = $trait;
        $instance->doubleColon = $doubleColon;
        $instance->member = $member;
        $instance->insteadof = $insteadof;
        $instance->excluded = $excluded;
        return $instance;
    }

    public function &_getNodeRefs(): array
    {
        $refs = [
            'trait' => &$this->trait,
            'doubleColon' => &$this->doubleColon,
            'member' => &$this->member,
            'insteadof' => &$this->insteadof,
            'excluded' => &$this->excluded,
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
            $trait = NodeConverter::convert($trait, Nodes\Name::class, $this->_phpVersion);
            $trait->_attachTo($this);
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
            $doubleColon = NodeConverter::convert($doubleColon, Token::class, $this->_phpVersion);
            $doubleColon->_attachTo($this);
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
            $member = NodeConverter::convert($member, Token::class, $this->_phpVersion);
            $member->_attachTo($this);
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
            $insteadof = NodeConverter::convert($insteadof, Token::class, $this->_phpVersion);
            $insteadof->_attachTo($this);
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
            $excluded = NodeConverter::convert($excluded, Nodes\Name::class, $this->_phpVersion);
            $excluded->_attachTo($this);
        }
        if ($this->excluded !== null)
        {
            $this->excluded->detach();
        }
        $this->excluded = $excluded;
    }
}
