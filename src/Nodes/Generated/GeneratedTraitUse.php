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

abstract class GeneratedTraitUse extends CompoundNode implements Nodes\ClassLikeMember
{
    /** @var Specification[] */
    private static $specifications;
    protected static function getSpecifications(): array
    {
        return self::$specifications ?? self::$specifications = [
            new ValidCompoundNode([
                'keyword' => new IsToken(\T_USE),
                'traits' => new And_(new EachItem(new IsInstanceOf(Nodes\Name::class)), new EachSeparator(new IsToken(','))),
                'leftBrace' => new Optional(new IsToken('{')),
                'modifications' => new EachItem(new IsInstanceOf(Nodes\TraitUseModification::class)),
                'rightBrace' => new Optional(new IsToken('{')),
                'semiColon' => new Optional(new IsToken(';')),
            ]),
        ];
    }

    /**
     * @var Token|null
     */
    private $keyword;
    /**
     * @var SeparatedNodesList|Nodes\Name[]
     */
    private $traits;
    /**
     * @var Token|null
     */
    private $leftBrace;
    /**
     * @var NodesList|Nodes\TraitUseModification[]
     */
    private $modifications;
    /**
     * @var Token|null
     */
    private $rightBrace;
    /**
     * @var Token|null
     */
    private $semiColon;

    /**
     */
    public function __construct()
    {
        parent::__construct();
        $this->traits = new SeparatedNodesList();
        $this->modifications = new NodesList();
    }

    /**
     * @param Token|null $keyword
     * @param mixed[] $traits
     * @param Token|null $leftBrace
     * @param mixed[] $modifications
     * @param Token|null $rightBrace
     * @param Token|null $semiColon
     * @return static
     */
    public static function __instantiateUnchecked($keyword, $traits, $leftBrace, $modifications, $rightBrace, $semiColon)
    {
        $instance = new static();
        $instance->keyword = $keyword;
        $instance->traits->__initUnchecked($traits);
        $instance->leftBrace = $leftBrace;
        $instance->modifications->__initUnchecked($modifications);
        $instance->rightBrace = $rightBrace;
        $instance->semiColon = $semiColon;
        return $instance;
    }

    public function &_getNodeRefs(): array
    {
        $refs = [
            'keyword' => &$this->keyword,
            'traits' => &$this->traits,
            'leftBrace' => &$this->leftBrace,
            'modifications' => &$this->modifications,
            'rightBrace' => &$this->rightBrace,
            'semiColon' => &$this->semiColon,
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
            $keyword = NodeConverter::convert($keyword, Token::class, $this->_phpVersion);
            $keyword->_attachTo($this);
        }
        if ($this->keyword !== null)
        {
            $this->keyword->detach();
        }
        $this->keyword = $keyword;
    }

    /**
     * @return SeparatedNodesList|Nodes\Name[]
     */
    public function getTraits(): SeparatedNodesList
    {
        return $this->traits;
    }

    /**
     * @param Nodes\Name $trait
     */
    public function addTrait($trait): void
    {
        /** @var Nodes\Name $trait */
        $trait = NodeConverter::convert($trait, Nodes\Name::class);
        $this->traits->add($trait);
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
     * @return NodesList|Nodes\TraitUseModification[]
     */
    public function getModifications(): NodesList
    {
        return $this->modifications;
    }

    /**
     * @param Nodes\TraitUseModification $modification
     */
    public function addModification($modification): void
    {
        /** @var Nodes\TraitUseModification $modification */
        $modification = NodeConverter::convert($modification, Nodes\TraitUseModification::class);
        $this->modifications->add($modification);
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
            $rightBrace = NodeConverter::convert($rightBrace, Token::class, $this->_phpVersion);
            $rightBrace->_attachTo($this);
        }
        if ($this->rightBrace !== null)
        {
            $this->rightBrace->detach();
        }
        $this->rightBrace = $rightBrace;
    }

    public function getSemiColon(): ?Token
    {
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
