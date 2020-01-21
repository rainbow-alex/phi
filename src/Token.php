<?php

namespace Phi;

use Phi\Nodes\Base\AbstractNode;
use Phi\Util\Console;
use InvalidArgumentException;

class Token extends AbstractNode
{
    public const SPECIAL_CLASSES = ['self', 'parent', 'static'];
    public const CASTS = [
        \T_ARRAY_CAST,
        \T_BOOL_CAST,
        \T_DOUBLE_CAST,
        \T_INT_CAST,
        \T_OBJECT_CAST,
        \T_STRING_CAST,
        \T_UNSET_CAST,
    ];

    public const EOF = 0;
    const MAGIC_CONSTANTS = [\T_DIR, \T_FILE, \T_LINE, \T_FUNC_C, \T_CLASS_C, \T_METHOD_C];
    const COMBINED_ASSIGNMENT = [
        \T_PLUS_EQUAL, \T_MINUS_EQUAL, \T_MUL_EQUAL, \T_POW_EQUAL, \T_DIV_EQUAL, \T_CONCAT_EQUAL, \T_MOD_EQUAL,
        \T_AND_EQUAL, \T_OR_EQUAL, \T_XOR_EQUAL, \T_SL_EQUAL, \T_SR_EQUAL,
    ];
    const IDENTIFIER_KEYWORDS = [ // TODO complete
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

    /** @var int|string */
    private $type;
    /** @var string */
    private $source;
    /** @var int|null */
    private $line;
    /** @var int|null */
    private $column;
    /** @var string|null */
    private $filename;

    /** @var Token|null */
    private $previous;
    /** @var Token|null */
    private $next;

    /** @var string */
    private $leftWhitespace = '';
    /** @var string */
    private $rightWhitespace = '';

    /**
     * @param int|string $type
     */
    public function __construct(
        $type,
        string $source = null,
        int $line = null,
        int $column = null,
        string $filename = null,
        Token $previous = null
    )
    {
        if ($source === null && !\is_string($type))
        {
            throw new InvalidArgumentException('Source should be provided for token of type ' . self::typeToString($type));
        }

        parent::__construct();
        $this->type = $type;
        $this->source = $source ?? (string) $type;
        $this->line = $line;
        $this->column = $column;
        $this->filename = $filename;
        $this->previous = $previous;
        if ($previous)
        {
            assert($previous->next === null);
            $previous->next = $this;
        }
    }

    public function _detachChild(Node $childToDetach): void
    {
        throw new \RuntimeException($childToDetach . ' is not attached to ' . $this);
    }

    public function childNodes(): array
    {
        return [];
    }

    public function tokens(): iterable
    {
        return [$this];
    }

    public function repr(): string
    {
        return 'Token<' . self::typeToString($this->type) . '>';
    }

    public function __toString(): string
    {
        return $this->leftWhitespace . $this->source . $this->rightWhitespace;
    }

    public function debugDump(string $indent = ''): void
    {
        $source = str_replace("\n", "\\n", \var_export($this->source, true));
        echo $indent . self::typeToString($this->type) . ": " . Console::yellow($source) . "\n";
    }

    /**
     * @return int|string
     */
    public function getType()
    {
        return $this->type;
    }

    public function getSource(): string
    {
        return $this->source;
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
     * @param int|string $type
     * @internal
     */
    public function _withType($type): self
    {
        $clone = clone $this;
        $clone->type = $type;
        return $clone;
    }

    /**
     * @param int|string $type
     */
    public static function typeToString($type): string
    {
        if ($type === self::EOF)
        {
            return 'EOF';
        }
        else if (\is_int($type))
        {
            return \token_name($type);
        }
        else
        {
            return '"' . $type . '"';
        }
    }
}
