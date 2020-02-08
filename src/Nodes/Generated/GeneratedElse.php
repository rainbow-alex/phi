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

trait GeneratedElse
{
    /**
     * @var \Phi\Token|null
     */
    private $keyword;

    /**
     * @var \Phi\Nodes\Block|null
     */
    private $block;

    /**
     * @param \Phi\Nodes\Block|\Phi\Node|string|null $block
     */
    public function __construct($block = null)
    {
        if ($block !== null)
        {
            $this->setBlock($block);
        }
    }

    /**
     * @param \Phi\Token $keyword
     * @param \Phi\Nodes\Block $block
     * @return self
     */
    public static function __instantiateUnchecked($keyword, $block)
    {
        $instance = new self;
        $instance->keyword = $keyword;
        $keyword->parent = $instance;
        $instance->block = $block;
        $block->parent = $instance;
        return $instance;
    }

    public function getChildNodes(): array
    {
        return \array_values(\array_filter([
            $this->keyword,
            $this->block,
        ]));
    }

    protected function &getChildRef(Node $childToDetach): Node
    {
        if ($this->keyword === $childToDetach)
        {
            return $this->keyword;
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
        if ($this->block === null)
            throw ValidationException::missingChild($this, "block");
        if ($this->keyword->getType() !== 165)
            throw ValidationException::invalidSyntax($this->keyword, [165]);


        $this->extraValidation($flags);

        $this->block->_validate(0);
    }

    public function _autocorrect(): void
    {
        if ($this->block)
            $this->block->_autocorrect();

        $this->extraAutocorrect();
    }
}
