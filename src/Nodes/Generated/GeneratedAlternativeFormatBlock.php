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

abstract class GeneratedAlternativeFormatBlock extends Nodes\Block
{
    /**
     * @var Token|null
     */
    private $colon;

    /**
     * @var NodesList|Nodes\Statement[]
     */
    private $statements;

    /**
     * @var Token|null
     */
    private $endKeyword;

    /**
     * @var Token|null
     */
    private $semiColon;

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
     * @param Token|null $colon
     * @param mixed[] $statements
     * @param Token|null $endKeyword
     * @param Token|null $semiColon
     * @return static
     */
    public static function __instantiateUnchecked($phpVersion, $colon, $statements, $endKeyword, $semiColon)
    {
        $instance = new static;
        $instance->phpVersion = $phpVersion;
        $instance->colon = $colon;
        $instance->colon->parent = $instance;
        $instance->statements->__initUnchecked($statements);
        $instance->statements->parent = $instance;
        $instance->endKeyword = $endKeyword;
        if ($endKeyword)
        {
            $instance->endKeyword->parent = $instance;
        }
        $instance->semiColon = $semiColon;
        if ($semiColon)
        {
            $instance->semiColon->parent = $instance;
        }
        return $instance;
    }

    protected function &_getNodeRefs(): array
    {
        $refs = [
            "colon" => &$this->colon,
            "statements" => &$this->statements,
            "endKeyword" => &$this->endKeyword,
            "semiColon" => &$this->semiColon,
        ];
        return $refs;
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

    public function getEndKeyword(): ?Token
    {
        return $this->endKeyword;
    }

    public function hasEndKeyword(): bool
    {
        return $this->endKeyword !== null;
    }

    /**
     * @param Token|Node|string|null $endKeyword
     */
    public function setEndKeyword($endKeyword): void
    {
        if ($endKeyword !== null)
        {
            /** @var Token $endKeyword */
            $endKeyword = NodeConverter::convert($endKeyword, Token::class, $this->phpVersion);
            $endKeyword->detach();
            $endKeyword->parent = $this;
        }
        if ($this->endKeyword !== null)
        {
            $this->endKeyword->detach();
        }
        $this->endKeyword = $endKeyword;
    }

    public function getSemiColon(): ?Token
    {
        return $this->semiColon;
    }

    public function hasSemiColon(): bool
    {
        return $this->semiColon !== null;
    }

    /**
     * @param Token|Node|string|null $semiColon
     */
    public function setSemiColon($semiColon): void
    {
        if ($semiColon !== null)
        {
            /** @var Token $semiColon */
            $semiColon = NodeConverter::convert($semiColon, Token::class, $this->phpVersion);
            $semiColon->detach();
            $semiColon->parent = $this;
        }
        if ($this->semiColon !== null)
        {
            $this->semiColon->detach();
        }
        $this->semiColon = $semiColon;
    }

    protected function _validate(int $flags): void
    {
        if ($flags & self::VALIDATE_TYPES)
        {
            if ($this->colon === null) throw ValidationException::childRequired($this, "colon");
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
