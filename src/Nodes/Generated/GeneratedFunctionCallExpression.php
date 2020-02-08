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

trait GeneratedFunctionCallExpression
{
    /**
     * @var \Phi\Nodes\Expression|null
     */
    private $callable;

    /**
     * @var \Phi\Token|null
     */
    private $leftParenthesis;

    /**
     * @var \Phi\Nodes\Base\SeparatedNodesList|\Phi\Nodes\Helpers\Argument[]
     * @phpstan-var \Phi\Nodes\Base\SeparatedNodesList<\Phi\Nodes\Helpers\Argument>
     */
    private $arguments;

    /**
     * @var \Phi\Token|null
     */
    private $rightParenthesis;

    /**
     * @param \Phi\Nodes\Expression|\Phi\Node|string|null $callable
     */
    public function __construct($callable = null)
    {
        if ($callable !== null)
        {
            $this->setCallable($callable);
        }
        $this->arguments = new \Phi\Nodes\Base\SeparatedNodesList(\Phi\Nodes\Helpers\Argument::class);
    }

    /**
     * @param \Phi\Nodes\Expression $callable
     * @param \Phi\Token $leftParenthesis
     * @param mixed[] $arguments
     * @param \Phi\Token $rightParenthesis
     * @return self
     */
    public static function __instantiateUnchecked($callable, $leftParenthesis, $arguments, $rightParenthesis)
    {
        $instance = new self;
        $instance->callable = $callable;
        $callable->parent = $instance;
        $instance->leftParenthesis = $leftParenthesis;
        $leftParenthesis->parent = $instance;
        $instance->arguments->__initUnchecked($arguments);
        $instance->arguments->parent = $instance;
        $instance->rightParenthesis = $rightParenthesis;
        $rightParenthesis->parent = $instance;
        return $instance;
    }

    public function getChildNodes(): array
    {
        return \array_values(\array_filter([
            $this->callable,
            $this->leftParenthesis,
            $this->arguments,
            $this->rightParenthesis,
        ]));
    }

    protected function &getChildRef(Node $childToDetach): Node
    {
        if ($this->callable === $childToDetach)
        {
            return $this->callable;
        }
        if ($this->leftParenthesis === $childToDetach)
        {
            return $this->leftParenthesis;
        }
        if ($this->rightParenthesis === $childToDetach)
        {
            return $this->rightParenthesis;
        }
        throw new \LogicException();
    }

    public function getCallable(): \Phi\Nodes\Expression
    {
        if ($this->callable === null)
        {
            throw TreeException::missingNode($this, "callable");
        }
        return $this->callable;
    }

    public function hasCallable(): bool
    {
        return $this->callable !== null;
    }

    /**
     * @param \Phi\Nodes\Expression|\Phi\Node|string|null $callable
     */
    public function setCallable($callable): void
    {
        if ($callable !== null)
        {
            /** @var \Phi\Nodes\Expression $callable */
            $callable = NodeCoercer::coerce($callable, \Phi\Nodes\Expression::class, $this->getPhpVersion());
            $callable->detach();
            $callable->parent = $this;
        }
        if ($this->callable !== null)
        {
            $this->callable->detach();
        }
        $this->callable = $callable;
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

    /**
     * @return \Phi\Nodes\Base\SeparatedNodesList|\Phi\Nodes\Helpers\Argument[]
     * @phpstan-return \Phi\Nodes\Base\SeparatedNodesList<\Phi\Nodes\Helpers\Argument>
     */
    public function getArguments(): \Phi\Nodes\Base\SeparatedNodesList
    {
        return $this->arguments;
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

    public function _validate(int $flags): void
    {
        if ($this->callable === null)
            throw ValidationException::missingChild($this, "callable");
        if ($this->leftParenthesis === null)
            throw ValidationException::missingChild($this, "leftParenthesis");
        if ($this->rightParenthesis === null)
            throw ValidationException::missingChild($this, "rightParenthesis");
        if ($this->leftParenthesis->getType() !== 105)
            throw ValidationException::invalidSyntax($this->leftParenthesis, [105]);
        foreach ($this->arguments->getSeparators() as $t)
            if ($t && $t->getType() !== 109)
                throw ValidationException::invalidSyntax($t, [109]);
        if ($this->rightParenthesis->getType() !== 106)
            throw ValidationException::invalidSyntax($this->rightParenthesis, [106]);

        if ($flags & 6)
            throw ValidationException::invalidExpressionInContext($this);

        $this->extraValidation($flags);

        $this->callable->_validate(1);
        foreach ($this->arguments as $t)
            $t->_validate(0);
    }

    public function _autocorrect(): void
    {
        if ($this->callable)
            $this->callable->_autocorrect();
        foreach ($this->arguments as $t)
            $t->_autocorrect();

        $this->extraAutocorrect();
    }
}
