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

trait GeneratedImplicitBlock
{
    /**
     * @var \Phi\Nodes\Statement|null
     */
    private $statement;

    /**
     * @param \Phi\Nodes\Statement|\Phi\Node|string|null $statement
     */
    public function __construct($statement = null)
    {
        if ($statement !== null)
        {
            $this->setStatement($statement);
        }
    }

    /**
     * @param \Phi\Nodes\Statement $statement
     * @return self
     */
    public static function __instantiateUnchecked($statement)
    {
        $instance = new self;
        $instance->statement = $statement;
        $statement->parent = $instance;
        return $instance;
    }

    public function getChildNodes(): array
    {
        return \array_values(\array_filter([
            $this->statement,
        ]));
    }

    protected function &getChildRef(Node $childToDetach): Node
    {
        if ($this->statement === $childToDetach)
        {
            return $this->statement;
        }
        throw new \LogicException();
    }

    public function getStatement(): \Phi\Nodes\Statement
    {
        if ($this->statement === null)
        {
            throw TreeException::missingNode($this, "statement");
        }
        return $this->statement;
    }

    public function hasStatement(): bool
    {
        return $this->statement !== null;
    }

    /**
     * @param \Phi\Nodes\Statement|\Phi\Node|string|null $statement
     */
    public function setStatement($statement): void
    {
        if ($statement !== null)
        {
            /** @var \Phi\Nodes\Statement $statement */
            $statement = NodeCoercer::coerce($statement, \Phi\Nodes\Statement::class, $this->getPhpVersion());
            $statement->detach();
            $statement->parent = $this;
        }
        if ($this->statement !== null)
        {
            $this->statement->detach();
        }
        $this->statement = $statement;
    }

    public function _validate(int $flags): void
    {
        if ($this->statement === null)
            throw ValidationException::missingChild($this, "statement");


        $this->extraValidation($flags);

        $this->statement->_validate(0);
    }

    public function _autocorrect(): void
    {
        if ($this->statement)
            $this->statement->_autocorrect();

        $this->extraAutocorrect();
    }
}
