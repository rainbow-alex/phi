<?php

namespace Phi\Nodes\Generated;

use Phi\Node;
use Phi\Token;
use Phi\Nodes\Base\CompoundNode;
use Phi\Nodes\Base\NodesList;
use Phi\Nodes\Base\SeparatedNodesList;
use Phi\Exception\MissingNodeException;
use Phi\NodeConverter;
use Phi\Specification;
use Phi\Optional;
use Phi\Specifications\And_;
use Phi\Specifications\Any;
use Phi\Specifications\IsToken;
use Phi\Specifications\IsInstanceOf;
use Phi\Specifications\ValidCompoundNode;
use Phi\Specifications\EachItem;
use Phi\Specifications\EachSeparator;
use Phi\Nodes as Nodes;
use Phi\Specifications as Specs;

abstract class GeneratedGroupedUsePrefix extends CompoundNode
{
    /** @var Specification[] */
    private static $specifications;
    protected static function getSpecifications(): array
    {
        return self::$specifications ?? self::$specifications = [
            new ValidCompoundNode([
                'prefix' => new Any,
                'separator' => new IsToken(\T_NS_SEPARATOR),
            ]),
        ];
    }

    /**
     * @var Nodes\RegularName|null
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
        parent::__construct();
    }

    /**
     * @param Nodes\RegularName|null $prefix
     * @param Token|null $separator
     * @return static
     */
    public static function __instantiateUnchecked($prefix, $separator)
    {
        $instance = new static();
        $instance->prefix = $prefix;
        $instance->separator = $separator;
        return $instance;
    }

    public function &_getNodeRefs(): array
    {
        $refs = [
            'prefix' => &$this->prefix,
            'separator' => &$this->separator,
        ];
        return $refs;
    }

    public function getPrefix(): Nodes\RegularName
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
     * @param Nodes\RegularName|Node|string|null $prefix
     */
    public function setPrefix($prefix): void
    {
        if ($prefix !== null)
        {
            /** @var Nodes\RegularName $prefix */
            $prefix = NodeConverter::convert($prefix, Nodes\RegularName::class, $this->_phpVersion);
            $prefix->_attachTo($this);
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
            $separator = NodeConverter::convert($separator, Token::class, $this->_phpVersion);
            $separator->_attachTo($this);
        }
        if ($this->separator !== null)
        {
            $this->separator->detach();
        }
        $this->separator = $separator;
    }
}
