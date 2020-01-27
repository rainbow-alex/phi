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

abstract class GeneratedParameter extends CompoundNode
{
    /**
     * @var Nodes\Type|null
     */
    private $type;

    /**
     * @var Token|null
     */
    private $byReference;

    /**
     * @var Token|null
     */
    private $ellipsis;

    /**
     * @var Token|null
     */
    private $variable;

    /**
     * @var Nodes\Default_|null
     */
    private $default;

    /**
     * @param Token|Node|string|null $variable
     */
    public function __construct($variable = null)
    {
        if ($variable !== null)
        {
            $this->setVariable($variable);
        }
    }

    /**
     * @param int $phpVersion
     * @param Nodes\Type|null $type
     * @param Token|null $byReference
     * @param Token|null $ellipsis
     * @param Token|null $variable
     * @param Nodes\Default_|null $default
     * @return static
     */
    public static function __instantiateUnchecked($phpVersion, $type, $byReference, $ellipsis, $variable, $default)
    {
        $instance = new static;
        $instance->phpVersion = $phpVersion;
        $instance->type = $type;
        if ($type)
        {
            $instance->type->parent = $instance;
        }
        $instance->byReference = $byReference;
        if ($byReference)
        {
            $instance->byReference->parent = $instance;
        }
        $instance->ellipsis = $ellipsis;
        if ($ellipsis)
        {
            $instance->ellipsis->parent = $instance;
        }
        $instance->variable = $variable;
        $instance->variable->parent = $instance;
        $instance->default = $default;
        if ($default)
        {
            $instance->default->parent = $instance;
        }
        return $instance;
    }

    protected function &_getNodeRefs(): array
    {
        $refs = [
            'type' => &$this->type,
            'byReference' => &$this->byReference,
            'ellipsis' => &$this->ellipsis,
            'variable' => &$this->variable,
            'default' => &$this->default,
        ];
        return $refs;
    }

    public function getType(): ?Nodes\Type
    {
        return $this->type;
    }

    public function hasType(): bool
    {
        return $this->type !== null;
    }

    /**
     * @param Nodes\Type|Node|string|null $type
     */
    public function setType($type): void
    {
        if ($type !== null)
        {
            /** @var Nodes\Type $type */
            $type = NodeConverter::convert($type, Nodes\Type::class, $this->phpVersion);
            $type->detach();
            $type->parent = $this;
        }
        if ($this->type !== null)
        {
            $this->type->detach();
        }
        $this->type = $type;
    }

    public function getByReference(): ?Token
    {
        return $this->byReference;
    }

    public function hasByReference(): bool
    {
        return $this->byReference !== null;
    }

    /**
     * @param Token|Node|string|null $byReference
     */
    public function setByReference($byReference): void
    {
        if ($byReference !== null)
        {
            /** @var Token $byReference */
            $byReference = NodeConverter::convert($byReference, Token::class, $this->phpVersion);
            $byReference->detach();
            $byReference->parent = $this;
        }
        if ($this->byReference !== null)
        {
            $this->byReference->detach();
        }
        $this->byReference = $byReference;
    }

    public function getEllipsis(): ?Token
    {
        return $this->ellipsis;
    }

    public function hasEllipsis(): bool
    {
        return $this->ellipsis !== null;
    }

    /**
     * @param Token|Node|string|null $ellipsis
     */
    public function setEllipsis($ellipsis): void
    {
        if ($ellipsis !== null)
        {
            /** @var Token $ellipsis */
            $ellipsis = NodeConverter::convert($ellipsis, Token::class, $this->phpVersion);
            $ellipsis->detach();
            $ellipsis->parent = $this;
        }
        if ($this->ellipsis !== null)
        {
            $this->ellipsis->detach();
        }
        $this->ellipsis = $ellipsis;
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
        if ($flags & self::VALIDATE_TYPES)
        {
            if ($this->variable === null) throw ValidationException::childRequired($this, 'variable');
        }
        if ($flags & self::VALIDATE_EXPRESSION_CONTEXT)
        {
        }
        if ($flags & self::VALIDATE_TOKENS)
        {
        }
        if ($this->type)
        {
            $this->type->_validate($flags);
        }
        if ($this->default)
        {
            $this->default->_validate($flags);
        }
    }
}
