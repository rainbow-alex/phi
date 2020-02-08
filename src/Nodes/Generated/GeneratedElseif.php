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

trait GeneratedElseif
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
     * @var \Phi\Nodes\Expression|null
     */
    private $condition;

    /**
     * @var \Phi\Token|null
     */
    private $rightParenthesis;

    /**
     * @var \Phi\Nodes\Block|null
     */
    private $block;

    /**
     * @param \Phi\Nodes\Expression|\Phi\Node|string|null $condition
     * @param \Phi\Nodes\Block|\Phi\Node|string|null $block
     */
    public function __construct($condition = null, $block = null)
    {
        if ($condition !== null)
        {
            $this->setCondition($condition);
        }
        if ($block !== null)
        {
            $this->setBlock($block);
        }
    }

    /**
     * @param \Phi\Token $keyword
     * @param \Phi\Token $leftParenthesis
     * @param \Phi\Nodes\Expression $condition
     * @param \Phi\Token $rightParenthesis
     * @param \Phi\Nodes\Block $block
     * @return self
     */
    public static function __instantiateUnchecked($keyword, $leftParenthesis, $condition, $rightParenthesis, $block)
    {
        $instance = new self;
        $instance->keyword = $keyword;
        $keyword->parent = $instance;
        $instance->leftParenthesis = $leftParenthesis;
        $leftParenthesis->parent = $instance;
        $instance->condition = $condition;
        $condition->parent = $instance;
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
            $this->condition,
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
        if ($this->condition === $childToDetach)
        {
            return $this->condition;
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

    public function getCondition(): \Phi\Nodes\Expression
    {
        if ($this->condition === null)
        {
            throw TreeException::missingNode($this, "condition");
        }
        return $this->condition;
    }

    public function hasCondition(): bool
    {
        return $this->condition !== null;
    }

    /**
     * @param \Phi\Nodes\Expression|\Phi\Node|string|null $condition
     */
    public function setCondition($condition): void
    {
        if ($condition !== null)
        {
            /** @var \Phi\Nodes\Expression $condition */
            $condition = NodeCoercer::coerce($condition, \Phi\Nodes\Expression::class, $this->getPhpVersion());
            $condition->detach();
            $condition->parent = $this;
        }
        if ($this->condition !== null)
        {
            $this->condition->detach();
        }
        $this->condition = $condition;
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

    public function getBlock(): \Phi\Nodes\Block
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
     * @param \Phi\Nodes\Block|\Phi\Node|string|null $block
     */
    public function setBlock($block): void
    {
        if ($block !== null)
        {
            /** @var \Phi\Nodes\Block $block */
            $block = NodeCoercer::coerce($block, \Phi\Nodes\Block::class, $this->getPhpVersion());
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
        if ($this->condition === null)
            throw ValidationException::missingChild($this, "condition");
        if ($this->rightParenthesis === null)
            throw ValidationException::missingChild($this, "rightParenthesis");
        if ($this->block === null)
            throw ValidationException::missingChild($this, "block");
        if ($this->keyword->getType() !== 166)
            throw ValidationException::invalidSyntax($this->keyword, [166]);
        if ($this->leftParenthesis->getType() !== 105)
            throw ValidationException::invalidSyntax($this->leftParenthesis, [105]);
        if ($this->rightParenthesis->getType() !== 106)
            throw ValidationException::invalidSyntax($this->rightParenthesis, [106]);


        $this->extraValidation($flags);

        $this->condition->_validate(1);
        $this->block->_validate(0);
    }

    public function _autocorrect(): void
    {
        if ($this->condition)
            $this->condition->_autocorrect();
        if ($this->block)
            $this->block->_autocorrect();

        $this->extraAutocorrect();
    }
}
