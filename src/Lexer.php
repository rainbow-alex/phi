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
			$hacks[] = self::emulateNoNumericLiteralSeparators($source);
			$hacks[] = self::emulateNoCoalesceEquals($source);
		}
		else if ($this->phpVersion >= PhpVersion::PHP_7_4 && \PHP_VERSION_ID < PhpVersion::PHP_7_4)
		{
			$hacks[] = self::emulateNumericLiteralSeparators($source);
			$hacks[] = self::emulateCoalesceEquals($source);
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
		$typeMap = TokenType::getPhpTypeMap();
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
			self::fix74TokensToPre74($tokens);
		}
		else if ($this->phpVersion >= PhpVersion::PHP_7_4 && \PHP_VERSION_ID < PhpVersion::PHP_7_4)
		{
			self::fixPre74TokensTo74($tokens);
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
	 * Emulate lack of support for numeric literal separators by inserting a space before each separator.
	 * E.g. 1_2 becomes 1 _2
	 *
	 * @return mixed[]
	 */
	private static function emulateNoNumericLiteralSeparators(string &$source): array
	{
		// match all literals
		\preg_match_all('{
			[^a-zA-Z0-9_\x80-\xff] # make sure we dont accidentally match part of an identifier
			(
				0x[0-9A-F_]+
				| 0b[01_]+
				| \.? [0-9_]+
			)
			(
				e [0-9_]+
			)?
		}xi', $source, $matches, \PREG_OFFSET_CAPTURE|\PREG_SET_ORDER);

		$changes = [];
		foreach ($matches as $m)
		{
			// now match each individual separator
			\preg_match_all('{[0-9A-F](_)(?=[0-9A-F])}i', $m[0][0], $underscoreMatches, \PREG_OFFSET_CAPTURE|\PREG_SET_ORDER);

			foreach ($underscoreMatches as $m2)
			{
				$offset = $m[0][1] + $m2[1][1];
				$source = Util::stringSplice($source, $offset + \count($changes), 0, ' ');
				$changes[] = [self::HACK_INSERT, $offset];
			}
		}

		return $changes;
	}

	/**
	 * Emulate support for numeric literal separators by removing them.
	 *
	 * @return mixed[]
	 */
	private static function emulateNumericLiteralSeparators(string &$source): array
	{
		\preg_match_all('{
			[0-9A-F]
			(_)
			(?=[0-9A-F])
		}xi', $source, $matches, \PREG_OFFSET_CAPTURE|\PREG_SET_ORDER);

		$changes = [];
		foreach ($matches as $m)
		{
			$offset = $m[1][1];
			$source = Util::stringSplice($source, $offset - \count($changes), 1);
			$changes[] = [self::HACK_DELETE, $offset, '_'];
		}

		return $changes;
	}

	/**
	 * Break ??= up to prevent it being lexed as such.
	 *
	 * Unfortunately we can't just scan the tokens for ??= after lexing and break it up then.
	 * In some cases it would be pretty complex to correctly fix the subsequent tokens:
	 * e.g. `??=>` would lex as `??= >` and have to be fixed to `?? =>`
	 *
	 * @return mixed[]
	 */
	private static function emulateNoCoalesceEquals(string &$source): array
	{
		\preg_match_all('{\?\?(=)}', $source, $matches, \PREG_OFFSET_CAPTURE|\PREG_SET_ORDER);
		$changes = [];
		foreach ($matches as $m)
		{
			$offset = $m[1][1];
			$source = Util::stringSplice($source, $offset + \count($changes), 0, ' ');
			$changes[] = [self::HACK_INSERT, $offset];
		}
		return $changes;
	}

	/**
	 * @param Token[] $tokens
	 */
	private static function fix74TokensToPre74(array $tokens): void
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
	 * Emulate ??= token by taking out the = so it gets lexed as T_COALESCE.
	 * We can then check for each coalesce token if the unhacked source is actually ??= and fudge the type.
	 *
	 * Unfortunately we can't just scan the tokens for ?? followed by =
	 * in some cases it would be pretty complex to correctly fix the subsequent tokens:
	 * e.g. `??=>=` would lex as `?? => =` and have to be fixed to `??= >=`
	 *
	 * @return mixed[]
	 */
	private static function emulateCoalesceEquals(string &$source): array
	{
		\preg_match_all('{\?\?(=)}', $source, $matches, \PREG_OFFSET_CAPTURE|\PREG_SET_ORDER);
		$changes = [];
		foreach ($matches as $m)
		{
			$offset = $m[1][1];
			$source = Util::stringSplice($source, $offset - \count($changes), 1);
			$changes[] = [self::HACK_DELETE, $offset, '='];
		}
		return $changes;
	}

	/**
	 * @param Token[] $tokens
	 */
	private static function fixPre74TokensTo74(array $tokens): void
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
}
