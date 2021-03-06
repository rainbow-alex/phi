#!/usr/bin/env php
<?php

use Symfony\Component\Finder\Finder;

require __DIR__ . '/vendor/autoload.php';

echo "PHP: " . \phpversion() . "\n";
echo "Xdebug: " . (extension_loaded("xdebug") ? "on" : "off") . "\n";
echo "OPCache: " . (extension_loaded("Zend OPcache") ? "on" : "off") . "\n";

$fileNames = (new Finder())->in(__DIR__ . '/src/Nodes/')->filter(function (\SplFileInfo $p)
{
    return $p->getExtension() === 'php';
});
$fileNames = array_map('strval', iterator_to_array($fileNames));
$reps = 10;

$files = [];
foreach ($fileNames as $fileName)
{
    $files[$fileName] = file_get_contents($fileName);
}

echo count($fileNames) . ' file(s), ' . $reps . " rep(s)\n";

gc_collect_cycles();

//*
echo "phi (without validation)... ";
$lexer = new \Phi\Lexer(PHP_VERSION_ID);
$parser = new \Phi\Parser(PHP_VERSION_ID);
$maxMemory = 0;

$start = microtime(true);
for ($i = 0; $i < $reps; $i++)
{
    foreach ($files as $fileName => $contents)
    {
        $ast = $parser->parse($fileName, $contents);
        $maxMemory = max($maxMemory, memory_get_usage());
        unset($ast);
    }
}
$time = microtime(true) - $start;
echo number_format($time, 3) . "s, " . number_format($maxMemory / 1e6, 2) . "MB\n";
//*/

gc_collect_cycles();

//*
echo "phi (with validation)... ";
$lexer = new \Phi\Lexer(PHP_VERSION_ID);
$parser = new \Phi\Parser(PHP_VERSION_ID);
$maxMemory = 0;
$start = microtime(true);
for ($i = 0; $i < $reps; $i++)
{
    foreach ($files as $fileName => $contents)
    {
        $ast = $parser->parse($fileName, $contents);
        $ast->validate();
        $maxMemory = max($maxMemory, memory_get_usage());
        unset($ast);
    }
}
$time = microtime(true) - $start;
echo number_format($time, 3) . "s, " . number_format($maxMemory / 1e6, 2) . "MB\n";
//*/

gc_collect_cycles();

//*
echo "nikic/php-parser... ";
$parser = (new \PhpParser\ParserFactory())->create(\PhpParser\ParserFactory::PREFER_PHP7);
$maxMemory = 0;
$start = microtime(true);
for ($i = 0; $i < $reps; $i++)
{
    foreach ($files as $fileName => $contents)
    {
        $ast = $parser->parse($contents);
        $maxMemory = max($maxMemory, memory_get_usage());
        unset($ast);
    }
}
$time = microtime(true) - $start;
echo number_format($time, 3) . "s, " . number_format($maxMemory / 1e6, 2) . "MB\n";
//*/
