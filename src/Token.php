<?php

namespace Phi;

use Phi\Util\Console;
use ReflectionClass;

class Token extends Node
{
    // these tokens can be used as a name in some places TODO describe & test where
    public const SPECIAL_CLASSES = ['self', 'parent', 'static'];

    public const CASTS = [
        self::PH_T_ARRAY_CAST,
        self::PH_T_BOOL_CAST,
        self::PH_T_DOUBLE_CAST,
        self::PH_T_INT_CAST,
        self::PH_T_OBJECT_CAST,
        self::PH_T_STRING_CAST,
        self::PH_T_UNSET_CAST,
    ];

    /** @deprecated */
    public const EOF = 999;
    const MAGIC_CONSTANTS = [self::PH_T_DIR, self::PH_T_FILE, self::PH_T_LINE, self::PH_T_FUNC_C, self::PH_T_CLASS_C, self::PH_T_METHOD_C];
    const COMBINED_ASSIGNMENT = [
        self::PH_T_PLUS_EQUAL, self::PH_T_MINUS_EQUAL, self::PH_T_MUL_EQUAL, self::PH_T_POW_EQUAL, self::PH_T_DIV_EQUAL, self::PH_T_CONCAT_EQUAL, self::PH_T_MOD_EQUAL,
        self::PH_T_AND_EQUAL, self::PH_T_OR_EQUAL, self::PH_T_XOR_EQUAL, self::PH_T_SL_EQUAL, self::PH_T_SR_EQUAL,
    ];
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
    private $leftWhitespace = '';
    /** @var string */
    private $rightWhitespace = '';

    public function __construct(
        int $type,
        string $source,
        int $line = null,
        int $column = null,
        string $filename = null,
        string $leftWhitespace = ''
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
        throw new \RuntimeException($childToDetach . ' is not attached to ' . $this->repr());
    }

    public function childNodes(): array
    {
        return [];
    }

    public function tokens(): iterable
    {
        return [$this];
    }

    protected function _validate(int $flags): void
    {
    }

    public function repr(): string
    {
        return 'Token<' . self::typeToString($this->type) . '>';
    }

    public function toPhp(): string
    {
        return $this->leftWhitespace . $this->source . $this->rightWhitespace;
    }

    public function debugDump(string $indent = ''): void
    {
        $source = str_replace("\n", "\\n", \var_export($this->source, true));
        echo $indent . self::typeToString($this->type) . ": " . Console::yellow($source) . "\n";
    }

    public function getType()
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

    public static function typeToString(int $type): string
    {
        return \array_search($type, self::getMap());
    }

    // TODO decide on a prefix here
    // TODO write generation script in meta
    public const PH_S_EXCLAMATION_MARK = 100;
    public const PH_S_DOUBLE_QUOTE = 101;
    public const PH_S_DOLLAR = 102;
    public const PH_S_MODULO = 103;
    public const PH_S_AMPERSAND = 104;
    public const PH_S_LEFT_PAREN = 105;
    public const PH_S_RIGHT_PAREN = 106;
    public const PH_S_ASTERISK = 107;
    public const PH_S_PLUS = 108;
    public const PH_S_COMMA = 109;
    public const PH_S_MINUS = 110;
    public const PH_S_DOT = 111;
    public const PH_S_FORWARD_SLASH = 112;
    public const PH_S_COLON = 113;
    public const PH_S_SEMICOLON = 114;
    public const PH_S_LT = 115;
    public const PH_S_EQUALS = 116;
    public const PH_S_GT = 117;
    public const PH_S_QUESTION_MARK = 118;
    public const PH_S_AT = 119;
    public const PH_S_LEFT_SQUARE_BRACKET = 120;
    public const PH_S_RIGHT_SQUARE_BRACKET = 121;
    public const PH_S_CARAT = 122;
    public const PH_S_BACKTICK = 123;
    public const PH_S_LEFT_CURLY_BRACE = 124;
    public const PH_S_VERTICAL_BAR = 125;
    public const PH_S_RIGHT_CURLY_BRACE = 126;
    public const PH_S_TILDE = 127;
    public const PH_T_ABSTRACT = 128;
    public const PH_T_AND_EQUAL = 129;
    public const PH_T_ARRAY = 130;
    public const PH_T_ARRAY_CAST = 131;
    public const PH_T_AS = 132;
    public const PH_T_BOOLEAN_AND = 133;
    public const PH_T_BOOLEAN_OR = 134;
    public const PH_T_BOOL_CAST = 135;
    public const PH_T_BREAK = 136;
    public const PH_T_CALLABLE = 137;
    public const PH_T_CASE = 138;
    public const PH_T_CATCH = 139;
    public const PH_T_CLASS = 140;
    public const PH_T_CLASS_C = 141;
    public const PH_T_CLONE = 142;
    public const PH_T_CLOSE_TAG = 143;
    public const PH_T_COALESCE = 144;
    public const PH_T_COMMENT = 145;
    public const PH_T_CONCAT_EQUAL = 146;
    public const PH_T_CONST = 147;
    public const PH_T_CONSTANT_ENCAPSED_STRING = 148;
    public const PH_T_CONTINUE = 149;
    public const PH_T_CURLY_OPEN = 150; // within interpolated strings this is the php type instead of '{'; TODO hide this behavior?
    public const PH_T_DEC = 151;
    public const PH_T_DECLARE = 152;
    public const PH_T_DEFAULT = 153;
    public const PH_T_DIR = 154;
    public const PH_T_DIV_EQUAL = 155;
    public const PH_T_DNUMBER = 156;
    public const PH_T_DO = 157;
    public const PH_T_DOC_COMMENT = 158;
    public const PH_T_DOLLAR_OPEN_CURLY_BRACES = 159;
    public const PH_T_DOUBLE_ARROW = 160;
    public const PH_T_DOUBLE_CAST = 161;
    public const PH_T_DOUBLE_COLON = 162;
    public const PH_T_ECHO = 163;
    public const PH_T_ELLIPSIS = 164;
    public const PH_T_ELSE = 165;
    public const PH_T_ELSEIF = 166;
    public const PH_T_EMPTY = 167;
    public const PH_T_ENCAPSED_AND_WHITESPACE = 168;
    public const PH_T_ENDDECLARE = 169;
    public const PH_T_ENDFOR = 170;
    public const PH_T_ENDFOREACH = 171;
    public const PH_T_ENDIF = 172;
    public const PH_T_ENDSWITCH = 173;
    public const PH_T_ENDWHILE = 174;
    public const PH_T_END_HEREDOC = 175;
    public const PH_T_EVAL = 176;
    public const PH_T_EXIT = 177;
    public const PH_T_EXTENDS = 178;
    public const PH_T_FILE = 179;
    public const PH_T_FINAL = 180;
    public const PH_T_FINALLY = 181;
    public const PH_T_FOR = 182;
    public const PH_T_FOREACH = 183;
    public const PH_T_FUNCTION = 184;
    public const PH_T_FUNC_C = 185;
    public const PH_T_GLOBAL = 186;
    public const PH_T_GOTO = 187;
    public const PH_T_HALT_COMPILER = 188;
    public const PH_T_IF = 189;
    public const PH_T_IMPLEMENTS = 190;
    public const PH_T_INC = 191;
    public const PH_T_INCLUDE = 192;
    public const PH_T_INCLUDE_ONCE = 193;
    public const PH_T_INLINE_HTML = 194;
    public const PH_T_INSTANCEOF = 195;
    public const PH_T_INSTEADOF = 196;
    public const PH_T_INTERFACE = 197;
    public const PH_T_INT_CAST = 198;
    public const PH_T_ISSET = 199;
    public const PH_T_IS_EQUAL = 200;
    public const PH_T_IS_GREATER_OR_EQUAL = 201;
    public const PH_T_IS_IDENTICAL = 202;
    public const PH_T_IS_NOT_EQUAL = 203;
    public const PH_T_IS_NOT_IDENTICAL = 204;
    public const PH_T_IS_SMALLER_OR_EQUAL = 205;
    public const PH_T_LINE = 206;
    public const PH_T_LIST = 207;
    public const PH_T_LNUMBER = 208;
    public const PH_T_LOGICAL_AND = 209;
    public const PH_T_LOGICAL_OR = 210;
    public const PH_T_LOGICAL_XOR = 211;
    public const PH_T_METHOD_C = 212;
    public const PH_T_MINUS_EQUAL = 213;
    public const PH_T_MOD_EQUAL = 214;
    public const PH_T_MUL_EQUAL = 215;
    public const PH_T_NAMESPACE = 216;
    public const PH_T_NEW = 217;
    public const PH_T_NS_C = 218;
    public const PH_T_NS_SEPARATOR = 219;
    public const PH_T_NUM_STRING = 220;
    public const PH_T_OBJECT_CAST = 221;
    public const PH_T_OBJECT_OPERATOR = 222;
    public const PH_T_OPEN_TAG = 223;
    public const PH_T_OPEN_TAG_WITH_ECHO = 224;
    public const PH_T_OR_EQUAL = 225;
    public const PH_T_PLUS_EQUAL = 226;
    public const PH_T_POW = 227;
    public const PH_T_POW_EQUAL = 228;
    public const PH_T_PRINT = 229;
    public const PH_T_PRIVATE = 230;
    public const PH_T_PROTECTED = 231;
    public const PH_T_PUBLIC = 232;
    public const PH_T_REQUIRE = 233;
    public const PH_T_REQUIRE_ONCE = 234;
    public const PH_T_RETURN = 235;
    public const PH_T_SL = 236;
    public const PH_T_SL_EQUAL = 237;
    public const PH_T_SPACESHIP = 238;
    public const PH_T_SR = 239;
    public const PH_T_SR_EQUAL = 240;
    public const PH_T_START_HEREDOC = 241;
    public const PH_T_STATIC = 242;
    public const PH_T_STRING = 243;
    public const PH_T_STRING_CAST = 244;
    public const PH_T_STRING_VARNAME = 245;
    public const PH_T_SWITCH = 246;
    public const PH_T_THROW = 247;
    public const PH_T_TRAIT = 248;
    public const PH_T_TRAIT_C = 249;
    public const PH_T_TRY = 250;
    public const PH_T_UNSET = 251;
    public const PH_T_UNSET_CAST = 252;
    public const PH_T_USE = 253;
    public const PH_T_VAR = 254;
    public const PH_T_VARIABLE = 255;
    public const PH_T_WHILE = 256;
    public const PH_T_WHITESPACE = 257;
    public const PH_T_XOR_EQUAL = 258;
    public const PH_T_YIELD = 259;
    public const PH_T_YIELD_FROM = 260;

    public const PH_T_EOF = 999;

    public static function getMap(): array
    {
        return \array_filter(
            (new ReflectionClass(__CLASS__))->getConstants(),
            function ($k) { return substr($k, 0, 3) === 'PH_'; },
            \ARRAY_FILTER_USE_KEY
        );
    }

    public static function getPhpTypeMap(): array
    {
        static $map;
        $map = $map ?? array_filter([
            '!' => self::PH_S_EXCLAMATION_MARK,
            '"' => self::PH_S_DOUBLE_QUOTE,
            '$' => self::PH_S_DOLLAR,
            '%' => self::PH_S_MODULO,
            '&' => self::PH_S_AMPERSAND,
            '(' => self::PH_S_LEFT_PAREN,
            ')' => self::PH_S_RIGHT_PAREN,
            '*' => self::PH_S_ASTERISK,
            '+' => self::PH_S_PLUS,
            ',' => self::PH_S_COMMA,
            '-' => self::PH_S_MINUS,
            '.' => self::PH_S_DOT,
            '/' => self::PH_S_FORWARD_SLASH,
            ':' => self::PH_S_COLON,
            ';' => self::PH_S_SEMICOLON,
            '<' => self::PH_S_LT,
            '=' => self::PH_S_EQUALS,
            '>' => self::PH_S_GT,
            '?' => self::PH_S_QUESTION_MARK,
            '@' => self::PH_S_AT,
            '[' => self::PH_S_LEFT_SQUARE_BRACKET,
            ']' => self::PH_S_RIGHT_SQUARE_BRACKET,
            '^' => self::PH_S_CARAT,
            '`' => self::PH_S_BACKTICK,
            '{' => self::PH_S_LEFT_CURLY_BRACE,
            '|' => self::PH_S_VERTICAL_BAR,
            '}' => self::PH_S_RIGHT_CURLY_BRACE,
            '~' => self::PH_S_TILDE,
            \T_ABSTRACT => self::PH_T_ABSTRACT,
            \T_AND_EQUAL => self::PH_T_AND_EQUAL,
            \T_ARRAY => self::PH_T_ARRAY,
            \T_ARRAY_CAST => self::PH_T_ARRAY_CAST,
            \T_AS => self::PH_T_AS,
            \T_BOOLEAN_AND => self::PH_T_BOOLEAN_AND,
            \T_BOOLEAN_OR => self::PH_T_BOOLEAN_OR,
            \T_BOOL_CAST => self::PH_T_BOOL_CAST,
            \T_BREAK => self::PH_T_BREAK,
            \T_CALLABLE => self::PH_T_CALLABLE,
            \T_CASE => self::PH_T_CASE,
            \T_CATCH => self::PH_T_CATCH,
            \T_CLASS => self::PH_T_CLASS,
            \T_CLASS_C => self::PH_T_CLASS_C,
            \T_CLONE => self::PH_T_CLONE,
            \T_CLOSE_TAG => self::PH_T_CLOSE_TAG,
            \T_COALESCE => self::PH_T_COALESCE,
            \T_COMMENT => self::PH_T_COMMENT,
            \T_CONCAT_EQUAL => self::PH_T_CONCAT_EQUAL,
            \T_CONST => self::PH_T_CONST,
            \T_CONSTANT_ENCAPSED_STRING => self::PH_T_CONSTANT_ENCAPSED_STRING,
            \T_CONTINUE => self::PH_T_CONTINUE,
            \T_CURLY_OPEN => self::PH_T_CURLY_OPEN,
            \T_DEC => self::PH_T_DEC,
            \T_DECLARE => self::PH_T_DECLARE,
            \T_DEFAULT => self::PH_T_DEFAULT,
            \T_DIR => self::PH_T_DIR,
            \T_DIV_EQUAL => self::PH_T_DIV_EQUAL,
            \T_DNUMBER => self::PH_T_DNUMBER,
            \T_DO => self::PH_T_DO,
            \T_DOC_COMMENT => self::PH_T_DOC_COMMENT,
            \T_DOLLAR_OPEN_CURLY_BRACES => self::PH_T_DOLLAR_OPEN_CURLY_BRACES,
            \T_DOUBLE_ARROW => self::PH_T_DOUBLE_ARROW,
            \T_DOUBLE_CAST => self::PH_T_DOUBLE_CAST,
            \T_DOUBLE_COLON => self::PH_T_DOUBLE_COLON,
            \T_ECHO => self::PH_T_ECHO,
            \T_ELLIPSIS => self::PH_T_ELLIPSIS,
            \T_ELSE => self::PH_T_ELSE,
            \T_ELSEIF => self::PH_T_ELSEIF,
            \T_EMPTY => self::PH_T_EMPTY,
            \T_ENCAPSED_AND_WHITESPACE => self::PH_T_ENCAPSED_AND_WHITESPACE,
            \T_ENDDECLARE => self::PH_T_ENDDECLARE,
            \T_ENDFOR => self::PH_T_ENDFOR,
            \T_ENDFOREACH => self::PH_T_ENDFOREACH,
            \T_ENDIF => self::PH_T_ENDIF,
            \T_ENDSWITCH => self::PH_T_ENDSWITCH,
            \T_ENDWHILE => self::PH_T_ENDWHILE,
            \T_END_HEREDOC => self::PH_T_END_HEREDOC,
            \T_EVAL => self::PH_T_EVAL,
            \T_EXIT => self::PH_T_EXIT,
            \T_EXTENDS => self::PH_T_EXTENDS,
            \T_FILE => self::PH_T_FILE,
            \T_FINAL => self::PH_T_FINAL,
            \T_FINALLY => self::PH_T_FINALLY,
            \T_FOR => self::PH_T_FOR,
            \T_FOREACH => self::PH_T_FOREACH,
            \T_FUNCTION => self::PH_T_FUNCTION,
            \T_FUNC_C => self::PH_T_FUNC_C,
            \T_GLOBAL => self::PH_T_GLOBAL,
            \T_GOTO => self::PH_T_GOTO,
            \T_HALT_COMPILER => self::PH_T_HALT_COMPILER,
            \T_IF => self::PH_T_IF,
            \T_IMPLEMENTS => self::PH_T_IMPLEMENTS,
            \T_INC => self::PH_T_INC,
            \T_INCLUDE => self::PH_T_INCLUDE,
            \T_INCLUDE_ONCE => self::PH_T_INCLUDE_ONCE,
            \T_INLINE_HTML => self::PH_T_INLINE_HTML,
            \T_INSTANCEOF => self::PH_T_INSTANCEOF,
            \T_INSTEADOF => self::PH_T_INSTEADOF,
            \T_INTERFACE => self::PH_T_INTERFACE,
            \T_INT_CAST => self::PH_T_INT_CAST,
            \T_ISSET => self::PH_T_ISSET,
            \T_IS_EQUAL => self::PH_T_IS_EQUAL,
            \T_IS_GREATER_OR_EQUAL => self::PH_T_IS_GREATER_OR_EQUAL,
            \T_IS_IDENTICAL => self::PH_T_IS_IDENTICAL,
            \T_IS_NOT_EQUAL => self::PH_T_IS_NOT_EQUAL,
            \T_IS_NOT_IDENTICAL => self::PH_T_IS_NOT_IDENTICAL,
            \T_IS_SMALLER_OR_EQUAL => self::PH_T_IS_SMALLER_OR_EQUAL,
            \T_LINE => self::PH_T_LINE,
            \T_LIST => self::PH_T_LIST,
            \T_LNUMBER => self::PH_T_LNUMBER,
            \T_LOGICAL_AND => self::PH_T_LOGICAL_AND,
            \T_LOGICAL_OR => self::PH_T_LOGICAL_OR,
            \T_LOGICAL_XOR => self::PH_T_LOGICAL_XOR,
            \T_METHOD_C => self::PH_T_METHOD_C,
            \T_MINUS_EQUAL => self::PH_T_MINUS_EQUAL,
            \T_MOD_EQUAL => self::PH_T_MOD_EQUAL,
            \T_MUL_EQUAL => self::PH_T_MUL_EQUAL,
            \T_NAMESPACE => self::PH_T_NAMESPACE,
            \T_NEW => self::PH_T_NEW,
            \T_NS_C => self::PH_T_NS_C,
            \T_NS_SEPARATOR => self::PH_T_NS_SEPARATOR,
            \T_NUM_STRING => self::PH_T_NUM_STRING,
            \T_OBJECT_CAST => self::PH_T_OBJECT_CAST,
            \T_OBJECT_OPERATOR => self::PH_T_OBJECT_OPERATOR,
            \T_OPEN_TAG => self::PH_T_OPEN_TAG,
            \T_OPEN_TAG_WITH_ECHO => self::PH_T_OPEN_TAG_WITH_ECHO,
            \T_OR_EQUAL => self::PH_T_OR_EQUAL,
            \T_PLUS_EQUAL => self::PH_T_PLUS_EQUAL,
            \T_POW => self::PH_T_POW,
            \T_POW_EQUAL => self::PH_T_POW_EQUAL,
            \T_PRINT => self::PH_T_PRINT,
            \T_PRIVATE => self::PH_T_PRIVATE,
            \T_PROTECTED => self::PH_T_PROTECTED,
            \T_PUBLIC => self::PH_T_PUBLIC,
            \T_REQUIRE => self::PH_T_REQUIRE,
            \T_REQUIRE_ONCE => self::PH_T_REQUIRE_ONCE,
            \T_RETURN => self::PH_T_RETURN,
            \T_SL => self::PH_T_SL,
            \T_SL_EQUAL => self::PH_T_SL_EQUAL,
            \T_SPACESHIP => self::PH_T_SPACESHIP,
            \T_SR => self::PH_T_SR,
            \T_SR_EQUAL => self::PH_T_SR_EQUAL,
            \T_START_HEREDOC => self::PH_T_START_HEREDOC,
            \T_STATIC => self::PH_T_STATIC,
            \T_STRING => self::PH_T_STRING,
            \T_STRING_CAST => self::PH_T_STRING_CAST,
            \T_STRING_VARNAME => self::PH_T_STRING_VARNAME,
            \T_SWITCH => self::PH_T_SWITCH,
            \T_THROW => self::PH_T_THROW,
            \T_TRAIT => self::PH_T_TRAIT,
            \T_TRAIT_C => self::PH_T_TRAIT_C,
            \T_TRY => self::PH_T_TRY,
            \T_UNSET => self::PH_T_UNSET,
            \T_UNSET_CAST => self::PH_T_UNSET_CAST,
            \T_USE => self::PH_T_USE,
            \T_VAR => self::PH_T_VAR,
            \T_VARIABLE => self::PH_T_VARIABLE,
            \T_WHILE => self::PH_T_WHILE,
            \T_WHITESPACE => self::PH_T_WHITESPACE,
            \T_XOR_EQUAL => self::PH_T_XOR_EQUAL,
            \T_YIELD => self::PH_T_YIELD,
            \T_YIELD_FROM => self::PH_T_YIELD_FROM,
            // TODO add tokens from other versions?
            // wrap constants that aren't always defined in @\constant('T_FOOBAR'),
        ]);
        return $map;
    }
}
