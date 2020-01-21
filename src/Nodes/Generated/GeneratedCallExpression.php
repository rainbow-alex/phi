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

abstract class GeneratedCallExpression extends CompoundNode implements Nodes\Expression
{
    /** @var Specification[] */
    private static $specifications;
    protected static function getSpecifications(): array
    {
        return self::$specifications ?? self::$specifications = [
            new ValidCompoundNode([
                'callee' => new Specs\IsReadExpression,
                'leftParenthesis' => new IsToken('('),
                'arguments' => new And_(new EachItem(new IsInstanceOf(Nodes\Argument::class)), new EachSeparator(new IsToken(','))),
                'rightParenthesis' => new IsToken(')'),
            ]),
        ];
    }

    /**
     * @var Nodes\Expression|null
     */
    private $callee;
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
     * @param Nodes\Expression|Node|string|null $callee
     */
    public function __construct($callee = null)
    {
        parent::__construct();
        if ($callee !== null)
        {
            $this->setCallee($callee);
        }
        $this->arguments = new SeparatedNodesList();
    }

    /**
     * @param Nodes\Expression|null $callee
     * @param Token|null $leftParenthesis
     * @param mixed[] $arguments
     * @param Token|null $rightParenthesis
     * @return static
     */
    public static function __instantiateUnchecked($callee, $leftParenthesis, $arguments, $rightParenthesis)
    {
        $instance = new static();
        $instance->callee = $callee;
        $instance->leftParenthesis = $leftParenthesis;
        $instance->arguments->__initUnchecked($arguments);
        $instance->rightParenthesis = $rightParenthesis;
        return $instance;
    }

    public function &_getNodeRefs(): array
    {
        $refs = [
            'callee' => &$this->callee,
            'leftParenthesis' => &$this->leftParenthesis,
            'arguments' => &$this->arguments,
            'rightParenthesis' => &$this->rightParenthesis,
        ];
        return $refs;
    }

    public function getCallee(): Nodes\Expression
    {
        if ($this->callee === null)
        {
            throw new MissingNodeException($this, __FUNCTION__);
        }
        return $this->callee;
    }

    public function hasCallee(): bool
    {
        return $this->callee !== null;
    }

    /**
     * @param Nodes\Expression|Node|string|null $callee
     */
    public function setCallee($callee): void
    {
        if ($callee !== null)
        {
            /** @var Nodes\Expression $callee */
            $callee = NodeConverter::convert($callee, Nodes\Expression::class, $this->_phpVersion);
            $callee->_attachTo($this);
        }
        if ($this->callee !== null)
        {
            $this->callee->detach();
        }
        $this->callee = $callee;
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
