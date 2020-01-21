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

abstract class GeneratedIfStatement extends CompoundNode implements Nodes\Statement
{
    /** @var Specification[] */
    private static $specifications;
    protected static function getSpecifications(): array
    {
        return self::$specifications ?? self::$specifications = [
            new ValidCompoundNode([
                'keyword' => new IsToken(\T_IF),
                'leftParenthesis' => new IsToken('('),
                'test' => new Specs\IsReadExpression,
                'rightParenthesis' => new IsToken(')'),
                'block' => new Any,
                'elseifs' => new EachItem(new IsInstanceOf(Nodes\Elseif_::class)),
                'else' => new Optional(new Any),
            ]),
        ];
    }

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
     * @var Nodes\Statement|null
     */
    private $block;
    /**
     * @var NodesList|Nodes\Elseif_[]
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
        parent::__construct();
        $this->elseifs = new NodesList();
    }

    /**
     * @param Token|null $keyword
     * @param Token|null $leftParenthesis
     * @param Nodes\Expression|null $test
     * @param Token|null $rightParenthesis
     * @param Nodes\Statement|null $block
     * @param mixed[] $elseifs
     * @param Nodes\Else_|null $else
     * @return static
     */
    public static function __instantiateUnchecked($keyword, $leftParenthesis, $test, $rightParenthesis, $block, $elseifs, $else)
    {
        $instance = new static();
        $instance->keyword = $keyword;
        $instance->leftParenthesis = $leftParenthesis;
        $instance->test = $test;
        $instance->rightParenthesis = $rightParenthesis;
        $instance->block = $block;
        $instance->elseifs->__initUnchecked($elseifs);
        $instance->else = $else;
        return $instance;
    }

    public function &_getNodeRefs(): array
    {
        $refs = [
            'keyword' => &$this->keyword,
            'leftParenthesis' => &$this->leftParenthesis,
            'test' => &$this->test,
            'rightParenthesis' => &$this->rightParenthesis,
            'block' => &$this->block,
            'elseifs' => &$this->elseifs,
            'else' => &$this->else,
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
            $leftParenthesis = NodeConverter::convert($leftParenthesis, Token::class, $this->_phpVersion);
            $leftParenthesis->_attachTo($this);
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
            $test = NodeConverter::convert($test, Nodes\Expression::class, $this->_phpVersion);
            $test->_attachTo($this);
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
            $rightParenthesis = NodeConverter::convert($rightParenthesis, Token::class, $this->_phpVersion);
            $rightParenthesis->_attachTo($this);
        }
        if ($this->rightParenthesis !== null)
        {
            $this->rightParenthesis->detach();
        }
        $this->rightParenthesis = $rightParenthesis;
    }

    public function getBlock(): Nodes\Statement
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
     * @param Nodes\Statement|Node|string|null $block
     */
    public function setBlock($block): void
    {
        if ($block !== null)
        {
            /** @var Nodes\Statement $block */
            $block = NodeConverter::convert($block, Nodes\Statement::class, $this->_phpVersion);
            $block->_attachTo($this);
        }
        if ($this->block !== null)
        {
            $this->block->detach();
        }
        $this->block = $block;
    }

    /**
     * @return NodesList|Nodes\Elseif_[]
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
        $elseif = NodeConverter::convert($elseif, Nodes\Elseif_::class);
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
            $else = NodeConverter::convert($else, Nodes\Else_::class, $this->_phpVersion);
            $else->_attachTo($this);
        }
        if ($this->else !== null)
        {
            $this->else->detach();
        }
        $this->else = $else;
    }
}
