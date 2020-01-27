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

abstract class GeneratedRootNode extends CompoundNode
{
    /**
     * @var NodesList|Nodes\Statement[]
     */
    private $statements;

    /**
     * @var Token|null
     */
    private $eof;

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
     * @param mixed[] $statements
     * @param Token|null $eof
     * @return static
     */
    public static function __instantiateUnchecked($phpVersion, $statements, $eof)
    {
        $instance = new static;
        $instance->phpVersion = $phpVersion;
        $instance->statements->__initUnchecked($statements);
        $instance->statements->parent = $instance;
        $instance->eof = $eof;
        if ($eof)
        {
            $instance->eof->parent = $instance;
        }
        return $instance;
    }

    protected function &_getNodeRefs(): array
    {
        $refs = [
            "statements" => &$this->statements,
            "eof" => &$this->eof,
        ];
        return $refs;
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

    public function getEof(): ?Token
    {
        return $this->eof;
    }

    public function hasEof(): bool
    {
        return $this->eof !== null;
    }

    /**
     * @param Token|Node|string|null $eof
     */
    public function setEof($eof): void
    {
        if ($eof !== null)
        {
            /** @var Token $eof */
            $eof = NodeConverter::convert($eof, Token::class, $this->phpVersion);
            $eof->detach();
            $eof->parent = $this;
        }
        if ($this->eof !== null)
        {
            $this->eof->detach();
        }
        $this->eof = $eof;
    }

    protected function _validate(int $flags): void
    {
        if ($flags & self::VALIDATE_TYPES)
        {
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
