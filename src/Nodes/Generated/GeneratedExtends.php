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

abstract class GeneratedExtends extends CompoundNode
{
    /**
     * @var Token|null
     */
    private $keyword;

    /**
     * @var SeparatedNodesList|Nodes\Name[]
     */
    private $names;

    /**
     * @param Nodes\Name $name
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
     * @param Token|null $keyword
     * @param mixed[] $names
     * @return static
     */
    public static function __instantiateUnchecked($phpVersion, $keyword, $names)
    {
        $instance = new static;
        $instance->phpVersion = $phpVersion;
        $instance->keyword = $keyword;
        $instance->keyword->parent = $instance;
        $instance->names->__initUnchecked($names);
        $instance->names->parent = $instance;
        return $instance;
    }

    protected function &_getNodeRefs(): array
    {
        $refs = [
            "keyword" => &$this->keyword,
            "names" => &$this->names,
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
     * @return SeparatedNodesList|Nodes\Name[]
     */
    public function getNames(): SeparatedNodesList
    {
        return $this->names;
    }

    /**
     * @param Nodes\Name $name
     */
    public function addName($name): void
    {
        /** @var Nodes\Name $name */
        $name = NodeConverter::convert($name, Nodes\Name::class, $this->phpVersion);
        $this->names->add($name);
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
        $this->names->_validate($flags);
    }
}
