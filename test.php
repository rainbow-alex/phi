<?php

use Phi\Nodes\ConstantStringLiteral;
use Phi\Parser;
use Phi\PhpVersion;
use Phi\Specifications\IsInstanceOf;

require __DIR__ . '/vendor/autoload.php';

$parser = new Parser(PhpVersion::PHP_7_2);

foreach (array_slice($argv, 1) as $arg)
{
    $ast = $parser->parse($arg, file_get_contents($arg));
    foreach ($ast->find(new IsInstanceOf(ConstantStringLiteral::class)) as $node)
    {
        /** @var ConstantStringLiteral $node */

        $source = $node->getSource()->getSource();

        if (strpbrk(substr($source, 1, -1), '\'"\\$') === false)
        {
            $node->debugDump();
            $node->getSource()->setSource('"' . substr($source, 1, -1) . '"');
            $node->debugDump();
        }
    }
    file_put_contents($arg, (string) $ast);
}
