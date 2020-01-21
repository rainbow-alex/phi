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

abstract class GeneratedRegularVariableExpression extends CompoundNode implements Nodes\Expression
{
    /** @var Specification[] */
    private static $specifications;
    protected static function getSpecifications(): array
    {
        return self::$specifications ?? self::$specifications = [
            new ValidCompoundNode([
                'variable' => new IsToken(\T_VARIABLE),
            ]),
        ];
    }

    /**
     * @var Token|null
     */
    private $variable;

    /**
     * @param Token|Node|string|null $variable
     */
    public function __construct($variable = null)
    {
        parent::__construct();
        if ($variable !== null)
        {
            $this->setVariable($variable);
        }
    }

    /**
     * @param Token|null $variable
     * @return static
     */
    public static function __instantiateUnchecked($variable)
    {
        $instance = new static();
        $instance->variable = $variable;
        return $instance;
    }

    public function &_getNodeRefs(): array
    {
        $refs = [
            'variable' => &$this->variable,
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
}
