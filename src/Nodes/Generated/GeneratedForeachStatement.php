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

abstract class GeneratedForeachStatement extends CompoundNode implements Nodes\Statement
{
    /** @var Specification[] */
    private static $specifications;
    protected static function getSpecifications(): array
    {
        return self::$specifications ?? self::$specifications = [
            new ValidCompoundNode([
                'keyword' => new IsToken(\T_FOREACH),
                'leftParenthesis' => new IsToken('('),
                'iterable' => new Specs\IsReadExpression,
                'as' => new IsToken(\T_AS),
                'key' => new Optional(new Any),
                'byReference' => new Optional(new IsToken('&')),
                'value' => new Any,
                'rightParenthesis' => new IsToken(')'),
                'block' => new Any,
            ]),
        ];
    }

    /**
     * @var Token|null
     */
    private $keyword;
    /**
     * @var Token|null
     */
    private $leftParenthesis;
    /**
     * @var Nodes\Expression|null
     */
    private $iterable;
    /**
     * @var Token|null
     */
    private $as;
    /**
     * @var Nodes\Key|null
     */
    private $key;
    /**
     * @var Token|null
     */
    private $byReference;
    /**
     * @var Nodes\Expression|null
     */
    private $value;
    /**
     * @var Token|null
     */
    private $rightParenthesis;
    /**
     * @var Nodes\Statement|null
     */
    private $block;

    /**
     * @param Nodes\Expression|Node|string|null $iterable
     * @param Nodes\Key|Node|string|null $key
     * @param Nodes\Expression|Node|string|null $value
     * @param Nodes\Statement|Node|string|null $block
     */
    public function __construct($iterable = null, $key = null, $value = null, $block = null)
    {
        parent::__construct();
        if ($iterable !== null)
        {
            $this->setIterable($iterable);
        }
        if ($key !== null)
        {
            $this->setKey($key);
        }
        if ($value !== null)
        {
            $this->setValue($value);
        }
        if ($block !== null)
        {
            $this->setBlock($block);
        }
    }

    /**
     * @param Token|null $keyword
     * @param Token|null $leftParenthesis
     * @param Nodes\Expression|null $iterable
     * @param Token|null $as
     * @param Nodes\Key|null $key
     * @param Token|null $byReference
     * @param Nodes\Expression|null $value
     * @param Token|null $rightParenthesis
     * @param Nodes\Statement|null $block
     * @return static
     */
    public static function __instantiateUnchecked($keyword, $leftParenthesis, $iterable, $as, $key, $byReference, $value, $rightParenthesis, $block)
    {
        $instance = new static();
        $instance->keyword = $keyword;
        $instance->leftParenthesis = $leftParenthesis;
        $instance->iterable = $iterable;
        $instance->as = $as;
        $instance->key = $key;
        $instance->byReference = $byReference;
        $instance->value = $value;
        $instance->rightParenthesis = $rightParenthesis;
        $instance->block = $block;
        return $instance;
    }

    public function &_getNodeRefs(): array
    {
        $refs = [
            'keyword' => &$this->keyword,
            'leftParenthesis' => &$this->leftParenthesis,
            'iterable' => &$this->iterable,
            'as' => &$this->as,
            'key' => &$this->key,
            'byReference' => &$this->byReference,
            'value' => &$this->value,
            'rightParenthesis' => &$this->rightParenthesis,
            'block' => &$this->block,
        ];
        return $refs;
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
            $keyword = NodeConverter::convert($keyword, Token::class, $this->_phpVersion);
            $keyword->_attachTo($this);
        }
        if ($this->keyword !== null)
        {
            $this->keyword->detach();
        }
        $this->keyword = $keyword;
    }

    public function getLeftParenthesis(): Token
    {
        if ($this->leftParenthesis === null)
        {
            throw new MissingNodeException($this, __FUNCTION__);
        }
        return $this->leftParenthesis;
    }

    public function hasLeftParenthesis(): bool
    {
        return $this->leftParenthesis !== null;
    }

    /**
     * @param Token|Node|string|null $leftParenthesis
     */
    public function setLeftParenthesis($leftParenthesis): void
    {
        if ($leftParenthesis !== null)
        {
            /** @var Token $leftParenthesis */
            $leftParenthesis = NodeConverter::convert($leftParenthesis, Token::class, $this->_phpVersion);
            $leftParenthesis->_attachTo($this);
        }
        if ($this->leftParenthesis !== null)
        {
            $this->leftParenthesis->detach();
        }
        $this->leftParenthesis = $leftParenthesis;
    }

    public function getIterable(): Nodes\Expression
    {
        if ($this->iterable === null)
        {
            throw new MissingNodeException($this, __FUNCTION__);
        }
        return $this->iterable;
    }

    public function hasIterable(): bool
    {
        return $this->iterable !== null;
    }

    /**
     * @param Nodes\Expression|Node|string|null $iterable
     */
    public function setIterable($iterable): void
    {
        if ($iterable !== null)
        {
            /** @var Nodes\Expression $iterable */
            $iterable = NodeConverter::convert($iterable, Nodes\Expression::class, $this->_phpVersion);
            $iterable->_attachTo($this);
        }
        if ($this->iterable !== null)
        {
            $this->iterable->detach();
        }
        $this->iterable = $iterable;
    }

    public function getAs(): Token
    {
        if ($this->as === null)
        {
            throw new MissingNodeException($this, __FUNCTION__);
        }
        return $this->as;
    }

    public function hasAs(): bool
    {
        return $this->as !== null;
    }

    /**
     * @param Token|Node|string|null $as
     */
    public function setAs($as): void
    {
        if ($as !== null)
        {
            /** @var Token $as */
            $as = NodeConverter::convert($as, Token::class, $this->_phpVersion);
            $as->_attachTo($this);
        }
        if ($this->as !== null)
        {
            $this->as->detach();
        }
        $this->as = $as;
    }

    public function getKey(): ?Nodes\Key
    {
        return $this->key;
    }

    public function hasKey(): bool
    {
        return $this->key !== null;
    }

    /**
     * @param Nodes\Key|Node|string|null $key
     */
    public function setKey($key): void
    {
        if ($key !== null)
        {
            /** @var Nodes\Key $key */
            $key = NodeConverter::convert($key, Nodes\Key::class, $this->_phpVersion);
            $key->_attachTo($this);
        }
        if ($this->key !== null)
        {
            $this->key->detach();
        }
        $this->key = $key;
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

    public function getValue(): Nodes\Expression
    {
        if ($this->value === null)
        {
            throw new MissingNodeException($this, __FUNCTION__);
        }
        return $this->value;
    }

    public function hasValue(): bool
    {
        return $this->value !== null;
    }

    /**
     * @param Nodes\Expression|Node|string|null $value
     */
    public function setValue($value): void
    {
        if ($value !== null)
        {
            /** @var Nodes\Expression $value */
            $value = NodeConverter::convert($value, Nodes\Expression::class, $this->_phpVersion);
            $value->_attachTo($this);
        }
        if ($this->value !== null)
        {
            $this->value->detach();
        }
        $this->value = $value;
    }

    public function getRightParenthesis(): Token
    {
        if ($this->rightParenthesis === null)
        {
            throw new MissingNodeException($this, __FUNCTION__);
        }
        return $this->rightParenthesis;
    }

    public function hasRightParenthesis(): bool
    {
        return $this->rightParenthesis !== null;
    }

    /**
     * @param Token|Node|string|null $rightParenthesis
     */
    public function setRightParenthesis($rightParenthesis): void
    {
        if ($rightParenthesis !== null)
        {
            /** @var Token $rightParenthesis */
            $rightParenthesis = NodeConverter::convert($rightParenthesis, Token::class, $this->_phpVersion);
            $rightParenthesis->_attachTo($this);
        }
        if ($this->rightParenthesis !== null)
        {
            $this->rightParenthesis->detach();
        }
        $this->rightParenthesis = $rightParenthesis;
    }

    public function getBlock(): Nodes\Statement
    {
        if ($this->block === null)
        {
            throw new MissingNodeException($this, __FUNCTION__);
        }
        return $this->block;
    }

    public function hasBlock(): bool
    {
        return $this->block !== null;
    }

    /**
     * @param Nodes\Statement|Node|string|null $block
     */
    public function setBlock($block): void
    {
        if ($block !== null)
        {
            /** @var Nodes\Statement $block */
            $block = NodeConverter::convert($block, Nodes\Statement::class, $this->_phpVersion);
            $block->_attachTo($this);
        }
        if ($this->block !== null)
        {
            $this->block->detach();
        }
        $this->block = $block;
    }
}
