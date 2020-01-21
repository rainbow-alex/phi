#!/usr/bin/env php
<?php

use Symfony\Component\Finder\Finder;

require __DIR__ . '/vendor/autoload.php';

echo "Xdebug: " . (extension_loaded("xdebug") ? "on" : "off") . "\n";
echo "OPCache: " . (extension_loaded("Zend OPcache") ? "on" : "off") . "\n";

foreach (array_slice($argv, 1) ?: ['nodes'] as $suite)
{
    $reps = 1;
    switch ($suite)
    {
        case 'nodes':
            $files = (new Finder())->in(__DIR__ . '/src/Nodes/')->filter(function (\SplFileInfo $p)
            {
                return $p->getExtension() === 'php';
            });
            $reps = 10;
            break;
        case 'nested':
            $files = [__DIR__ . '/benchmarks/nested.php'];
            $reps = 100;
            break;
        default:
            throw new \RuntimeException("Unknown suite: $suite");
    }

    echo "\e[1m$suite:\e[0m\n";
    echo count($files) . ' file(s), ' . $reps . " rep(s)\n";

    gc_collect_cycles();

    echo "phi (no validation)... ";
    $lexer = new \Phi\Lexer();
    $parser = new \Phi\Parser();
    $maxMemory = 0;

    $start = microtime(true);
    for ($i = 0; $i < $reps; $i++)
    {
        foreach ($files as $file)
        {
            $ast = $parser->parse($file, file_get_contents($file));
            $maxMemory = max($maxMemory, memory_get_usage());
            unset($ast);
        }
    }
    $time = microtime(true) - $start;
    echo number_format($time, 3) . "s, " . number_format($maxMemory / 1e6, 2) . "MB\n";

    gc_collect_cycles();

    echo "phi... ";
    $lexer = new \Phi\Lexer();
    $parser = new \Phi\Parser();
    $maxMemory = 0;
    $start = microtime(true);
    for ($i = 0; $i < $reps; $i++)
    {
        foreach ($files as $file)
        {
            $ast = $parser->parse($file, file_get_contents($file));
            $ast->validate();
            $maxMemory = max($maxMemory, memory_get_usage());
            unset($ast);
        }
    }
    $time = microtime(true) - $start;
    echo number_format($time, 3) . "s, " . number_format($maxMemory / 1e6, 2) . "MB\n";

    gc_collect_cycles();

    echo "nikic/php-parser... ";
    $parser = (new \PhpParser\ParserFactory())->create(\PhpParser\ParserFactory::PREFER_PHP7);
    $maxMemory = 0;
    $start = microtime(true);
    for ($i = 0; $i < $reps; $i++)
    {
        foreach ($files as $file)
        {
            $ast = $parser->parse(file_get_contents($file));
            $maxMemory = max($maxMemory, memory_get_usage());
            unset($ast);
        }
    }
    $time = microtime(true) - $start;
    echo number_format($time, 3) . "s, " . number_format($maxMemory / 1e6, 2) . "MB\n";

    /*
    echo "microsoft/tolerant-php-parser... ";
    $parser = new \Microsoft\PhpParser\Parser();
    $maxMemory = 0;
    $start = microtime(true);
    for ($i = 0; $i < $reps; $i++)
    {
        foreach ($files as $file)
        {
            $parser->parseSourceFile(file_get_contents($file));
            $maxMemory = max($maxMemory, memory_get_usage());
        }
    }
    $time = microtime(true) - $start;
    echo number_format($time, 3) . "s, " . number_format($maxMemory / 1e6, 2) . "MB\n";
    //*/
}
