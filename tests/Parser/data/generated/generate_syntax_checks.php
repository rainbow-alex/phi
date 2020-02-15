#!/usr/bin/env php
<?php

use Phi\Tests\Parser\ParserFuzzTest;

require __DIR__ . "/../../vendor/autoload.php";

// spawning 1000s of processes can take pretty long, this caches the results
$cache = json_decode(file_get_contents(__DIR__ . "/../../../../cache/.php-l-cache"), true) ?: [];

foreach ($cache as $src => $entry)
{
	foreach ($entry as $version => $result)
	{
		if ($result === "")
		{
			unset($cache[$src][$version]);
		}
	}
}

// make sure the cache is saved even if the process is interrupted
declare(ticks=1);
pcntl_signal(SIGINT, function () { exit(1); });
register_shutdown_function(function () use (&$cache)
{
	file_put_contents(__DIR__ . "/../cache/.php-l-cache", json_encode($cache, JSON_PRETTY_PRINT));
});

$srcs = [];
foreach (ParserFuzzTest::generate() as $src) $srcs[] = $src;
$srcs = \array_unique($srcs);
sort($srcs);

$cases = [];

foreach ($srcs as $src)
{
	$cases[$src] = $cases[$src] ?? [];

	foreach (["7.2", "7.3", "7.4"] as $version) // TODO use ids
	{
		$result = $cache[$src][$version] ?? $cache[$src][$version] = (function () use ($src, $version)
		{
			echo $src . "\n";

			$h = proc_open(
				"php" . $version . " --no-php-ini -d short_open_tag=0 --syntax-check",
				[["pipe", "r"], ["pipe", "w"], ["pipe", "w"]],
				$pipes
			);
			fwrite($pipes[0], $src);
			fclose($pipes[0]);
			$out = trim(stream_get_contents($pipes[1]));
			$r = proc_close($h);

			$out = str_replace("Errors parsing Standard input code\n", "", $out);
			$out = str_replace("PHP Fatal error:  ", "", $out);
			$out = str_replace("PHP Parse error:  ", "", $out);
			$out = str_replace(" in Standard input code on line 1", "", $out);

			return $r === 0 ? true : $out;
		})();

		$cases[$src][$version] = $result;
	}
}

ksort($cases);

// for manual inspection, not checked into git
file_put_contents(__DIR__ . "/syntax-checks.json", json_encode($cases, JSON_PRETTY_PRINT));
// checked in and used by the test
file_put_contents(__DIR__ . "/syntax-checks.json.gz", gzencode(json_encode($cases)));

echo count($cases) . " cases generated\n";
