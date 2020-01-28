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

abstract class GeneratedImplicitBlock extends Nodes\Block
{
    /**
     * @var Nodes\Statement|null
     */
    private $statement;


    /**
     * @param Nodes\Statement|Node|string|null $statement
     */
    public function __construct($statement = null)
    {
        if ($statement !== null)
        {
            $this->setStatement($statement);
        }
    }

    /**
     * @param int $phpVersion
     * @param Nodes\Statement $statement
     * @return static
     */
    public static function __instantiateUnchecked($phpVersion, $statement)
    {
        $instance = new static;
        $instance->phpVersion = $phpVersion;
        $instance->statement = $statement;
        $statement->parent = $instance;
        return $instance;
    }

    protected function &_getNodeRefs(): array
    {
        $refs = [
            "statement" => &$this->statement,
        ];
        return $refs;
    }

    public function getStatement(): Nodes\Statement
    {
        if ($this->statement === null)
        {
            throw new MissingNodeException($this, __FUNCTION__);
        }
        return $this->statement;
    }

    public function hasStatement(): bool
    {
        return $this->statement !== null;
    }

    /**
     * @param Nodes\Statement|Node|string|null $statement
     */
    public function setStatement($statement): void
    {
        if ($statement !== null)
        {
            /** @var Nodes\Statement $statement */
            $statement = NodeConverter::convert($statement, Nodes\Statement::class, $this->phpVersion);
            $statement->detach();
            $statement->parent = $this;
        }
        if ($this->statement !== null)
        {
            $this->statement->detach();
        }
        $this->statement = $statement;
    }

    protected function _validate(int $flags): void
    {
        if ($this->statement === null) throw ValidationException::childRequired($this, "statement");
        if ($flags & self::VALIDATE_TYPES)
        {
        }
        if ($flags & self::VALIDATE_EXPRESSION_CONTEXT)
        {
        }
        if ($flags & self::VALIDATE_TOKENS)
        {
        }
        $this->statement->_validate($flags);
    }
}
