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

abstract class GeneratedDivideExpression extends Nodes\Expression
{
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
     * @param int $phpVersion
     * @param Nodes\Expression $left
     * @param Token $operator
     * @param Nodes\Expression $right
     * @return static
     */
    public static function __instantiateUnchecked($phpVersion, $left, $operator, $right)
    {
        $instance = new static;
        $instance->phpVersion = $phpVersion;
        $instance->left = $left;
        $left->parent = $instance;
        $instance->operator = $operator;
        $operator->parent = $instance;
        $instance->right = $right;
        $right->parent = $instance;
        return $instance;
    }

    protected function &_getNodeRefs(): array
    {
        $refs = [
            "left" => &$this->left,
            "operator" => &$this->operator,
            "right" => &$this->right,
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
            $left = NodeConverter::convert($left, Nodes\Expression::class, $this->phpVersion);
            $left->detach();
            $left->parent = $this;
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
            $operator = NodeConverter::convert($operator, Token::class, $this->phpVersion);
            $operator->detach();
            $operator->parent = $this;
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
            $right = NodeConverter::convert($right, Nodes\Expression::class, $this->phpVersion);
            $right->detach();
            $right->parent = $this;
        }
        if ($this->right !== null)
        {
            $this->right->detach();
        }
        $this->right = $right;
    }

    protected function _validate(int $flags): void
    {
        if ($this->left === null) throw ValidationException::childRequired($this, "left");
        if ($this->operator === null) throw ValidationException::childRequired($this, "operator");
        if ($this->right === null) throw ValidationException::childRequired($this, "right");
        if ($flags & self::VALIDATE_TYPES)
        {
        }
        if ($flags & self::VALIDATE_EXPRESSION_CONTEXT)
        {
        }
        if ($flags & self::VALIDATE_TOKENS)
        {
        }
        $this->left->_validate($flags);
        $this->right->_validate($flags);
    }
}
