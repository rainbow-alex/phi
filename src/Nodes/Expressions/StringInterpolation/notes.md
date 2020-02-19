# String interpolation syntax

What an ugly mess.

Here are examples of each syntax and the equivalent using concatenation.

# NormalInterpolatedStringVariable

	"$foo" --> . $foo .
	"$foo->bar" --> . $foo->bar .
	"$foo->bar->baz" --> . $foo->bar . "->baz"

# NormalInterpolatedStringVariableArrayAccess

This could have been ArrayAccessExpression, but the semantics are different; see the last example.

	"$foo[0]" --> . $foo[0] .
	"$foo[-1]" --> . $foo[-1] .
	"$foo[bar]" --> . $foo["bar"] . # Note the quotes here...

# BracedInterpolatedStringVariable

I wish these could have just been optional braces around NormalInterpolatedStringVariable, but again, semantics are different.

	"{$foo}" --> . $foo .
	"{$foo[bar]}" --> . $foo[bar] . # here bar is interpreted as a constant, the value of which will be the index
	"{$foo[EXPR]}" --> . $foo[EXPR] . # allows any expression in the index position
	"{$foo->bar->baz}" --> . $foo->bar->baz .

# VariableInterpolatedStringVariable

	"${foo::bar}" --> . ${foo::bar} .
	"${(foo)}" --> . ${foo} .

# ConfusingInterpolatedStringVariable

This is where it gets really messed up:

	"${foo}" --> . $foo .
	"${foo[0]}" --> . $foo[0] .
	"${foo[EXPR]}" --> . $foo[EXPR] .

That's right, depending on what follows after `${foo` it is interpreted differently...
