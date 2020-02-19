#!/usr/bin/env php
<?php

use Phi\Meta\NodeDef;

require_once __DIR__ . "/../../vendor/autoload.php";

foreach (glob(__DIR__ . "/../../src/Nodes/Generated/*.php") as $f)
{
	unlink($f);
}

$nodes = array_merge(
	require __DIR__ . "/../resources/nodedefs/expressions.php",
	require __DIR__ . "/../resources/nodedefs/statements.php",
	require __DIR__ . "/../resources/nodedefs/members.php",
	require __DIR__ . "/../resources/nodedefs/types.php",
	require __DIR__ . "/../resources/nodedefs/helpers.php"
);

foreach ($nodes as $node)
{
	assert($node instanceof NodeDef);

	ob_start();
	include __DIR__ . "/../resources/node_template.php";
	$src = ob_get_contents();
	ob_end_clean();

	file_put_contents(__DIR__ . "/../../src/Nodes/Generated/" . $node->shortGeneratedClassName() . ".php", $src);
}
