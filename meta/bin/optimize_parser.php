#!/usr/bin/env php
<?php

use Phi\Token;

// TODO mark ALL generated code as generated

require __DIR__ . "/../../vendor/autoload.php";
assert(class_exists(Token::class));

$src = file_get_contents(__DIR__ . "/../../src/Parser.src.php");

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

file_put_contents(__DIR__ . "/../../src/_optimized/Parser.php", $src);
