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
		];

		// -> is excluded on the left because the assertion doesn't work for e.g. ->abstract
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
					TokenType::requireSeparatingWhitespace($leftToken, $rightToken),
					TokenType::typeToString($leftType) . ' ' . TokenType::typeToString($rightType)
				);
			}
		}
	}

	// these combinations are special because they depend on certain contexts
	public function test_requires_whitespace_manual(): void
	{
		// looks like `${` but that token is only parsed in strings
		self::assertFalse(TokenType::requireSeparatingWhitespace(
			new Token(TokenType::S_DOLLAR, '$'),
			new Token(TokenType::S_LEFT_CURLY_BRACE, '{')
		));

		self::assertTrue(TokenType::requireSeparatingWhitespace(
			new Token(TokenType::S_LT, '<'),
			new Token(TokenType::T_START_HEREDOC, "<<<FOO\n")
		));
		self::assertTrue(TokenType::requireSeparatingWhitespace(
			new Token(TokenType::T_END_HEREDOC, 'FOO'),
			new Token(TokenType::T_STRING, 'foo')
		));

		self::assertTrue(TokenType::requireSeparatingWhitespace(
			new Token(TokenType::S_QUESTION_MARK, '?'),
			new Token(TokenType::T_CLOSE_TAG, '?>')
		));

		self::assertTrue(TokenType::requireSeparatingWhitespace(
			new Token(TokenType::T_ABSTRACT, 'abstract'),
			new Token(TokenType::T_HALT_COMPILER, '__halt_compiler')
		));
		self::assertTrue(TokenType::requireSeparatingWhitespace(
			new Token(TokenType::T_STRING, 'foo'),
			new Token(TokenType::T_HALT_COMPILER, '__halt_compiler')
		));

		self::assertFalse(TokenType::requireSeparatingWhitespace(
			new Token(TokenType::T_OBJECT_OPERATOR, '->'),
			new Token(TokenType::T_STRING, 'foo')
		));
		self::assertFalse(TokenType::requireSeparatingWhitespace(
			new Token(TokenType::T_OBJECT_OPERATOR, '->'),
			new Token(TokenType::T_ABSTRACT, 'abstract')
		));
	}
}
