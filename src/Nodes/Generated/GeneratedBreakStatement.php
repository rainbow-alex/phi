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

abstract class GeneratedBreakStatement extends Nodes\Statement
{
    /**
     * @var Token|null
     */
    private $keyword;

    /**
     * @var Nodes\IntegerLiteral|null
     */
    private $levels;

    /**
     * @var Token|null
     */
    private $delimiter;


    /**
     */
    public function __construct()
    {
    }

    /**
     * @param int $phpVersion
     * @param Token $keyword
     * @param Nodes\IntegerLiteral|null $levels
     * @param Token $delimiter
     * @return static
     */
    public static function __instantiateUnchecked($phpVersion, $keyword, $levels, $delimiter)
    {
        $instance = new static;
        $instance->phpVersion = $phpVersion;
        $instance->keyword = $keyword;
        $keyword->parent = $instance;
        $instance->levels = $levels;
        if ($levels) $levels->parent = $instance;
        $instance->delimiter = $delimiter;
        $delimiter->parent = $instance;
        return $instance;
    }

    protected function &_getNodeRefs(): array
    {
        $refs = [
            "keyword" => &$this->keyword,
            "levels" => &$this->levels,
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

    public function getLevels(): ?Nodes\IntegerLiteral
    {
        return $this->levels;
    }

    public function hasLevels(): bool
    {
        return $this->levels !== null;
    }

    /**
     * @param Nodes\IntegerLiteral|Node|string|null $levels
     */
    public function setLevels($levels): void
    {
        if ($levels !== null)
        {
            /** @var Nodes\IntegerLiteral $levels */
            $levels = NodeConverter::convert($levels, Nodes\IntegerLiteral::class, $this->phpVersion);
            $levels->detach();
            $levels->parent = $this;
        }
        if ($this->levels !== null)
        {
            $this->levels->detach();
        }
        $this->levels = $levels;
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
        if ($this->levels)
        {
            $this->levels->_validate($flags);
        }
    }
}
