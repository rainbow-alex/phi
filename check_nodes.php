#!/usr/bin/env php
<?php

/** @noinspection PhpComposerExtensionStubsInspection */

use Phi\PhpVersion;

require __DIR__ . '/vendor/autoload.php';

if (count($argv) > 1)
{
    foreach (array_slice($argv, 1) as $arg)
    {
        echo "\e[45mphp -l\e[0m\n";
        system('echo ' . escapeshellarg('<?php ' . $arg . ';') . ' | php -l');

        echo "\e[45mnikic/php-parser\e[0m\n";
        try
        {
            $parser1 = (new \PhpParser\ParserFactory())->create(\PhpParser\ParserFactory::PREFER_PHP7);
            $ast1 = $parser1->parse('<?php ' . $arg . ';');
            echo (new \PhpParser\NodeDumper())->dump($ast1) . "\n";
        }
        catch (\Throwable $t)
        {
            echo "$t\n";
        }

        echo "\e[45mphi\e[0m\n";
        try
        {
            $ast2 = (new \Phi\Parser(PhpVersion::DEFAULT()))->parseStatement($arg . ';');
            $ast2->debugDump();
        }
        catch (\Throwable $t)
        {
            echo "$t\n";
        }
    }

    exit();
}
else
{
    while (true)
    {
        echo "\e[1m>>>\e[0m ";
        $line = readline('');

        if ($line === false)
        {
            echo "\n";
            exit(1);
        }

        readline_add_history($line);
        passthru('PHI_DEBUG_COLOR=1 ' . $argv[0] . ' ' . escapeshellarg($line));
    }
}
