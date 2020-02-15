<?php

declare(strict_types=1);

namespace Phi\Tests\Parser;

use Phi\Exception\PhiException;
use Phi\Nodes\Statements\ExpressionStatement;
use Phi\Parser;
use Phi\Tests\Nodes\NodesFuzzTest;
use Phi\Tests\Testing\Fuzz\FuzzGenerator;
use Phi\Tests\Testing\Fuzz\Permute;
use Phi\Tests\Testing\Fuzz\WeightedPermute;
use Phi\Tests\Testing\NodeAssertions;
use PhpParser\NodeDumper;
use PhpParser\ParserFactory;
use PHPUnit\Framework\TestCase;

/**
 * This test validates correctness of the parser & validation.
 * Small php fragments are generated, and the result of parsing them is compared against `php -l` and php-parser.
 *
 * - valid syntax should be accepted
 * - invalid syntax should be rejected
 * - code is identical when converting back to source
 * - expressions used in the wrong context should be rejected
 * - operator precedence should be correct
 * - ...and any other condition that would lead to a parse error
 *
 * Note: php-parser accepts a lot of invalid code, so we only compare ASTs when the code is valid.
 *
 * This test will not cover invalid ASTs that can only be created by manipulating nodes. For that
 * @see NodesFuzzTest
 */
class ParserFuzzTest extends TestCase
{
	use NodeAssertions;

	// phpunit overhead seems to be pretty big per test, running things in batches is a lot faster
	private const BATCH = 100;
	/** @var string[] */
	private static $cases;

	/** @var array<array<string|true>> result of php -l, per version (pre-generated) */
	private static $phpLint;

	/** @var \PhpParser\Parser */
	private static $ppParser;
	/** @var NodeDumper */
	private static $ppDumper;

	public static function setUpBeforeClass(): void
	{
		parent::setUpBeforeClass();

		// self::$cases gets initialized by the data provider

		self::$phpLint = \json_decode(\gzdecode(
			\file_get_contents(__DIR__ . "/data/generated/syntax-checks.json.gz")
		), true);

		self::$ppParser = (new ParserFactory())->create(ParserFactory::ONLY_PHP7);
		self::$ppDumper = (new NodeDumper());
	}

	/** @dataProvider cases */
	public function test_7_2(int $offset): void
	{
		$this->doTest($offset, "7.2", 70200);
	}

	/** @dataProvider cases */
	public function test_7_3(int $offset): void
	{
		$this->doTest($offset, "7.3", 70300);
	}

	/** @dataProvider cases */
	public function test_7_4(int $offset): void
	{
		$this->doTest($offset, "7.4", 70400);
	}

	private function doTest(int $offset, string $version, int $versionId): void
	{
		foreach (\array_slice(self::$cases, $offset, self::BATCH) as $source)
		{
			$parser = new Parser($versionId);

			if (!isset(self::$phpLint[$source][$version]))
			{
				self::addWarning("PHP syntax check result not available: " . $source);
				continue;
			}

			try
			{
				$ast = $parser->parse(null, $source);
				self::assertNodeTreeIsCorrect($ast);
				$ast->validate();
			}
			catch (PhiException $e)
			{
				if (self::$phpLint[$source][$version] === true)
				{
					self::fail(
						"Failed to parse valid code!\n"
						. $version . ": " . $source
						. "Got: " . $e->getMessage() . "\n"
						. $e->getTraceAsString()
					);
				}

				self::assertTrue(true);
				continue;
			}

			// TODO assert tokens when emulating?

			if (self::$phpLint[$source][$version] !== true)
			{
				self::fail(
					"Accepted invalid code!\n"
					. "Expected: " . self::$phpLint[$source][$version] . "\n"
					. $version . ": " . $source
				);
			}

			self::assertSame($source, $ast->toPhp());

			// TODO log these bugs with php-parser
			if (
				\strpos($source, '<<<') !== false // can't correctly parse some unflexible here/nowdocs
				|| \strpos($source, 'isset((') !== false
				|| \strpos($source, 'list((') !== false
				|| $source === '<?php fn::class;'
				|| $source === '<?php new fn();'
			)
			{
				return;
			}

			// TODO also check statements
			if (\count($ast->getStatements()) === 2)
			{
				$stmt = $ast->getStatements()->getItems()[1];
				if ($stmt instanceof ExpressionStatement)
				{
					try
					{
						$expectedPpDump = self::$ppDumper->dump(self::$ppParser->parse($source));
					}
					catch (\Throwable $e)
					{
						var_dump($source);
						throw $e;
					}
					try
					{
						$actualPpDump = self::$ppDumper->dump($ast->convertToPhpParser());
					}
					catch (PhiException $e)
					{
						// self::fail($e->getMessageWithContext());
						return;
					}

					self::assertSame($expectedPpDump, $actualPpDump, $source);
				}
			}
		}
	}

	public function cases(): iterable
	{
		if (!self::$cases)
		{
			self::$cases = \iterator_to_array(self::generate(), false);
			self::$cases = \array_unique(self::$cases);
			sort(self::$cases);

			if ($filter = getenv('PHI_FUZZ_FILTER'))
			{
				self::$cases = \array_values(\array_filter(self::$cases, function ($src) use ($filter)
				{
					return strpos($src, $filter) !== false;
				}));
			}
		}

		// case data is passed via offset so phpunit doesn't dump all of it when a test fails
		for ($i = 0; $i < count(self::$cases); $i += self::BATCH)
		{
			yield [$i];
		}
	}

	public static function generate()
	{
		$generator = FuzzGenerator::parseDir(__DIR__ . "/data/");

		$exprFull = $generator->getRule("EXPR_FULL");
		\assert($exprFull instanceof WeightedPermute);

		// reusable variant of EXPR_FULL which generates less combination, for testing in combination with other constructs
		$generator->addRule("EXPR", $exprFull->withMax(6));
		// generates some expressions more likely to work in a const context
		$generator->addRule("EXPR_CONST", $exprFull->withMax(8)->map(function (string $rhs) {
			return \str_replace('$v', 'ident', $rhs);
		}));

		// binops, but operators with same precedence emitted to reduce redundant test cases
		$generator->addRule("BINOP_MIN", $generator->getRule("BINOP")->withMax(1));

		return $generator->generate("__ROOT__");
	}
}
