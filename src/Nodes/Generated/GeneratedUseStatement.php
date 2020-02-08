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

trait GeneratedUseStatement
{
    /**
     * @var \Phi\Token|null
     */
    private $keyword;

    /**
     * @var \Phi\Token|null
     */
    private $type;

    /**
     * @var \Phi\Nodes\Helpers\Name|null
     */
    private $prefix;

    /**
     * @var \Phi\Token|null
     */
    private $leftBrace;

    /**
     * @var \Phi\Nodes\Base\SeparatedNodesList|\Phi\Nodes\Statements\UseDeclaration[]
     * @phpstan-var \Phi\Nodes\Base\SeparatedNodesList<\Phi\Nodes\Statements\UseDeclaration>
     */
    private $declarations;

    /**
     * @var \Phi\Token|null
     */
    private $rightBrace;

    /**
     * @var \Phi\Token|null
     */
    private $delimiter;

    /**
     * @param \Phi\Nodes\Statements\UseDeclaration $declaration
     */
    public function __construct($declaration = null)
    {
        $this->declarations = new \Phi\Nodes\Base\SeparatedNodesList(\Phi\Nodes\Statements\UseDeclaration::class);
        if ($declaration !== null)
        {
            $this->declarations->add($declaration);
        }
    }

    /**
     * @param \Phi\Token $keyword
     * @param \Phi\Token|null $type
     * @param \Phi\Nodes\Helpers\Name|null $prefix
     * @param \Phi\Token|null $leftBrace
     * @param mixed[] $declarations
     * @param \Phi\Token|null $rightBrace
     * @param \Phi\Token $delimiter
     * @return self
     */
    public static function __instantiateUnchecked($keyword, $type, $prefix, $leftBrace, $declarations, $rightBrace, $delimiter)
    {
        $instance = new self;
        $instance->keyword = $keyword;
        $keyword->parent = $instance;
        $instance->type = $type;
        if ($type) $type->parent = $instance;
        $instance->prefix = $prefix;
        if ($prefix) $prefix->parent = $instance;
        $instance->leftBrace = $leftBrace;
        if ($leftBrace) $leftBrace->parent = $instance;
        $instance->declarations->__initUnchecked($declarations);
        $instance->declarations->parent = $instance;
        $instance->rightBrace = $rightBrace;
        if ($rightBrace) $rightBrace->parent = $instance;
        $instance->delimiter = $delimiter;
        $delimiter->parent = $instance;
        return $instance;
    }

    public function getChildNodes(): array
    {
        return \array_values(\array_filter([
            $this->keyword,
            $this->type,
            $this->prefix,
            $this->leftBrace,
            $this->declarations,
            $this->rightBrace,
            $this->delimiter,
        ]));
    }

    protected function &getChildRef(Node $childToDetach): Node
    {
        if ($this->keyword === $childToDetach)
        {
            return $this->keyword;
        }
        if ($this->type === $childToDetach)
        {
            return $this->type;
        }
        if ($this->prefix === $childToDetach)
        {
            return $this->prefix;
        }
        if ($this->leftBrace === $childToDetach)
        {
            return $this->leftBrace;
        }
        if ($this->rightBrace === $childToDetach)
        {
            return $this->rightBrace;
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

    public function getType(): ?\Phi\Token
    {
        return $this->type;
    }

    public function hasType(): bool
    {
        return $this->type !== null;
    }

    /**
     * @param \Phi\Token|\Phi\Node|string|null $type
     */
    public function setType($type): void
    {
        if ($type !== null)
        {
            /** @var \Phi\Token $type */
            $type = NodeCoercer::coerce($type, \Phi\Token::class, $this->getPhpVersion());
            $type->detach();
            $type->parent = $this;
        }
        if ($this->type !== null)
        {
            $this->type->detach();
        }
        $this->type = $type;
    }

    public function getPrefix(): ?\Phi\Nodes\Helpers\Name
    {
        return $this->prefix;
    }

    public function hasPrefix(): bool
    {
        return $this->prefix !== null;
    }

    /**
     * @param \Phi\Nodes\Helpers\Name|\Phi\Node|string|null $prefix
     */
    public function setPrefix($prefix): void
    {
        if ($prefix !== null)
        {
            /** @var \Phi\Nodes\Helpers\Name $prefix */
            $prefix = NodeCoercer::coerce($prefix, \Phi\Nodes\Helpers\Name::class, $this->getPhpVersion());
            $prefix->detach();
            $prefix->parent = $this;
        }
        if ($this->prefix !== null)
        {
            $this->prefix->detach();
        }
        $this->prefix = $prefix;
    }

    public function getLeftBrace(): ?\Phi\Token
    {
        return $this->leftBrace;
    }

    public function hasLeftBrace(): bool
    {
        return $this->leftBrace !== null;
    }

    /**
     * @param \Phi\Token|\Phi\Node|string|null $leftBrace
     */
    public function setLeftBrace($leftBrace): void
    {
        if ($leftBrace !== null)
        {
            /** @var \Phi\Token $leftBrace */
            $leftBrace = NodeCoercer::coerce($leftBrace, \Phi\Token::class, $this->getPhpVersion());
            $leftBrace->detach();
            $leftBrace->parent = $this;
        }
        if ($this->leftBrace !== null)
        {
            $this->leftBrace->detach();
        }
        $this->leftBrace = $leftBrace;
    }

    /**
     * @return \Phi\Nodes\Base\SeparatedNodesList|\Phi\Nodes\Statements\UseDeclaration[]
     * @phpstan-return \Phi\Nodes\Base\SeparatedNodesList<\Phi\Nodes\Statements\UseDeclaration>
     */
    public function getDeclarations(): \Phi\Nodes\Base\SeparatedNodesList
    {
        return $this->declarations;
    }

    public function getRightBrace(): ?\Phi\Token
    {
        return $this->rightBrace;
    }

    public function hasRightBrace(): bool
    {
        return $this->rightBrace !== null;
    }

    /**
     * @param \Phi\Token|\Phi\Node|string|null $rightBrace
     */
    public function setRightBrace($rightBrace): void
    {
        if ($rightBrace !== null)
        {
            /** @var \Phi\Token $rightBrace */
            $rightBrace = NodeCoercer::coerce($rightBrace, \Phi\Token::class, $this->getPhpVersion());
            $rightBrace->detach();
            $rightBrace->parent = $this;
        }
        if ($this->rightBrace !== null)
        {
            $this->rightBrace->detach();
        }
        $this->rightBrace = $rightBrace;
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
        if ($this->delimiter === null)
            throw ValidationException::missingChild($this, "delimiter");
        if ($this->keyword->getType() !== 253)
            throw ValidationException::invalidSyntax($this->keyword, [253]);
        if ($this->type)
        if (!\in_array($this->type->getType(), [184, 147], true))
            throw ValidationException::invalidSyntax($this->type, [184, 147]);
        if ($this->leftBrace)
        if ($this->leftBrace->getType() !== 124)
            throw ValidationException::invalidSyntax($this->leftBrace, [124]);
        foreach ($this->declarations->getSeparators() as $t)
            if ($t && $t->getType() !== 109)
                throw ValidationException::invalidSyntax($t, [109]);
        if ($this->rightBrace)
        if ($this->rightBrace->getType() !== 126)
            throw ValidationException::invalidSyntax($this->rightBrace, [126]);
        if (!\in_array($this->delimiter->getType(), [114, 143], true))
            throw ValidationException::invalidSyntax($this->delimiter, [114, 143]);


        $this->extraValidation($flags);

        if ($this->prefix)
            $this->prefix->_validate(0);
        foreach ($this->declarations as $t)
            $t->_validate(0);
    }

    public function _autocorrect(): void
    {
        if ($this->prefix)
            $this->prefix->_autocorrect();
        foreach ($this->declarations as $t)
            $t->_autocorrect();

        $this->extraAutocorrect();
    }
}
