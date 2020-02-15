<?php

declare(strict_types=1);

namespace Phi\Tests;

use Phi\Lexer;
use Phi\PhpVersion;
use Phi\Token;
use Phi\TokenType;
use PHPUnit\Framework\TestCase;

class TokenTest extends TestCase
{
	private static function discoverTokenTypes(): array
	{
		$allTypes = [];

		for ($t = 0; $t < 9999; $t++)
		{
			if (\token_name($t) !== "UNKNOWN")
			{
				$allTypes[] = $t;
			}
		}

		for ($i = 1; $i < 256; $i++)
		{
			$lexed = @\token_get_all("<?php " . \chr($i));
			if (isset($lexed[1]))
			{
				$t = $lexed[1][0];

				if (\in_array($t, $allTypes))
				{
					continue;
				}

				$allTypes[] = $t;
			}
		}

		// TODO research
		if (\defined('T_BAD_CHARACTER'))
		{
			$allTypes = \array_values(\array_filter($allTypes, function ($t)
			{
				return $t !== \T_BAD_CHARACTER;
			}));
		}

		return $allTypes;
	}

	public function test_map(): void
	{
		$defined = TokenType::getAll();
		$phpTypeMap = TokenType::getPhpTypeMap();
		$discovered = self::discoverTokenTypes();

		// each token has a unique value
		self::assertCount(count($defined), \array_unique(\array_values($defined)));

		// mapped types are all defined
		self::assertEmpty(\array_diff($phpTypeMap, $defined));

		// each mapped type has a unique value
		self::assertCount(count($phpTypeMap), \array_unique(\array_values($phpTypeMap)));

		// all discovered types are in the type map and vice versa
		self::assertEmpty(\array_diff($discovered, \array_keys($phpTypeMap)));
		self::assertEmpty(\array_diff(\array_keys($phpTypeMap), $discovered));
	}

	// brute force as many token combinations as possible
	public function test_requires_whitespace_brute_force(): void
	{
		$types = TokenType::getAll();
		$typeToSource = TokenType::AUTOCORRECT + [
			TokenType::T_STRING => 'foo',
			TokenType::T_VARIABLE => '$v',
			TokenType::T_LNUMBER => '123',
			TokenType::T_DNUMBER => '1.23',
		];

		$excludedTypes = [
			TokenType::T_WHITESPACE,
			TokenType::T_DOC_COMMENT,
			TokenType::T_COMMENT,
			TokenType::T_EOF,
			// not used, we replace it with S_LEFT_CURLY_BRACE
			TokenType::T_CURLY_OPEN,
			// string related tokens, there is no whitespace inside strings
			TokenType::T_INLINE_HTML,
			TokenType::T_CONSTANT_ENCAPSED_STRING,
			TokenType::T_ENCAPSED_AND_WHITESPACE,
			TokenType::T_START_HEREDOC,
			TokenType::T_END_HEREDOC,
			TokenType::S_DOUBLE_QUOTE,
			TokenType::T_DOLLAR_OPEN_CURLY_BRACES,
			TokenType::T_STRING_VARNAME,
			TokenType::T_NUM_STRING,
			TokenType::S_BACKTICK,
			// this test won't be able to check these tokens correctly, to be checked manually in another
			TokenType::T_OPEN_TAG,
			TokenType::T_OPEN_TAG_WITH_ECHO,
			TokenType::T_CLOSE_TAG,
			TokenType::T_HALT_COMPILER,
		];

		$leftTypes = \array_diff($types, $excludedTypes, [TokenType::T_OBJECT_OPERATOR]);
		$rightTypes = \array_diff($types, $excludedTypes);

		foreach ($leftTypes as $leftType)
		{
			$leftToken = new Token($leftType, $typeToSource[$leftType]);
			foreach ($rightTypes as $rightType)
			{
				$rightToken = new Token($rightType, $typeToSource[$rightType]);

				$src = $leftToken . $rightToken;
				$relexed = (new Lexer(PhpVersion::PHP_7_4))->lexFragment($src);

				$relexedIdentically = (
					count($relexed) === 3 // 2 tokens + EOF
					&& $relexed[0]->getType() === $leftType
					&& $relexed[0]->getSource() === $leftToken->getSource()
					&& $relexed[1]->getType() === $rightType
					&& $relexed[1]->getSource() === $rightToken->getSource()
				);

				self::assertSame(
					!$relexedIdentically,
					TokenType::requireSeparatingWhitespace($leftType, $rightType),
					TokenType::typeToString($leftType) . ' ' . TokenType::typeToString($rightType)
				);
			}
		}
	}

	// these combinations are special because they depend on certain contexts
	public function test_requires_whitespace_manual(): void
	{
		// looks like ${ but that token is only parsed in strings
		self::assertFalse(TokenType::requireSeparatingWhitespace(TokenType::S_DOLLAR, TokenType::S_LEFT_CURLY_BRACE));

		$stringTypes = [
			TokenType::T_INLINE_HTML,
			TokenType::T_CONSTANT_ENCAPSED_STRING,
			TokenType::T_ENCAPSED_AND_WHITESPACE,
			TokenType::T_START_HEREDOC,
			TokenType::T_END_HEREDOC,
			TokenType::S_DOUBLE_QUOTE,
			TokenType::T_DOLLAR_OPEN_CURLY_BRACES,
			TokenType::S_BACKTICK,

		];
		foreach ($stringTypes as $type)
		{
			foreach (TokenType::getAll() as $otherType)
			{
				self::assertFalse(TokenType::requireSeparatingWhitespace($type, $otherType));
				self::assertFalse(TokenType::requireSeparatingWhitespace($otherType, $type));
			}
		}

		self::assertTrue(TokenType::requireSeparatingWhitespace(TokenType::S_QUESTION_MARK, TokenType::T_CLOSE_TAG));
		self::assertTrue(TokenType::requireSeparatingWhitespace(TokenType::T_ABSTRACT, TokenType::T_HALT_COMPILER));
		self::assertTrue(TokenType::requireSeparatingWhitespace(TokenType::T_STRING, TokenType::T_HALT_COMPILER));
	}
}
