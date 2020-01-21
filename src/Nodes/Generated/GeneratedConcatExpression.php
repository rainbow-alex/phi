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

abstract class GeneratedConcatExpression extends CompoundNode implements Nodes\Expression
{
    /** @var Specification[] */
    private static $specifications;
    protected static function getSpecifications(): array
    {
        return self::$specifications ?? self::$specifications = [
            new ValidCompoundNode([
                'left' => new Specs\IsReadExpression,
                'operator' => new IsToken('.'),
                'right' => new Specs\IsReadExpression,
            ]),
        ];
    }

    /**
     * @var Nodes\Expression|null
     */
    private $left;
    /**
     * @var Token|null
     */
    private $operator;
    /**
     * @var Nodes\Expression|null
     */
    private $right;

    /**
     * @param Nodes\Expression|Node|string|null $left
     * @param Nodes\Expression|Node|string|null $right
     */
    public function __construct($left = null, $right = null)
    {
        parent::__construct();
        if ($left !== null)
        {
            $this->setLeft($left);
        }
        if ($right !== null)
        {
            $this->setRight($right);
        }
    }

    /**
     * @param Nodes\Expression|null $left
     * @param Token|null $operator
     * @param Nodes\Expression|null $right
     * @return static
     */
    public static function __instantiateUnchecked($left, $operator, $right)
    {
        $instance = new static();
        $instance->left = $left;
        $instance->operator = $operator;
        $instance->right = $right;
        return $instance;
    }

    public function &_getNodeRefs(): array
    {
        $refs = [
            'left' => &$this->left,
            'operator' => &$this->operator,
            'right' => &$this->right,
        ];
        return $refs;
    }

    public function getLeft(): Nodes\Expression
    {
        if ($this->left === null)
        {
            throw new MissingNodeException($this, __FUNCTION__);
        }
        return $this->left;
    }

    public function hasLeft(): bool
    {
        return $this->left !== null;
    }

    /**
     * @param Nodes\Expression|Node|string|null $left
     */
    public function setLeft($left): void
    {
        if ($left !== null)
        {
            /** @var Nodes\Expression $left */
            $left = NodeConverter::convert($left, Nodes\Expression::class, $this->_phpVersion);
            $left->_attachTo($this);
        }
        if ($this->left !== null)
        {
            $this->left->detach();
        }
        $this->left = $left;
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

    public function getRight(): Nodes\Expression
    {
        if ($this->right === null)
        {
            throw new MissingNodeException($this, __FUNCTION__);
        }
        return $this->right;
    }

    public function hasRight(): bool
    {
        return $this->right !== null;
    }

    /**
     * @param Nodes\Expression|Node|string|null $right
     */
    public function setRight($right): void
    {
        if ($right !== null)
        {
            /** @var Nodes\Expression $right */
            $right = NodeConverter::convert($right, Nodes\Expression::class, $this->_phpVersion);
            $right->_attachTo($this);
        }
        if ($this->right !== null)
        {
            $this->right->detach();
        }
        $this->right = $right;
    }
}
