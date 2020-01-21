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

abstract class GeneratedClassNameResolutionExpression extends CompoundNode implements Nodes\Expression
{
    /** @var Specification[] */
    private static $specifications;
    protected static function getSpecifications(): array
    {
        return self::$specifications ?? self::$specifications = [
            new ValidCompoundNode([
                'class' => new Specs\IsReadExpression,
                'operator' => new IsToken(\T_DOUBLE_COLON),
                'keyword' => new IsToken(\T_CLASS),
            ]),
        ];
    }

    /**
     * @var Nodes\Expression|null
     */
    private $class;
    /**
     * @var Token|null
     */
    private $operator;
    /**
     * @var Token|null
     */
    private $keyword;

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
    }

    /**
     * @param Nodes\Expression|null $class
     * @param Token|null $operator
     * @param Token|null $keyword
     * @return static
     */
    public static function __instantiateUnchecked($class, $operator, $keyword)
    {
        $instance = new static();
        $instance->class = $class;
        $instance->operator = $operator;
        $instance->keyword = $keyword;
        return $instance;
    }

    public function &_getNodeRefs(): array
    {
        $refs = [
            'class' => &$this->class,
            'operator' => &$this->operator,
            'keyword' => &$this->keyword,
        ];
        return $refs;
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

    public function getOperator(): Token
    {
        if ($this->operator === null)
        {
            throw new MissingNodeException($this, __FUNCTION__);
        }
        return $this->operator;
    }

    public function hasOperator(): bool
    {
        return $this->operator !== null;
    }

    /**
     * @param Token|Node|string|null $operator
     */
    public function setOperator($operator): void
    {
        if ($operator !== null)
        {
            /** @var Token $operator */
            $operator = NodeConverter::convert($operator, Token::class, $this->_phpVersion);
            $operator->_attachTo($this);
        }
        if ($this->operator !== null)
        {
            $this->operator->detach();
        }
        $this->operator = $operator;
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
}
