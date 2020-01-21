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

abstract class GeneratedUnsetExpression extends CompoundNode implements Nodes\Expression
{
    /** @var Specification[] */
    private static $specifications;
    protected static function getSpecifications(): array
    {
        return self::$specifications ?? self::$specifications = [
            new ValidCompoundNode([
                'keyword' => new IsToken(\T_UNSET),
                'leftParenthesis' => new IsToken('('),
                'expressions' => new And_(new EachItem(new IsInstanceOf(Nodes\Expression::class)), new EachSeparator(new IsToken(','))),
                'rightParenthesis' => new IsToken(')'),
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
     * @var SeparatedNodesList|Nodes\Expression[]
     */
    private $expressions;
    /**
     * @var Token|null
     */
    private $rightParenthesis;

    /**
     */
    public function __construct()
    {
        parent::__construct();
        $this->expressions = new SeparatedNodesList();
    }

    /**
     * @param Token|null $keyword
     * @param Token|null $leftParenthesis
     * @param mixed[] $expressions
     * @param Token|null $rightParenthesis
     * @return static
     */
    public static function __instantiateUnchecked($keyword, $leftParenthesis, $expressions, $rightParenthesis)
    {
        $instance = new static();
        $instance->keyword = $keyword;
        $instance->leftParenthesis = $leftParenthesis;
        $instance->expressions->__initUnchecked($expressions);
        $instance->rightParenthesis = $rightParenthesis;
        return $instance;
    }

    public function &_getNodeRefs(): array
    {
        $refs = [
            'keyword' => &$this->keyword,
            'leftParenthesis' => &$this->leftParenthesis,
            'expressions' => &$this->expressions,
            'rightParenthesis' => &$this->rightParenthesis,
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

    /**
     * @return SeparatedNodesList|Nodes\Expression[]
     */
    public function getExpressions(): SeparatedNodesList
    {
        return $this->expressions;
    }

    /**
     * @param Nodes\Expression $expression
     */
    public function addExpression($expression): void
    {
        /** @var Nodes\Expression $expression */
        $expression = NodeConverter::convert($expression, Nodes\Expression::class);
        $this->expressions->add($expression);
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
}
