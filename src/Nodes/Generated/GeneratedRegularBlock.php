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

abstract class GeneratedRegularBlock extends Nodes\Block
{
    /**
     * @var Token|null
     */
    private $leftBrace;

    /**
     * @var NodesList|Nodes\Statement[]
     */
    private $statements;

    /**
     * @var Token|null
     */
    private $rightBrace;

    /**
     * @param Nodes\Statement $statement
     */
    public function __construct($statement = null)
    {
        $this->statements = new NodesList();
        if ($statement !== null)
        {
            $this->addStatement($statement);
        }
    }

    /**
     * @param int $phpVersion
     * @param Token|null $leftBrace
     * @param mixed[] $statements
     * @param Token|null $rightBrace
     * @return static
     */
    public static function __instantiateUnchecked($phpVersion, $leftBrace, $statements, $rightBrace)
    {
        $instance = new static;
        $instance->phpVersion = $phpVersion;
        $instance->leftBrace = $leftBrace;
        $instance->leftBrace->parent = $instance;
        $instance->statements->__initUnchecked($statements);
        $instance->statements->parent = $instance;
        $instance->rightBrace = $rightBrace;
        $instance->rightBrace->parent = $instance;
        return $instance;
    }

    protected function &_getNodeRefs(): array
    {
        $refs = [
            "leftBrace" => &$this->leftBrace,
            "statements" => &$this->statements,
            "rightBrace" => &$this->rightBrace,
        ];
        return $refs;
    }

    public function getLeftBrace(): Token
    {
        if ($this->leftBrace === null)
        {
            throw new MissingNodeException($this, __FUNCTION__);
        }
        return $this->leftBrace;
    }

    public function hasLeftBrace(): bool
    {
        return $this->leftBrace !== null;
    }

    /**
     * @param Token|Node|string|null $leftBrace
     */
    public function setLeftBrace($leftBrace): void
    {
        if ($leftBrace !== null)
        {
            /** @var Token $leftBrace */
            $leftBrace = NodeConverter::convert($leftBrace, Token::class, $this->phpVersion);
            $leftBrace->detach();
            $leftBrace->parent = $this;
        }
        if ($this->leftBrace !== null)
        {
            $this->leftBrace->detach();
        }
        $this->leftBrace = $leftBrace;
    }

    /**
     * @return NodesList|Nodes\Statement[]
     */
    public function getStatements(): NodesList
    {
        return $this->statements;
    }

    /**
     * @param Nodes\Statement $statement
     */
    public function addStatement($statement): void
    {
        /** @var Nodes\Statement $statement */
        $statement = NodeConverter::convert($statement, Nodes\Statement::class, $this->phpVersion);
        $this->statements->add($statement);
    }

    public function getRightBrace(): Token
    {
        if ($this->rightBrace === null)
        {
            throw new MissingNodeException($this, __FUNCTION__);
        }
        return $this->rightBrace;
    }

    public function hasRightBrace(): bool
    {
        return $this->rightBrace !== null;
    }

    /**
     * @param Token|Node|string|null $rightBrace
     */
    public function setRightBrace($rightBrace): void
    {
        if ($rightBrace !== null)
        {
            /** @var Token $rightBrace */
            $rightBrace = NodeConverter::convert($rightBrace, Token::class, $this->phpVersion);
            $rightBrace->detach();
            $rightBrace->parent = $this;
        }
        if ($this->rightBrace !== null)
        {
            $this->rightBrace->detach();
        }
        $this->rightBrace = $rightBrace;
    }

    protected function _validate(int $flags): void
    {
        if ($flags & self::VALIDATE_TYPES)
        {
            if ($this->leftBrace === null) throw ValidationException::childRequired($this, "leftBrace");
            if ($this->rightBrace === null) throw ValidationException::childRequired($this, "rightBrace");
        }
        if ($flags & self::VALIDATE_EXPRESSION_CONTEXT)
        {
        }
        if ($flags & self::VALIDATE_TOKENS)
        {
        }
        $this->statements->_validate($flags);
    }
}
