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

abstract class GeneratedArgument extends CompoundNode
{
    /** @var Specification[] */
    private static $specifications;
    protected static function getSpecifications(): array
    {
        return self::$specifications ?? self::$specifications = [
            new ValidCompoundNode([
                'unpack' => new Optional(new IsToken(\T_ELLIPSIS)),
                'expression' => new Specs\IsReadExpression,
            ]),
        ];
    }

    /**
     * @var Token|null
     */
    private $unpack;
    /**
     * @var Nodes\Expression|null
     */
    private $expression;

    /**
     * @param Nodes\Expression|Node|string|null $expression
     */
    public function __construct($expression = null)
    {
        parent::__construct();
        if ($expression !== null)
        {
            $this->setExpression($expression);
        }
    }

    /**
     * @param Token|null $unpack
     * @param Nodes\Expression|null $expression
     * @return static
     */
    public static function __instantiateUnchecked($unpack, $expression)
    {
        $instance = new static();
        $instance->unpack = $unpack;
        $instance->expression = $expression;
        return $instance;
    }

    public function &_getNodeRefs(): array
    {
        $refs = [
            'unpack' => &$this->unpack,
            'expression' => &$this->expression,
        ];
        return $refs;
    }

    public function getUnpack(): ?Token
    {
        return $this->unpack;
    }

    public function hasUnpack(): bool
    {
        return $this->unpack !== null;
    }

    /**
     * @param Token|Node|string|null $unpack
     */
    public function setUnpack($unpack): void
    {
        if ($unpack !== null)
        {
            /** @var Token $unpack */
            $unpack = NodeConverter::convert($unpack, Token::class, $this->_phpVersion);
            $unpack->_attachTo($this);
        }
        if ($this->unpack !== null)
        {
            $this->unpack->detach();
        }
        $this->unpack = $unpack;
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
