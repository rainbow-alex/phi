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

trait GeneratedDoWhileStatement
{
    /**
     * @var \Phi\Token|null
     */
    private $doKeyword;

    /**
     * @var \Phi\Nodes\Block|null
     */
    private $block;

    /**
     * @var \Phi\Token|null
     */
    private $whileKeyword;

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
     * @var \Phi\Token|null
     */
    private $delimiter;

    /**
     */
    public function __construct()
    {
    }

    /**
     * @param \Phi\Token $doKeyword
     * @param \Phi\Nodes\Block $block
     * @param \Phi\Token $whileKeyword
     * @param \Phi\Token $leftParenthesis
     * @param \Phi\Nodes\Expression $condition
     * @param \Phi\Token $rightParenthesis
     * @param \Phi\Token $delimiter
     * @return self
     */
    public static function __instantiateUnchecked($doKeyword, $block, $whileKeyword, $leftParenthesis, $condition, $rightParenthesis, $delimiter)
    {
        $instance = new self;
        $instance->doKeyword = $doKeyword;
        $doKeyword->parent = $instance;
        $instance->block = $block;
        $block->parent = $instance;
        $instance->whileKeyword = $whileKeyword;
        $whileKeyword->parent = $instance;
        $instance->leftParenthesis = $leftParenthesis;
        $leftParenthesis->parent = $instance;
        $instance->condition = $condition;
        $condition->parent = $instance;
        $instance->rightParenthesis = $rightParenthesis;
        $rightParenthesis->parent = $instance;
        $instance->delimiter = $delimiter;
        $delimiter->parent = $instance;
        return $instance;
    }

    public function getChildNodes(): array
    {
        return \array_values(\array_filter([
            $this->doKeyword,
            $this->block,
            $this->whileKeyword,
            $this->leftParenthesis,
            $this->condition,
            $this->rightParenthesis,
            $this->delimiter,
        ]));
    }

    protected function &getChildRef(Node $childToDetach): Node
    {
        if ($this->doKeyword === $childToDetach)
        {
            return $this->doKeyword;
        }
        if ($this->block === $childToDetach)
        {
            return $this->block;
        }
        if ($this->whileKeyword === $childToDetach)
        {
            return $this->whileKeyword;
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
        if ($this->delimiter === $childToDetach)
        {
            return $this->delimiter;
        }
        throw new \LogicException();
    }

    public function getDoKeyword(): \Phi\Token
    {
        if ($this->doKeyword === null)
        {
            throw TreeException::missingNode($this, "doKeyword");
        }
        return $this->doKeyword;
    }

    public function hasDoKeyword(): bool
    {
        return $this->doKeyword !== null;
    }

    /**
     * @param \Phi\Token|\Phi\Node|string|null $doKeyword
     */
    public function setDoKeyword($doKeyword): void
    {
        if ($doKeyword !== null)
        {
            /** @var \Phi\Token $doKeyword */
            $doKeyword = NodeCoercer::coerce($doKeyword, \Phi\Token::class, $this->getPhpVersion());
            $doKeyword->detach();
            $doKeyword->parent = $this;
        }
        if ($this->doKeyword !== null)
        {
            $this->doKeyword->detach();
        }
        $this->doKeyword = $doKeyword;
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

    public function getWhileKeyword(): \Phi\Token
    {
        if ($this->whileKeyword === null)
        {
            throw TreeException::missingNode($this, "whileKeyword");
        }
        return $this->whileKeyword;
    }

    public function hasWhileKeyword(): bool
    {
        return $this->whileKeyword !== null;
    }

    /**
     * @param \Phi\Token|\Phi\Node|string|null $whileKeyword
     */
    public function setWhileKeyword($whileKeyword): void
    {
        if ($whileKeyword !== null)
        {
            /** @var \Phi\Token $whileKeyword */
            $whileKeyword = NodeCoercer::coerce($whileKeyword, \Phi\Token::class, $this->getPhpVersion());
            $whileKeyword->detach();
            $whileKeyword->parent = $this;
        }
        if ($this->whileKeyword !== null)
        {
            $this->whileKeyword->detach();
        }
        $this->whileKeyword = $whileKeyword;
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

    public function getDelimiter(): \Phi\Token
    {
        if ($this->delimiter === null)
        {
            throw TreeException::missingNode($this, "delimiter");
        }
        return $this->delimiter;
    }

    public function hasDelimiter(): bool
    {
        return $this->delimiter !== null;
    }

    /**
     * @param \Phi\Token|\Phi\Node|string|null $delimiter
     */
    public function setDelimiter($delimiter): void
    {
        if ($delimiter !== null)
        {
            /** @var \Phi\Token $delimiter */
            $delimiter = NodeCoercer::coerce($delimiter, \Phi\Token::class, $this->getPhpVersion());
            $delimiter->detach();
            $delimiter->parent = $this;
        }
        if ($this->delimiter !== null)
        {
            $this->delimiter->detach();
        }
        $this->delimiter = $delimiter;
    }

    public function _validate(int $flags): void
    {
        if ($this->doKeyword === null)
            throw ValidationException::missingChild($this, "doKeyword");
        if ($this->block === null)
            throw ValidationException::missingChild($this, "block");
        if ($this->whileKeyword === null)
            throw ValidationException::missingChild($this, "whileKeyword");
        if ($this->leftParenthesis === null)
            throw ValidationException::missingChild($this, "leftParenthesis");
        if ($this->condition === null)
            throw ValidationException::missingChild($this, "condition");
        if ($this->rightParenthesis === null)
            throw ValidationException::missingChild($this, "rightParenthesis");
        if ($this->delimiter === null)
            throw ValidationException::missingChild($this, "delimiter");
        if ($this->doKeyword->getType() !== 157)
            throw ValidationException::invalidSyntax($this->doKeyword, [157]);
        if ($this->whileKeyword->getType() !== 256)
            throw ValidationException::invalidSyntax($this->whileKeyword, [256]);
        if ($this->leftParenthesis->getType() !== 105)
            throw ValidationException::invalidSyntax($this->leftParenthesis, [105]);
        if ($this->rightParenthesis->getType() !== 106)
            throw ValidationException::invalidSyntax($this->rightParenthesis, [106]);
        if (!\in_array($this->delimiter->getType(), [114, 143], true))
            throw ValidationException::invalidSyntax($this->delimiter, [114, 143]);


        $this->extraValidation($flags);

        $this->block->_validate(0);
        $this->condition->_validate(1);
    }

    public function _autocorrect(): void
    {
        if ($this->block)
            $this->block->_autocorrect();
        if ($this->condition)
            $this->condition->_autocorrect();

        $this->extraAutocorrect();
    }
}
