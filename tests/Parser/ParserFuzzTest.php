<?php

declare(strict_types=1);

namespace Phi\Tests\Parser;

use Phi\Exception\PhiException;
use Phi\Nodes\Statements\ExpressionStatement;
use Phi\Parser;
use Phi\PhpVersion;
use Phi\Tests\Nodes\NodesFuzzTest;
use Phi\Tests\Testing\Fuzz\FuzzGenerator;
use Phi\Tests\Testing\Fuzz\Permute;
use Phi\Tests\Testing\Fuzz\WeightedPermute;
use Phi\Tests\Testing\TestRepr;
use PhpParser\Node as PPNodes;
use PhpParser\NodeDumper;
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
    // unfortunately overhead seems to be pretty big per test, running things in batches is a bit faster
    private const BATCH = 25;
    // case data passed via this static var so phpunit doesn't dump all of it when a test fails
    private static $cases;

    /** @dataProvider cases */
    public function test(int $offset): void
    {
        foreach (\array_slice(self::$cases, $offset, self::BATCH) as $source => $case)
        {
            $parser = new Parser(PhpVersion::PHP_7_2);

            try
            {
                $ast = $parser->parse(null, $source);
                $ast->validate();
            }
            catch (PhiException $e)
            {
                if ($case["php"] === true)
                {
                    self::fail(
                        "Failed to parse valid code!\n"
                        . "Got: " . $e->getMessageWithContext() . "\n"
                        . $source
                    );
                }

                self::assertTrue(true);
                continue;
            }

            if ($case["php"] !== true)
            {
                self::fail(
                    "Accepted invalid code!\n"
                    . "Expected: " . $case["php"] . "\n"
                    . $source
                );
            }

            // test for parsing regressions TODO remove this? what does it detect...?
            self::assertSame($case["phi"], TestRepr::node($ast), $source);

            self::assertSame($source, (string) $ast);

            // TODO log these bugs with php-parser
            if (
                \strpos($source, "NOWDOC") !== false // TODO only broken on <7.3?
                || $source === '<?php isset(($a));'
                || $source === '<?php list(($a)) = $b;'
                || $source === '<?php list(($a[])) = $b;'
                || $source === '<?php list(($a[$b])) = $c;'
            )
            {
                return;
            }

            // TODO also check against php-parser for statements
            $stmt = $ast->getStatements()->getItems()[1];
            if ($stmt instanceof ExpressionStatement && count($ast->getStatements()) === 2)
            {
                $expr = $stmt->getExpression();

                $ppDumper = (new NodeDumper());
                $ppParser = (new \PhpParser\ParserFactory())->create(\PhpParser\ParserFactory::ONLY_PHP7);

                try
                {
                    $ppAst = $ppParser->parse($source);
                }
                catch (\Throwable $t)
                {
                    var_dump($source); // TODO improve error reporting
                    throw $t;
                }
                self::assertCount(1, $ppAst);
                self::assertInstanceOf(PPNodes\Stmt\Expression::class, $ppAst[0]);
                $expectedPpDump = $ppDumper->dump($ppAst[0]->expr);

                $actualPpDump = $ppDumper->dump($expr->convertToPhpParserNode());

                self::assertSame($expectedPpDump, $actualPpDump, $source);
            }
        }
    }

    public function cases(): iterable
    {
        self::$cases = \json_decode(\file_get_contents(__DIR__ . "/data/generated/fuzz.json"), true);
        for ($i = 0; $i < count(self::$cases); $i += self::BATCH)
        {
            yield [$i];
        }
    }

    /**
     * @return iterable<string>
     */
    public static function generate()
    {
        $generator = FuzzGenerator::parseDir(__DIR__ . "/data/");

        // helper rules for things we can't generate with the helper syntax
        $generator->addRule("#BLANK#", new Permute(['']));
        $generator->addRule("#SPACE#", new Permute([" "]));
        $generator->addRule("#NEWLINE#", new Permute(["\n"]));

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
