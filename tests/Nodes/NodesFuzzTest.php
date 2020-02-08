<?php

declare(strict_types=1);

namespace Phi\Tests\Nodes;

use Phi\Exception\ParseException;
use Phi\Exception\SyntaxException;
use Phi\Exception\ValidationException;
use Phi\Node;
use Phi\Parser;
use Phi\PhpVersion;
use Phi\Tests\Testing\Fuzz\Cycle;
use Phi\Tests\Testing\Fuzz\FuzzGenerator;
use Phi\Tests\Testing\Fuzz\Permute;
use Phi\Tests\Testing\Fuzz\WeightedPermute;
use Phi\Tests\Testing\TestRepr;
use Phi\TokenType;
use PHPUnit\Framework\TestCase;

class NodesFuzzTest extends TestCase
{
    /** @dataProvider cases */
    public function test_nodes(Node $node)
    {
        try
        {
            $node->validate();
        }
        catch (ValidationException $e)
        {
            $node->autocorrect();

            try
            {
                $node->validate();
            }
            catch (ValidationException $e)
            {
                self::assertTrue(true);
                return;
            }
        }

        $src = (string) $node;
        $parser = new Parser(PhpVersion::PHP_7_2);
        try
        {
            $reparsedNode = $parser->parseExpression($src);
            $reparsedNode->validate();
        }
        catch (SyntaxException $e)
        {
            self::fail($src);
            return;
        }

        self::assertSame(TestRepr::node($node), TestRepr::node($reparsedNode));
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
            yield [eval($src)];
        }
    }
}
