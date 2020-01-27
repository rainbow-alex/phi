#!/usr/bin/env php
<?php

use Phi\Exception\MissingNodeException;
use Phi\Exception\ValidationException;
use Phi\Meta\NodeDef;
use Phi\Node;
use Phi\NodeConverter;
use Phi\Nodes\Base\CompoundNode;
use Phi\Nodes\Base\NodesList;
use Phi\Nodes\Base\SeparatedNodesList;
use Phi\Token;

require_once __DIR__ . "/../../vendor/autoload.php";

const IMPORTS = [
    Node::class,
    Token::class,
    CompoundNode::class,
    NodesList::class,
    SeparatedNodesList::class,
    MissingNodeException::class,
    NodeConverter::class,
    ValidationException::class,
    "Phi\\Nodes" => "Nodes",
];

function main()
{
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

        file_put_contents(__DIR__ . "/../../src/Nodes/Generated/" . $node->shortClassName() . ".php", $src);
    }
}

function imported(string $name): string
{
    foreach (IMPORTS as $k => $v)
    {
        if (is_int($k))
        {
            if ($name === $v)
            {
                return (new ReflectionClass($v))->getShortName();
            }
        }
        else
        {
            if (substr($name, 0, strlen($k) + 1) === "$k\\")
            {
                return $v . substr($name, strlen($k));
            }
        }
    }

    return $name;
}

function singular(string $s): ?string
{
    switch ($s)
    {
        case "names":
            return "name";

        default:
            if (substr($s, -3) === "ies")
            {
                return substr($s, 0, -3) . "y";
            }
            else if (substr($s, -2) === "es")
            {
                return substr($s, 0, -2);
            }
            else if (substr($s, -1) === "s")
            {
                return substr($s, 0, -1);
            }
            else
            {
                return null;
            }
    }
}

function ensure_array($v)
{
    return is_array($v) ? $v : [$v];
}

main();
