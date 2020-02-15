<?php

declare(strict_types=1);

namespace Phi\Tests\Nodes;

use Phi\Exception\SyntaxException;
use Phi\Exception\ValidationException;
use Phi\Node;
use Phi\Parser;
use Phi\PhpVersion;
use Phi\Tests\Testing\Fuzz\FuzzGenerator;
use Phi\Tests\Testing\Fuzz\Permute;
use Phi\Tests\Testing\NodeAssertions;
use Phi\TokenType;
use PHPUnit\Framework\TestCase;

class NodesFuzzTest extends TestCase
{
	use NodeAssertions;

	private const AUTOCORRECT_IGNORE_FAILURE = [
		'{.*instanceof.*}', // making the rhs newable is not always possible
	];

	private static $autocorrectFailureIgnored = 0;

	/** @dataProvider cases */
	public function test_nodes(string $nodeSrc)
	{
		$node = eval($nodeSrc);
		assert($node instanceof Node);

		self::assertNodeTreeIsCorrect($node);

		try
		{
			$node->validate();
		}
		catch (ValidationException $e)
		{
			$node->autocorrect();
			self::assertNodeTreeIsCorrect($node);

			try
			{
				$node->validate();
			}
			catch (ValidationException $e)
			{
				$src = $node->toPhp();

				// these are combinations that autocorrect simply can't fix
				// e.g. instanceof (3 + 4)
				foreach (self::AUTOCORRECT_IGNORE_FAILURE as $ignorePattern)
				{
					if (\preg_match($ignorePattern, $src))
					{
						self::$autocorrectFailureIgnored++;
						// make sure there are no regressions in the situations we can autocorrect
						self::assertLessThan(41, self::$autocorrectFailureIgnored);
						return;
					}
				}

				self::fail(
					$e->getMessage() . "\n"
					. $e->getTraceAsString() . "\n"
					. $src . "\n");

				return;
			}
		}

		$src = $node->toPhp();
		$parser = new Parser(PhpVersion::PHP_7_2);
		try
		{
			$reparsedNode = $parser->parseExpression($src);
			$reparsedNode->validate();
		}
		catch (SyntaxException $e)
		{
			self::fail(
				"Valid node is not identical after reparse.\n"
				. $src
			);
			return;
		}

		self::assertNodeStructEquals($node, $reparsedNode);
	}

	public function cases()
	{
		$generator = FuzzGenerator::parseDir(__DIR__ . "/data/");

		foreach (TokenType::AUTOCORRECT as $k => $v)
		{
			$generator->addRule("`" . $v . "`", new Permute(['new Token(' . $k . ', ' . \var_export($v, true) . ')']));
		}

		foreach ($generator->generate('__ROOT__') as $src)
		{
			yield [$src];
		}
	}
}
