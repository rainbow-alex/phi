<?php

declare(strict_types=1);

/**
 * This code is generated.
 * @see meta/
 */

namespace Phi\Nodes\Generated;

use Phi\Node;
use Phi\Token;
use Phi\Exception\TreeException;
use Phi\NodeCoercer;
use Phi\Exception\ValidationException;

trait GeneratedAliasingExpression
{
    /**
     * @var \Phi\Nodes\Expression|null
     */
    private $left;

    /**
     * @var \Phi\Token|null
     */
    private $operator1;

    /**
     * @var \Phi\Token|null
     */
    private $operator2;

    /**
     * @var \Phi\Nodes\Expression|null
     */
    private $right;

    /**
     * @param \Phi\Nodes\Expression|\Phi\Node|string|null $left
     * @param \Phi\Nodes\Expression|\Phi\Node|string|null $right
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
     * @param \Phi\Nodes\Expression $left
     * @param \Phi\Token $operator1
     * @param \Phi\Token $operator2
     * @param \Phi\Nodes\Expression $right
     * @return self
     */
    public static function __instantiateUnchecked($left, $operator1, $operator2, $right)
    {
        $instance = new self;
        $instance->left = $left;
        $left->parent = $instance;
        $instance->operator1 = $operator1;
        $operator1->parent = $instance;
        $instance->operator2 = $operator2;
        $operator2->parent = $instance;
        $instance->right = $right;
        $right->parent = $instance;
        return $instance;
    }

    public function getChildNodes(): array
    {
        return \array_values(\array_filter([
            $this->left,
            $this->operator1,
            $this->operator2,
            $this->right,
        ]));
    }

    protected function &getChildRef(Node $childToDetach): Node
    {
        if ($this->left === $childToDetach)
        {
            return $this->left;
        }
        if ($this->operator1 === $childToDetach)
        {
            return $this->operator1;
        }
        if ($this->operator2 === $childToDetach)
        {
            return $this->operator2;
        }
        if ($this->right === $childToDetach)
        {
            return $this->right;
        }
        throw new \LogicException();
    }

    public function getLeft(): \Phi\Nodes\Expression
    {
        if ($this->left === null)
        {
            throw TreeException::missingNode($this, "left");
        }
        return $this->left;
    }

    public function hasLeft(): bool
    {
        return $this->left !== null;
    }

    /**
     * @param \Phi\Nodes\Expression|\Phi\Node|string|null $left
     */
    public function setLeft($left): void
    {
        if ($left !== null)
        {
            /** @var \Phi\Nodes\Expression $left */
            $left = NodeCoercer::coerce($left, \Phi\Nodes\Expression::class, $this->getPhpVersion());
            $left->detach();
            $left->parent = $this;
        }
        if ($this->left !== null)
        {
            $this->left->detach();
        }
        $this->left = $left;
    }

    public function getOperator1(): \Phi\Token
    {
        if ($this->operator1 === null)
        {
            throw TreeException::missingNode($this, "operator1");
        }
        return $this->operator1;
    }

    public function hasOperator1(): bool
    {
        return $this->operator1 !== null;
    }

    /**
     * @param \Phi\Token|\Phi\Node|string|null $operator1
     */
    public function setOperator1($operator1): void
    {
        if ($operator1 !== null)
        {
            /** @var \Phi\Token $operator1 */
            $operator1 = NodeCoercer::coerce($operator1, \Phi\Token::class, $this->getPhpVersion());
            $operator1->detach();
            $operator1->parent = $this;
        }
        if ($this->operator1 !== null)
        {
            $this->operator1->detach();
        }
        $this->operator1 = $operator1;
    }

    public function getOperator2(): \Phi\Token
    {
        if ($this->operator2 === null)
        {
            throw TreeException::missingNode($this, "operator2");
        }
        return $this->operator2;
    }

    public function hasOperator2(): bool
    {
        return $this->operator2 !== null;
    }

    /**
     * @param \Phi\Token|\Phi\Node|string|null $operator2
     */
    public function setOperator2($operator2): void
    {
        if ($operator2 !== null)
        {
            /** @var \Phi\Token $operator2 */
            $operator2 = NodeCoercer::coerce($operator2, \Phi\Token::class, $this->getPhpVersion());
            $operator2->detach();
            $operator2->parent = $this;
        }
        if ($this->operator2 !== null)
        {
            $this->operator2->detach();
        }
        $this->operator2 = $operator2;
    }

    public function getRight(): \Phi\Nodes\Expression
    {
        if ($this->right === null)
        {
            throw TreeException::missingNode($this, "right");
        }
        return $this->right;
    }

    public function hasRight(): bool
    {
        return $this->right !== null;
    }

    /**
     * @param \Phi\Nodes\Expression|\Phi\Node|string|null $right
     */
    public function setRight($right): void
    {
        if ($right !== null)
        {
            /** @var \Phi\Nodes\Expression $right */
            $right = NodeCoercer::coerce($right, \Phi\Nodes\Expression::class, $this->getPhpVersion());
            $right->detach();
            $right->parent = $this;
        }
        if ($this->right !== null)
        {
            $this->right->detach();
        }
        $this->right = $right;
    }

    public function _validate(int $flags): void
    {
        if ($this->left === null)
            throw ValidationException::missingChild($this, "left");
        if ($this->operator1 === null)
            throw ValidationException::missingChild($this, "operator1");
        if ($this->operator2 === null)
            throw ValidationException::missingChild($this, "operator2");
        if ($this->right === null)
            throw ValidationException::missingChild($this, "right");
        if ($this->operator1->getType() !== 116)
            throw ValidationException::invalidSyntax($this->operator1, [116]);
        if ($this->operator2->getType() !== 104)
            throw ValidationException::invalidSyntax($this->operator2, [104]);

        if ($flags & 14)
            throw ValidationException::invalidExpressionInContext($this);

        $this->extraValidation($flags);

        $this->left->_validate(4);
        $this->right->_validate(8);
    }

    public function _autocorrect(): void
    {
        if ($this->left)
            $this->left->_autocorrect();
        if ($this->right)
            $this->right->_autocorrect();

        $this->extraAutocorrect();
    }
}
