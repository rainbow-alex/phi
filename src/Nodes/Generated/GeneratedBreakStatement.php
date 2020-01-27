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
    private $semiColon;

    /**
     */
    public function __construct()
    {
    }

    /**
     * @param int $phpVersion
     * @param Token|null $keyword
     * @param Nodes\IntegerLiteral|null $levels
     * @param Token|null $semiColon
     * @return static
     */
    public static function __instantiateUnchecked($phpVersion, $keyword, $levels, $semiColon)
    {
        $instance = new static;
        $instance->phpVersion = $phpVersion;
        $instance->keyword = $keyword;
        $instance->keyword->parent = $instance;
        $instance->levels = $levels;
        if ($levels)
        {
            $instance->levels->parent = $instance;
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
            "keyword" => &$this->keyword,
            "levels" => &$this->levels,
            "semiColon" => &$this->semiColon,
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
            if ($this->keyword === null) throw ValidationException::childRequired($this, "keyword");
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
