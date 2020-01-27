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

abstract class GeneratedAnonymousFunctionUseBinding extends CompoundNode
{
    /**
     * @var Token|null
     */
    private $byReference;

    /**
     * @var Token|null
     */
    private $variable;

    /**
     */
    public function __construct()
    {
    }

    /**
     * @param int $phpVersion
     * @param Token|null $byReference
     * @param Token|null $variable
     * @return static
     */
    public static function __instantiateUnchecked($phpVersion, $byReference, $variable)
    {
        $instance = new static;
        $instance->phpVersion = $phpVersion;
        $instance->byReference = $byReference;
        if ($byReference)
        {
            $instance->byReference->parent = $instance;
        }
        $instance->variable = $variable;
        $instance->variable->parent = $instance;
        return $instance;
    }

    protected function &_getNodeRefs(): array
    {
        $refs = [
            "byReference" => &$this->byReference,
            "variable" => &$this->variable,
        ];
        return $refs;
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

    protected function _validate(int $flags): void
    {
        if ($flags & self::VALIDATE_TYPES)
        {
            if ($this->variable === null) throw ValidationException::childRequired($this, "variable");
        }
        if ($flags & self::VALIDATE_EXPRESSION_CONTEXT)
        {
        }
        if ($flags & self::VALIDATE_TOKENS)
        {
        }
    }
}
