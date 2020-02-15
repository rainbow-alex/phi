#!/usr/bin/env php
<?php

use Phi\Exception\TreeException;
use Phi\Exception\ValidationException;
use Phi\Meta\NodeDef;
use Phi\Node;
use Phi\NodeCoercer;
use Phi\Token;

require_once __DIR__ . "/../../vendor/autoload.php";

const IMPORTS = [
	Node::class,
	Token::class,
	TreeException::class,
	NodeCoercer::class,
	ValidationException::class,
];

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
