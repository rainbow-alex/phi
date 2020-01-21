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

abstract class GeneratedProperty extends CompoundNode implements Nodes\ClassLikeMember
{
    /** @var Specification[] */
    private static $specifications;
    protected static function getSpecifications(): array
    {
        return self::$specifications ?? self::$specifications = [
            new ValidCompoundNode([
                'modifiers' => new EachItem(new IsToken(\T_PUBLIC, \T_PROTECTED, \T_PRIVATE, \T_STATIC)),
                'variable' => new IsToken(\T_VARIABLE),
                'default' => new Optional(new Any),
                'semiColon' => new IsToken(';'),
            ]),
        ];
    }

    /**
     * @var NodesList|Token[]
     */
    private $modifiers;
    /**
     * @var Token|null
     */
    private $variable;
    /**
     * @var Nodes\Default_|null
     */
    private $default;
    /**
     * @var Token|null
     */
    private $semiColon;

    /**
     * @param Token|Node|string|null $variable
     */
    public function __construct($variable = null)
    {
        parent::__construct();
        $this->modifiers = new NodesList();
        if ($variable !== null)
        {
            $this->setVariable($variable);
        }
    }

    /**
     * @param mixed[] $modifiers
     * @param Token|null $variable
     * @param Nodes\Default_|null $default
     * @param Token|null $semiColon
     * @return static
     */
    public static function __instantiateUnchecked($modifiers, $variable, $default, $semiColon)
    {
        $instance = new static();
        $instance->modifiers->__initUnchecked($modifiers);
        $instance->variable = $variable;
        $instance->default = $default;
        $instance->semiColon = $semiColon;
        return $instance;
    }

    public function &_getNodeRefs(): array
    {
        $refs = [
            'modifiers' => &$this->modifiers,
            'variable' => &$this->variable,
            'default' => &$this->default,
            'semiColon' => &$this->semiColon,
        ];
        return $refs;
    }

    /**
     * @return NodesList|Token[]
     */
    public function getModifiers(): NodesList
    {
        return $this->modifiers;
    }

    /**
     * @param Token $modifier
     */
    public function addModifier($modifier): void
    {
        /** @var Token $modifier */
        $modifier = NodeConverter::convert($modifier, Token::class);
        $this->modifiers->add($modifier);
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

    public function getSemiColon(): Token
    {
        if ($this->semiColon === null)
        {
            throw new MissingNodeException($this, __FUNCTION__);
        }
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
            $semiColon = NodeConverter::convert($semiColon, Token::class, $this->_phpVersion);
            $semiColon->_attachTo($this);
        }
        if ($this->semiColon !== null)
        {
            $this->semiColon->detach();
        }
        $this->semiColon = $semiColon;
    }
}
