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

trait GeneratedNamespaceStatement
{
    /**
     * @var \Phi\Token|null
     */
    private $keyword;

    /**
     * @var \Phi\Nodes\Helpers\Name|null
     */
    private $name;

    /**
     * @var \Phi\Nodes\Blocks\RegularBlock|null
     */
    private $block;

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
     * @param \Phi\Token $keyword
     * @param \Phi\Nodes\Helpers\Name|null $name
     * @param \Phi\Nodes\Blocks\RegularBlock|null $block
     * @param \Phi\Token|null $delimiter
     * @return self
     */
    public static function __instantiateUnchecked($keyword, $name, $block, $delimiter)
    {
        $instance = new self;
        $instance->keyword = $keyword;
        $keyword->parent = $instance;
        $instance->name = $name;
        if ($name) $name->parent = $instance;
        $instance->block = $block;
        if ($block) $block->parent = $instance;
        $instance->delimiter = $delimiter;
        if ($delimiter) $delimiter->parent = $instance;
        return $instance;
    }

    public function getChildNodes(): array
    {
        return \array_values(\array_filter([
            $this->keyword,
            $this->name,
            $this->block,
            $this->delimiter,
        ]));
    }

    protected function &getChildRef(Node $childToDetach): Node
    {
        if ($this->keyword === $childToDetach)
        {
            return $this->keyword;
        }
        if ($this->name === $childToDetach)
        {
            return $this->name;
        }
        if ($this->block === $childToDetach)
        {
            return $this->block;
        }
        if ($this->delimiter === $childToDetach)
        {
            return $this->delimiter;
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

    public function getName(): ?\Phi\Nodes\Helpers\Name
    {
        return $this->name;
    }

    public function hasName(): bool
    {
        return $this->name !== null;
    }

    /**
     * @param \Phi\Nodes\Helpers\Name|\Phi\Node|string|null $name
     */
    public function setName($name): void
    {
        if ($name !== null)
        {
            /** @var \Phi\Nodes\Helpers\Name $name */
            $name = NodeCoercer::coerce($name, \Phi\Nodes\Helpers\Name::class, $this->getPhpVersion());
            $name->detach();
            $name->parent = $this;
        }
        if ($this->name !== null)
        {
            $this->name->detach();
        }
        $this->name = $name;
    }

    public function getBlock(): ?\Phi\Nodes\Blocks\RegularBlock
    {
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

    public function getDelimiter(): ?\Phi\Token
    {
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
        if ($this->keyword === null)
            throw ValidationException::missingChild($this, "keyword");
        if ($this->keyword->getType() !== 216)
            throw ValidationException::invalidSyntax($this->keyword, [216]);
        if ($this->delimiter)
        if (!\in_array($this->delimiter->getType(), [114, 143], true))
            throw ValidationException::invalidSyntax($this->delimiter, [114, 143]);


        $this->extraValidation($flags);

        if ($this->name)
            $this->name->_validate(0);
        if ($this->block)
            $this->block->_validate(0);
    }

    public function _autocorrect(): void
    {
        if ($this->name)
            $this->name->_autocorrect();
        if ($this->block)
            $this->block->_autocorrect();

        $this->extraAutocorrect();
    }
}
