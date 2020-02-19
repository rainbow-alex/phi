<?php

declare(strict_types=1);

namespace Phi\Tests;

use Phi\Lexer;
use Phi\TokenType;
use PHPUnit\Framework\TestCase;

class LexerTest extends TestCase
{
	public function test_map(): void
	{
		$defined = TokenType::getAll();
		$phpTypeMap = Lexer::getPhpTypeMap();
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
}
