<?php

namespace Phi;

use Phi\Util\Console;

class Token extends Node
{
    // these tokens can be used as a name in some places TODO describe & test where
    public const SPECIAL_CLASSES = ["self", "parent", "static"];

    const IDENTIFIER_KEYWORDS = [ // TODO complete, test
        \T_AS,
        \T_CLASS,
        \T_DEFAULT,
        \T_DO,
        \T_ELSE,
        \T_ELSEIF,
        \T_EMPTY,
        \T_EXIT,
        \T_EVAL,
        \T_FOR,
        \T_FUNCTION,
        \T_IF,
        \T_INSTANCEOF,
        \T_INSTEADOF,
        \T_ISSET,
        \T_INTERFACE,
        \T_TRAIT,
        \T_EXTENDS,
        \T_IMPLEMENTS,
        \T_LOGICAL_AND,
        \T_LOGICAL_OR,
        \T_NAMESPACE,
        \T_UNSET,
        \T_WHILE,
        \T_YIELD,
    ];

    /** @var int */
    private $type;
    /** @var string */
    private $source;
    /** @var int|null */
    private $line;
    /** @var int|null */
    private $column;
    /** @var string|null */
    private $filename;

    /** @var string */
    private $leftWhitespace = "";
    /** @var string */
    private $rightWhitespace = "";

    public function __construct(
        int $type,
        string $source,
        int $line = null,
        int $column = null,
        string $filename = null,
        string $leftWhitespace = ""
    )
    {
        $this->type = $type;
        $this->source = $source;
        $this->line = $line;
        $this->column = $column;
        $this->filename = $filename;
        $this->leftWhitespace = $leftWhitespace;
    }

    protected function detachChild(Node $childToDetach): void
    {
        throw new \RuntimeException($childToDetach . " is not attached to " . $this->repr());
    }

    public function getChildNodes(): array
    {
        return [];
    }

    public function iterTokens(): iterable
    {
        return [$this];
    }

    public function getFirstToken(): ?Token
    {
        return $this;
    }

    public function getLastToken(): ?Token
    {
        return $this;
    }

    public function getPreviousToken(): ?Token
    {
        $node = $this;
        do
        {
            $siblings = $node->getParent()->getChildNodes();
            $i = \array_search($node, $siblings);
            assert($i !== false);
            while (--$i >= 0)
            {
                return $siblings[$i]->getLastToken();
            }
        }
        while ($node = $node->getParent());
        return null;
    }

    public function getNextToken(): ?Token
    {
        $node = $this;
        do
        {
            $siblings = $node->getParent()->getChildNodes();
            $i = \array_search($node, $siblings);
            assert($i !== false);
            while (++$i < \count($siblings))
            {
                return $siblings[$i]->getFirstToken();
            }
        }
        while ($node = $node->getParent());
        return null;
    }

    protected function _validate(int $flags): void
    {
    }

    public function repr(): string
    {
        return "Token<" . TokenType::typeToString($this->type) . ">";
    }

    public function toPhp(): string
    {
        return $this->leftWhitespace . $this->source . $this->rightWhitespace;
    }

    public function debugDump(string $indent = ""): void
    {
        $source = str_replace("\n", "\\n", \var_export($this->source, true));
        echo $indent . TokenType::typeToString($this->type) . ": " . Console::yellow($source) . "\n";
    }

    public function convertToPhpParserNode()
    {
        return $this->getSource();
    }

    public function getType(): int
    {
        return $this->type;
    }

    public function getSource(): string
    {
        return $this->source;
    }

    public function setSource(string $source): void
    {
        $this->source = $source;
    }

    public function getLine(): ?int
    {
        return $this->line;
    }

    public function getColumn(): ?int
    {
        return $this->column;
    }

    public function getFilename(): ?string
    {
        return $this->filename;
    }

    public function setFilename(?string $filename): void
    {
        $this->filename = $filename;
    }

    /** @internal */
    public function setLeftWhitespace(string $leftWhitespace): void
    {
        $this->leftWhitespace = $leftWhitespace;
    }

    public function getLeftWhitespace(): string
    {
        return $this->leftWhitespace;
    }

    /** @internal */
    public function setRightWhitespace(string $rightWhitespace): void
    {
        $this->rightWhitespace = $rightWhitespace;
    }

    public function getRightWhitespace(): string
    {
        return $this->rightWhitespace;
    }

    /**
     * @param int $type
     * @internal
     */
    public function _withType(int $type): self
    {
        $clone = clone $this;
        $clone->type = $type;
        return $clone;
    }
}
