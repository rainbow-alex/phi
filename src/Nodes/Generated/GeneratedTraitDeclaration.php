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

trait GeneratedTraitDeclaration
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
    private $leftBrace;

    /**
     * @var \Phi\Nodes\Base\NodesList|\Phi\Nodes\Oop\OopMember[]
     * @phpstan-var \Phi\Nodes\Base\NodesList<\Phi\Nodes\Oop\OopMember>
     */
    private $members;

    /**
     * @var \Phi\Token|null
     */
    private $rightBrace;

    /**
     */
    public function __construct()
    {
        $this->members = new \Phi\Nodes\Base\NodesList(\Phi\Nodes\Oop\OopMember::class);
    }

    /**
     * @param \Phi\Token $keyword
     * @param \Phi\Token $name
     * @param \Phi\Token $leftBrace
     * @param mixed[] $members
     * @param \Phi\Token $rightBrace
     * @return self
     */
    public static function __instantiateUnchecked($keyword, $name, $leftBrace, $members, $rightBrace)
    {
        $instance = new self;
        $instance->keyword = $keyword;
        $keyword->parent = $instance;
        $instance->name = $name;
        $name->parent = $instance;
        $instance->leftBrace = $leftBrace;
        $leftBrace->parent = $instance;
        $instance->members->__initUnchecked($members);
        $instance->members->parent = $instance;
        $instance->rightBrace = $rightBrace;
        $rightBrace->parent = $instance;
        return $instance;
    }

    public function getChildNodes(): array
    {
        return \array_values(\array_filter([
            $this->keyword,
            $this->name,
            $this->leftBrace,
            $this->members,
            $this->rightBrace,
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
        if ($this->leftBrace === $childToDetach)
        {
            return $this->leftBrace;
        }
        if ($this->rightBrace === $childToDetach)
        {
            return $this->rightBrace;
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

    public function getLeftBrace(): \Phi\Token
    {
        if ($this->leftBrace === null)
        {
            throw TreeException::missingNode($this, "leftBrace");
        }
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
     * @return \Phi\Nodes\Base\NodesList|\Phi\Nodes\Oop\OopMember[]
     * @phpstan-return \Phi\Nodes\Base\NodesList<\Phi\Nodes\Oop\OopMember>
     */
    public function getMembers(): \Phi\Nodes\Base\NodesList
    {
        return $this->members;
    }

    public function getRightBrace(): \Phi\Token
    {
        if ($this->rightBrace === null)
        {
            throw TreeException::missingNode($this, "rightBrace");
        }
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

    public function _validate(int $flags): void
    {
        if ($this->keyword === null)
            throw ValidationException::missingChild($this, "keyword");
        if ($this->name === null)
            throw ValidationException::missingChild($this, "name");
        if ($this->leftBrace === null)
            throw ValidationException::missingChild($this, "leftBrace");
        if ($this->rightBrace === null)
            throw ValidationException::missingChild($this, "rightBrace");
        if ($this->keyword->getType() !== 248)
            throw ValidationException::invalidSyntax($this->keyword, [248]);
        if ($this->name->getType() !== 243)
            throw ValidationException::invalidSyntax($this->name, [243]);
        if ($this->leftBrace->getType() !== 124)
            throw ValidationException::invalidSyntax($this->leftBrace, [124]);
        if ($this->rightBrace->getType() !== 126)
            throw ValidationException::invalidSyntax($this->rightBrace, [126]);


        $this->extraValidation($flags);

        foreach ($this->members as $t)
            $t->_validate(0);
    }

    public function _autocorrect(): void
    {
        foreach ($this->members as $t)
            $t->_autocorrect();

        $this->extraAutocorrect();
    }
}
