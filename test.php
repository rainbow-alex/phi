<?php

/*

NormalInterpolatedStringVariable
	{?
		expr
	}?
	limits:
		only normal variable
		in optionally one time arrayaccess (-? literal) xor propertyaccess (normal)

// TODO RFC for removal
ConfusingInterpolatedStringVariable
	${
		ConfusingEncapsedVarName (t_string)
		(
			[ expr ]
		)?
	}
	problem: t_string isn't a constant, but we would like to put it in ArrayAccessExpression
	WeirdEncapsedVarVariable

VariableInterpolatedStringVariable
	${
		expression
	}
	limits:
		can't match the ConfusingEncapsedVar syntax (ArrayAccessExpression of simple Name)

*/

namespace bar { const bar = ["test3"]; }

namespace
{
	function foo() { return 0; }
	function foo2() { return 1; }
	use bar as fooz;

	$test1 = 1;
	$test1b = [1];
	$test1c = new class { public $foo = 1; };

	$test2 = 2;
	$test2b = [2];

	$test3 = 3;
	$name = "test3";
	const foo = ["test3"];

// NormalEncapsedVar
	echo __LINE__ . ": $test1\n";
	echo __LINE__ . ": {$test1}\n";
	echo __LINE__ . ": $test1b[0]\n";
	echo __LINE__ . ": {$test1b[0]}\n";
//	echo __LINE__ . ": $test1b[foo2()-1]\n"; INVALID, needs braces to get that kind of expr (can be validated & autocorrected, so same node is fine)
	echo __LINE__ . ": {$test1b[foo2()-1]}\n";
	echo __LINE__ . ": $test1c->foo\n";
	echo __LINE__ . ": {$test1c->foo}\n";

// ConfusingEncapsedVar
// the name should be a dead give-away, this is very confusing
// all of these have the same result when you flip the ${ around
	echo __LINE__ . ": ${test2}\n";
	echo __LINE__ . ": {$test2}\n";
	echo __LINE__ . ": ${test2b[0]}\n";
	echo __LINE__ . ": {$test2b[0]}\n";
	echo __LINE__ . ": ${test2b[foo2()-1]}\n";
	echo __LINE__ . ": {$test2b[foo2()-1]}\n";

// VariableEncapsedVar
	echo __LINE__ . ": ${"test3"}\n";
	echo __LINE__ . ": ${$name}\n";
	echo __LINE__ . ": ${\foo[0]}\n";
	echo __LINE__ . ": ${fooz\bar[0]}\n";
}
