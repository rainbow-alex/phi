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

abstract class GeneratedRegularName extends Nodes\Name
{
    /**
     * @var SeparatedNodesList|Token[]
     */
    private $parts;

    /**
     */
    public function __construct()
    {
        $this->parts = new SeparatedNodesList();
    }

    /**
     * @param int $phpVersion
     * @param mixed[] $parts
     * @return static
     */
    public static function __instantiateUnchecked($phpVersion, $parts)
    {
        $instance = new static;
        $instance->phpVersion = $phpVersion;
        $instance->parts->__initUnchecked($parts);
        $instance->parts->parent = $instance;
        return $instance;
    }

    protected function &_getNodeRefs(): array
    {
        $refs = [
            "parts" => &$this->parts,
        ];
        return $refs;
    }

    /**
     * @return SeparatedNodesList|Token[]
     */
    public function getParts(): SeparatedNodesList
    {
        return $this->parts;
    }

    /**
     * @param Token $part
     */
    public function addPart($part): void
    {
        /** @var Token $part */
        $part = NodeConverter::convert($part, Token::class, $this->phpVersion);
        $this->parts->add($part);
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
    }
}
