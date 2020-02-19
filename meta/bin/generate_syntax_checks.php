#!/usr/bin/env php
<?php

use Phi\Tests\Parser\ParserFuzzTest;
use Phi\Util\Util;

require __DIR__ . "/../../vendor/autoload.php";

const VERSIONS = ["7.2", "7.3", "7.4"];

declare(ticks=1);

$srcs = Util::iterableToArray(ParserFuzzTest::generate());
$srcs = \array_unique($srcs);
sort($srcs);

function cleanOutput(string $out): string
{
	$out = str_replace([
		"Errors parsing Standard input code",
		"No syntax errors detected in Standard input code",
		"Fatal error:",
		"Parse error:",
		"Warning:",
		"PHP Fatal error:",
		"PHP Parse error:",
		" in Standard input code on line 1",
		" in Standard input code on line 2",
		" in Standard input code on line 3",
	], "", $out);

	$out = preg_replace('{^ +| +$}', " ", $out);
	$out = preg_replace('{\n+}', "\n", $out);
	return trim($out);
}

/**
 * @return string|true
 */
function filterOutput(string $out)
{
	// TODO implement these validations
	foreach ([
		'{Method name must be a string}',
		'{The use statement with non-compound name \'[^\']+\' has no effect}',
	] as $pattern)
	{
		$out = preg_replace($pattern, "", $out);
	}

	$out = preg_replace('{^ +| +$}', " ", $out);
	$out = preg_replace('{\n+}', "\n", $out);
	return trim($out) ?: true;
}

$pids = [];

foreach (VERSIONS as $version)
{
	$pid = pcntl_fork();
	if ($pid !== 0)
	{
		$pids[] = $pid;
		continue;
	}

	$cacheFile = __DIR__ . "/../../cache/.php" . $version . "-l-cache";
	if (file_exists($cacheFile))
	{
		$cache = json_decode(file_get_contents($cacheFile), true);
		assert(!json_last_error(), json_last_error_msg());
	}
	else
	{
		$cache = [];
	}

	// make sure the cache is saved even if the process is interrupted
	pcntl_signal(SIGINT, function () { exit(); });
	register_shutdown_function(function () use ($cacheFile, &$cache)
	{
		file_put_contents($cacheFile, json_encode($cache, JSON_PRETTY_PRINT));
		assert(!json_last_error(), json_last_error_msg());
	});

	foreach ($srcs as $src)
	{
		if (!isset($cache[$src]))
		{
			echo $src . "\n";

			$h = proc_open(
				"php" . $version . " --no-php-ini -d short_open_tag=0 --syntax-check",
				[["pipe", "r"], ["pipe", "w"], ["pipe", "w"]],
				$pipes
			);
			fwrite($pipes[0], $src);
			fclose($pipes[0]);
			$out = stream_get_contents($pipes[1]);
			$r = proc_close($h);

			$cache[$src] = $out;
		}

		$cache[$src] = cleanOutput($cache[$src]);
	}

	die();
}

function awaitForks(array $pids)
{
	$status = 0;
	foreach ($pids as $pid)
	{
		pcntl_waitpid($pid, $s);
		$status += ($s ? 1 : 0);
	}
	if ($status)
	{
		exit($status);
	}
}

pcntl_signal(SIGINT, function () use ($pids)
{
	foreach ($pids as $pid)
	{
		posix_kill($pid, SIGINT);
	}
	awaitForks($pids);
	exit(1);
});

awaitForks($pids);

$results = [];
foreach (VERSIONS as $version)
{
	$versionCache = json_decode(file_get_contents(__DIR__ . "/../../cache/.php" . $version  . "-l-cache"), true);
	foreach ($srcs as $src)
	{
		$results[$src][$version] = filterOutput($versionCache[$src]);
	}
}
ksort($results);

// for manual inspection, not checked into git
file_put_contents(__DIR__ . "/../../tests/Parser/data/generated/syntax-checks.json", json_encode($results, JSON_PRETTY_PRINT));
// checked in and used by the test
file_put_contents(__DIR__ . "/../../tests/Parser/data/generated/syntax-checks.json.gz", gzencode(json_encode($results)));

echo count($results) . " checks saved\n";
