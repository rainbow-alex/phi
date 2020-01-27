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

abstract class GeneratedClassNameResolutionExpression extends Nodes\Expression
{
    /**
     * @var Nodes\Expression|null
     */
    private $class;

    /**
     * @var Token|null
     */
    private $operator;

    /**
     * @var Token|null
     */
    private $keyword;

    /**
     * @param Nodes\Expression|Node|string|null $class
     */
    public function __construct($class = null)
    {
        if ($class !== null)
        {
            $this->setClass($class);
        }
    }

    /**
     * @param int $phpVersion
     * @param Nodes\Expression|null $class
     * @param Token|null $operator
     * @param Token|null $keyword
     * @return static
     */
    public static function __instantiateUnchecked($phpVersion, $class, $operator, $keyword)
    {
        $instance = new static;
        $instance->phpVersion = $phpVersion;
        $instance->class = $class;
        $instance->class->parent = $instance;
        $instance->operator = $operator;
        $instance->operator->parent = $instance;
        $instance->keyword = $keyword;
        $instance->keyword->parent = $instance;
        return $instance;
    }

    protected function &_getNodeRefs(): array
    {
        $refs = [
            'class' => &$this->class,
            'operator' => &$this->operator,
            'keyword' => &$this->keyword,
        ];
        return $refs;
    }

    public function getClass(): Nodes\Expression
    {
        if ($this->class === null)
        {
            throw new MissingNodeException($this, __FUNCTION__);
        }
        return $this->class;
    }

    public function hasClass(): bool
    {
        return $this->class !== null;
    }

    /**
     * @param Nodes\Expression|Node|string|null $class
     */
    public function setClass($class): void
    {
        if ($class !== null)
        {
            /** @var Nodes\Expression $class */
            $class = NodeConverter::convert($class, Nodes\Expression::class, $this->phpVersion);
            $class->detach();
            $class->parent = $this;
        }
        if ($this->class !== null)
        {
            $this->class->detach();
        }
        $this->class = $class;
    }

    public function getOperator(): Token
    {
        if ($this->operator === null)
        {
            throw new MissingNodeException($this, __FUNCTION__);
        }
        return $this->operator;
    }

    public function hasOperator(): bool
    {
        return $this->operator !== null;
    }

    /**
     * @param Token|Node|string|null $operator
     */
    public function setOperator($operator): void
    {
        if ($operator !== null)
        {
            /** @var Token $operator */
            $operator = NodeConverter::convert($operator, Token::class, $this->phpVersion);
            $operator->detach();
            $operator->parent = $this;
        }
        if ($this->operator !== null)
        {
            $this->operator->detach();
        }
        $this->operator = $operator;
    }

    public function getKeyword(): Token
    {
        if ($this->keyword === null)
        {
            throw new MissingNodeException($this, __FUNCTION__);
        }
        return $this->keyword;
    }

    public function hasKeyword(): bool
    {
        return $this->keyword !== null;
    }

    /**
     * @param Token|Node|string|null $keyword
     */
    public function setKeyword($keyword): void
    {
        if ($keyword !== null)
        {
            /** @var Token $keyword */
            $keyword = NodeConverter::convert($keyword, Token::class, $this->phpVersion);
            $keyword->detach();
            $keyword->parent = $this;
        }
        if ($this->keyword !== null)
        {
            $this->keyword->detach();
        }
        $this->keyword = $keyword;
    }

    protected function _validate(int $flags): void
    {
        if ($flags & self::VALIDATE_TYPES)
        {
            if ($this->class === null) throw ValidationException::childRequired($this, 'class');
            if ($this->operator === null) throw ValidationException::childRequired($this, 'operator');
            if ($this->keyword === null) throw ValidationException::childRequired($this, 'keyword');
        }
        if ($flags & self::VALIDATE_EXPRESSION_CONTEXT)
        {
        }
        if ($flags & self::VALIDATE_TOKENS)
        {
        }
        $this->class->_validate($flags);
    }
}
