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

abstract class GeneratedSwitchDefault extends CompoundNode
{
    /**
     * @var Token|null
     */
    private $keyword;

    /**
     * @var Token|null
     */
    private $colon;

    /**
     * @var NodesList|Nodes\Statement[]
     * @phpstan-var NodesList<\Phi\Nodes\Statement>
     */
    private $statements;


    /**
     */
    public function __construct()
    {
        $this->statements = new NodesList();
    }

    /**
     * @param int $phpVersion
     * @param Token $keyword
     * @param Token $colon
     * @param mixed[] $statements
     * @return static
     */
    public static function __instantiateUnchecked($phpVersion, $keyword, $colon, $statements)
    {
        $instance = new static;
        $instance->phpVersion = $phpVersion;
        $instance->keyword = $keyword;
        $keyword->parent = $instance;
        $instance->colon = $colon;
        $colon->parent = $instance;
        $instance->statements->__initUnchecked($statements);
        $instance->statements->parent = $instance;
        return $instance;
    }

    protected function &_getNodeRefs(): array
    {
        $refs = [
            "keyword" => &$this->keyword,
            "colon" => &$this->colon,
            "statements" => &$this->statements,
        ];
        return $refs;
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
            $keyword = NodeConverter::convert($keyword, Token::class, $this->phpVersion);
            $keyword->detach();
            $keyword->parent = $this;
        }
        if ($this->keyword !== null)
        {
            $this->keyword->detach();
        }
        $this->keyword = $keyword;
    }

    public function getColon(): Token
    {
        if ($this->colon === null)
        {
            throw new MissingNodeException($this, __FUNCTION__);
        }
        return $this->colon;
    }

    public function hasColon(): bool
    {
        return $this->colon !== null;
    }

    /**
     * @param Token|Node|string|null $colon
     */
    public function setColon($colon): void
    {
        if ($colon !== null)
        {
            /** @var Token $colon */
            $colon = NodeConverter::convert($colon, Token::class, $this->phpVersion);
            $colon->detach();
            $colon->parent = $this;
        }
        if ($this->colon !== null)
        {
            $this->colon->detach();
        }
        $this->colon = $colon;
    }

    /**
     * @return NodesList|Nodes\Statement[]
     * @phpstan-return NodesList<\Phi\Nodes\Statement>
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

    protected function _validate(int $flags): void
    {
        if ($this->keyword === null) throw ValidationException::childRequired($this, "keyword");
        if ($this->colon === null) throw ValidationException::childRequired($this, "colon");
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
