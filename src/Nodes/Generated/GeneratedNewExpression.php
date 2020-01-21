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

abstract class GeneratedNewExpression extends CompoundNode implements Nodes\Expression
{
    /** @var Specification[] */
    private static $specifications;
    protected static function getSpecifications(): array
    {
        return self::$specifications ?? self::$specifications = [
            new ValidCompoundNode([
                'keyword' => new IsToken(\T_NEW),
                'class' => new Specs\IsReadExpression,
                'leftParenthesis' => new Optional(new IsToken('(')),
                'arguments' => new And_(new EachItem(new IsInstanceOf(Nodes\Argument::class)), new EachSeparator(new IsToken(','))),
                'rightParenthesis' => new Optional(new IsToken(')')),
            ]),
        ];
    }

    /**
     * @var Token|null
     */
    private $keyword;
    /**
     * @var Nodes\Expression|null
     */
    private $class;
    /**
     * @var Token|null
     */
    private $leftParenthesis;
    /**
     * @var SeparatedNodesList|Nodes\Argument[]
     */
    private $arguments;
    /**
     * @var Token|null
     */
    private $rightParenthesis;

    /**
     * @param Nodes\Expression|Node|string|null $class
     */
    public function __construct($class = null)
    {
        parent::__construct();
        if ($class !== null)
        {
            $this->setClass($class);
        }
        $this->arguments = new SeparatedNodesList();
    }

    /**
     * @param Token|null $keyword
     * @param Nodes\Expression|null $class
     * @param Token|null $leftParenthesis
     * @param mixed[] $arguments
     * @param Token|null $rightParenthesis
     * @return static
     */
    public static function __instantiateUnchecked($keyword, $class, $leftParenthesis, $arguments, $rightParenthesis)
    {
        $instance = new static();
        $instance->keyword = $keyword;
        $instance->class = $class;
        $instance->leftParenthesis = $leftParenthesis;
        $instance->arguments->__initUnchecked($arguments);
        $instance->rightParenthesis = $rightParenthesis;
        return $instance;
    }

    public function &_getNodeRefs(): array
    {
        $refs = [
            'keyword' => &$this->keyword,
            'class' => &$this->class,
            'leftParenthesis' => &$this->leftParenthesis,
            'arguments' => &$this->arguments,
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

    public function getClass(): Nodes\Expression
    {
        if ($this->class === null)
        {
            throw new MissingNodeException($this, __FUNCTION__);
        }
        return $this->class;
    }

    public function hasClass(): bool
    {
        return $this->class !== null;
    }

    /**
     * @param Nodes\Expression|Node|string|null $class
     */
    public function setClass($class): void
    {
        if ($class !== null)
        {
            /** @var Nodes\Expression $class */
            $class = NodeConverter::convert($class, Nodes\Expression::class, $this->_phpVersion);
            $class->_attachTo($this);
        }
        if ($this->class !== null)
        {
            $this->class->detach();
        }
        $this->class = $class;
    }

    public function getLeftParenthesis(): ?Token
    {
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
     * @return SeparatedNodesList|Nodes\Argument[]
     */
    public function getArguments(): SeparatedNodesList
    {
        return $this->arguments;
    }

    /**
     * @param Nodes\Argument $argument
     */
    public function addArgument($argument): void
    {
        /** @var Nodes\Argument $argument */
        $argument = NodeConverter::convert($argument, Nodes\Argument::class);
        $this->arguments->add($argument);
    }

    public function getRightParenthesis(): ?Token
    {
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
