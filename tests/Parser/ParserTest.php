<?php

namespace Phi\Tests\Parser;

use Phi\Exception\ParseException;
use Phi\Nodes\ExpressionStatement;
use Phi\Parser;
use Phi\PhpVersion;
use Phi\Tests\Testing\AssertThrows;
use Phi\Tests\Testing\Stringify;
use PHPUnit\Framework\TestCase;

class ParserTest extends TestCase
{
    use AssertThrows;

    /** @return iterable|string[] */
    public function expressionFiles(): iterable
    {
        foreach (glob(__DIR__ . '/data/expressions/*.php') as $file)
        {
            yield \basename($file) => [$file];
        }
    }

    /** @dataProvider expressionFiles */
    public function test_expressions(string $file): void
    {
        foreach ($this->lines($file) as &$line)
        {
            $parts = explode(' // ', $line, 2);
            $php = $parts[0];
            $expected = $parts[1] ?? null;

            try
            {
                $stmt = (new Parser(PhpVersion::PHP_7_2))->parseStatement(ltrim($php, '# '));
                assert($stmt instanceof ExpressionStatement);
                $this->assertEquals($php, (string) $stmt);
                $actual = Stringify::node($stmt->getExpression());
            }
            catch (ParseException $e)
            {
                $actual = $e->getSourceLine() . ':' . $e->getSourceColumn() . ' - ' . $e->getMessage();
            }

            if ($expected === null && $this->isAutocompleteEnabled())
            {
                $line = $php . ' // ' . $actual;
            }
            else
            {
                $this->assertEquals($expected, $actual);
            }
        }
    }

    /** @return iterable|string[] */
    public function statementFiles(): iterable
    {
        foreach (\glob(__DIR__ . '/data/statements/*.php') as $file)
        {
            yield \basename($file) => [$file];
        }
    }

    /** @dataProvider statementFiles */
    public function test_statements(string $file): void
    {
        foreach ($this->lines($file) as &$line)
        {
            $parts = explode(' // ', $line, 2);
            $php = $parts[0];
            $expected = $parts[1] ?? null;

            try
            {
                $stmt = (new Parser(PhpVersion::PHP_7_2))->parseStatement(trim($php, '# '));
                $this->assertEquals($php, (string) $stmt);
                $actual = Stringify::node($stmt);
            }
            catch (ParseException $e)
            {
                $actual = $e->getSourceLine() . ':' . $e->getSourceColumn() . ' - ' . $e->getMessage();
            }

            if ($expected === null && $this->isAutocompleteEnabled())
            {
                $line = $php . ' // ' . $actual;
            }
            else
            {
                $this->assertEquals($expected, $actual);
            }
        }
    }

    private function &lines(string $filename): \Generator
    {
        $lines = explode("\n", \file_get_contents($filename));

        foreach ($lines as &$line)
        {
            $line = trim($line);

            if (preg_match('{^($|/\\*|//|<\\?php)}', $line))
            {
                continue;
            }

            yield $line;
        }

        if ($this->isAutocompleteEnabled())
        {
            \file_put_contents($filename, implode("\n", $lines));
        }
    }

    private function isAutocompleteEnabled(): bool
    {
        return getenv('PHI_PHPUNIT_AUTOCOMPLETE');
    }
}

class Line
{
    public $source;
    public $expected;

    public function __construct(string $source, string $expected)
    {
        $this->source = $source;
        $this->expected = $expected;
    }
}
