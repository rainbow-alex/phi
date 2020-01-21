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

abstract class GeneratedStaticVariable extends CompoundNode
{
    /** @var Specification[] */
    private static $specifications;
    protected static function getSpecifications(): array
    {
        return self::$specifications ?? self::$specifications = [
            new ValidCompoundNode([
                'variable' => new IsToken(\T_VARIABLE),
                'default' => new Optional(new Any),
            ]),
        ];
    }

    /**
     * @var Token|null
     */
    private $variable;
    /**
     * @var Nodes\Default_|null
     */
    private $default;

    /**
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param Token|null $variable
     * @param Nodes\Default_|null $default
     * @return static
     */
    public static function __instantiateUnchecked($variable, $default)
    {
        $instance = new static();
        $instance->variable = $variable;
        $instance->default = $default;
        return $instance;
    }

    public function &_getNodeRefs(): array
    {
        $refs = [
            'variable' => &$this->variable,
            'default' => &$this->default,
        ];
        return $refs;
    }

    public function getVariable(): Token
    {
        if ($this->variable === null)
        {
            throw new MissingNodeException($this, __FUNCTION__);
        }
        return $this->variable;
    }

    public function hasVariable(): bool
    {
        return $this->variable !== null;
    }

    /**
     * @param Token|Node|string|null $variable
     */
    public function setVariable($variable): void
    {
        if ($variable !== null)
        {
            /** @var Token $variable */
            $variable = NodeConverter::convert($variable, Token::class, $this->_phpVersion);
            $variable->_attachTo($this);
        }
        if ($this->variable !== null)
        {
            $this->variable->detach();
        }
        $this->variable = $variable;
    }

    public function getDefault(): ?Nodes\Default_
    {
        return $this->default;
    }

    public function hasDefault(): bool
    {
        return $this->default !== null;
    }

    /**
     * @param Nodes\Default_|Node|string|null $default
     */
    public function setDefault($default): void
    {
        if ($default !== null)
        {
            /** @var Nodes\Default_ $default */
            $default = NodeConverter::convert($default, Nodes\Default_::class, $this->_phpVersion);
            $default->_attachTo($this);
        }
        if ($this->default !== null)
        {
            $this->default->detach();
        }
        $this->default = $default;
    }
}
