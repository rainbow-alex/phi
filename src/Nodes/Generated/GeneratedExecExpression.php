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

trait GeneratedExecExpression
{
    /**
     * @var \Phi\Token|null
     */
    private $leftDelimiter;

    /**
     * @var \Phi\Token|null
     */
    private $command;

    /**
     * @var \Phi\Token|null
     */
    private $rightDelimiter;

    /**
     * @param \Phi\Token|\Phi\Node|string|null $command
     */
    public function __construct($command = null)
    {
        if ($command !== null)
        {
            $this->setCommand($command);
        }
    }

    /**
     * @param \Phi\Token $leftDelimiter
     * @param \Phi\Token $command
     * @param \Phi\Token $rightDelimiter
     * @return self
     */
    public static function __instantiateUnchecked($leftDelimiter, $command, $rightDelimiter)
    {
        $instance = new self;
        $instance->leftDelimiter = $leftDelimiter;
        $leftDelimiter->parent = $instance;
        $instance->command = $command;
        $command->parent = $instance;
        $instance->rightDelimiter = $rightDelimiter;
        $rightDelimiter->parent = $instance;
        return $instance;
    }

    public function getChildNodes(): array
    {
        return \array_values(\array_filter([
            $this->leftDelimiter,
            $this->command,
            $this->rightDelimiter,
        ]));
    }

    protected function &getChildRef(Node $childToDetach): Node
    {
        if ($this->leftDelimiter === $childToDetach)
        {
            return $this->leftDelimiter;
        }
        if ($this->command === $childToDetach)
        {
            return $this->command;
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

    public function getCommand(): \Phi\Token
    {
        if ($this->command === null)
        {
            throw TreeException::missingNode($this, "command");
        }
        return $this->command;
    }

    public function hasCommand(): bool
    {
        return $this->command !== null;
    }

    /**
     * @param \Phi\Token|\Phi\Node|string|null $command
     */
    public function setCommand($command): void
    {
        if ($command !== null)
        {
            /** @var \Phi\Token $command */
            $command = NodeCoercer::coerce($command, \Phi\Token::class, $this->getPhpVersion());
            $command->detach();
            $command->parent = $this;
        }
        if ($this->command !== null)
        {
            $this->command->detach();
        }
        $this->command = $command;
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
        if ($this->command === null)
            throw ValidationException::missingChild($this, "command");
        if ($this->rightDelimiter === null)
            throw ValidationException::missingChild($this, "rightDelimiter");
        if ($this->leftDelimiter->getType() !== 123)
            throw ValidationException::invalidSyntax($this->leftDelimiter, [123]);
        if ($this->command->getType() !== 168)
            throw ValidationException::invalidSyntax($this->command, [168]);
        if ($this->rightDelimiter->getType() !== 123)
            throw ValidationException::invalidSyntax($this->rightDelimiter, [123]);

        if ($flags & 14)
            throw ValidationException::invalidExpressionInContext($this);

        $this->extraValidation($flags);

    }

    public function _autocorrect(): void
    {

        $this->extraAutocorrect();
    }
}
