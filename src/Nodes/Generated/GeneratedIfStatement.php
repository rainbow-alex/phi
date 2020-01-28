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

abstract class GeneratedIfStatement extends Nodes\Statement
{
    /**
     * @var Token|null
     */
    private $keyword;

    /**
     * @var Token|null
     */
    private $leftParenthesis;

    /**
     * @var Nodes\Expression|null
     */
    private $test;

    /**
     * @var Token|null
     */
    private $rightParenthesis;

    /**
     * @var Nodes\Block|null
     */
    private $block;

    /**
     * @var NodesList|Nodes\Elseif_[]
     * @phpstan-var NodesList<\Phi\Nodes\Elseif_>
     */
    private $elseifs;

    /**
     * @var Nodes\Else_|null
     */
    private $else;


    /**
     */
    public function __construct()
    {
        $this->elseifs = new NodesList();
    }

    /**
     * @param int $phpVersion
     * @param Token $keyword
     * @param Token $leftParenthesis
     * @param Nodes\Expression $test
     * @param Token $rightParenthesis
     * @param Nodes\Block $block
     * @param mixed[] $elseifs
     * @param Nodes\Else_|null $else
     * @return static
     */
    public static function __instantiateUnchecked($phpVersion, $keyword, $leftParenthesis, $test, $rightParenthesis, $block, $elseifs, $else)
    {
        $instance = new static;
        $instance->phpVersion = $phpVersion;
        $instance->keyword = $keyword;
        $keyword->parent = $instance;
        $instance->leftParenthesis = $leftParenthesis;
        $leftParenthesis->parent = $instance;
        $instance->test = $test;
        $test->parent = $instance;
        $instance->rightParenthesis = $rightParenthesis;
        $rightParenthesis->parent = $instance;
        $instance->block = $block;
        $block->parent = $instance;
        $instance->elseifs->__initUnchecked($elseifs);
        $instance->elseifs->parent = $instance;
        $instance->else = $else;
        if ($else) $else->parent = $instance;
        return $instance;
    }

    protected function &_getNodeRefs(): array
    {
        $refs = [
            "keyword" => &$this->keyword,
            "leftParenthesis" => &$this->leftParenthesis,
            "test" => &$this->test,
            "rightParenthesis" => &$this->rightParenthesis,
            "block" => &$this->block,
            "elseifs" => &$this->elseifs,
            "else" => &$this->else,
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

    public function getLeftParenthesis(): Token
    {
        if ($this->leftParenthesis === null)
        {
            throw new MissingNodeException($this, __FUNCTION__);
        }
        return $this->leftParenthesis;
    }

    public function hasLeftParenthesis(): bool
    {
        return $this->leftParenthesis !== null;
    }

    /**
     * @param Token|Node|string|null $leftParenthesis
     */
    public function setLeftParenthesis($leftParenthesis): void
    {
        if ($leftParenthesis !== null)
        {
            /** @var Token $leftParenthesis */
            $leftParenthesis = NodeConverter::convert($leftParenthesis, Token::class, $this->phpVersion);
            $leftParenthesis->detach();
            $leftParenthesis->parent = $this;
        }
        if ($this->leftParenthesis !== null)
        {
            $this->leftParenthesis->detach();
        }
        $this->leftParenthesis = $leftParenthesis;
    }

    public function getTest(): Nodes\Expression
    {
        if ($this->test === null)
        {
            throw new MissingNodeException($this, __FUNCTION__);
        }
        return $this->test;
    }

    public function hasTest(): bool
    {
        return $this->test !== null;
    }

    /**
     * @param Nodes\Expression|Node|string|null $test
     */
    public function setTest($test): void
    {
        if ($test !== null)
        {
            /** @var Nodes\Expression $test */
            $test = NodeConverter::convert($test, Nodes\Expression::class, $this->phpVersion);
            $test->detach();
            $test->parent = $this;
        }
        if ($this->test !== null)
        {
            $this->test->detach();
        }
        $this->test = $test;
    }

    public function getRightParenthesis(): Token
    {
        if ($this->rightParenthesis === null)
        {
            throw new MissingNodeException($this, __FUNCTION__);
        }
        return $this->rightParenthesis;
    }

    public function hasRightParenthesis(): bool
    {
        return $this->rightParenthesis !== null;
    }

    /**
     * @param Token|Node|string|null $rightParenthesis
     */
    public function setRightParenthesis($rightParenthesis): void
    {
        if ($rightParenthesis !== null)
        {
            /** @var Token $rightParenthesis */
            $rightParenthesis = NodeConverter::convert($rightParenthesis, Token::class, $this->phpVersion);
            $rightParenthesis->detach();
            $rightParenthesis->parent = $this;
        }
        if ($this->rightParenthesis !== null)
        {
            $this->rightParenthesis->detach();
        }
        $this->rightParenthesis = $rightParenthesis;
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
            $block = NodeConverter::convert($block, Nodes\Block::class, $this->phpVersion);
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
     * @return NodesList|Nodes\Elseif_[]
     * @phpstan-return NodesList<\Phi\Nodes\Elseif_>
     */
    public function getElseifs(): NodesList
    {
        return $this->elseifs;
    }

    /**
     * @param Nodes\Elseif_ $elseif
     */
    public function addElseif($elseif): void
    {
        /** @var Nodes\Elseif_ $elseif */
        $elseif = NodeConverter::convert($elseif, Nodes\Elseif_::class, $this->phpVersion);
        $this->elseifs->add($elseif);
    }

    public function getElse(): ?Nodes\Else_
    {
        return $this->else;
    }

    public function hasElse(): bool
    {
        return $this->else !== null;
    }

    /**
     * @param Nodes\Else_|Node|string|null $else
     */
    public function setElse($else): void
    {
        if ($else !== null)
        {
            /** @var Nodes\Else_ $else */
            $else = NodeConverter::convert($else, Nodes\Else_::class, $this->phpVersion);
            $else->detach();
            $else->parent = $this;
        }
        if ($this->else !== null)
        {
            $this->else->detach();
        }
        $this->else = $else;
    }

    protected function _validate(int $flags): void
    {
        if ($this->keyword === null) throw ValidationException::childRequired($this, "keyword");
        if ($this->leftParenthesis === null) throw ValidationException::childRequired($this, "leftParenthesis");
        if ($this->test === null) throw ValidationException::childRequired($this, "test");
        if ($this->rightParenthesis === null) throw ValidationException::childRequired($this, "rightParenthesis");
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
        $this->test->_validate($flags);
        $this->block->_validate($flags);
        $this->elseifs->_validate($flags);
        if ($this->else)
        {
            $this->else->_validate($flags);
        }
    }
}
