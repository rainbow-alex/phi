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

abstract class GeneratedRegularUseStatement extends Nodes\UseStatement
{
    /**
     * @var Token|null
     */
    private $keyword;

    /**
     * @var Token|null
     */
    private $type;

    /**
     * @var SeparatedNodesList|Nodes\UseName[]
     * @phpstan-var SeparatedNodesList<\Phi\Nodes\UseName>
     */
    private $names;

    /**
     * @var Token|null
     */
    private $semiColon;


    /**
     * @param Nodes\UseName $name
     */
    public function __construct($name = null)
    {
        $this->names = new SeparatedNodesList();
        if ($name !== null)
        {
            $this->addName($name);
        }
    }

    /**
     * @param int $phpVersion
     * @param Token $keyword
     * @param Token|null $type
     * @param mixed[] $names
     * @param Token|null $semiColon
     * @return static
     */
    public static function __instantiateUnchecked($phpVersion, $keyword, $type, $names, $semiColon)
    {
        $instance = new static;
        $instance->phpVersion = $phpVersion;
        $instance->keyword = $keyword;
        $keyword->parent = $instance;
        $instance->type = $type;
        if ($type) $type->parent = $instance;
        $instance->names->__initUnchecked($names);
        $instance->names->parent = $instance;
        $instance->semiColon = $semiColon;
        if ($semiColon) $semiColon->parent = $instance;
        return $instance;
    }

    protected function &_getNodeRefs(): array
    {
        $refs = [
            "keyword" => &$this->keyword,
            "type" => &$this->type,
            "names" => &$this->names,
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

    public function getType(): ?Token
    {
        return $this->type;
    }

    public function hasType(): bool
    {
        return $this->type !== null;
    }

    /**
     * @param Token|Node|string|null $type
     */
    public function setType($type): void
    {
        if ($type !== null)
        {
            /** @var Token $type */
            $type = NodeConverter::convert($type, Token::class, $this->phpVersion);
            $type->detach();
            $type->parent = $this;
        }
        if ($this->type !== null)
        {
            $this->type->detach();
        }
        $this->type = $type;
    }

    /**
     * @return SeparatedNodesList|Nodes\UseName[]
     * @phpstan-return SeparatedNodesList<\Phi\Nodes\UseName>
     */
    public function getNames(): SeparatedNodesList
    {
        return $this->names;
    }

    /**
     * @param Nodes\UseName $name
     */
    public function addName($name): void
    {
        /** @var Nodes\UseName $name */
        $name = NodeConverter::convert($name, Nodes\UseName::class, $this->phpVersion);
        $this->names->add($name);
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
        if ($this->keyword === null) throw ValidationException::childRequired($this, "keyword");
        if ($flags & self::VALIDATE_TYPES)
        {
        }
        if ($flags & self::VALIDATE_EXPRESSION_CONTEXT)
        {
        }
        if ($flags & self::VALIDATE_TOKENS)
        {
        }
        $this->names->_validate($flags);
    }
}
