<?php

declare(strict_types=1);

namespace Phi;

use Phi\Exception\PhiException;
use Phi\Exception\TreeException;
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

    public function getPhpVersion(): ?int
    {
        return $this->getRoot()->phpVersion;
    }

    public function setPhpVersion(int $version): void
    {
        PhpVersion::validate($version);

        if ($this->parent)
        {
            throw new TreeException("phpVersion can only be set on the root node", $this);
        }

        $this->phpVersion = $version;
    }

    public function getParent(): ?Node
    {
        return $this->parent;
    }

    public function getRoot(): Node
    {
        $node = $this;
        while ($node->parent)
        {
            $node = $node->parent;
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
            $this->phpVersion = $this->parent->getPhpVersion();
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

    public function validate(): void
    {
        /** @var Token|null $previous */
        $previous = null;
        foreach ($this->iterTokens() as $token)
        {
            if (
                $previous
                && $previous->getRightWhitespace() === ""
                && $token->getLeftWhitespace() === ""
                && TokenType::requireSeparatingWhitespace($previous->getType(), $token->getType())
            )
            {
                throw ValidationException::missingWhitespace($token);
            }

            $previous = $token;
        }
    }

    public function autocorrect(): void
    {
        /** @var Token|null $previous */
        $previous = null;
        foreach ($this->iterTokens() as $token)
        {
            if (
                $previous
                && $previous->getRightWhitespace() === ""
                && $token->getLeftWhitespace() === ""
                && TokenType::requireSeparatingWhitespace($previous->getType(), $token->getType())
            )
            {
                $previous->setRightWhitespace(" ");
            }

            $previous = $token;
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
