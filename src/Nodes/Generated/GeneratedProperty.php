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

abstract class GeneratedProperty extends Nodes\ClassLikeMember
{
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
        $this->modifiers = new NodesList();
        if ($variable !== null)
        {
            $this->setVariable($variable);
        }
    }

    /**
     * @param int $phpVersion
     * @param mixed[] $modifiers
     * @param Token|null $variable
     * @param Nodes\Default_|null $default
     * @param Token|null $semiColon
     * @return static
     */
    public static function __instantiateUnchecked($phpVersion, $modifiers, $variable, $default, $semiColon)
    {
        $instance = new static;
        $instance->phpVersion = $phpVersion;
        $instance->modifiers->__initUnchecked($modifiers);
        $instance->modifiers->parent = $instance;
        $instance->variable = $variable;
        $instance->variable->parent = $instance;
        $instance->default = $default;
        if ($default)
        {
            $instance->default->parent = $instance;
        }
        $instance->semiColon = $semiColon;
        $instance->semiColon->parent = $instance;
        return $instance;
    }

    protected function &_getNodeRefs(): array
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
        $modifier = NodeConverter::convert($modifier, Token::class, $this->phpVersion);
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
            $variable = NodeConverter::convert($variable, Token::class, $this->phpVersion);
            $variable->detach();
            $variable->parent = $this;
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
            $default = NodeConverter::convert($default, Nodes\Default_::class, $this->phpVersion);
            $default->detach();
            $default->parent = $this;
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
            if ($this->variable === null) throw ValidationException::childRequired($this, 'variable');
            if ($this->semiColon === null) throw ValidationException::childRequired($this, 'semiColon');
        }
        if ($flags & self::VALIDATE_EXPRESSION_CONTEXT)
        {
        }
        if ($flags & self::VALIDATE_TOKENS)
        {
        }
        if ($this->default)
        {
            $this->default->_validate($flags);
        }
    }
}
