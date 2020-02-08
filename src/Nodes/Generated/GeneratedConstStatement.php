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

trait GeneratedConstStatement
{
    /**
     * @var \Phi\Token|null
     */
    private $keyword;

    /**
     * @var \Phi\Token|null
     */
    private $name;

    /**
     * @var \Phi\Token|null
     */
    private $equals;

    /**
     * @var \Phi\Nodes\Expression|null
     */
    private $value;

    /**
     * @var \Phi\Token|null
     */
    private $delimiter;

    /**
     * @param \Phi\Token|\Phi\Node|string|null $name
     * @param \Phi\Nodes\Expression|\Phi\Node|string|null $value
     */
    public function __construct($name = null, $value = null)
    {
        if ($name !== null)
        {
            $this->setName($name);
        }
        if ($value !== null)
        {
            $this->setValue($value);
        }
    }

    /**
     * @param \Phi\Token $keyword
     * @param \Phi\Token $name
     * @param \Phi\Token $equals
     * @param \Phi\Nodes\Expression $value
     * @param \Phi\Token $delimiter
     * @return self
     */
    public static function __instantiateUnchecked($keyword, $name, $equals, $value, $delimiter)
    {
        $instance = new self;
        $instance->keyword = $keyword;
        $keyword->parent = $instance;
        $instance->name = $name;
        $name->parent = $instance;
        $instance->equals = $equals;
        $equals->parent = $instance;
        $instance->value = $value;
        $value->parent = $instance;
        $instance->delimiter = $delimiter;
        $delimiter->parent = $instance;
        return $instance;
    }

    public function getChildNodes(): array
    {
        return \array_values(\array_filter([
            $this->keyword,
            $this->name,
            $this->equals,
            $this->value,
            $this->delimiter,
        ]));
    }

    protected function &getChildRef(Node $childToDetach): Node
    {
        if ($this->keyword === $childToDetach)
        {
            return $this->keyword;
        }
        if ($this->name === $childToDetach)
        {
            return $this->name;
        }
        if ($this->equals === $childToDetach)
        {
            return $this->equals;
        }
        if ($this->value === $childToDetach)
        {
            return $this->value;
        }
        if ($this->delimiter === $childToDetach)
        {
            return $this->delimiter;
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

    public function getName(): \Phi\Token
    {
        if ($this->name === null)
        {
            throw TreeException::missingNode($this, "name");
        }
        return $this->name;
    }

    public function hasName(): bool
    {
        return $this->name !== null;
    }

    /**
     * @param \Phi\Token|\Phi\Node|string|null $name
     */
    public function setName($name): void
    {
        if ($name !== null)
        {
            /** @var \Phi\Token $name */
            $name = NodeCoercer::coerce($name, \Phi\Token::class, $this->getPhpVersion());
            $name->detach();
            $name->parent = $this;
        }
        if ($this->name !== null)
        {
            $this->name->detach();
        }
        $this->name = $name;
    }

    public function getEquals(): \Phi\Token
    {
        if ($this->equals === null)
        {
            throw TreeException::missingNode($this, "equals");
        }
        return $this->equals;
    }

    public function hasEquals(): bool
    {
        return $this->equals !== null;
    }

    /**
     * @param \Phi\Token|\Phi\Node|string|null $equals
     */
    public function setEquals($equals): void
    {
        if ($equals !== null)
        {
            /** @var \Phi\Token $equals */
            $equals = NodeCoercer::coerce($equals, \Phi\Token::class, $this->getPhpVersion());
            $equals->detach();
            $equals->parent = $this;
        }
        if ($this->equals !== null)
        {
            $this->equals->detach();
        }
        $this->equals = $equals;
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

    public function getDelimiter(): \Phi\Token
    {
        if ($this->delimiter === null)
        {
            throw TreeException::missingNode($this, "delimiter");
        }
        return $this->delimiter;
    }

    public function hasDelimiter(): bool
    {
        return $this->delimiter !== null;
    }

    /**
     * @param \Phi\Token|\Phi\Node|string|null $delimiter
     */
    public function setDelimiter($delimiter): void
    {
        if ($delimiter !== null)
        {
            /** @var \Phi\Token $delimiter */
            $delimiter = NodeCoercer::coerce($delimiter, \Phi\Token::class, $this->getPhpVersion());
            $delimiter->detach();
            $delimiter->parent = $this;
        }
        if ($this->delimiter !== null)
        {
            $this->delimiter->detach();
        }
        $this->delimiter = $delimiter;
    }

    public function _validate(int $flags): void
    {
        if ($this->keyword === null)
            throw ValidationException::missingChild($this, "keyword");
        if ($this->name === null)
            throw ValidationException::missingChild($this, "name");
        if ($this->equals === null)
            throw ValidationException::missingChild($this, "equals");
        if ($this->value === null)
            throw ValidationException::missingChild($this, "value");
        if ($this->delimiter === null)
            throw ValidationException::missingChild($this, "delimiter");
        if ($this->keyword->getType() !== 147)
            throw ValidationException::invalidSyntax($this->keyword, [147]);
        if ($this->name->getType() !== 243)
            throw ValidationException::invalidSyntax($this->name, [243]);
        if ($this->equals->getType() !== 116)
            throw ValidationException::invalidSyntax($this->equals, [116]);
        if (!\in_array($this->delimiter->getType(), [114, 143], true))
            throw ValidationException::invalidSyntax($this->delimiter, [114, 143]);


        $this->extraValidation($flags);

        $this->value->_validate(1);
    }

    public function _autocorrect(): void
    {
        if ($this->value)
            $this->value->_autocorrect();

        $this->extraAutocorrect();
    }
}
