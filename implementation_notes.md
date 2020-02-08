# Implementation notes

## Parsing algorithm

Phi uses a hand-written recursive descent parser.
[Precedence climbing](https://eli.thegreenplace.net/2012/08/02/parsing-expressions-by-precedence-climbing)
is used to (drastically) reduce the amount of recursion needed to parse expressions.

As far as I can tell right now the bottleneck during both lexing and parsing is the allocation of new tokens and nodes.

Recursive descent seems to be much more lenient in the combinations of expressions it will accept.
This is fine because ultimately in phi validation happens on the syntax tree (after potential manipulation of the tree), not during the parsing phase.

The parser is also optimized by inlining helper methods and replacing some constants by their literal values.
See `optimize_parser.php`.
This eliminates a lot of method calls and should take advantage of some switch optimizations in php 7.2.
This is an extreme measure and very much unique to the way this parsing algorithm behaves in a language like PHP.
It is ABSOLUTELY NOT a good idea in general.

## Code generation

PHP has a surprisingly large amount of expressions and statements.
Phi needs the tree to remain consistent: nodes must have a correct reference to their parent and they can only belong to (at most) one parent.
Instead of writing all those constructors, getters and setters by hand that part of node implementation is generated into traits.

Eventually I hope to eliminate this, but for now there is too much variability in the node implementation to maintain by hand.

## Fuzz testing

TODO

## Code style

I know it's not PSR. If you want to contribute and have a problem with it we can discuss it, otherwise it is my call.
