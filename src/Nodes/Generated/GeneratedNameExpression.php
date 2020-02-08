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

trait GeneratedNameExpression
{
    /**
     * @var \Phi\Nodes\Helpers\Name|null
     */
    private $name;

    /**
     * @param \Phi\Nodes\Helpers\Name|\Phi\Node|string|null $name
     */
    public function __construct($name = null)
    {
        if ($name !== null)
        {
            $this->setName($name);
        }
    }

    /**
     * @param \Phi\Nodes\Helpers\Name $name
     * @return self
     */
    public static function __instantiateUnchecked($name)
    {
        $instance = new self;
        $instance->name = $name;
        $name->parent = $instance;
        return $instance;
    }

    public function getChildNodes(): array
    {
        return \array_values(\array_filter([
            $this->name,
        ]));
    }

    protected function &getChildRef(Node $childToDetach): Node
    {
        if ($this->name === $childToDetach)
        {
            return $this->name;
        }
        throw new \LogicException();
    }

    public function getName(): \Phi\Nodes\Helpers\Name
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
     * @param \Phi\Nodes\Helpers\Name|\Phi\Node|string|null $name
     */
    public function setName($name): void
    {
        if ($name !== null)
        {
            /** @var \Phi\Nodes\Helpers\Name $name */
            $name = NodeCoercer::coerce($name, \Phi\Nodes\Helpers\Name::class, $this->getPhpVersion());
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
        if ($this->name === null)
            throw ValidationException::missingChild($this, "name");

        if ($flags & 14)
            throw ValidationException::invalidExpressionInContext($this);

        $this->extraValidation($flags);

        $this->name->_validate(0);
    }

    public function _autocorrect(): void
    {
        if ($this->name)
            $this->name->_autocorrect();

        $this->extraAutocorrect();
    }
}
