<?php

use Phi\Exception\SyntaxException;
use Phi\Nodes\InterpolatedString;
use Phi\Parser;
use Phi\PhpVersion;
use Phi\Specifications\IsInstanceOf;

require __DIR__ . '/vendor/autoload.php';

$parser = new Parser(PhpVersion::PHP_7_2);

foreach (array_slice($argv, 1) as $arg)
{
    try
    {
        $ast = $parser->parse($arg, file_get_contents($arg));
    }
    catch (SyntaxException $e)
    {
        echo $e->getMessageWithContext() . "\n";
        continue;
    }

    foreach ($ast->findNodes(new IsInstanceOf(InterpolatedString::class)) as $node)
    {
        echo $arg . "\n";
        /** @var InterpolatedString $node */
        $node->debugDump();
    }

    file_put_contents($arg, (string) $ast);
}
