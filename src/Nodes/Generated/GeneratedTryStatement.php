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

abstract class GeneratedTryStatement extends Nodes\Statement
{
    /**
     * @var Token|null
     */
    private $keyword;

    /**
     * @var Nodes\RegularBlock|null
     */
    private $block;

    /**
     * @var NodesList|Nodes\Catch_[]
     */
    private $catches;

    /**
     * @var Nodes\Finally_|null
     */
    private $finally;

    /**
     */
    public function __construct()
    {
        $this->catches = new NodesList();
    }

    /**
     * @param int $phpVersion
     * @param Token|null $keyword
     * @param Nodes\RegularBlock|null $block
     * @param mixed[] $catches
     * @param Nodes\Finally_|null $finally
     * @return static
     */
    public static function __instantiateUnchecked($phpVersion, $keyword, $block, $catches, $finally)
    {
        $instance = new static;
        $instance->phpVersion = $phpVersion;
        $instance->keyword = $keyword;
        $instance->keyword->parent = $instance;
        $instance->block = $block;
        $instance->block->parent = $instance;
        $instance->catches->__initUnchecked($catches);
        $instance->catches->parent = $instance;
        $instance->finally = $finally;
        if ($finally)
        {
            $instance->finally->parent = $instance;
        }
        return $instance;
    }

    protected function &_getNodeRefs(): array
    {
        $refs = [
            'keyword' => &$this->keyword,
            'block' => &$this->block,
            'catches' => &$this->catches,
            'finally' => &$this->finally,
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

    public function getBlock(): Nodes\RegularBlock
    {
        if ($this->block === null)
        {
            throw new MissingNodeException($this, __FUNCTION__);
        }
        return $this->block;
    }

    public function hasBlock(): bool
    {
        return $this->block !== null;
    }

    /**
     * @param Nodes\RegularBlock|Node|string|null $block
     */
    public function setBlock($block): void
    {
        if ($block !== null)
        {
            /** @var Nodes\RegularBlock $block */
            $block = NodeConverter::convert($block, Nodes\RegularBlock::class, $this->phpVersion);
            $block->detach();
            $block->parent = $this;
        }
        if ($this->block !== null)
        {
            $this->block->detach();
        }
        $this->block = $block;
    }

    /**
     * @return NodesList|Nodes\Catch_[]
     */
    public function getCatches(): NodesList
    {
        return $this->catches;
    }

    /**
     * @param Nodes\Catch_ $catch
     */
    public function addCatch($catch): void
    {
        /** @var Nodes\Catch_ $catch */
        $catch = NodeConverter::convert($catch, Nodes\Catch_::class, $this->phpVersion);
        $this->catches->add($catch);
    }

    public function getFinally(): ?Nodes\Finally_
    {
        return $this->finally;
    }

    public function hasFinally(): bool
    {
        return $this->finally !== null;
    }

    /**
     * @param Nodes\Finally_|Node|string|null $finally
     */
    public function setFinally($finally): void
    {
        if ($finally !== null)
        {
            /** @var Nodes\Finally_ $finally */
            $finally = NodeConverter::convert($finally, Nodes\Finally_::class, $this->phpVersion);
            $finally->detach();
            $finally->parent = $this;
        }
        if ($this->finally !== null)
        {
            $this->finally->detach();
        }
        $this->finally = $finally;
    }

    protected function _validate(int $flags): void
    {
        if ($flags & self::VALIDATE_TYPES)
        {
            if ($this->keyword === null) throw ValidationException::childRequired($this, 'keyword');
            if ($this->block === null) throw ValidationException::childRequired($this, 'block');
        }
        if ($flags & self::VALIDATE_EXPRESSION_CONTEXT)
        {
        }
        if ($flags & self::VALIDATE_TOKENS)
        {
        }
        $this->block->_validate($flags);
        $this->catches->_validate($flags);
        if ($this->finally)
        {
            $this->finally->_validate($flags);
        }
    }
}
