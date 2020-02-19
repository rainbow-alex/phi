<?php

declare(strict_types=1);

namespace Phi;

use Phi\Util\Util;

class Lexer
{
	/**
	 * @var int
	 * @see PhpVersion
	 */
	private $phpVersion;

	public function __construct(int $phpVersion)
	{
		PhpVersion::validate($phpVersion);
		$this->phpVersion = $phpVersion;
	}

	/**
	 * @return Token[]
	 */
	public function lex(?string $filename, string $source, bool $forcePhp = false): array
	{
		$hacks = [];
		if ($this->phpVersion < PhpVersion::PHP_7_3 && \PHP_VERSION_ID >= PhpVersion::PHP_7_3)
		{
			$hacks[] = self::emulateUnflexibleDocstrings($source);
		}
		else if ($this->phpVersion >= PhpVersion::PHP_7_3 && \PHP_VERSION_ID < PhpVersion::PHP_7_3)
		{
			$hacks[] = self::emulateFlexibleDocstrings($source);
		}

		if ($this->phpVersion < PhpVersion::PHP_7_4 && \PHP_VERSION_ID >= PhpVersion::PHP_7_4)
		{
			$hacks[] = self::emulateNumericLiteralSeparators($source, false);
			$hacks[] = self::emulateCoalesceEquals($source, false);
		}
		else if ($this->phpVersion >= PhpVersion::PHP_7_4 && \PHP_VERSION_ID < PhpVersion::PHP_7_4)
		{
			$hacks[] = self::emulateNumericLiteralSeparators($source, true);
			$hacks[] = self::emulateCoalesceEquals($source, true);
		}

		if ($forcePhp)
		{
			$phpTokens = @\token_get_all("<?php " . $source);
			\array_shift($phpTokens);
		}
		else
		{
			$phpTokens = @\token_get_all($source);
		}

		if ($hacks)
		{
			// it's important to revert the hacks in reverse, since each hack registers
			// the offsets without taking into account changes from previous hacks
			foreach (\array_reverse($hacks) as $changes)
			{
				self::undoChanges($changes, $phpTokens);
			}
		}

		/** @var Token[] $tokens */
		$tokens = [];

		$whitespace = "";
		$line = 1;
		$typeMap = self::getPhpTypeMap();
		foreach ($phpTokens as $phpToken)
		{
			if (\is_array($phpToken))
			{
				[$phpType, $source, $line] = $phpToken;

				if ($phpType === \T_WHITESPACE || $phpType === \T_COMMENT || $phpType === \T_DOC_COMMENT)
				{
					$whitespace .= $source;
					continue;
				}
				else if ($phpType === \T_CONSTANT_ENCAPSED_STRING  && $source[0] === '"')
				{
					$tokens[] = new Token(TokenType::S_DOUBLE_QUOTE, '"', $filename, $line, $whitespace);
					$whitespace = "";
					$tokens[] = new Token(TokenType::T_ENCAPSED_AND_WHITESPACE, \substr($source, 1, \strlen($source) - 2), $filename, $line);
					$line += \substr_count($source, "\n");
					$tokens[] = new Token(TokenType::S_DOUBLE_QUOTE, '"', $filename, $line);
					continue;
				}
				else if ($phpType === \T_CURLY_OPEN)
				{
					$phpType = '{';
				}
			}
			else
			{
				/** @var string $phpToken */
				$phpType = $source = $phpToken;
			}

			$tokens[] = new Token($typeMap[$phpType], $source, $filename, $line, $whitespace);
			$whitespace = "";
		}

		$tokens[] = new Token(TokenType::T_EOF, "", $filename, $line, $whitespace);

		if ($this->phpVersion < PhpVersion::PHP_7_4 && \PHP_VERSION_ID >= PhpVersion::PHP_7_4)
		{
			self::emulateNoFn($tokens);
		}
		else if ($this->phpVersion >= PhpVersion::PHP_7_4 && \PHP_VERSION_ID < PhpVersion::PHP_7_4)
		{
			self::emulateFnAndCoalesceEquals($tokens);
		}

		return $tokens;
	}

	/**
	 * @return Token[]
	 */
	public function lexFragment(string $source): array
	{
		return $this->lex(null, $source, true);
	}

	//
	// 1337 HaCkEr ZoNe!!!1
	//

	/**
	 * Indicates that a single char was inserted.
	 * e.g. [self::HACK_INSERT, 88] when a char was inserted at offset 88
	 */
	private const HACK_INSERT = 1;
	/**
	 * Indicates that a single char was deleted.
	 * e.g. [self::HACK_DELETE, 40, '='] when a '=' was deleted at offset 40
	 */
	private const HACK_DELETE = 2;

	/**
	 * Emulate support for numeric literal separators by either removing them,
	 * or emulate pre 7.4 lexing by inserting a space before each separator.
	 * E.g. 1_2 becomes 1 _2
	 *
	 * @return mixed[]
	 */
	private static function emulateNumericLiteralSeparators(string &$source, bool $supported): array
	{
		// match all literals
		\preg_match_all('{
			[^a-zA-Z0-9_\x80-\xff] # make sure we dont accidentally match part of an identifier
			(
				0(x)[0-9A-F_]+
				| 0b[01_]+
				| \.? [0-9_]+ ( e [0-9_]+ )?
			)
		}xi', $source, $matches, \PREG_OFFSET_CAPTURE|\PREG_SET_ORDER);

		$changes = [];
		foreach ($matches as $m)
		{
			// now match each individual separator
			// we don't want to match the e in float literals, or the b in binary numerals, so check for hex
			$pattern = (isset($m[2]) && $m[2][1] !== -1) ? '{[0-9A-F](_)(?=[0-9A-F])}i' : '{[0-9](_)(?=[0-9])}';
			\preg_match_all($pattern, $m[0][0], $underscoreMatches, \PREG_OFFSET_CAPTURE|\PREG_SET_ORDER);

			foreach ($underscoreMatches as $m2)
			{
				$offset = $m[0][1] + $m2[1][1];

				if ($supported)
				{
					$source = Util::stringSplice($source, $offset - \count($changes), 1);
					$changes[] = [self::HACK_DELETE, $offset, '_'];
				}
				else
				{
					$source = Util::stringSplice($source, $offset + \count($changes), 0, ' ');
					$changes[] = [self::HACK_INSERT, $offset];
				}
			}
		}

		return $changes;
	}

	/**
	 * Emulate (lack of) support for ??=.
	 *
	 * Unfortunately we can't just scan the tokens for ??= after lexing and fix it then.
	 * In some cases it would be pretty complex to correctly fix the subsequent tokens:
	 * e.g. `??=>` might lex as `??= >` and have to be fixed to `?? =>`
	 *
	 * @return mixed[]
	 */
	private static function emulateCoalesceEquals(string &$source, bool $supported): array
	{
		\preg_match_all('{\?\?(=)}', $source, $matches, \PREG_OFFSET_CAPTURE|\PREG_SET_ORDER);
		$changes = [];
		foreach ($matches as $m)
		{
			if ($supported)
			{
				// Take out the = so it gets lexed as T_COALESCE.
				// We can then check for each coalesce token if the unhacked source is actually ??= and fudge the type.
				/** @see emulateFnAndCoalesceEquals */
				$offset = $m[1][1];
				$source = Util::stringSplice($source, $offset - \count($changes), 1);
				$changes[] = [self::HACK_DELETE, $offset, '='];
			}
			else
			{
				// Break it into ?? and =
				$offset = $m[1][1];
				$source = Util::stringSplice($source, $offset + \count($changes), 0, ' ');
				$changes[] = [self::HACK_INSERT, $offset];
			}
		}
		return $changes;
	}

	/**
	 * @param Token[] $tokens
	 */
	private static function emulateNoFn(array $tokens): void
	{
		foreach ($tokens as $t)
		{
			// fn keyword didn't exist before 7.4
			if ($t->getType() === TokenType::T_FN)
			{
				$t->_fudgeType(TokenType::T_STRING);
			}
		}
	}

	/**
	 * @param Token[] $tokens
	 */
	private static function emulateFnAndCoalesceEquals(array $tokens): void
	{
		/** @var Token|null $previous */
		$previous = null;
		foreach ($tokens as $t)
		{
			// fn got lexed as T_STRING
			if (
				$t->getType() === TokenType::T_STRING && strcasecmp($t->getSource(), 'fn') === 0
				// after -> keywords should be lexed as T_STRING
				&& (!$previous || $previous->getType() !== TokenType::T_OBJECT_OPERATOR)
			)
			{
				$t->_fudgeType(TokenType::T_FN);
			}
			// we hid the '=' from token_get_all
			/** @see emulateCoalesceEquals */
			else if ($t->getType() === TokenType::T_COALESCE && $t->getSource()[-1] === '=')
			{
				$t->_fudgeType(TokenType::T_COALESCE_EQUAL);
			}
			$previous = $t;
		}
	}

	/**
	 * Emulate non-flexible here/nowdoc syntax by inserting an x before each flexible delimiter.
	 * @return mixed[]
	 */
	private static function emulateUnflexibleDocstrings(string &$source): array
	{
		// first match all here/nowdocs until their non-flexible delimiter (or the end of source)
		\preg_match_all('{
			<<<[ \t]*
			([\'"]?)
			([a-zA-Z_\x80-\xff][a-zA-Z0-9_\x80-\xff]*)
			\1
			\r?\n
			((?:.*\r?\n)*?)
			(
				(\2)
				;?\r?\n
				| $ # unterminated here/nowdoc TODO test coverage
			)
		}xD', $source, $matches, \PREG_OFFSET_CAPTURE|\PREG_SET_ORDER);

		$changes = [];

		foreach ($matches as $match)
		{
			// now match *each* 'flexible' delimiter within the string contents
			\preg_match_all($r = '{
				^
				\h*
				(' . \preg_quote($match[2][0]) . ')
			}xm', $match[3][0], $innerMatches, \PREG_OFFSET_CAPTURE|\PREG_SET_ORDER);

			foreach ($innerMatches as $innerMatch)
			{
				$offset = $match[3][1] + $innerMatch[1][1];
				$source = Util::stringSplice($source, $offset + \count($changes), 0, 'x');
				$changes[] = [self::HACK_INSERT, $offset];
			}
		}

		return $changes;
	}

	/**
	 * Emulate flexible docstrings by inserting newlines around flexible ending delimiters.
	 * @return mixed[]
	 */
	private static function emulateFlexibleDocstrings(string &$source): array
	{
		// first match all here/nowdocs until their first flexible delimiter
		\preg_match_all('{
			<<<[ \t]*
			([\'"]?)
			([a-zA-Z_\x80-\xff][a-zA-Z0-9_\x80-\xff]*)
			\1
			\r?\n
			(?:.*\r?\n)*?
			(?:\h*)
			(\2)
			(?![a-zA-Z0-9_\x80-\xff])
		}x', $source, $matches, \PREG_OFFSET_CAPTURE|\PREG_SET_ORDER);

		$changes = [];

		foreach ($matches as $match)
		{
			$start = $match[3][1];
			$source = Util::stringSplice($source, $start + \count($changes), 0, "\n");
			$changes[] = [self::HACK_INSERT, $start];

			$end = $match[3][1] + \strlen($match[3][0]);
			$source = Util::stringSplice($source, $end + \count($changes), 0, "\n");
			$changes[] = [self::HACK_INSERT, $end];
		}

		return $changes;
	}

	/**
	 * @param mixed[] $changes
	 * @param array<array|string> $phpTokens
	 */
	private static function undoChanges(array $changes, array &$phpTokens): void
	{
		if (!$changes)
		{
			return;
		}

		$sourceOffset = 0;
		$removedNewlines = 0;
		$nextChange = \array_shift($changes);
		foreach ($phpTokens as &$token)
		{
			if (!\is_array($token))
			{
				$sourceOffset++;
				continue;
			}

			$token[2] -= $removedNewlines; // TODO needs test coverage

			while ($nextChange)
			{
				$tokenChangeOfsset = $nextChange[1] - $sourceOffset;

				if ($nextChange[0] === self::HACK_INSERT && $tokenChangeOfsset < \strlen($token[1]))
				{
					$removedNewlines += $token[1][$tokenChangeOfsset] === "\n" ? 1 : 0;
					$token[1] = Util::stringSplice($token[1], $tokenChangeOfsset, 1);
				}
				else if ($nextChange[0] === self::HACK_DELETE && $tokenChangeOfsset <= \strlen($token[1]))
				{
					$token[1] = Util::stringSplice($token[1], $tokenChangeOfsset, 0, $nextChange[2]);
				}
				else
				{
					break;
				}

				$nextChange = \array_shift($changes);
				// run the change check again on the same token
			}

			$sourceOffset += \strlen($token[1]);
		}
	}

	/**
	 * Maps php types to TokenType constants.
	 *
	 * @return array<string|int, int>
	 */
	public static function getPhpTypeMap(): array
	{
		static $map;
		if (!$map)
		{
			$map = [
				"!" => TokenType::S_EXCLAMATION_MARK,
				"\"" => TokenType::S_DOUBLE_QUOTE,
				"\$" => TokenType::S_DOLLAR,
				"%" => TokenType::S_MODULO,
				"&" => TokenType::S_AMPERSAND,
				"(" => TokenType::S_LEFT_PARENTHESIS,
				")" => TokenType::S_RIGHT_PARENTHESIS,
				"*" => TokenType::S_ASTERISK,
				"+" => TokenType::S_PLUS,
				"," => TokenType::S_COMMA,
				"-" => TokenType::S_MINUS,
				"." => TokenType::S_DOT,
				"/" => TokenType::S_FORWARD_SLASH,
				":" => TokenType::S_COLON,
				";" => TokenType::S_SEMICOLON,
				"<" => TokenType::S_LT,
				"=" => TokenType::S_EQUALS,
				">" => TokenType::S_GT,
				"?" => TokenType::S_QUESTION_MARK,
				"@" => TokenType::S_AT,
				"[" => TokenType::S_LEFT_SQUARE_BRACKET,
				"]" => TokenType::S_RIGHT_SQUARE_BRACKET,
				"^" => TokenType::S_CARET,
				"`" => TokenType::S_BACKTICK,
				"{" => TokenType::S_LEFT_CURLY_BRACE,
				"|" => TokenType::S_VERTICAL_BAR,
				"}" => TokenType::S_RIGHT_CURLY_BRACE,
				"~" => TokenType::S_TILDE,
				\T_ABSTRACT => TokenType::T_ABSTRACT,
				\T_AND_EQUAL => TokenType::T_AND_EQUAL,
				\T_ARRAY => TokenType::T_ARRAY,
				\T_ARRAY_CAST => TokenType::T_ARRAY_CAST,
				\T_AS => TokenType::T_AS,
				\T_BOOLEAN_AND => TokenType::T_BOOLEAN_AND,
				\T_BOOLEAN_OR => TokenType::T_BOOLEAN_OR,
				\T_BOOL_CAST => TokenType::T_BOOL_CAST,
				\T_BREAK => TokenType::T_BREAK,
				\T_CALLABLE => TokenType::T_CALLABLE,
				\T_CASE => TokenType::T_CASE,
				\T_CATCH => TokenType::T_CATCH,
				\T_CLASS => TokenType::T_CLASS,
				\T_CLASS_C => TokenType::T_CLASS_C,
				\T_CLONE => TokenType::T_CLONE,
				\T_CLOSE_TAG => TokenType::T_CLOSE_TAG,
				\T_COALESCE => TokenType::T_COALESCE,
				\defined('T_COALESCE_EQUAL') ? \constant('T_COALESCE_EQUAL') : -1 => TokenType::T_COALESCE_EQUAL,
				\T_COMMENT => TokenType::T_COMMENT,
				\T_CONCAT_EQUAL => TokenType::T_CONCAT_EQUAL,
				\T_CONST => TokenType::T_CONST,
				\T_CONSTANT_ENCAPSED_STRING => TokenType::T_CONSTANT_ENCAPSED_STRING,
				\T_CONTINUE => TokenType::T_CONTINUE,
				\T_CURLY_OPEN => TokenType::T_CURLY_OPEN,
				\T_DEC => TokenType::T_DEC,
				\T_DECLARE => TokenType::T_DECLARE,
				\T_DEFAULT => TokenType::T_DEFAULT,
				\T_DIR => TokenType::T_DIR,
				\T_DIV_EQUAL => TokenType::T_DIV_EQUAL,
				\T_DNUMBER => TokenType::T_DNUMBER,
				\T_DO => TokenType::T_DO,
				\T_DOC_COMMENT => TokenType::T_DOC_COMMENT,
				\T_DOLLAR_OPEN_CURLY_BRACES => TokenType::T_DOLLAR_OPEN_CURLY_BRACES,
				\T_DOUBLE_ARROW => TokenType::T_DOUBLE_ARROW,
				\T_DOUBLE_CAST => TokenType::T_DOUBLE_CAST,
				\T_DOUBLE_COLON => TokenType::T_DOUBLE_COLON,
				\T_ECHO => TokenType::T_ECHO,
				\T_ELLIPSIS => TokenType::T_ELLIPSIS,
				\T_ELSE => TokenType::T_ELSE,
				\T_ELSEIF => TokenType::T_ELSEIF,
				\T_EMPTY => TokenType::T_EMPTY,
				\T_ENCAPSED_AND_WHITESPACE => TokenType::T_ENCAPSED_AND_WHITESPACE,
				\T_ENDDECLARE => TokenType::T_ENDDECLARE,
				\T_ENDFOR => TokenType::T_ENDFOR,
				\T_ENDFOREACH => TokenType::T_ENDFOREACH,
				\T_ENDIF => TokenType::T_ENDIF,
				\T_ENDSWITCH => TokenType::T_ENDSWITCH,
				\T_ENDWHILE => TokenType::T_ENDWHILE,
				\T_END_HEREDOC => TokenType::T_END_HEREDOC,
				\T_EVAL => TokenType::T_EVAL,
				\T_EXIT => TokenType::T_EXIT,
				\T_EXTENDS => TokenType::T_EXTENDS,
				\T_FILE => TokenType::T_FILE,
				\T_FINAL => TokenType::T_FINAL,
				\T_FINALLY => TokenType::T_FINALLY,
				\defined('T_FN') ? \constant('T_FN') : -1 => TokenType::T_FN,
				\T_FOR => TokenType::T_FOR,
				\T_FOREACH => TokenType::T_FOREACH,
				\T_FUNCTION => TokenType::T_FUNCTION,
				\T_FUNC_C => TokenType::T_FUNC_C,
				\T_GLOBAL => TokenType::T_GLOBAL,
				\T_GOTO => TokenType::T_GOTO,
				\T_HALT_COMPILER => TokenType::T_HALT_COMPILER,
				\T_IF => TokenType::T_IF,
				\T_IMPLEMENTS => TokenType::T_IMPLEMENTS,
				\T_INC => TokenType::T_INC,
				\T_INCLUDE => TokenType::T_INCLUDE,
				\T_INCLUDE_ONCE => TokenType::T_INCLUDE_ONCE,
				\T_INLINE_HTML => TokenType::T_INLINE_HTML,
				\T_INSTANCEOF => TokenType::T_INSTANCEOF,
				\T_INSTEADOF => TokenType::T_INSTEADOF,
				\T_INTERFACE => TokenType::T_INTERFACE,
				\T_INT_CAST => TokenType::T_INT_CAST,
				\T_ISSET => TokenType::T_ISSET,
				\T_IS_EQUAL => TokenType::T_IS_EQUAL,
				\T_IS_GREATER_OR_EQUAL => TokenType::T_IS_GREATER_OR_EQUAL,
				\T_IS_IDENTICAL => TokenType::T_IS_IDENTICAL,
				\T_IS_NOT_EQUAL => TokenType::T_IS_NOT_EQUAL,
				\T_IS_NOT_IDENTICAL => TokenType::T_IS_NOT_IDENTICAL,
				\T_IS_SMALLER_OR_EQUAL => TokenType::T_IS_SMALLER_OR_EQUAL,
				\T_LINE => TokenType::T_LINE,
				\T_LIST => TokenType::T_LIST,
				\T_LNUMBER => TokenType::T_LNUMBER,
				\T_LOGICAL_AND => TokenType::T_LOGICAL_AND,
				\T_LOGICAL_OR => TokenType::T_LOGICAL_OR,
				\T_LOGICAL_XOR => TokenType::T_LOGICAL_XOR,
				\T_METHOD_C => TokenType::T_METHOD_C,
				\T_MINUS_EQUAL => TokenType::T_MINUS_EQUAL,
				\T_MOD_EQUAL => TokenType::T_MOD_EQUAL,
				\T_MUL_EQUAL => TokenType::T_MUL_EQUAL,
				\T_NAMESPACE => TokenType::T_NAMESPACE,
				\T_NEW => TokenType::T_NEW,
				\T_NS_C => TokenType::T_NS_C,
				\T_NS_SEPARATOR => TokenType::T_NS_SEPARATOR,
				\T_NUM_STRING => TokenType::T_NUM_STRING,
				\T_OBJECT_CAST => TokenType::T_OBJECT_CAST,
				\T_OBJECT_OPERATOR => TokenType::T_OBJECT_OPERATOR,
				\T_OPEN_TAG => TokenType::T_OPEN_TAG,
				\T_OPEN_TAG_WITH_ECHO => TokenType::T_OPEN_TAG_WITH_ECHO,
				\T_OR_EQUAL => TokenType::T_OR_EQUAL,
				\T_PLUS_EQUAL => TokenType::T_PLUS_EQUAL,
				\T_POW => TokenType::T_POW,
				\T_POW_EQUAL => TokenType::T_POW_EQUAL,
				\T_PRINT => TokenType::T_PRINT,
				\T_PRIVATE => TokenType::T_PRIVATE,
				\T_PROTECTED => TokenType::T_PROTECTED,
				\T_PUBLIC => TokenType::T_PUBLIC,
				\T_REQUIRE => TokenType::T_REQUIRE,
				\T_REQUIRE_ONCE => TokenType::T_REQUIRE_ONCE,
				\T_RETURN => TokenType::T_RETURN,
				\T_SL => TokenType::T_SL,
				\T_SL_EQUAL => TokenType::T_SL_EQUAL,
				\T_SPACESHIP => TokenType::T_SPACESHIP,
				\T_SR => TokenType::T_SR,
				\T_SR_EQUAL => TokenType::T_SR_EQUAL,
				\T_START_HEREDOC => TokenType::T_START_HEREDOC,
				\T_STATIC => TokenType::T_STATIC,
				\T_STRING => TokenType::T_STRING,
				\T_STRING_CAST => TokenType::T_STRING_CAST,
				\T_STRING_VARNAME => TokenType::T_STRING_VARNAME,
				\T_SWITCH => TokenType::T_SWITCH,
				\T_THROW => TokenType::T_THROW,
				\T_TRAIT => TokenType::T_TRAIT,
				\T_TRAIT_C => TokenType::T_TRAIT_C,
				\T_TRY => TokenType::T_TRY,
				\T_UNSET => TokenType::T_UNSET,
				\T_UNSET_CAST => TokenType::T_UNSET_CAST,
				\T_USE => TokenType::T_USE,
				\T_VAR => TokenType::T_VAR,
				\T_VARIABLE => TokenType::T_VARIABLE,
				\T_WHILE => TokenType::T_WHILE,
				\T_WHITESPACE => TokenType::T_WHITESPACE,
				\T_XOR_EQUAL => TokenType::T_XOR_EQUAL,
				\T_YIELD => TokenType::T_YIELD,
				\T_YIELD_FROM => TokenType::T_YIELD_FROM,
			];
			unset($map[-1]);
		}
		return $map;
	}
}
