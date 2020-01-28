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

abstract class GeneratedEchoStatement extends Nodes\Statement
{
    /**
     * @var Token|null
     */
    private $keyword;

    /**
     * @var SeparatedNodesList|Nodes\Expression[]
     * @phpstan-var SeparatedNodesList<\Phi\Nodes\Expression>
     */
    private $expressions;

    /**
     * @var Token|null
     */
    private $delimiter;


    /**
     */
    public function __construct()
    {
        $this->expressions = new SeparatedNodesList();
    }

    /**
     * @param int $phpVersion
     * @param Token $keyword
     * @param mixed[] $expressions
     * @param Token $delimiter
     * @return static
     */
    public static function __instantiateUnchecked($phpVersion, $keyword, $expressions, $delimiter)
    {
        $instance = new static;
        $instance->phpVersion = $phpVersion;
        $instance->keyword = $keyword;
        $keyword->parent = $instance;
        $instance->expressions->__initUnchecked($expressions);
        $instance->expressions->parent = $instance;
        $instance->delimiter = $delimiter;
        $delimiter->parent = $instance;
        return $instance;
    }

    protected function &_getNodeRefs(): array
    {
        $refs = [
            "keyword" => &$this->keyword,
            "expressions" => &$this->expressions,
            "delimiter" => &$this->delimiter,
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

    /**
     * @return SeparatedNodesList|Nodes\Expression[]
     * @phpstan-return SeparatedNodesList<\Phi\Nodes\Expression>
     */
    public function getExpressions(): SeparatedNodesList
    {
        return $this->expressions;
    }

    /**
     * @param Nodes\Expression $expression
     */
    public function addExpression($expression): void
    {
        /** @var Nodes\Expression $expression */
        $expression = NodeConverter::convert($expression, Nodes\Expression::class, $this->phpVersion);
        $this->expressions->add($expression);
    }

    public function getDelimiter(): Token
    {
        if ($this->delimiter === null)
        {
            throw new MissingNodeException($this, __FUNCTION__);
        }
        return $this->delimiter;
    }

    public function hasDelimiter(): bool
    {
        return $this->delimiter !== null;
    }

    /**
     * @param Token|Node|string|null $delimiter
     */
    public function setDelimiter($delimiter): void
    {
        if ($delimiter !== null)
        {
            /** @var Token $delimiter */
            $delimiter = NodeConverter::convert($delimiter, Token::class, $this->phpVersion);
            $delimiter->detach();
            $delimiter->parent = $this;
        }
        if ($this->delimiter !== null)
        {
            $this->delimiter->detach();
        }
        $this->delimiter = $delimiter;
    }

    protected function _validate(int $flags): void
    {
        if ($this->keyword === null) throw ValidationException::childRequired($this, "keyword");
        if ($this->delimiter === null) throw ValidationException::childRequired($this, "delimiter");
        if ($flags & self::VALIDATE_TYPES)
        {
        }
        if ($flags & self::VALIDATE_EXPRESSION_CONTEXT)
        {
        }
        if ($flags & self::VALIDATE_TOKENS)
        {
        }
        $this->expressions->_validate($flags);
    }
}
