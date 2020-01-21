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

abstract class GeneratedComplexInterpolatedStringExpression extends CompoundNode implements Nodes\InterpolatedStringExpression
{
    /** @var Specification[] */
    private static $specifications;
    protected static function getSpecifications(): array
    {
        return self::$specifications ?? self::$specifications = [
            new ValidCompoundNode([
                'leftBrace' => new IsToken(\T_CURLY_OPEN),
                'expression' => new Specs\IsReadExpression,
                'rightBrace' => new IsToken('}'),
            ]),
        ];
    }

    /**
     * @var Token|null
     */
    private $leftBrace;
    /**
     * @var Nodes\Expression|null
     */
    private $expression;
    /**
     * @var Token|null
     */
    private $rightBrace;

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
     * @param Token|null $leftBrace
     * @param Nodes\Expression|null $expression
     * @param Token|null $rightBrace
     * @return static
     */
    public static function __instantiateUnchecked($leftBrace, $expression, $rightBrace)
    {
        $instance = new static();
        $instance->leftBrace = $leftBrace;
        $instance->expression = $expression;
        $instance->rightBrace = $rightBrace;
        return $instance;
    }

    public function &_getNodeRefs(): array
    {
        $refs = [
            'leftBrace' => &$this->leftBrace,
            'expression' => &$this->expression,
            'rightBrace' => &$this->rightBrace,
        ];
        return $refs;
    }

    public function getLeftBrace(): Token
    {
        if ($this->leftBrace === null)
        {
            throw new MissingNodeException($this, __FUNCTION__);
        }
        return $this->leftBrace;
    }

    public function hasLeftBrace(): bool
    {
        return $this->leftBrace !== null;
    }

    /**
     * @param Token|Node|string|null $leftBrace
     */
    public function setLeftBrace($leftBrace): void
    {
        if ($leftBrace !== null)
        {
            /** @var Token $leftBrace */
            $leftBrace = NodeConverter::convert($leftBrace, Token::class, $this->_phpVersion);
            $leftBrace->_attachTo($this);
        }
        if ($this->leftBrace !== null)
        {
            $this->leftBrace->detach();
        }
        $this->leftBrace = $leftBrace;
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

    public function getRightBrace(): Token
    {
        if ($this->rightBrace === null)
        {
            throw new MissingNodeException($this, __FUNCTION__);
        }
        return $this->rightBrace;
    }

    public function hasRightBrace(): bool
    {
        return $this->rightBrace !== null;
    }

    /**
     * @param Token|Node|string|null $rightBrace
     */
    public function setRightBrace($rightBrace): void
    {
        if ($rightBrace !== null)
        {
            /** @var Token $rightBrace */
            $rightBrace = NodeConverter::convert($rightBrace, Token::class, $this->_phpVersion);
            $rightBrace->_attachTo($this);
        }
        if ($this->rightBrace !== null)
        {
            $this->rightBrace->detach();
        }
        $this->rightBrace = $rightBrace;
    }
}
