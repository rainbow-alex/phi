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

abstract class GeneratedRegularVariableExpression extends Nodes\Variable
{
    /**
     * @var Token|null
     */
    private $variable;


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
     * @param Token $variable
     * @return static
     */
    public static function __instantiateUnchecked($phpVersion, $variable)
    {
        $instance = new static;
        $instance->phpVersion = $phpVersion;
        $instance->variable = $variable;
        $variable->parent = $instance;
        return $instance;
    }

    protected function &_getNodeRefs(): array
    {
        $refs = [
            "variable" => &$this->variable,
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
    }
}
