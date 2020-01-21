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

abstract class GeneratedAnonymousFunctionUseBinding extends CompoundNode
{
    /** @var Specification[] */
    private static $specifications;
    protected static function getSpecifications(): array
    {
        return self::$specifications ?? self::$specifications = [
            new ValidCompoundNode([
                'byReference' => new Optional(new IsToken('&')),
                'variable' => new IsToken(\T_VARIABLE),
            ]),
        ];
    }

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
        parent::__construct();
    }

    /**
     * @param Token|null $byReference
     * @param Token|null $variable
     * @return static
     */
    public static function __instantiateUnchecked($byReference, $variable)
    {
        $instance = new static();
        $instance->byReference = $byReference;
        $instance->variable = $variable;
        return $instance;
    }

    public function &_getNodeRefs(): array
    {
        $refs = [
            'byReference' => &$this->byReference,
            'variable' => &$this->variable,
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
            $byReference = NodeConverter::convert($byReference, Token::class, $this->_phpVersion);
            $byReference->_attachTo($this);
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
