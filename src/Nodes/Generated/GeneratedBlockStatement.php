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

abstract class GeneratedBlockStatement extends Nodes\Statement
{
    /**
     * @var Nodes\RegularBlock|null
     */
    private $block;


    /**
     * @param Nodes\RegularBlock|Node|string|null $block
     */
    public function __construct($block = null)
    {
        if ($block !== null)
        {
            $this->setBlock($block);
        }
    }

    /**
     * @param int $phpVersion
     * @param Nodes\RegularBlock $block
     * @return static
     */
    public static function __instantiateUnchecked($phpVersion, $block)
    {
        $instance = new static;
        $instance->phpVersion = $phpVersion;
        $instance->block = $block;
        $block->parent = $instance;
        return $instance;
    }

    protected function &_getNodeRefs(): array
    {
        $refs = [
            "block" => &$this->block,
        ];
        return $refs;
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

    protected function _validate(int $flags): void
    {
        if ($this->block === null) throw ValidationException::childRequired($this, "block");
        if ($flags & self::VALIDATE_TYPES)
        {
        }
        if ($flags & self::VALIDATE_EXPRESSION_CONTEXT)
        {
        }
        if ($flags & self::VALIDATE_TOKENS)
        {
        }
        $this->block->_validate($flags);
    }
}
