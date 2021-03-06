<?php

declare(strict_types=1);

namespace Phi;

use ReflectionClass;

abstract class TokenType
{
	public const S_EXCLAMATION_MARK = 100;
	public const S_DOUBLE_QUOTE = 101;
	public const S_DOLLAR = 102;
	public const S_MODULO = 103;
	public const S_AMPERSAND = 104;
	public const S_LEFT_PARENTHESIS = 105;
	public const S_RIGHT_PARENTHESIS = 106;
	public const S_ASTERISK = 107;
	public const S_PLUS = 108;
	public const S_COMMA = 109;
	public const S_MINUS = 110;
	public const S_DOT = 111;
	public const S_FORWARD_SLASH = 112;
	public const S_COLON = 113;
	public const S_SEMICOLON = 114;
	public const S_LT = 115;
	public const S_EQUALS = 116;
	public const S_GT = 117;
	public const S_QUESTION_MARK = 118;
	public const S_AT = 119;
	public const S_LEFT_SQUARE_BRACKET = 120;
	public const S_RIGHT_SQUARE_BRACKET = 121;
	public const S_CARET = 122;
	public const S_BACKTICK = 123;
	public const S_LEFT_CURLY_BRACE = 124;
	public const S_VERTICAL_BAR = 125;
	public const S_RIGHT_CURLY_BRACE = 126;
	public const S_TILDE = 127;
	public const T_ABSTRACT = 128;
	public const T_AND_EQUAL = 129;
	public const T_ARRAY = 130;
	public const T_ARRAY_CAST = 131;
	public const T_AS = 132;
	public const T_BOOLEAN_AND = 133;
	public const T_BOOLEAN_OR = 134;
	public const T_BOOL_CAST = 135;
	public const T_BREAK = 136;
	public const T_CALLABLE = 137;
	public const T_CASE = 138;
	public const T_CATCH = 139;
	public const T_CLASS = 140;
	public const T_CLASS_C = 141;
	public const T_CLONE = 142;
	public const T_CLOSE_TAG = 143;
	public const T_COALESCE = 144;
	public const T_COALESCE_EQUAL = 145;
	public const T_COMMENT = 146;
	public const T_CONCAT_EQUAL = 147;
	public const T_CONST = 148;
	public const T_CONSTANT_ENCAPSED_STRING = 149;
	public const T_CONTINUE = 150;
	/**
	 * Not used
	 * @see TokenType::S_LEFT_CURLY_BRACE
	 */
	public const T_CURLY_OPEN = 151;
	public const T_DEC = 152;
	public const T_DECLARE = 153;
	public const T_DEFAULT = 154;
	public const T_DIR = 155;
	public const T_DIV_EQUAL = 156;
	public const T_DNUMBER = 157;
	public const T_DO = 158;
	public const T_DOC_COMMENT = 159;
	public const T_DOLLAR_OPEN_CURLY_BRACES = 160;
	public const T_DOUBLE_ARROW = 161;
	public const T_DOUBLE_CAST = 162;
	public const T_DOUBLE_COLON = 163;
	public const T_ECHO = 164;
	public const T_ELLIPSIS = 165;
	public const T_ELSE = 166;
	public const T_ELSEIF = 167;
	public const T_EMPTY = 168;
	public const T_ENCAPSED_AND_WHITESPACE = 169;
	public const T_ENDDECLARE = 170;
	public const T_ENDFOR = 171;
	public const T_ENDFOREACH = 172;
	public const T_ENDIF = 173;
	public const T_ENDSWITCH = 174;
	public const T_ENDWHILE = 175;
	public const T_END_HEREDOC = 176;
	public const T_EVAL = 177;
	public const T_EXIT = 178;
	public const T_EXTENDS = 179;
	public const T_FILE = 180;
	public const T_FINAL = 181;
	public const T_FINALLY = 182;
	public const T_FN = 183;
	public const T_FOR = 184;
	public const T_FOREACH = 185;
	public const T_FUNCTION = 186;
	public const T_FUNC_C = 187;
	public const T_GLOBAL = 188;
	public const T_GOTO = 189;
	public const T_HALT_COMPILER = 190;
	public const T_IF = 191;
	public const T_IMPLEMENTS = 192;
	public const T_INC = 193;
	public const T_INCLUDE = 194;
	public const T_INCLUDE_ONCE = 195;
	public const T_INLINE_HTML = 196;
	public const T_INSTANCEOF = 197;
	public const T_INSTEADOF = 198;
	public const T_INTERFACE = 199;
	public const T_INT_CAST = 200;
	public const T_ISSET = 201;
	public const T_IS_EQUAL = 202;
	public const T_IS_GREATER_OR_EQUAL = 203;
	public const T_IS_IDENTICAL = 204;
	public const T_IS_NOT_EQUAL = 205;
	public const T_IS_NOT_IDENTICAL = 206;
	public const T_IS_SMALLER_OR_EQUAL = 207;
	public const T_LINE = 208;
	public const T_LIST = 209;
	public const T_LNUMBER = 210;
	public const T_LOGICAL_AND = 211;
	public const T_LOGICAL_OR = 212;
	public const T_LOGICAL_XOR = 213;
	public const T_METHOD_C = 214;
	public const T_MINUS_EQUAL = 215;
	public const T_MOD_EQUAL = 216;
	public const T_MUL_EQUAL = 217;
	public const T_NAMESPACE = 218;
	public const T_NEW = 219;
	public const T_NS_C = 220;
	public const T_NS_SEPARATOR = 221;
	public const T_NUM_STRING = 222;
	public const T_OBJECT_CAST = 223;
	public const T_OBJECT_OPERATOR = 224;
	public const T_OPEN_TAG = 225;
	public const T_OPEN_TAG_WITH_ECHO = 226;
	public const T_OR_EQUAL = 227;
	public const T_PLUS_EQUAL = 228;
	public const T_POW = 229;
	public const T_POW_EQUAL = 230;
	public const T_PRINT = 231;
	public const T_PRIVATE = 232;
	public const T_PROTECTED = 233;
	public const T_PUBLIC = 234;
	public const T_REQUIRE = 235;
	public const T_REQUIRE_ONCE = 236;
	public const T_RETURN = 237;
	public const T_SL = 238;
	public const T_SL_EQUAL = 239;
	public const T_SPACESHIP = 240;
	public const T_SR = 241;
	public const T_SR_EQUAL = 242;
	public const T_START_HEREDOC = 243;
	public const T_STATIC = 244;
	public const T_STRING = 245;
	public const T_STRING_CAST = 246;
	public const T_STRING_VARNAME = 247;
	public const T_SWITCH = 248;
	public const T_THROW = 249;
	public const T_TRAIT = 250;
	public const T_TRAIT_C = 251;
	public const T_TRY = 252;
	public const T_UNSET = 253;
	public const T_UNSET_CAST = 254;
	public const T_USE = 255;
	public const T_VAR = 256;
	public const T_VARIABLE = 257;
	public const T_WHILE = 258;
	public const T_WHITESPACE = 259;
	public const T_XOR_EQUAL = 260;
	public const T_YIELD = 261;
	public const T_YIELD_FROM = 262;

	public const T_EOF = 999;

	/** @return int[] */
	public static function getAll(): array
	{
		return \array_values(self::getMap());
	}

	/** @return array<string, int> */
	private static function getMap(): array
	{
		static $map;
		$map = $map ?? \array_filter(
			(new ReflectionClass(__CLASS__))->getConstants(),
			'is_int'
		);
		return $map;
	}

	public static function typeToString(int $type): string
	{
		$name = \array_search($type, self::getMap(), true);
		assert($name !== false);
		return $name;
	}

	private const NEEDS_WHITESPACE = [
		[self::S_DOLLAR, self::T_STRING],
		[self::S_DOT, self::T_DNUMBER],
		[self::S_DOT, self::T_LNUMBER],
		[self::S_FORWARD_SLASH, self::S_ASTERISK],
		[self::S_FORWARD_SLASH, self::S_FORWARD_SLASH],
		[self::S_FORWARD_SLASH, self::T_DIV_EQUAL],
		[self::S_FORWARD_SLASH, self::T_MUL_EQUAL],
		[self::S_FORWARD_SLASH, self::T_POW],
		[self::S_FORWARD_SLASH, self::T_POW_EQUAL],
		[self::S_LT, self::S_GT],
		[self::S_LT, self::T_IS_GREATER_OR_EQUAL],
		[self::S_LT, self::T_SR],
		[self::S_LT, self::T_SR_EQUAL],
		[self::T_DNUMBER, self::T_DNUMBER],
		[self::T_DNUMBER, self::T_LNUMBER],
		[self::T_LNUMBER, self::T_DNUMBER],
		[self::T_LNUMBER, self::T_ELLIPSIS],
		[self::T_LNUMBER, self::T_LNUMBER],
		[self::T_STRING, self::T_DNUMBER],
		[self::T_STRING, self::T_LNUMBER],
		[self::T_STRING, self::T_STRING],
		[self::T_VARIABLE, self::T_DNUMBER],
		[self::T_VARIABLE, self::T_LNUMBER],
		[self::T_VARIABLE, self::T_STRING],
	];

	/**
	 * Determines if two tokens need some whitespace in between them in order to be parsed correctly.
	 * E.g. in `function foo() {}` whitespace is required between `function` and `foo`.
	 */
	public static function requireSeparatingWhitespace(Token $t1, Token $t2): bool
	{
		$type1 = $t1->getType();
		$src1 = $t1->getSource();
		$type2 = $t2->getType();
		$src2 = $t2->getSource();

		// edge case: `${` is only lexed inside interpolated strings, no whitespace needed
		if ($type1 === self::S_DOLLAR && $type2 === self::S_LEFT_CURLY_BRACE)
		{
			return false;
		}

		// check if part of the second token can turn the first one into a different token
		for ($i = 1; $i <= \strlen($src2); $i++)
		{
			if (\in_array($src1 . \substr($src2, 0, $i), self::AUTOCORRECT, true))
			{
				return true;
			}
		}

		// 0x0.0 (i.e. `0x0` `.0`) does not require whitespace
		if ($type1 === self::T_LNUMBER && ($type2 === self::S_DOT || $type2 === self::T_CONCAT_EQUAL))
		{
			return !\preg_match('{^0[xb]}i', $src1);
		}

		// treat wordlike tokens as if they were T_STRING
		// TODO extend regex with unicode?
		if ($src1 && \preg_match('{[A-Za-z_]$}D', $src1))
		{
			$type1 = self::T_STRING;
		}
		if ($src2 && \preg_match('{^[A-Za-z_]}', $src2))
		{
			$type2 = self::T_STRING;
		}

		return \in_array([$type1, $type2], self::NEEDS_WHITESPACE, true);
	}

	public const MAGIC_CONSTANTS = [
		self::T_DIR, self::T_FILE, self::T_LINE,
		self::T_FUNC_C, self::T_CLASS_C, self::T_METHOD_C,
		self::T_TRAIT_C, self::T_NS_C,
	];

	public const STRINGY_KEYWORDS = [
		self::T_ABSTRACT,
		self::T_ARRAY,
		self::T_AS,
		self::T_BREAK,
		self::T_CALLABLE,
		self::T_CASE,
		self::T_CATCH,
		self::T_CLASS,
		self::T_CLASS_C,
		self::T_CLONE,
		self::T_CONST,
		self::T_CONTINUE,
		self::T_DECLARE,
		self::T_DEFAULT,
		self::T_DIR,
		self::T_DO,
		self::T_ECHO,
		self::T_ELSE,
		self::T_ELSEIF,
		self::T_EMPTY,
		self::T_ENDDECLARE,
		self::T_ENDFOR,
		self::T_ENDFOREACH,
		self::T_ENDIF,
		self::T_ENDSWITCH,
		self::T_ENDWHILE,
		self::T_EVAL,
		self::T_EXIT,
		self::T_EXTENDS,
		self::T_FILE,
		self::T_FINAL,
		self::T_FINALLY,
		self::T_FN,
		self::T_FOR,
		self::T_FOREACH,
		self::T_FUNC_C,
		self::T_FUNCTION,
		self::T_GLOBAL,
		self::T_GOTO,
		self::T_IF,
		self::T_IMPLEMENTS,
		self::T_INCLUDE,
		self::T_INCLUDE_ONCE,
		self::T_INSTANCEOF,
		self::T_INSTEADOF,
		self::T_INTERFACE,
		self::T_ISSET,
		self::T_LINE,
		self::T_LIST,
		self::T_LOGICAL_AND,
		self::T_LOGICAL_OR,
		self::T_LOGICAL_XOR,
		self::T_METHOD_C,
		self::T_NAMESPACE,
		self::T_NEW,
		self::T_NS_C,
		self::T_PRINT,
		self::T_PRIVATE,
		self::T_PROTECTED,
		self::T_PUBLIC,
		self::T_REQUIRE,
		self::T_REQUIRE_ONCE,
		self::T_RETURN,
		self::T_STATIC,
		self::T_SWITCH,
		self::T_THROW,
		self::T_TRAIT,
		self::T_TRAIT_C,
		self::T_TRY,
		self::T_UNSET,
		self::T_USE,
		self::T_VAR,
		self::T_WHILE,
		self::T_YIELD,
// TODO        self::T_HALT_COMPILER,
	];

	private const SPECIAL_CLASSES = [
		'parent' => true,
		'self' => true,
	];

	public static function isSpecialClass(Token $token): bool
	{
		return $token->getType() === self::T_STRING
			&& isset(self::SPECIAL_CLASSES[\strtolower($token->getSource())]);
	}

	private const SPECIAL_TYPES = [
		'bool' => true,
		'float' => true,
		'int' => true,
		'iterable' => true,
		'object' => true,
		'string' => true,
		'void' => true,
	];

	public static function isSpecialType(Token $token): bool
	{
		return $token->getType() === self::T_ARRAY
			|| $token->getType() === self::T_CALLABLE
			|| ($token->getType() === self::T_STRING && isset(self::SPECIAL_TYPES[\strtolower($token->getSource())]));
	}

	private const SPECIAL_CONST = [
		'false' => true,
		'null' => true,
		'true' => true,
	];

	public static function isSpecialConst(Token $token): bool
	{
		return $token->getType() === self::T_STRING
			&& isset(self::SPECIAL_CONST[\strtolower($token->getSource())]);
	}

	public static function isReservedWord(Token $token): bool
	{
		$token = \strtolower($token->getSource());
		return isset(self::SPECIAL_CLASSES[$token])
			|| isset(self::SPECIAL_TYPES[$token])
			|| isset(self::SPECIAL_CONST[$token]);
	}

	public const AUTOCORRECT = [
		self::S_AMPERSAND => '&',
		self::S_ASTERISK => '*',
		self::S_AT => '@',
		self::S_BACKTICK => '`',
		self::S_CARET => '^',
		self::S_COLON => ':',
		self::S_COMMA => ',',
		self::S_DOLLAR => '$',
		self::S_DOT => '.',
		self::S_EQUALS => '=',
		self::S_EXCLAMATION_MARK => '!',
		self::S_FORWARD_SLASH => '/',
		self::S_GT => '>',
		self::S_LEFT_CURLY_BRACE => '{',
		self::S_LEFT_PARENTHESIS => '(',
		self::S_LEFT_SQUARE_BRACKET => '[',
		self::S_LT => '<',
		self::S_MINUS => '-',
		self::S_MODULO => '%',
		self::S_PLUS => '+',
		self::S_QUESTION_MARK => '?',
		self::S_RIGHT_CURLY_BRACE => '}',
		self::S_RIGHT_PARENTHESIS => ')',
		self::S_RIGHT_SQUARE_BRACKET => ']',
		self::S_SEMICOLON => ';',
		self::S_TILDE => '~',
		self::S_VERTICAL_BAR => '|',
		self::T_ABSTRACT => 'abstract',
		self::T_AND_EQUAL => '&=',
		self::T_ARRAY => 'array',
		self::T_ARRAY_CAST => '(array)',
		self::T_AS => 'as',
		self::T_BOOLEAN_AND => '&&',
		self::T_BOOLEAN_OR => '||',
		self::T_BOOL_CAST => '(bool)',
		self::T_BREAK => 'break',
		self::T_CALLABLE => 'callable',
		self::T_CASE => 'case',
		self::T_CATCH => 'catch',
		self::T_CLASS => 'class',
		self::T_CLASS_C => '__CLASS__',
		self::T_CLONE => 'clone',
		self::T_CLOSE_TAG => '?>',
		self::T_COALESCE => '??',
		self::T_COALESCE_EQUAL => '??=',
		self::T_CONCAT_EQUAL => '.=',
		self::T_CONST => 'const',
		self::T_CONTINUE => 'continue',
		self::T_DEC => '--',
		self::T_DECLARE => 'declare',
		self::T_DEFAULT => 'default',
		self::T_DIR => '__DIR__',
		self::T_DIV_EQUAL => '/=',
		self::T_DO => 'do',
		self::T_DOLLAR_OPEN_CURLY_BRACES => '${',
		self::T_DOUBLE_ARROW => '=>',
		self::T_DOUBLE_CAST => '(float)',
		self::T_DOUBLE_COLON => '::',
		self::T_ECHO => 'echo',
		self::T_ELLIPSIS => '...',
		self::T_ELSE => 'else',
		self::T_ELSEIF => 'elseif',
		self::T_EMPTY => 'empty',
		self::T_ENDDECLARE => 'enddeclare',
		self::T_ENDFOR => 'endfor',
		self::T_ENDFOREACH => 'endforeach',
		self::T_ENDIF => 'endif',
		self::T_ENDSWITCH => 'endswitch',
		self::T_ENDWHILE => 'endwhile',
		self::T_EVAL => 'eval',
		self::T_EXIT => 'exit',
		self::T_EXTENDS => 'extends',
		self::T_FILE => '__FILE__',
		self::T_FINAL => 'final',
		self::T_FINALLY => 'finally',
		self::T_FN => 'fn',
		self::T_FOR => 'for',
		self::T_FOREACH => 'foreach',
		self::T_FUNCTION => 'function',
		self::T_FUNC_C => '__FUNCTION__',
		self::T_GLOBAL => 'global',
		self::T_GOTO => 'goto',
		self::T_HALT_COMPILER => '__halt_compiler',
		self::T_IF => 'if',
		self::T_IMPLEMENTS => 'implements',
		self::T_INC => '++',
		self::T_INCLUDE => 'include',
		self::T_INCLUDE_ONCE => 'include_once',
		self::T_INSTANCEOF => 'instanceof',
		self::T_INSTEADOF => 'insteadof',
		self::T_INTERFACE => 'interface',
		self::T_INT_CAST => '(int)',
		self::T_ISSET => 'isset',
		self::T_IS_EQUAL => '==',
		self::T_IS_GREATER_OR_EQUAL => '>=',
		self::T_IS_IDENTICAL => '===',
		self::T_IS_NOT_EQUAL => '!=',
		self::T_IS_NOT_IDENTICAL => '!==',
		self::T_IS_SMALLER_OR_EQUAL => '<=',
		self::T_LINE => '__LINE__',
		self::T_LIST => 'list',
		self::T_LOGICAL_AND => 'and',
		self::T_LOGICAL_OR => 'or',
		self::T_LOGICAL_XOR => 'xor',
		self::T_METHOD_C => '__METHOD__',
		self::T_MINUS_EQUAL => '-=',
		self::T_MOD_EQUAL => '%=',
		self::T_MUL_EQUAL => '*=',
		self::T_NAMESPACE => 'namespace',
		self::T_NEW => 'new',
		self::T_NS_C => '__NAMESPACE__',
		self::T_NS_SEPARATOR => '\\',
		self::T_OBJECT_CAST => '(object)',
		self::T_OBJECT_OPERATOR => '->',
		self::T_OPEN_TAG => '<?php ', // note a byte of whitespace is always part of the token
		self::T_OPEN_TAG_WITH_ECHO => '<?=',
		self::T_OR_EQUAL => '|=',
		self::T_PLUS_EQUAL => '+=',
		self::T_POW => '**',
		self::T_POW_EQUAL => '**=',
		self::T_PRINT => 'print',
		self::T_PRIVATE => 'private',
		self::T_PROTECTED => 'protected',
		self::T_PUBLIC => 'public',
		self::T_REQUIRE => 'require',
		self::T_REQUIRE_ONCE => 'require_once',
		self::T_RETURN => 'return',
		self::T_SL => '<<',
		self::T_SL_EQUAL => '<<=',
		self::T_SPACESHIP => '<=>',
		self::T_SR => '>>',
		self::T_SR_EQUAL => '>>=',
		self::T_STATIC => 'static',
		self::T_STRING_CAST => '(string)',
		self::T_SWITCH => 'switch',
		self::T_THROW => 'throw',
		self::T_TRAIT => 'trait',
		self::T_TRAIT_C => '__TRAIT__',
		self::T_TRY => 'try',
		self::T_UNSET => 'unset',
		self::T_UNSET_CAST => '(unset)',
		self::T_USE => 'use',
		self::T_VAR => 'var',
		self::T_WHILE => 'while',
		self::T_XOR_EQUAL => '^=',
		self::T_YIELD => 'yield',
		self::T_YIELD_FROM => 'yield from',
	];
}
