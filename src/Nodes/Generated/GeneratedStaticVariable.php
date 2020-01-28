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

abstract class GeneratedStaticVariable extends CompoundNode
{
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
    }

    /**
     * @param int $phpVersion
     * @param Token $variable
     * @param Nodes\Default_|null $default
     * @return static
     */
    public static function __instantiateUnchecked($phpVersion, $variable, $default)
    {
        $instance = new static;
        $instance->phpVersion = $phpVersion;
        $instance->variable = $variable;
        $variable->parent = $instance;
        $instance->default = $default;
        if ($default) $default->parent = $instance;
        return $instance;
    }

    protected function &_getNodeRefs(): array
    {
        $refs = [
            "variable" => &$this->variable,
            "default" => &$this->default,
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

    protected function _validate(int $flags): void
    {
        if ($this->variable === null) throw ValidationException::childRequired($this, "variable");
        if ($flags & self::VALIDATE_TYPES)
        {
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
