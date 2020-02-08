#!/usr/bin/env php
<?php

use Phi\Lexer;
use Phi\Parser;
use Phi\PhpVersion;
use Phi\Tests\Parser\ParserFuzzTest;
use Phi\Tests\Testing\TestRepr;

require __DIR__ . "/../../vendor/autoload.php";

// spawning 1000s of processes can take pretty long, this caches the results
$syntaxCheckCache = json_decode(file_get_contents(__DIR__ . "/.php-l-cache"), true) ?: [];

// make sure the cache is saved even if the process is interrupted
declare(ticks=1);
pcntl_signal(SIGINT, function () { exit(1); });
register_shutdown_function(function () use (&$syntaxCheckCache)
{
    file_put_contents(__DIR__ . "/.php-l-cache", json_encode($syntaxCheckCache, JSON_PRETTY_PRINT));
});

$srcs = [];
foreach (ParserFuzzTest::generate() as $src) $srcs[] = $src;
$srcs = \array_unique($srcs);
sort($srcs);

$lexer = new Lexer(PhpVersion::PHP_7_2);
$parser = new Parser(PhpVersion::PHP_7_2);

$cases = [];

foreach ($srcs as $src)
{
    echo $src . "\n";

    $case = [];

    // figure out if php will parse this -- used to make sure phi's parser matches zend's behavior
    $case["php"] = $syntaxCheckCache[$src] ?? $syntaxCheckCache[$src] = (function () use ($src)
    {
        $h = proc_open("php -l", [["pipe", "r"], ["pipe", "w"], ["pipe", "w"]], $pipes);
        fwrite($pipes[0], $src);
        fclose($pipes[0]);
        $out = trim(stream_get_contents($pipes[2]));
        $r = proc_close($h);

        $out = str_replace("Errors parsing Standard input code\n", "", $out);
        $out = str_replace("PHP Fatal error:  ", "", $out);
        $out = str_replace("PHP Parse error:  ", "", $out);
        $out = str_replace(" in Standard input code on line 1", "", $out);

        return $r === 0 ? true : $out;
    })();

    // lex & parse the source using phi -- this acts as a baseline to prevent regressions
    try
    {
        $ast = $parser->parse(null, $src);
        $ast->validate();
        $case["phi"] = TestRepr::node($ast);
    }
    catch (Throwable $t)
    {
        $case["phi"] = false;
    }

    $cases[$src] = $case;
}

ksort($cases);

file_put_contents(__DIR__ . "/../../tests/Parser/data/generated/fuzz.json", json_encode($cases, JSON_PRETTY_PRINT));

echo count($cases) . " cases generated\n";
