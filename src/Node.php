<?php

namespace Phi;

use Phi\Exception\PhiException;
use Phi\Exception\ValidationException;

abstract class Node
{
    /**
     * @var int|null
     * @see PhpVersion
     */
    protected $phpVersion;
    /**
     * @var Node|null
     * @internal
     */
    protected $parent;

    /** @see PhpVersion */
    public function setPhpVersion(int $version): void
    {
        // TODO
    }

    public function getParent(): ?Node
    {
        return $this->parent;
    }

    public function getRoot(): Node
    {
        $node = $this;
        while ($node->getParent())
        {
            $node = $node->getParent();
        }
        return $node;
    }

    public function isAttached(): bool
    {
        return $this->parent !== null;
    }

    public function detach(): void
    {
        if ($this->parent)
        {
            $this->parent->detachChild($this);
            $this->parent = null;
        }
    }

    abstract protected function detachChild(Node $childToDetach): void;

    /** @return Node[] */
    abstract public function getChildNodes(): array;

    /** @return iterable|Node[] */
    public function findNodes(Specification $spec): iterable
    {
        if ($spec->isSatisfiedBy($this))
        {
            yield $this;
        }

        foreach ($this->getChildNodes() as $node)
        {
            yield from $node->findNodes($spec);
        }
    }

    /** @return iterable|Token[] */
    abstract public function iterTokens(): iterable;

    public function getFirstToken(): ?Token
    {
        foreach ($this->iterTokens() as $t)
        {
            return $t;
        }
        return null;
    }

    public function getLastToken(): ?Token
    {
        $last = null;
        foreach ($this->iterTokens() as $t)
        {
            $last = $t;
        }
        return $last;
    }

    public function getLeftWhitespace(): string
    {
        $token = $this->getFirstToken();
        return $token ? $token->getLeftWhitespace() : "";
    }

    public function getRightWhitespace(): string
    {
        $token = $this->getLastToken();
        return $token ? $token->getRightWhitespace() : "";
    }

    /** validate that nodes set are the right type and required nodes are present */
    public const VALIDATE_TYPES = 0x01;
    /** validate that expressions are used in the right context */
    public const VALIDATE_EXPRESSION_CONTEXT = 0x02;
    /** check that delimiter tokens match up, modifier keywords are unique, etc. */
    public const VALIDATE_TOKENS = 0x04;
    /** check that there is no essential whitespace missing */
    public const VALIDATE_WHITESPACE = 0x08;
    /** any other validations */
    public const VALIDATE_OTHER = 0x10;

    public const VALIDATE_ALL = -1;

    final public function validate(int $flags = self::VALIDATE_ALL): void
    {
        $this->_validate($flags ^ self::VALIDATE_WHITESPACE);

        if ($flags & self::VALIDATE_WHITESPACE)
        {
            // TODO check whitespace
        }
    }

    /**
     * @throws ValidationException
     */
    abstract protected function _validate(int $flags): void;

    /** @var bool */
    private static $autocorrectRecursion = false;

    public function autocorrect(): Node
    {
        $root = self::$autocorrectRecursion === false;
        self::$autocorrectRecursion = true;

        try
        {
            $node = $this;
//            foreach (static::getSpecifications() as $specification)
//            {
//                $node = $specification->autocorrect($node);
//            }

            // check for corrected tokens that need some whitespace to be lexed correctly, e.g. `+` followed by `+` or `function` followed by `name`
            if ($root)
            {
                $lexer = new Lexer($this->phpVersion ?? PhpVersion::DEFAULT());
                /** @var Token|null $previous */
                $previous = null;
                foreach ($this->iterTokens() as $token)
                {
                    if ($previous)
                    {
                        if (!$previous->getRightWhitespace() && !$token->getLeftWhitespace())
                        {
                            $relexed = $lexer->lexFragment($previous . $token);
                            \array_pop($relexed); // eof
                            if (count($relexed) !== 2 || $relexed[0]->getSource() !== $previous->getSource() || $relexed[1]->getSource() !== $token->getSource())
                            {
                                $token->setLeftWhitespace(" ");
                            }
                        }
                    }
                    $previous = $token;
                }
            }

            return $node;
        }
        finally
        {
            if ($root)
            {
                self::$autocorrectRecursion = false;
            }
        }
    }

    public function repr(): string
    {
        return \str_replace('Phi\\Nodes\\', '', \get_class($this));
    }

    abstract public function toPhp(): string;

    abstract public function debugDump(string $indent = ""): void;

    final public function __toString(): string
    {
        return $this->toPhp();
    }

    /**
     * Attempts to convert this node to the equivalent PHP-Parser node.
     *
     * @return mixed
     */
    public function convertToPhpParserNode()
    {
        throw new PhiException('Failed to convert ' . $this->repr() . ' to PHP-Parser node', $this);
    }
}
