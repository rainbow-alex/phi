<?php

namespace Phi\Tests\Parser;

use Phi\Exception\PhiException;
use Phi\Nodes\Expression;
use Phi\Parser;
use Phi\PhpParserCompat;
use Phi\PhpVersion;
use Phi\Tests\Testing\TestRepr;
use PhpParser\Node as PPNodes;
use PhpParser\NodeDumper;
use PHPUnit\Framework\TestCase;

class ParserTest extends TestCase
{
    // unfortunately overhead seems to be pretty big per test, running things in batches is *much* faster
    private const BATCH = 20;
    // case data passed via this static var so phpunit doesn't dump all of it when a test fails
    private static $cases;

    public function cases(): iterable
    {
        self::$cases = \json_decode(\file_get_contents(__DIR__ . '/data/data.json'), true);
        foreach (self::$cases as $root => $group)
        {
            for ($i = 0; $i < count($group); $i += self::BATCH)
            {
                yield [$root, $i];
            }
        }
    }

    /** @dataProvider cases */
    public function test(string $root, int $offset): void
    {
        foreach (\array_slice(self::$cases[$root], $offset, self::BATCH) as $case)
        {
            $parser = new Parser(PhpVersion::PHP_7_2);

            try
            {
                $ast = $parser->parseFragment($case['source']);
            }
            catch (PhiException $e)
            {
                if ($case['php']['valid'])
                {
                    self::fail(
                        "Failed to parse valid code!\n"
                        . "Got: " . $e->getMessageWithContext() . "\n"
                        . $case['source']
                    );
                }

                // TODO test parse error
                self::assertTrue(true);
                continue;
            }

            if (!($case['php']['valid']))
            {
                self::fail(
                    "Accepted invalid code!\n"
                    . "Expected: " . $case['php']['error'] . "\n"
                    . $case['source']);
            }

            // test for parsing regressions
            self::assertTrue($case['phi']['valid']);
            self::assertSame($case['phi']['repr'], TestRepr::node($ast), $case['source']);
            self::assertSame($case['source'], (string) $ast);

            if ($ast instanceof Expression)
            {
                $nikiDumper = (new NodeDumper());
                $nikiParser = (new \PhpParser\ParserFactory())->create(\PhpParser\ParserFactory::ONLY_PHP7);

                try
                {
                    $nikiAst = $nikiParser->parse('<?php ' . $case['source'] . ' ?>');
                    self::assertCount(1, $nikiAst);
                    self::assertInstanceOf(PPNodes\Stmt\Expression::class, $nikiAst[0]);
                    $expectedNikiDump = $nikiDumper->dump($nikiAst[0]->expr);

                    $actualNikiDump = $nikiDumper->dump(PhpParserCompat::convert($ast));

                    self::assertSame($expectedNikiDump, $actualNikiDump, $case['source']);
                }
                catch (\RuntimeException $e)
                {
//                    throw $e;
                }
            }
        }
    }
}
