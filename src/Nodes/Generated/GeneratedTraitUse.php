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

abstract class GeneratedTraitUse extends Nodes\ClassLikeMember
{
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
        $this->traits = new SeparatedNodesList();
        $this->modifications = new NodesList();
    }

    /**
     * @param int $phpVersion
     * @param Token|null $keyword
     * @param mixed[] $traits
     * @param Token|null $leftBrace
     * @param mixed[] $modifications
     * @param Token|null $rightBrace
     * @param Token|null $semiColon
     * @return static
     */
    public static function __instantiateUnchecked($phpVersion, $keyword, $traits, $leftBrace, $modifications, $rightBrace, $semiColon)
    {
        $instance = new static;
        $instance->phpVersion = $phpVersion;
        $instance->keyword = $keyword;
        $instance->keyword->parent = $instance;
        $instance->traits->__initUnchecked($traits);
        $instance->traits->parent = $instance;
        $instance->leftBrace = $leftBrace;
        if ($leftBrace)
        {
            $instance->leftBrace->parent = $instance;
        }
        $instance->modifications->__initUnchecked($modifications);
        $instance->modifications->parent = $instance;
        $instance->rightBrace = $rightBrace;
        if ($rightBrace)
        {
            $instance->rightBrace->parent = $instance;
        }
        $instance->semiColon = $semiColon;
        if ($semiColon)
        {
            $instance->semiColon->parent = $instance;
        }
        return $instance;
    }

    protected function &_getNodeRefs(): array
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
        $trait = NodeConverter::convert($trait, Nodes\Name::class, $this->phpVersion);
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
        $modification = NodeConverter::convert($modification, Nodes\TraitUseModification::class, $this->phpVersion);
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
        if ($flags & self::VALIDATE_TYPES)
        {
            if ($this->keyword === null) throw ValidationException::childRequired($this, 'keyword');
        }
        if ($flags & self::VALIDATE_EXPRESSION_CONTEXT)
        {
        }
        if ($flags & self::VALIDATE_TOKENS)
        {
        }
        $this->traits->_validate($flags);
        $this->modifications->_validate($flags);
    }
}
