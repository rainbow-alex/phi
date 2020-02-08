<?php

declare(strict_types=1);

namespace Phi;

use Phi\Util\Console;

class Token extends Node
{
    /**
     * @var int
     * @see TokenType
     */
    private $type;
    /** @var string */
    private $source;
    /** @var string|null */
    private $filename;
    /** @var int|null */
    private $line;
    /** @var int|null */
    private $column;

    /** @var string */
    private $leftWhitespace = "";
    /** @var string */
    private $rightWhitespace = "";

    public function __construct(
        int $type,
        string $source,
        string $filename = null,
        int $line = null,
        string $leftWhitespace = ""
    )
    {
        $this->type = $type;
        $this->source = $source;
        $this->line = $line;
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
        while ($parent = $node->parent)
        {
            $siblings = $parent->getChildNodes();
            $i = \array_search($node, $siblings, true);
            assert(\is_int($i));
            while (--$i >= 0)
            {
                return $siblings[$i]->getLastToken();
            }
            $node = $parent;
        }
        return null;
    }

    public function getNextToken(): ?Token
    {
        $node = $this;
        while ($parent = $node->parent)
        {
            $siblings = $parent->getChildNodes();
            $i = \array_search($node, $siblings, true);
            assert(\is_int($i));
            while (++$i < \count($siblings))
            {
                return $siblings[$i]->getFirstToken();
            }
        }
        return null;
    }

    public function validate(): void
    {
    }

    public function repr(): string
    {
        return TokenType::typeToString($this->type);
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

    /** @internal */
    public function _fudgeType(int $type): void
    {
        $this->type = $type;
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
