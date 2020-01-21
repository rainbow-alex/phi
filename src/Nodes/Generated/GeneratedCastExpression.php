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

abstract class GeneratedCastExpression extends CompoundNode implements Nodes\Expression
{
    /** @var Specification[] */
    private static $specifications;
    protected static function getSpecifications(): array
    {
        return self::$specifications ?? self::$specifications = [
            new ValidCompoundNode([
                'cast' => new IsToken(\T_ARRAY_CAST, \T_BOOL_CAST, \T_DOUBLE_CAST, \T_INT_CAST, \T_OBJECT_CAST, \T_STRING_CAST, \T_UNSET_CAST),
                'expression' => new Specs\IsReadExpression,
            ]),
        ];
    }

    /**
     * @var Token|null
     */
    private $cast;
    /**
     * @var Nodes\Expression|null
     */
    private $expression;

    /**
     * @param Token|Node|string|null $cast
     * @param Nodes\Expression|Node|string|null $expression
     */
    public function __construct($cast = null, $expression = null)
    {
        parent::__construct();
        if ($cast !== null)
        {
            $this->setCast($cast);
        }
        if ($expression !== null)
        {
            $this->setExpression($expression);
        }
    }

    /**
     * @param Token|null $cast
     * @param Nodes\Expression|null $expression
     * @return static
     */
    public static function __instantiateUnchecked($cast, $expression)
    {
        $instance = new static();
        $instance->cast = $cast;
        $instance->expression = $expression;
        return $instance;
    }

    public function &_getNodeRefs(): array
    {
        $refs = [
            'cast' => &$this->cast,
            'expression' => &$this->expression,
        ];
        return $refs;
    }

    public function getCast(): Token
    {
        if ($this->cast === null)
        {
            throw new MissingNodeException($this, __FUNCTION__);
        }
        return $this->cast;
    }

    public function hasCast(): bool
    {
        return $this->cast !== null;
    }

    /**
     * @param Token|Node|string|null $cast
     */
    public function setCast($cast): void
    {
        if ($cast !== null)
        {
            /** @var Token $cast */
            $cast = NodeConverter::convert($cast, Token::class, $this->_phpVersion);
            $cast->_attachTo($this);
        }
        if ($this->cast !== null)
        {
            $this->cast->detach();
        }
        $this->cast = $cast;
    }

    public function getExpression(): Nodes\Expression
    {
        if ($this->expression === null)
        {
            throw new MissingNodeException($this, __FUNCTION__);
        }
        return $this->expression;
    }

    public function hasExpression(): bool
    {
        return $this->expression !== null;
    }

    /**
     * @param Nodes\Expression|Node|string|null $expression
     */
    public function setExpression($expression): void
    {
        if ($expression !== null)
        {
            /** @var Nodes\Expression $expression */
            $expression = NodeConverter::convert($expression, Nodes\Expression::class, $this->_phpVersion);
            $expression->_attachTo($this);
        }
        if ($this->expression !== null)
        {
            $this->expression->detach();
        }
        $this->expression = $expression;
    }
}