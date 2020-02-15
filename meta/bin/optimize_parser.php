#!/usr/bin/env php
<?php

use Phi\Nodes\Expression;
use Phi\TokenType;

require __DIR__ . "/../../vendor/autoload.php";

$src = file_get_contents(__DIR__ . "/../../src/Parser.src.php");

// inline helper methods
// this is like 30% faster, easy

$src = str_replace('$this->peek()->getType()', '$this->types[$this->i]', $src);
$src = str_replace('$this->peek(1)->getType()', '$this->types[$this->i + 1]', $src);
$src = str_replace('$this->peek(2)->getType()', '$this->types[$this->i + 2]', $src);

$src = str_replace('$this->peek()', '$this->tokens[$this->i]', $src);
$src = str_replace('$this->peek(1)', '$this->tokens[$this->i + 1]', $src);
$src = str_replace('$this->peek(2)', '$this->tokens[$this->i + 2]', $src);

$src = str_replace('$this->read()', '$this->tokens[$this->i++]', $src);
$src = preg_replace('{\$this->opt\(([^)]+)\)}', '($this->types[$this->i] === $1 ? $this->tokens[$this->i++] : null)', $src);

// replace constants with their literal values
// doesn't do much yet, let's check again after I replace if/else with switch
/** @see https://derickrethans.nl/php7.2-switch.html */

assert(class_exists(TokenType::class));
$src = preg_replace_callback('{T::([ST]_[A-Z0-9_]+)}', function ($m)
{
	return constant(TokenType::class . "::" . $m[1]);
}, $src);

assert(class_exists(Expression::class));
$src = preg_replace_callback('{Expr::(PRECEDENCE_[A-Z0-9_]+)}', function ($m)
{
	return constant(Expression::class . "::" . $m[1]);
}, $src);

file_put_contents(__DIR__ . "/../../src/_optimized/Parser.php", $src);
