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

abstract class GeneratedInterpolatedString extends Nodes\Expression
{
    /**
     * @var Token|null
     */
    private $leftDelimiter;

    /**
     * @var NodesList|Nodes\CInterpolatedStringPart[]
     */
    private $parts;

    /**
     * @var Token|null
     */
    private $rightDelimiter;

    /**
     */
    public function __construct()
    {
        $this->parts = new NodesList();
    }

    /**
     * @param int $phpVersion
     * @param Token|null $leftDelimiter
     * @param mixed[] $parts
     * @param Token|null $rightDelimiter
     * @return static
     */
    public static function __instantiateUnchecked($phpVersion, $leftDelimiter, $parts, $rightDelimiter)
    {
        $instance = new static;
        $instance->phpVersion = $phpVersion;
        $instance->leftDelimiter = $leftDelimiter;
        $instance->leftDelimiter->parent = $instance;
        $instance->parts->__initUnchecked($parts);
        $instance->parts->parent = $instance;
        $instance->rightDelimiter = $rightDelimiter;
        $instance->rightDelimiter->parent = $instance;
        return $instance;
    }

    protected function &_getNodeRefs(): array
    {
        $refs = [
            'leftDelimiter' => &$this->leftDelimiter,
            'parts' => &$this->parts,
            'rightDelimiter' => &$this->rightDelimiter,
        ];
        return $refs;
    }

    public function getLeftDelimiter(): Token
    {
        if ($this->leftDelimiter === null)
        {
            throw new MissingNodeException($this, __FUNCTION__);
        }
        return $this->leftDelimiter;
    }

    public function hasLeftDelimiter(): bool
    {
        return $this->leftDelimiter !== null;
    }

    /**
     * @param Token|Node|string|null $leftDelimiter
     */
    public function setLeftDelimiter($leftDelimiter): void
    {
        if ($leftDelimiter !== null)
        {
            /** @var Token $leftDelimiter */
            $leftDelimiter = NodeConverter::convert($leftDelimiter, Token::class, $this->phpVersion);
            $leftDelimiter->detach();
            $leftDelimiter->parent = $this;
        }
        if ($this->leftDelimiter !== null)
        {
            $this->leftDelimiter->detach();
        }
        $this->leftDelimiter = $leftDelimiter;
    }

    /**
     * @return NodesList|Nodes\CInterpolatedStringPart[]
     */
    public function getParts(): NodesList
    {
        return $this->parts;
    }

    /**
     * @param Nodes\CInterpolatedStringPart $part
     */
    public function addPart($part): void
    {
        /** @var Nodes\CInterpolatedStringPart $part */
        $part = NodeConverter::convert($part, Nodes\CInterpolatedStringPart::class, $this->phpVersion);
        $this->parts->add($part);
    }

    public function getRightDelimiter(): Token
    {
        if ($this->rightDelimiter === null)
        {
            throw new MissingNodeException($this, __FUNCTION__);
        }
        return $this->rightDelimiter;
    }

    public function hasRightDelimiter(): bool
    {
        return $this->rightDelimiter !== null;
    }

    /**
     * @param Token|Node|string|null $rightDelimiter
     */
    public function setRightDelimiter($rightDelimiter): void
    {
        if ($rightDelimiter !== null)
        {
            /** @var Token $rightDelimiter */
            $rightDelimiter = NodeConverter::convert($rightDelimiter, Token::class, $this->phpVersion);
            $rightDelimiter->detach();
            $rightDelimiter->parent = $this;
        }
        if ($this->rightDelimiter !== null)
        {
            $this->rightDelimiter->detach();
        }
        $this->rightDelimiter = $rightDelimiter;
    }

    protected function _validate(int $flags): void
    {
        if ($flags & self::VALIDATE_TYPES)
        {
            if ($this->leftDelimiter === null) throw ValidationException::childRequired($this, 'leftDelimiter');
            if ($this->rightDelimiter === null) throw ValidationException::childRequired($this, 'rightDelimiter');
        }
        if ($flags & self::VALIDATE_EXPRESSION_CONTEXT)
        {
        }
        if ($flags & self::VALIDATE_TOKENS)
        {
        }
        $this->parts->_validate($flags);
    }
}
