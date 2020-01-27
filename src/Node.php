<?php

namespace Phi;

// TODO make stuff final here as much as possible
use Phi\Exception\ValidationException;

abstract class Node
{
    /**
     * @var int|null
     * @see PhpVersion
     */
    protected $phpVersion;
    /** @var int|null */
    private $id;
    /**
     * @var Node|null
     * @internal
     */
    protected $parent;

    /** @see PhpVersion */
    public function setPhpVersion(int $version): void
    {
        PhpVersion::validate($version);
        // TODO check parent, limit recursion, etc.
        $this->phpVersion = $version;
        foreach ($this->childNodes() as $n)
        {
            $n->setPhpVersion($version);
        }
    }

    public function getId(): int
    {
        static $inc = 0;
        return $this->id = ($this->id ?: ++$inc);
    }

    public function isAttached(): bool
    {
        return $this->parent !== null;
    }

    public function getParent(): ?Node
    {
        return $this->parent;
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
    abstract public function childNodes(): array;

    /** @return iterable|Token[] */
    abstract public function tokens(): iterable;

    public function firstToken(): Token
    {
        foreach ($this->tokens() as $t)
        {
            return $t;
        }

        throw new \LogicException(); // TODO maybe an UnreachableException?
    }

    abstract public function getLeftWhitespace(): string;
    abstract public function getRightWhitespace(): string;

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
                $lexer = new Lexer($this->phpVersion ?: PhpVersion::DEFAULT());
                /** @var Token|null $previous */
                $previous = null;
                foreach ($this->tokens() as $token)
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

    /** @return iterable|Node[] */
    public function find(Specification $spec): iterable
    {
        if ($spec->isSatisfiedBy($this))
        {
            yield $this;
        }

        foreach ($this->childNodes() as $node)
        {
            yield from $node->find($spec);
        }
    }
    public function repr(): string
    {
        // TODO phpstan
        return (string) \preg_replace('{^Phi\\\\Nodes\\\\}', "", \get_class($this));
    }

    abstract public function toPhp(): string;

    abstract public function debugDump(string $indent = ""): void;

    final public function __toString(): string
    {
        return $this->toPhp();
    }
}
