<?php

declare(strict_types=1);

/**
 * This code is generated.
 * @see meta/
 */

namespace Phi\Nodes\Generated;

use Phi\Node;
use Phi\Token;
use Phi\Exception\TreeException;
use Phi\NodeCoercer;
use Phi\Exception\ValidationException;

trait GeneratedTraitUse
{
    /**
     * @var \Phi\Token|null
     */
    private $keyword;

    /**
     * @var \Phi\Nodes\Base\SeparatedNodesList|\Phi\Nodes\Helpers\Name[]
     * @phpstan-var \Phi\Nodes\Base\SeparatedNodesList<\Phi\Nodes\Helpers\Name>
     */
    private $traits;

    /**
     * @var \Phi\Token|null
     */
    private $leftBrace;

    /**
     * @var \Phi\Nodes\Base\NodesList|\Phi\Nodes\Oop\TraitUseModification[]
     * @phpstan-var \Phi\Nodes\Base\NodesList<\Phi\Nodes\Oop\TraitUseModification>
     */
    private $modifications;

    /**
     * @var \Phi\Token|null
     */
    private $rightBrace;

    /**
     * @var \Phi\Token|null
     */
    private $semiColon;

    /**
     */
    public function __construct()
    {
        $this->traits = new \Phi\Nodes\Base\SeparatedNodesList(\Phi\Nodes\Helpers\Name::class);
        $this->modifications = new \Phi\Nodes\Base\NodesList(\Phi\Nodes\Oop\TraitUseModification::class);
    }

    /**
     * @param \Phi\Token $keyword
     * @param mixed[] $traits
     * @param \Phi\Token|null $leftBrace
     * @param mixed[] $modifications
     * @param \Phi\Token|null $rightBrace
     * @param \Phi\Token|null $semiColon
     * @return self
     */
    public static function __instantiateUnchecked($keyword, $traits, $leftBrace, $modifications, $rightBrace, $semiColon)
    {
        $instance = new self;
        $instance->keyword = $keyword;
        $keyword->parent = $instance;
        $instance->traits->__initUnchecked($traits);
        $instance->traits->parent = $instance;
        $instance->leftBrace = $leftBrace;
        if ($leftBrace) $leftBrace->parent = $instance;
        $instance->modifications->__initUnchecked($modifications);
        $instance->modifications->parent = $instance;
        $instance->rightBrace = $rightBrace;
        if ($rightBrace) $rightBrace->parent = $instance;
        $instance->semiColon = $semiColon;
        if ($semiColon) $semiColon->parent = $instance;
        return $instance;
    }

    public function getChildNodes(): array
    {
        return \array_values(\array_filter([
            $this->keyword,
            $this->traits,
            $this->leftBrace,
            $this->modifications,
            $this->rightBrace,
            $this->semiColon,
        ]));
    }

    protected function &getChildRef(Node $childToDetach): Node
    {
        if ($this->keyword === $childToDetach)
        {
            return $this->keyword;
        }
        if ($this->leftBrace === $childToDetach)
        {
            return $this->leftBrace;
        }
        if ($this->rightBrace === $childToDetach)
        {
            return $this->rightBrace;
        }
        if ($this->semiColon === $childToDetach)
        {
            return $this->semiColon;
        }
        throw new \LogicException();
    }

    public function getKeyword(): \Phi\Token
    {
        if ($this->keyword === null)
        {
            throw TreeException::missingNode($this, "keyword");
        }
        return $this->keyword;
    }

    public function hasKeyword(): bool
    {
        return $this->keyword !== null;
    }

    /**
     * @param \Phi\Token|\Phi\Node|string|null $keyword
     */
    public function setKeyword($keyword): void
    {
        if ($keyword !== null)
        {
            /** @var \Phi\Token $keyword */
            $keyword = NodeCoercer::coerce($keyword, \Phi\Token::class, $this->getPhpVersion());
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
     * @return \Phi\Nodes\Base\SeparatedNodesList|\Phi\Nodes\Helpers\Name[]
     * @phpstan-return \Phi\Nodes\Base\SeparatedNodesList<\Phi\Nodes\Helpers\Name>
     */
    public function getTraits(): \Phi\Nodes\Base\SeparatedNodesList
    {
        return $this->traits;
    }

    public function getLeftBrace(): ?\Phi\Token
    {
        return $this->leftBrace;
    }

    public function hasLeftBrace(): bool
    {
        return $this->leftBrace !== null;
    }

    /**
     * @param \Phi\Token|\Phi\Node|string|null $leftBrace
     */
    public function setLeftBrace($leftBrace): void
    {
        if ($leftBrace !== null)
        {
            /** @var \Phi\Token $leftBrace */
            $leftBrace = NodeCoercer::coerce($leftBrace, \Phi\Token::class, $this->getPhpVersion());
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
     * @return \Phi\Nodes\Base\NodesList|\Phi\Nodes\Oop\TraitUseModification[]
     * @phpstan-return \Phi\Nodes\Base\NodesList<\Phi\Nodes\Oop\TraitUseModification>
     */
    public function getModifications(): \Phi\Nodes\Base\NodesList
    {
        return $this->modifications;
    }

    public function getRightBrace(): ?\Phi\Token
    {
        return $this->rightBrace;
    }

    public function hasRightBrace(): bool
    {
        return $this->rightBrace !== null;
    }

    /**
     * @param \Phi\Token|\Phi\Node|string|null $rightBrace
     */
    public function setRightBrace($rightBrace): void
    {
        if ($rightBrace !== null)
        {
            /** @var \Phi\Token $rightBrace */
            $rightBrace = NodeCoercer::coerce($rightBrace, \Phi\Token::class, $this->getPhpVersion());
            $rightBrace->detach();
            $rightBrace->parent = $this;
        }
        if ($this->rightBrace !== null)
        {
            $this->rightBrace->detach();
        }
        $this->rightBrace = $rightBrace;
    }

    public function getSemiColon(): ?\Phi\Token
    {
        return $this->semiColon;
    }

    public function hasSemiColon(): bool
    {
        return $this->semiColon !== null;
    }

    /**
     * @param \Phi\Token|\Phi\Node|string|null $semiColon
     */
    public function setSemiColon($semiColon): void
    {
        if ($semiColon !== null)
        {
            /** @var \Phi\Token $semiColon */
            $semiColon = NodeCoercer::coerce($semiColon, \Phi\Token::class, $this->getPhpVersion());
            $semiColon->detach();
            $semiColon->parent = $this;
        }
        if ($this->semiColon !== null)
        {
            $this->semiColon->detach();
        }
        $this->semiColon = $semiColon;
    }

    public function _validate(int $flags): void
    {
        if ($this->keyword === null)
            throw ValidationException::missingChild($this, "keyword");
        if ($this->keyword->getType() !== 253)
            throw ValidationException::invalidSyntax($this->keyword, [253]);
        foreach ($this->traits->getSeparators() as $t)
            if ($t && $t->getType() !== 109)
                throw ValidationException::invalidSyntax($t, [109]);
        if ($this->leftBrace)
        if ($this->leftBrace->getType() !== 124)
            throw ValidationException::invalidSyntax($this->leftBrace, [124]);
        if ($this->rightBrace)
        if ($this->rightBrace->getType() !== 126)
            throw ValidationException::invalidSyntax($this->rightBrace, [126]);
        if ($this->semiColon)
        if ($this->semiColon->getType() !== 114)
            throw ValidationException::invalidSyntax($this->semiColon, [114]);


        $this->extraValidation($flags);

        foreach ($this->traits as $t)
            $t->_validate(0);
        foreach ($this->modifications as $t)
            $t->_validate(0);
    }

    public function _autocorrect(): void
    {
        foreach ($this->traits as $t)
            $t->_autocorrect();
        foreach ($this->modifications as $t)
            $t->_autocorrect();

        $this->extraAutocorrect();
    }
}
