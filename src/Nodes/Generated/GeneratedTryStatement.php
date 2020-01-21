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

abstract class GeneratedTryStatement extends CompoundNode implements Nodes\Statement
{
    /** @var Specification[] */
    private static $specifications;
    protected static function getSpecifications(): array
    {
        return self::$specifications ?? self::$specifications = [
            new ValidCompoundNode([
                'keyword' => new IsToken(\T_TRY),
                'block' => new Any,
                'catches' => new EachItem(new IsInstanceOf(Nodes\Catch_::class)),
                'finally' => new Optional(new Any),
            ]),
        ];
    }

    /**
     * @var Token|null
     */
    private $keyword;
    /**
     * @var Nodes\Block|null
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
        parent::__construct();
        $this->catches = new NodesList();
    }

    /**
     * @param Token|null $keyword
     * @param Nodes\Block|null $block
     * @param mixed[] $catches
     * @param Nodes\Finally_|null $finally
     * @return static
     */
    public static function __instantiateUnchecked($keyword, $block, $catches, $finally)
    {
        $instance = new static();
        $instance->keyword = $keyword;
        $instance->block = $block;
        $instance->catches->__initUnchecked($catches);
        $instance->finally = $finally;
        return $instance;
    }

    public function &_getNodeRefs(): array
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
            $keyword = NodeConverter::convert($keyword, Token::class, $this->_phpVersion);
            $keyword->_attachTo($this);
        }
        if ($this->keyword !== null)
        {
            $this->keyword->detach();
        }
        $this->keyword = $keyword;
    }

    public function getBlock(): Nodes\Block
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
     * @param Nodes\Block|Node|string|null $block
     */
    public function setBlock($block): void
    {
        if ($block !== null)
        {
            /** @var Nodes\Block $block */
            $block = NodeConverter::convert($block, Nodes\Block::class, $this->_phpVersion);
            $block->_attachTo($this);
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
        $catch = NodeConverter::convert($catch, Nodes\Catch_::class);
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
            $finally = NodeConverter::convert($finally, Nodes\Finally_::class, $this->_phpVersion);
            $finally->_attachTo($this);
        }
        if ($this->finally !== null)
        {
            $this->finally->detach();
        }
        $this->finally = $finally;
    }
}
