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

abstract class GeneratedGroupedUsePrefix extends CompoundNode
{
    /**
     * @var Nodes\Name|null
     */
    private $prefix;

    /**
     * @var Token|null
     */
    private $separator;

    /**
     */
    public function __construct()
    {
    }

    /**
     * @param int $phpVersion
     * @param Nodes\Name|null $prefix
     * @param Token|null $separator
     * @return static
     */
    public static function __instantiateUnchecked($phpVersion, $prefix, $separator)
    {
        $instance = new static;
        $instance->phpVersion = $phpVersion;
        $instance->prefix = $prefix;
        $instance->prefix->parent = $instance;
        $instance->separator = $separator;
        $instance->separator->parent = $instance;
        return $instance;
    }

    protected function &_getNodeRefs(): array
    {
        $refs = [
            'prefix' => &$this->prefix,
            'separator' => &$this->separator,
        ];
        return $refs;
    }

    public function getPrefix(): Nodes\Name
    {
        if ($this->prefix === null)
        {
            throw new MissingNodeException($this, __FUNCTION__);
        }
        return $this->prefix;
    }

    public function hasPrefix(): bool
    {
        return $this->prefix !== null;
    }

    /**
     * @param Nodes\Name|Node|string|null $prefix
     */
    public function setPrefix($prefix): void
    {
        if ($prefix !== null)
        {
            /** @var Nodes\Name $prefix */
            $prefix = NodeConverter::convert($prefix, Nodes\Name::class, $this->phpVersion);
            $prefix->detach();
            $prefix->parent = $this;
        }
        if ($this->prefix !== null)
        {
            $this->prefix->detach();
        }
        $this->prefix = $prefix;
    }

    public function getSeparator(): Token
    {
        if ($this->separator === null)
        {
            throw new MissingNodeException($this, __FUNCTION__);
        }
        return $this->separator;
    }

    public function hasSeparator(): bool
    {
        return $this->separator !== null;
    }

    /**
     * @param Token|Node|string|null $separator
     */
    public function setSeparator($separator): void
    {
        if ($separator !== null)
        {
            /** @var Token $separator */
            $separator = NodeConverter::convert($separator, Token::class, $this->phpVersion);
            $separator->detach();
            $separator->parent = $this;
        }
        if ($this->separator !== null)
        {
            $this->separator->detach();
        }
        $this->separator = $separator;
    }

    protected function _validate(int $flags): void
    {
        if ($flags & self::VALIDATE_TYPES)
        {
            if ($this->prefix === null) throw ValidationException::childRequired($this, 'prefix');
            if ($this->separator === null) throw ValidationException::childRequired($this, 'separator');
        }
        if ($flags & self::VALIDATE_EXPRESSION_CONTEXT)
        {
        }
        if ($flags & self::VALIDATE_TOKENS)
        {
        }
        $this->prefix->_validate($flags);
    }
}
