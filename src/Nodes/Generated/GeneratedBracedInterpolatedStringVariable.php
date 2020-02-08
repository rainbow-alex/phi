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

trait GeneratedBracedInterpolatedStringVariable
{
    /**
     * @var \Phi\Token|null
     */
    private $leftDelimiter;

    /**
     * @var \Phi\Token|null
     */
    private $name;

    /**
     * @var \Phi\Token|null
     */
    private $rightDelimiter;

    /**
     * @param \Phi\Token|\Phi\Node|string|null $name
     */
    public function __construct($name = null)
    {
        if ($name !== null)
        {
            $this->setName($name);
        }
    }

    /**
     * @param \Phi\Token $leftDelimiter
     * @param \Phi\Token $name
     * @param \Phi\Token $rightDelimiter
     * @return self
     */
    public static function __instantiateUnchecked($leftDelimiter, $name, $rightDelimiter)
    {
        $instance = new self;
        $instance->leftDelimiter = $leftDelimiter;
        $leftDelimiter->parent = $instance;
        $instance->name = $name;
        $name->parent = $instance;
        $instance->rightDelimiter = $rightDelimiter;
        $rightDelimiter->parent = $instance;
        return $instance;
    }

    public function getChildNodes(): array
    {
        return \array_values(\array_filter([
            $this->leftDelimiter,
            $this->name,
            $this->rightDelimiter,
        ]));
    }

    protected function &getChildRef(Node $childToDetach): Node
    {
        if ($this->leftDelimiter === $childToDetach)
        {
            return $this->leftDelimiter;
        }
        if ($this->name === $childToDetach)
        {
            return $this->name;
        }
        if ($this->rightDelimiter === $childToDetach)
        {
            return $this->rightDelimiter;
        }
        throw new \LogicException();
    }

    public function getLeftDelimiter(): \Phi\Token
    {
        if ($this->leftDelimiter === null)
        {
            throw TreeException::missingNode($this, "leftDelimiter");
        }
        return $this->leftDelimiter;
    }

    public function hasLeftDelimiter(): bool
    {
        return $this->leftDelimiter !== null;
    }

    /**
     * @param \Phi\Token|\Phi\Node|string|null $leftDelimiter
     */
    public function setLeftDelimiter($leftDelimiter): void
    {
        if ($leftDelimiter !== null)
        {
            /** @var \Phi\Token $leftDelimiter */
            $leftDelimiter = NodeCoercer::coerce($leftDelimiter, \Phi\Token::class, $this->getPhpVersion());
            $leftDelimiter->detach();
            $leftDelimiter->parent = $this;
        }
        if ($this->leftDelimiter !== null)
        {
            $this->leftDelimiter->detach();
        }
        $this->leftDelimiter = $leftDelimiter;
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

    public function getRightDelimiter(): \Phi\Token
    {
        if ($this->rightDelimiter === null)
        {
            throw TreeException::missingNode($this, "rightDelimiter");
        }
        return $this->rightDelimiter;
    }

    public function hasRightDelimiter(): bool
    {
        return $this->rightDelimiter !== null;
    }

    /**
     * @param \Phi\Token|\Phi\Node|string|null $rightDelimiter
     */
    public function setRightDelimiter($rightDelimiter): void
    {
        if ($rightDelimiter !== null)
        {
            /** @var \Phi\Token $rightDelimiter */
            $rightDelimiter = NodeCoercer::coerce($rightDelimiter, \Phi\Token::class, $this->getPhpVersion());
            $rightDelimiter->detach();
            $rightDelimiter->parent = $this;
        }
        if ($this->rightDelimiter !== null)
        {
            $this->rightDelimiter->detach();
        }
        $this->rightDelimiter = $rightDelimiter;
    }

    public function _validate(int $flags): void
    {
        if ($this->leftDelimiter === null)
            throw ValidationException::missingChild($this, "leftDelimiter");
        if ($this->name === null)
            throw ValidationException::missingChild($this, "name");
        if ($this->rightDelimiter === null)
            throw ValidationException::missingChild($this, "rightDelimiter");
        if ($this->leftDelimiter->getType() !== 159)
            throw ValidationException::invalidSyntax($this->leftDelimiter, [159]);
        if ($this->name->getType() !== 245)
            throw ValidationException::invalidSyntax($this->name, [245]);
        if ($this->rightDelimiter->getType() !== 126)
            throw ValidationException::invalidSyntax($this->rightDelimiter, [126]);


        $this->extraValidation($flags);

    }

    public function _autocorrect(): void
    {

        $this->extraAutocorrect();
    }
}
