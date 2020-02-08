<?php

declare(strict_types=1);

/**
 * This code is generated.
 * @see meta/
 */

namespace Phi\Nodes\Generated;

use Phi\Node;
use Phi\Token;
use Phi\Exception\TreeException;
use Phi\NodeCoercer;
use Phi\Exception\ValidationException;

trait GeneratedForeachStatement
{
    /**
     * @var \Phi\Token|null
     */
    private $keyword;

    /**
     * @var \Phi\Token|null
     */
    private $leftParenthesis;

    /**
     * @var \Phi\Nodes\Expression|null
     */
    private $iterable;

    /**
     * @var \Phi\Token|null
     */
    private $asKeyword;

    /**
     * @var \Phi\Nodes\Helpers\Key|null
     */
    private $key;

    /**
     * @var \Phi\Token|null
     */
    private $byReference;

    /**
     * @var \Phi\Nodes\Expression|null
     */
    private $value;

    /**
     * @var \Phi\Token|null
     */
    private $rightParenthesis;

    /**
     * @var \Phi\Nodes\Block|null
     */
    private $block;

    /**
     * @param \Phi\Nodes\Expression|\Phi\Node|string|null $iterable
     * @param \Phi\Nodes\Helpers\Key|\Phi\Node|string|null $key
     * @param \Phi\Nodes\Expression|\Phi\Node|string|null $value
     * @param \Phi\Nodes\Block|\Phi\Node|string|null $block
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
     * @param \Phi\Token $keyword
     * @param \Phi\Token $leftParenthesis
     * @param \Phi\Nodes\Expression $iterable
     * @param \Phi\Token $asKeyword
     * @param \Phi\Nodes\Helpers\Key|null $key
     * @param \Phi\Token|null $byReference
     * @param \Phi\Nodes\Expression $value
     * @param \Phi\Token $rightParenthesis
     * @param \Phi\Nodes\Block $block
     * @return self
     */
    public static function __instantiateUnchecked($keyword, $leftParenthesis, $iterable, $asKeyword, $key, $byReference, $value, $rightParenthesis, $block)
    {
        $instance = new self;
        $instance->keyword = $keyword;
        $keyword->parent = $instance;
        $instance->leftParenthesis = $leftParenthesis;
        $leftParenthesis->parent = $instance;
        $instance->iterable = $iterable;
        $iterable->parent = $instance;
        $instance->asKeyword = $asKeyword;
        $asKeyword->parent = $instance;
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

    public function getChildNodes(): array
    {
        return \array_values(\array_filter([
            $this->keyword,
            $this->leftParenthesis,
            $this->iterable,
            $this->asKeyword,
            $this->key,
            $this->byReference,
            $this->value,
            $this->rightParenthesis,
            $this->block,
        ]));
    }

    protected function &getChildRef(Node $childToDetach): Node
    {
        if ($this->keyword === $childToDetach)
        {
            return $this->keyword;
        }
        if ($this->leftParenthesis === $childToDetach)
        {
            return $this->leftParenthesis;
        }
        if ($this->iterable === $childToDetach)
        {
            return $this->iterable;
        }
        if ($this->asKeyword === $childToDetach)
        {
            return $this->asKeyword;
        }
        if ($this->key === $childToDetach)
        {
            return $this->key;
        }
        if ($this->byReference === $childToDetach)
        {
            return $this->byReference;
        }
        if ($this->value === $childToDetach)
        {
            return $this->value;
        }
        if ($this->rightParenthesis === $childToDetach)
        {
            return $this->rightParenthesis;
        }
        if ($this->block === $childToDetach)
        {
            return $this->block;
        }
        throw new \LogicException();
    }

    public function getKeyword(): \Phi\Token
    {
        if ($this->keyword === null)
        {
            throw TreeException::missingNode($this, "keyword");
        }
        return $this->keyword;
    }

    public function hasKeyword(): bool
    {
        return $this->keyword !== null;
    }

    /**
     * @param \Phi\Token|\Phi\Node|string|null $keyword
     */
    public function setKeyword($keyword): void
    {
        if ($keyword !== null)
        {
            /** @var \Phi\Token $keyword */
            $keyword = NodeCoercer::coerce($keyword, \Phi\Token::class, $this->getPhpVersion());
            $keyword->detach();
            $keyword->parent = $this;
        }
        if ($this->keyword !== null)
        {
            $this->keyword->detach();
        }
        $this->keyword = $keyword;
    }

    public function getLeftParenthesis(): \Phi\Token
    {
        if ($this->leftParenthesis === null)
        {
            throw TreeException::missingNode($this, "leftParenthesis");
        }
        return $this->leftParenthesis;
    }

    public function hasLeftParenthesis(): bool
    {
        return $this->leftParenthesis !== null;
    }

    /**
     * @param \Phi\Token|\Phi\Node|string|null $leftParenthesis
     */
    public function setLeftParenthesis($leftParenthesis): void
    {
        if ($leftParenthesis !== null)
        {
            /** @var \Phi\Token $leftParenthesis */
            $leftParenthesis = NodeCoercer::coerce($leftParenthesis, \Phi\Token::class, $this->getPhpVersion());
            $leftParenthesis->detach();
            $leftParenthesis->parent = $this;
        }
        if ($this->leftParenthesis !== null)
        {
            $this->leftParenthesis->detach();
        }
        $this->leftParenthesis = $leftParenthesis;
    }

    public function getIterable(): \Phi\Nodes\Expression
    {
        if ($this->iterable === null)
        {
            throw TreeException::missingNode($this, "iterable");
        }
        return $this->iterable;
    }

    public function hasIterable(): bool
    {
        return $this->iterable !== null;
    }

    /**
     * @param \Phi\Nodes\Expression|\Phi\Node|string|null $iterable
     */
    public function setIterable($iterable): void
    {
        if ($iterable !== null)
        {
            /** @var \Phi\Nodes\Expression $iterable */
            $iterable = NodeCoercer::coerce($iterable, \Phi\Nodes\Expression::class, $this->getPhpVersion());
            $iterable->detach();
            $iterable->parent = $this;
        }
        if ($this->iterable !== null)
        {
            $this->iterable->detach();
        }
        $this->iterable = $iterable;
    }

    public function getAsKeyword(): \Phi\Token
    {
        if ($this->asKeyword === null)
        {
            throw TreeException::missingNode($this, "asKeyword");
        }
        return $this->asKeyword;
    }

    public function hasAsKeyword(): bool
    {
        return $this->asKeyword !== null;
    }

    /**
     * @param \Phi\Token|\Phi\Node|string|null $asKeyword
     */
    public function setAsKeyword($asKeyword): void
    {
        if ($asKeyword !== null)
        {
            /** @var \Phi\Token $asKeyword */
            $asKeyword = NodeCoercer::coerce($asKeyword, \Phi\Token::class, $this->getPhpVersion());
            $asKeyword->detach();
            $asKeyword->parent = $this;
        }
        if ($this->asKeyword !== null)
        {
            $this->asKeyword->detach();
        }
        $this->asKeyword = $asKeyword;
    }

    public function getKey(): ?\Phi\Nodes\Helpers\Key
    {
        return $this->key;
    }

    public function hasKey(): bool
    {
        return $this->key !== null;
    }

    /**
     * @param \Phi\Nodes\Helpers\Key|\Phi\Node|string|null $key
     */
    public function setKey($key): void
    {
        if ($key !== null)
        {
            /** @var \Phi\Nodes\Helpers\Key $key */
            $key = NodeCoercer::coerce($key, \Phi\Nodes\Helpers\Key::class, $this->getPhpVersion());
            $key->detach();
            $key->parent = $this;
        }
        if ($this->key !== null)
        {
            $this->key->detach();
        }
        $this->key = $key;
    }

    public function getByReference(): ?\Phi\Token
    {
        return $this->byReference;
    }

    public function hasByReference(): bool
    {
        return $this->byReference !== null;
    }

    /**
     * @param \Phi\Token|\Phi\Node|string|null $byReference
     */
    public function setByReference($byReference): void
    {
        if ($byReference !== null)
        {
            /** @var \Phi\Token $byReference */
            $byReference = NodeCoercer::coerce($byReference, \Phi\Token::class, $this->getPhpVersion());
            $byReference->detach();
            $byReference->parent = $this;
        }
        if ($this->byReference !== null)
        {
            $this->byReference->detach();
        }
        $this->byReference = $byReference;
    }

    public function getValue(): \Phi\Nodes\Expression
    {
        if ($this->value === null)
        {
            throw TreeException::missingNode($this, "value");
        }
        return $this->value;
    }

    public function hasValue(): bool
    {
        return $this->value !== null;
    }

    /**
     * @param \Phi\Nodes\Expression|\Phi\Node|string|null $value
     */
    public function setValue($value): void
    {
        if ($value !== null)
        {
            /** @var \Phi\Nodes\Expression $value */
            $value = NodeCoercer::coerce($value, \Phi\Nodes\Expression::class, $this->getPhpVersion());
            $value->detach();
            $value->parent = $this;
        }
        if ($this->value !== null)
        {
            $this->value->detach();
        }
        $this->value = $value;
    }

    public function getRightParenthesis(): \Phi\Token
    {
        if ($this->rightParenthesis === null)
        {
            throw TreeException::missingNode($this, "rightParenthesis");
        }
        return $this->rightParenthesis;
    }

    public function hasRightParenthesis(): bool
    {
        return $this->rightParenthesis !== null;
    }

    /**
     * @param \Phi\Token|\Phi\Node|string|null $rightParenthesis
     */
    public function setRightParenthesis($rightParenthesis): void
    {
        if ($rightParenthesis !== null)
        {
            /** @var \Phi\Token $rightParenthesis */
            $rightParenthesis = NodeCoercer::coerce($rightParenthesis, \Phi\Token::class, $this->getPhpVersion());
            $rightParenthesis->detach();
            $rightParenthesis->parent = $this;
        }
        if ($this->rightParenthesis !== null)
        {
            $this->rightParenthesis->detach();
        }
        $this->rightParenthesis = $rightParenthesis;
    }

    public function getBlock(): \Phi\Nodes\Block
    {
        if ($this->block === null)
        {
            throw TreeException::missingNode($this, "block");
        }
        return $this->block;
    }

    public function hasBlock(): bool
    {
        return $this->block !== null;
    }

    /**
     * @param \Phi\Nodes\Block|\Phi\Node|string|null $block
     */
    public function setBlock($block): void
    {
        if ($block !== null)
        {
            /** @var \Phi\Nodes\Block $block */
            $block = NodeCoercer::coerce($block, \Phi\Nodes\Block::class, $this->getPhpVersion());
            $block->detach();
            $block->parent = $this;
        }
        if ($this->block !== null)
        {
            $this->block->detach();
        }
        $this->block = $block;
    }

    public function _validate(int $flags): void
    {
        if ($this->keyword === null)
            throw ValidationException::missingChild($this, "keyword");
        if ($this->leftParenthesis === null)
            throw ValidationException::missingChild($this, "leftParenthesis");
        if ($this->iterable === null)
            throw ValidationException::missingChild($this, "iterable");
        if ($this->asKeyword === null)
            throw ValidationException::missingChild($this, "asKeyword");
        if ($this->value === null)
            throw ValidationException::missingChild($this, "value");
        if ($this->rightParenthesis === null)
            throw ValidationException::missingChild($this, "rightParenthesis");
        if ($this->block === null)
            throw ValidationException::missingChild($this, "block");
        if ($this->keyword->getType() !== 183)
            throw ValidationException::invalidSyntax($this->keyword, [183]);
        if ($this->leftParenthesis->getType() !== 105)
            throw ValidationException::invalidSyntax($this->leftParenthesis, [105]);
        if ($this->asKeyword->getType() !== 132)
            throw ValidationException::invalidSyntax($this->asKeyword, [132]);
        if ($this->byReference)
        if ($this->byReference->getType() !== 104)
            throw ValidationException::invalidSyntax($this->byReference, [104]);
        if ($this->rightParenthesis->getType() !== 106)
            throw ValidationException::invalidSyntax($this->rightParenthesis, [106]);


        $this->extraValidation($flags);

        $this->iterable->_validate(1);
        if ($this->key)
            $this->key->_validate(2);
        $this->value->_validate($this->byReference ? self::CTX_ALIAS_WRITE : self::CTX_WRITE);
        $this->block->_validate(0);
    }

    public function _autocorrect(): void
    {
        if ($this->iterable)
            $this->iterable->_autocorrect();
        if ($this->key)
            $this->key->_autocorrect();
        if ($this->value)
            $this->value->_autocorrect();
        if ($this->block)
            $this->block->_autocorrect();

        $this->extraAutocorrect();
    }
}
