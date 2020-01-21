<?php

namespace Phi\Nodes\Base;

use Phi\Lexer;
use Phi\Node;
use Phi\PhpVersion;
use Phi\Specification;
use Phi\Token;

abstract class AbstractNode implements Node
{
    /** @return Specification[] */
    protected static function getSpecifications(): array
    {
        return [];
    }

    /** @var int */
    private static $idGenerator = 0;

    /** @var int */
    private $id;
    /**
     * @var int|null
     * @see PhpVersion
     */
    public $_phpVersion;
    /**
     * @var Node|null
     * @internal
     */
    public $_parent;

    public function __construct()
    {
        $this->id = ++self::$idGenerator;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setPhpVersion(int $version): void
    {
        $this->_phpVersion = $version;
        foreach ($this->childNodes() as $n)
        {
            $n->setPhpVersion($version);
        }
    }

    public function isAttached(): bool
    {
        return $this->_parent !== null;
    }

    public function getParent(): ?Node
    {
        return $this->_parent;
    }

    /** @internal */
    public function _attachTo(Node $_parent): void
    {
        $this->detach();
        $this->_parent = $_parent;
    }

    public function detach(): void
    {
        if ($this->_parent)
        {
            $this->_parent->_detachChild($this);
            $this->_parent = null;
        }
    }

    public function validate(): void
    {
        foreach (static::getSpecifications() as $specification)
        {
            $specification->validate($this);
        }

        foreach ($this->childNodes() as $child)
        {
            $child->validate();
        }
    }

    /** @var bool */
    private static $autocorrectRecursion = false;
    public function autocorrect(): ?Node
    {
        $root = self::$autocorrectRecursion === false;
        self::$autocorrectRecursion = true;

        try
        {
            $node = $this;
            foreach (static::getSpecifications() as $specification)
            {
                $node = $specification->autocorrect($node);
            }

            // check for corrected tokens that need some whitespace to be lexed correctly, e.g. `+` followed by `+` or `function` followed by `name`
            if ($root)
            {
                $lexer = new Lexer($this->_phpVersion ?: PhpVersion::DEFAULT());
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
                                $token->setLeftWhitespace(' ');
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
        return (string) \preg_replace('{^Phi\\\\Nodes\\\\}', '', \get_class($this));
    }
}
