#!/usr/bin/env php
<?php

use Phi\Token;

// TODO mark ALL generated code as generated

require __DIR__ . "/../../vendor/autoload.php";
assert(class_exists(Token::class));

$src = file_get_contents(__DIR__ . "/../../src/Parser.src.php");

$exprShortcuts = file_get_contents(__DIR__ . "/../resources/optimization/parser_shortcuts.php");
$exprShortcuts = str_replace("\n", "", $exprShortcuts);
$exprShortcuts = substr($exprShortcuts, strpos($exprShortcuts, "/* START */") + 11);
$src = str_replace("/* EXPR SHORTCUTS */", $exprShortcuts, $src);

$src = str_replace('$this->peek()->getType()', '$this->types[$this->i]', $src);
$src = str_replace('$this->peek(1)->getType()', '$this->types[$this->i + 1]', $src);
$src = str_replace('$this->peek(2)->getType()', '$this->types[$this->i + 2]', $src);

$src = str_replace('$this->peek()', '$this->tokens[$this->i]', $src);
$src = str_replace('$this->peek(1)', '$this->tokens[$this->i + 1]', $src);
$src = str_replace('$this->peek(2)', '$this->tokens[$this->i + 2]', $src);

$src = str_replace('$this->read()', '$this->tokens[$this->i++]', $src);

$src = preg_replace_callback('{Token::(PH_[A-Z_]+)}', function ($m)
{
    return constant(Token::class . "::" . $m[1]);
}, $src);

$src = preg_replace('{ (\d\d\d) \\. "}', ' "$1', $src);
$src = preg_replace('{ (\d\d\d) \\. "}', ' "$1', $src);

/*
foreach (['tokens', 'i', 'types', 'typezip', 'typezip2'] as $var)
{
    $src = preg_replace('{private \\$' . $var . '\b}', 'private static $' . $var, $src);
    $src = preg_replace('{\\$this->' . $var . '\b}', 'self::$' . $var, $src);
}

preg_match_all('{private function ([a-zA-Z0-9_]+)\(}', $src, $m);
foreach ($m[1] as $method)
{
    $src = str_replace('private function ' . $method . '(', 'private static function ' . $method . '(', $src);
    $src = str_replace('$this->' . $method . '(', 'self::' . $method . '(', $src);
}

$src = preg_replace(
    '{self::read\((\'[^\']\'|\d+)\)}',
    '(self::$types[self::$i] === $1 ? self::$tokens[self::$i++] : self::read($1))',
    $src
);
//*/

file_put_contents(__DIR__ . "/../../src/_optimized/Parser.php", $src);
