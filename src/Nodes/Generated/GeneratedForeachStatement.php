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

abstract class GeneratedForeachStatement extends Nodes\Statement
{
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
     * @var Nodes\Block|null
     */
    private $block;


    /**
     * @param Nodes\Expression|Node|string|null $iterable
     * @param Nodes\Key|Node|string|null $key
     * @param Nodes\Expression|Node|string|null $value
     * @param Nodes\Block|Node|string|null $block
     */
    public function __construct($iterable = null, $key = null, $value = null, $block = null)
    {
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
     * @param int $phpVersion
     * @param Token $keyword
     * @param Token $leftParenthesis
     * @param Nodes\Expression $iterable
     * @param Token $as
     * @param Nodes\Key|null $key
     * @param Token|null $byReference
     * @param Nodes\Expression $value
     * @param Token $rightParenthesis
     * @param Nodes\Block $block
     * @return static
     */
    public static function __instantiateUnchecked($phpVersion, $keyword, $leftParenthesis, $iterable, $as, $key, $byReference, $value, $rightParenthesis, $block)
    {
        $instance = new static;
        $instance->phpVersion = $phpVersion;
        $instance->keyword = $keyword;
        $keyword->parent = $instance;
        $instance->leftParenthesis = $leftParenthesis;
        $leftParenthesis->parent = $instance;
        $instance->iterable = $iterable;
        $iterable->parent = $instance;
        $instance->as = $as;
        $as->parent = $instance;
        $instance->key = $key;
        if ($key) $key->parent = $instance;
        $instance->byReference = $byReference;
        if ($byReference) $byReference->parent = $instance;
        $instance->value = $value;
        $value->parent = $instance;
        $instance->rightParenthesis = $rightParenthesis;
        $rightParenthesis->parent = $instance;
        $instance->block = $block;
        $block->parent = $instance;
        return $instance;
    }

    protected function &_getNodeRefs(): array
    {
        $refs = [
            "keyword" => &$this->keyword,
            "leftParenthesis" => &$this->leftParenthesis,
            "iterable" => &$this->iterable,
            "as" => &$this->as,
            "key" => &$this->key,
            "byReference" => &$this->byReference,
            "value" => &$this->value,
            "rightParenthesis" => &$this->rightParenthesis,
            "block" => &$this->block,
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
            $leftParenthesis = NodeConverter::convert($leftParenthesis, Token::class, $this->phpVersion);
            $leftParenthesis->detach();
            $leftParenthesis->parent = $this;
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
            $iterable = NodeConverter::convert($iterable, Nodes\Expression::class, $this->phpVersion);
            $iterable->detach();
            $iterable->parent = $this;
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
            $as = NodeConverter::convert($as, Token::class, $this->phpVersion);
            $as->detach();
            $as->parent = $this;
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
            $key = NodeConverter::convert($key, Nodes\Key::class, $this->phpVersion);
            $key->detach();
            $key->parent = $this;
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
            $value = NodeConverter::convert($value, Nodes\Expression::class, $this->phpVersion);
            $value->detach();
            $value->parent = $this;
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
            $rightParenthesis = NodeConverter::convert($rightParenthesis, Token::class, $this->phpVersion);
            $rightParenthesis->detach();
            $rightParenthesis->parent = $this;
        }
        if ($this->rightParenthesis !== null)
        {
            $this->rightParenthesis->detach();
        }
        $this->rightParenthesis = $rightParenthesis;
    }

    public function getBlock(): Nodes\Block
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
     * @param Nodes\Block|Node|string|null $block
     */
    public function setBlock($block): void
    {
        if ($block !== null)
        {
            /** @var Nodes\Block $block */
            $block = NodeConverter::convert($block, Nodes\Block::class, $this->phpVersion);
            $block->detach();
            $block->parent = $this;
        }
        if ($this->block !== null)
        {
            $this->block->detach();
        }
        $this->block = $block;
    }

    protected function _validate(int $flags): void
    {
        if ($this->keyword === null) throw ValidationException::childRequired($this, "keyword");
        if ($this->leftParenthesis === null) throw ValidationException::childRequired($this, "leftParenthesis");
        if ($this->iterable === null) throw ValidationException::childRequired($this, "iterable");
        if ($this->as === null) throw ValidationException::childRequired($this, "as");
        if ($this->value === null) throw ValidationException::childRequired($this, "value");
        if ($this->rightParenthesis === null) throw ValidationException::childRequired($this, "rightParenthesis");
        if ($this->block === null) throw ValidationException::childRequired($this, "block");
        if ($flags & self::VALIDATE_TYPES)
        {
        }
        if ($flags & self::VALIDATE_EXPRESSION_CONTEXT)
        {
        }
        if ($flags & self::VALIDATE_TOKENS)
        {
        }
        $this->iterable->_validate($flags);
        if ($this->key)
        {
            $this->key->_validate($flags);
        }
        $this->value->_validate($flags);
        $this->block->_validate($flags);
    }
}
