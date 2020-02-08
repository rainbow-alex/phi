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

trait GeneratedCatch
{
    /**
     * @var \Phi\Token|null
     */
    private $keyword;

    /**
     * @var \Phi\Token|null
     */
    private $leftParenthesis;

    /**
     * @var \Phi\Nodes\Base\SeparatedNodesList|\Phi\Nodes\Type[]
     * @phpstan-var \Phi\Nodes\Base\SeparatedNodesList<\Phi\Nodes\Type>
     */
    private $types;

    /**
     * @var \Phi\Token|null
     */
    private $variable;

    /**
     * @var \Phi\Token|null
     */
    private $rightParenthesis;

    /**
     * @var \Phi\Nodes\Blocks\RegularBlock|null
     */
    private $block;

    /**
     */
    public function __construct()
    {
        $this->types = new \Phi\Nodes\Base\SeparatedNodesList(\Phi\Nodes\Type::class);
    }

    /**
     * @param \Phi\Token $keyword
     * @param \Phi\Token $leftParenthesis
     * @param mixed[] $types
     * @param \Phi\Token $variable
     * @param \Phi\Token $rightParenthesis
     * @param \Phi\Nodes\Blocks\RegularBlock $block
     * @return self
     */
    public static function __instantiateUnchecked($keyword, $leftParenthesis, $types, $variable, $rightParenthesis, $block)
    {
        $instance = new self;
        $instance->keyword = $keyword;
        $keyword->parent = $instance;
        $instance->leftParenthesis = $leftParenthesis;
        $leftParenthesis->parent = $instance;
        $instance->types->__initUnchecked($types);
        $instance->types->parent = $instance;
        $instance->variable = $variable;
        $variable->parent = $instance;
        $instance->rightParenthesis = $rightParenthesis;
        $rightParenthesis->parent = $instance;
        $instance->block = $block;
        $block->parent = $instance;
        return $instance;
    }

    public function getChildNodes(): array
    {
        return \array_values(\array_filter([
            $this->keyword,
            $this->leftParenthesis,
            $this->types,
            $this->variable,
            $this->rightParenthesis,
            $this->block,
        ]));
    }

    protected function &getChildRef(Node $childToDetach): Node
    {
        if ($this->keyword === $childToDetach)
        {
            return $this->keyword;
        }
        if ($this->leftParenthesis === $childToDetach)
        {
            return $this->leftParenthesis;
        }
        if ($this->variable === $childToDetach)
        {
            return $this->variable;
        }
        if ($this->rightParenthesis === $childToDetach)
        {
            return $this->rightParenthesis;
        }
        if ($this->block === $childToDetach)
        {
            return $this->block;
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

    public function getLeftParenthesis(): \Phi\Token
    {
        if ($this->leftParenthesis === null)
        {
            throw TreeException::missingNode($this, "leftParenthesis");
        }
        return $this->leftParenthesis;
    }

    public function hasLeftParenthesis(): bool
    {
        return $this->leftParenthesis !== null;
    }

    /**
     * @param \Phi\Token|\Phi\Node|string|null $leftParenthesis
     */
    public function setLeftParenthesis($leftParenthesis): void
    {
        if ($leftParenthesis !== null)
        {
            /** @var \Phi\Token $leftParenthesis */
            $leftParenthesis = NodeCoercer::coerce($leftParenthesis, \Phi\Token::class, $this->getPhpVersion());
            $leftParenthesis->detach();
            $leftParenthesis->parent = $this;
        }
        if ($this->leftParenthesis !== null)
        {
            $this->leftParenthesis->detach();
        }
        $this->leftParenthesis = $leftParenthesis;
    }

    /**
     * @return \Phi\Nodes\Base\SeparatedNodesList|\Phi\Nodes\Type[]
     * @phpstan-return \Phi\Nodes\Base\SeparatedNodesList<\Phi\Nodes\Type>
     */
    public function getTypes(): \Phi\Nodes\Base\SeparatedNodesList
    {
        return $this->types;
    }

    public function getVariable(): \Phi\Token
    {
        if ($this->variable === null)
        {
            throw TreeException::missingNode($this, "variable");
        }
        return $this->variable;
    }

    public function hasVariable(): bool
    {
        return $this->variable !== null;
    }

    /**
     * @param \Phi\Token|\Phi\Node|string|null $variable
     */
    public function setVariable($variable): void
    {
        if ($variable !== null)
        {
            /** @var \Phi\Token $variable */
            $variable = NodeCoercer::coerce($variable, \Phi\Token::class, $this->getPhpVersion());
            $variable->detach();
            $variable->parent = $this;
        }
        if ($this->variable !== null)
        {
            $this->variable->detach();
        }
        $this->variable = $variable;
    }

    public function getRightParenthesis(): \Phi\Token
    {
        if ($this->rightParenthesis === null)
        {
            throw TreeException::missingNode($this, "rightParenthesis");
        }
        return $this->rightParenthesis;
    }

    public function hasRightParenthesis(): bool
    {
        return $this->rightParenthesis !== null;
    }

    /**
     * @param \Phi\Token|\Phi\Node|string|null $rightParenthesis
     */
    public function setRightParenthesis($rightParenthesis): void
    {
        if ($rightParenthesis !== null)
        {
            /** @var \Phi\Token $rightParenthesis */
            $rightParenthesis = NodeCoercer::coerce($rightParenthesis, \Phi\Token::class, $this->getPhpVersion());
            $rightParenthesis->detach();
            $rightParenthesis->parent = $this;
        }
        if ($this->rightParenthesis !== null)
        {
            $this->rightParenthesis->detach();
        }
        $this->rightParenthesis = $rightParenthesis;
    }

    public function getBlock(): \Phi\Nodes\Blocks\RegularBlock
    {
        if ($this->block === null)
        {
            throw TreeException::missingNode($this, "block");
        }
        return $this->block;
    }

    public function hasBlock(): bool
    {
        return $this->block !== null;
    }

    /**
     * @param \Phi\Nodes\Blocks\RegularBlock|\Phi\Node|string|null $block
     */
    public function setBlock($block): void
    {
        if ($block !== null)
        {
            /** @var \Phi\Nodes\Blocks\RegularBlock $block */
            $block = NodeCoercer::coerce($block, \Phi\Nodes\Blocks\RegularBlock::class, $this->getPhpVersion());
            $block->detach();
            $block->parent = $this;
        }
        if ($this->block !== null)
        {
            $this->block->detach();
        }
        $this->block = $block;
    }

    public function _validate(int $flags): void
    {
        if ($this->keyword === null)
            throw ValidationException::missingChild($this, "keyword");
        if ($this->leftParenthesis === null)
            throw ValidationException::missingChild($this, "leftParenthesis");
        if ($this->variable === null)
            throw ValidationException::missingChild($this, "variable");
        if ($this->rightParenthesis === null)
            throw ValidationException::missingChild($this, "rightParenthesis");
        if ($this->block === null)
            throw ValidationException::missingChild($this, "block");
        if ($this->keyword->getType() !== 139)
            throw ValidationException::invalidSyntax($this->keyword, [139]);
        if ($this->leftParenthesis->getType() !== 105)
            throw ValidationException::invalidSyntax($this->leftParenthesis, [105]);
        foreach ($this->types->getSeparators() as $t)
            if ($t && $t->getType() !== 125)
                throw ValidationException::invalidSyntax($t, [125]);
        if ($this->variable->getType() !== 255)
            throw ValidationException::invalidSyntax($this->variable, [255]);
        if ($this->rightParenthesis->getType() !== 106)
            throw ValidationException::invalidSyntax($this->rightParenthesis, [106]);


        $this->extraValidation($flags);

        foreach ($this->types as $t)
            $t->_validate(0);
        $this->block->_validate(0);
    }

    public function _autocorrect(): void
    {
        foreach ($this->types as $t)
            $t->_autocorrect();
        if ($this->block)
            $this->block->_autocorrect();

        $this->extraAutocorrect();
    }
}
