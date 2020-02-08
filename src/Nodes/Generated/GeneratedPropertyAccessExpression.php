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

trait GeneratedPropertyAccessExpression
{
    /**
     * @var \Phi\Nodes\Expression|null
     */
    private $object;

    /**
     * @var \Phi\Token|null
     */
    private $operator;

    /**
     * @var \Phi\Nodes\Helpers\MemberName|null
     */
    private $name;

    /**
     * @param \Phi\Nodes\Expression|\Phi\Node|string|null $object
     * @param \Phi\Nodes\Helpers\MemberName|\Phi\Node|string|null $name
     */
    public function __construct($object = null, $name = null)
    {
        if ($object !== null)
        {
            $this->setObject($object);
        }
        if ($name !== null)
        {
            $this->setName($name);
        }
    }

    /**
     * @param \Phi\Nodes\Expression $object
     * @param \Phi\Token $operator
     * @param \Phi\Nodes\Helpers\MemberName $name
     * @return self
     */
    public static function __instantiateUnchecked($object, $operator, $name)
    {
        $instance = new self;
        $instance->object = $object;
        $object->parent = $instance;
        $instance->operator = $operator;
        $operator->parent = $instance;
        $instance->name = $name;
        $name->parent = $instance;
        return $instance;
    }

    public function getChildNodes(): array
    {
        return \array_values(\array_filter([
            $this->object,
            $this->operator,
            $this->name,
        ]));
    }

    protected function &getChildRef(Node $childToDetach): Node
    {
        if ($this->object === $childToDetach)
        {
            return $this->object;
        }
        if ($this->operator === $childToDetach)
        {
            return $this->operator;
        }
        if ($this->name === $childToDetach)
        {
            return $this->name;
        }
        throw new \LogicException();
    }

    public function getObject(): \Phi\Nodes\Expression
    {
        if ($this->object === null)
        {
            throw TreeException::missingNode($this, "object");
        }
        return $this->object;
    }

    public function hasObject(): bool
    {
        return $this->object !== null;
    }

    /**
     * @param \Phi\Nodes\Expression|\Phi\Node|string|null $object
     */
    public function setObject($object): void
    {
        if ($object !== null)
        {
            /** @var \Phi\Nodes\Expression $object */
            $object = NodeCoercer::coerce($object, \Phi\Nodes\Expression::class, $this->getPhpVersion());
            $object->detach();
            $object->parent = $this;
        }
        if ($this->object !== null)
        {
            $this->object->detach();
        }
        $this->object = $object;
    }

    public function getOperator(): \Phi\Token
    {
        if ($this->operator === null)
        {
            throw TreeException::missingNode($this, "operator");
        }
        return $this->operator;
    }

    public function hasOperator(): bool
    {
        return $this->operator !== null;
    }

    /**
     * @param \Phi\Token|\Phi\Node|string|null $operator
     */
    public function setOperator($operator): void
    {
        if ($operator !== null)
        {
            /** @var \Phi\Token $operator */
            $operator = NodeCoercer::coerce($operator, \Phi\Token::class, $this->getPhpVersion());
            $operator->detach();
            $operator->parent = $this;
        }
        if ($this->operator !== null)
        {
            $this->operator->detach();
        }
        $this->operator = $operator;
    }

    public function getName(): \Phi\Nodes\Helpers\MemberName
    {
        if ($this->name === null)
        {
            throw TreeException::missingNode($this, "name");
        }
        return $this->name;
    }

    public function hasName(): bool
    {
        return $this->name !== null;
    }

    /**
     * @param \Phi\Nodes\Helpers\MemberName|\Phi\Node|string|null $name
     */
    public function setName($name): void
    {
        if ($name !== null)
        {
            /** @var \Phi\Nodes\Helpers\MemberName $name */
            $name = NodeCoercer::coerce($name, \Phi\Nodes\Helpers\MemberName::class, $this->getPhpVersion());
            $name->detach();
            $name->parent = $this;
        }
        if ($this->name !== null)
        {
            $this->name->detach();
        }
        $this->name = $name;
    }

    public function _validate(int $flags): void
    {
        if ($this->object === null)
            throw ValidationException::missingChild($this, "object");
        if ($this->operator === null)
            throw ValidationException::missingChild($this, "operator");
        if ($this->name === null)
            throw ValidationException::missingChild($this, "name");
        if ($this->operator->getType() !== 222)
            throw ValidationException::invalidSyntax($this->operator, [222]);


        $this->extraValidation($flags);

        $this->object->_validate(1);
        $this->name->_validate(0);
    }

    public function _autocorrect(): void
    {
        if ($this->object)
            $this->object->_autocorrect();
        if ($this->name)
            $this->name->_autocorrect();

        $this->extraAutocorrect();
    }
}
