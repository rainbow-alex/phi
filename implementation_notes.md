# Implementation notes

## Parsing algorithm

Phi uses a hand-written recursive descent parser.
[Precedence climbing](https://eli.thegreenplace.net/2012/08/02/parsing-expressions-by-precedence-climbing)
is used to (drastically) reduce the amount of recursion needed to parse expressions.

The parser is pretty lenient in the combinations of expressions it will accept.
This is fine because ultimately in phi validation happens mostly on the AST (after potential manipulation), not during the parsing phase.

The parser is also optimized by inlining helper methods and replacing some constants by their literal values.
See `optimize_parser.php`.
This eliminates a lot of method calls and should take advantage of some switch optimizations in php 7.2.
This is an extreme measure and very much unique to the way this parsing algorithm behaves in a language like PHP.
It is ABSOLUTELY NOT a good idea in general. I might revert it after some more benchmarking.

## Code generation

PHP has a surprisingly large amount of expressions and statements.
Phi needs the tree to remain consistent: nodes must have a correct reference to their parent and they can only belong to (at most) one parent.
Instead of writing all those constructors, getters and setters by hand that part of node implementation is generated into traits.

Eventually I hope to eliminate this, but for now there is too much flux in the node implementation to maintain by hand.

## Lexer hacks

The lexer implementation is based on using token_get_all().
Unfortunately token_get_all() always lexes for the current php version.
So if you're lexing 7.2 code with php 7.4, it will recognize 1_2_3 as a valid integer literal.

The lexer contains several hacks for working around backwards/forwards incompatible changes in lexing.
By temporarily inserting/deleting specific characters we break/create tokens so token_get_all does the right thing.
Afterwards the inserted/deleted characters are restored.

I briefly experimented with implementing a completely userland lexer using preg.
It was mostly ok, only 3-4x slower, but I ran into trouble with "${expr}" syntax.
This is syntactically valid PHP: `"${"test"{"${"bar"{1}}"}";`.

## Fuzz testing

To cover all the different ways PHP's expressions and constructs interact,
test cases for parsing, validation and autocorrect are generated.
Results of parsing are compared against the output of `php -l` or the php-parser AST for the same code.

See `ParserFuzzTest.php` and `NodesFuzzTest.php` for more details.

## Code style

I know it's not PSR. If you want to contribute and have a problem with it we can discuss it, otherwise it is my call.
