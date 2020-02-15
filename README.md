# Phi

Phi is a userland parser for PHP code. Currently a work in progress, the intended features for the first release are:

* Parsing PHP 5.6, 7.x code using any version of PHP >= 7.2.
* Detailed syntax validation matching the behavior of the zend parser exactly, for each minor version.
* Validation of modified trees.
* Expressive and concise methods to find and traverse nodes.
* Easy node manipulation: automatic wrapping/unwrapping of nodes; on-the-fly parsing of code snippets.
* Automatic correction of tree defects (missing delimiters, incorrect precedence after modifications, ...).
* Complete retention and accessibility of whitespace, comments, delimiter tokens, etc.
* Conversion to and from [php-parser](https://github.com/nikic/PHP-Parser) ASTs.

The goal is to make a parser that is both powerful and safe to use.

## Contributing

Ideas, design feedback, bug reports, etc. are welcome via github issues.

## Examples

Finding method calls named `isFoo`:

```
$ast->find(new And_(new IsInstanceOf(MethodCall::class), new IsNamed('isFoo')));
```

Automatic wrapping of nodes:

```
$if = new IfStatement();
$if->setBlock(new MethodCall(...)); // automatically wraps in ExpressionStatement, Block

$fn = new FunctionStatement();
$fn->addParameter(new Variable(...)); // automatically wraps in Parameter
```

On-the-fly parsing of code strings:

```
$function = new FunctionDeclaration();
$function->getBody()->addStatement("return 3;");

$parameter->setDefault("null");
```

Automatic correction of tree defects:

```
$p = new ParenthesizedExpression($expr);
$p->autocorrect(); // creates `(` and `)` tokens

$m = new MinusExpression("2", "-2");
$m->autocorrect(); // creates `-` token, adds whitespace before `-2`

$x = new MulExpression("2", new AddExpression("4", "5"));
$x->autocorrect(); // wraps `4 + 5` in parentheses
```

## To do

Not currently an exhaustive list :)

- prepub
	- nowdoc/heredoc code
	- short function parsing
	- anonymous class parsing
	- global variable declaration
	- shebang
	- flesh out readme
	- TODO <15
	- packagist
- parsing
	- halt compiler & __COMPILER_HALT_OFFSET__
	- short open tags, etc.
- validation
	- statement levels
	- string contents
	- "foo$bar"->baz() should not be valid
	- ...
- features
	- conversion to php-parser nodes of statements
	- tree & list manipulation api
	- tree searching
	- automatic node coercion
- test coverage
	- more parser fuzz coverage
	- more node fuzz coverage
	- ...
- documentation
	- tutorial
	- api reference
- misc
	- clean up binaries & scripts ***
	- CI
	- packagist
- further analysis
	- phpunit code coverage
	- pseudo-nodes for (doc-)comments
	- error-resistant parsing
	- serialization
	- reverting php-parser trees back to phi

## Caveats

- Token types (`TokenType`) do not match the values of native `T_` constants;
  they can't because those are not the same across different versions of PHP.
- Parity with `php -l`: phi does not check if functions are already declared; `php -l` does.

## License

Phi, a PHP parser
Copyright (C) 2020 Alex Deleyn

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <https://www.gnu.org/licenses/>.
