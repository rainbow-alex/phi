# Phi

Phi is a parser for PHP code. Currently a work in progress, the intended features for the first release are:

* Parsing PHP 5.6, 7.x code using any version of PHP >= 7.2.
* Syntax validation matching the behavior of the zend parser exactly, for each minor version.
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

Quickly parsing some source code:

```
$ast = (new Parser(PHP_VERSION_ID))->parse(__FILE__);
```

Finding all method calls named `isFoo`:

```
$ast->findNodes(new And_(new IsInstanceOf(MethodCall::class), new IsNamed('isFoo')));
```

If you wanna know what's in a node, you can call `->debugDump()` to get a highlighted dump.
For example, `$foo->bar();` looks like:

```
ExpressionStatement
 ├─ expression: MethodCallExpression
 │   ├─ object: NormalVariableExpression
 │   │   └─ token: <T_VARIABLE> '$foo'
 │   ├─ operator: <T_OBJECT_OPERATOR> '->'
 │   ├─ name: NormalMemberName
 │   │   └─ token: <T_STRING> 'bar'
 │   ├─ leftParenthesis: <S_LEFT_PARENTHESIS> '('
 │   ├─ arguments: SeparatedNodesList
 │   └─ rightParenthesis: <S_RIGHT_PARENTHESIS> ')'
 └─ semiColon: <S_SEMICOLON> ';'
```

All nodes have annotated getters and setters you can use to manipulate the tree.

When you're done, you can call `->validate()` to verify that what you've done is syntactically correct.

```
$ast->validate();
$moddedSubtree->validate();
```

Since Phi retains all whitespace and tokens, sometimes extra nodes are needed to house these tokens.
Other times wrapper nodes are needed for the tree to be correctly typed.
You don't have to worry about those:

```
$if = new IfStatement();
$if->setBlock(new MethodCall(...)); // automatically wraps in ExpressionStatement, Block

$cc = new Property();
$cc->setDefault(new NumberLiteral(...)); // automatically wraps in Default
```

In fact, phi can just parse snippets of code on the fly:

```
$function = new FunctionDeclaration();
$function->getBody()->addStatement("return 3;");

$parameter->setDefault("null");
```

If you're creating new nodes, you don't need to worry about adding every single token needed to convert it back to code.
Phi can fix it for you:

```
$p = new ParenthesizedExpression($expr);
$p->autocorrect(); // creates `(` and `)` tokens
```

In some situations a little whitespace is needed to make the tree work.
Phi can correct that too:

```
$m = new MinusExpression("2", "-2");
$m->autocorrect(); // creates `-` token, adds whitespace before `-2`
```

It's also pretty easy to accidentally create impossible expressions.
If possible, Phi will correct those as well:

```
$x = new MulExpression("2", new AddExpression("4", "5"));
$x->autocorrect(); // wraps `4 + 5` in parentheses
```

Finally, we can convert it back to source code:

```
$ast->toPhp();
```

## To do

Not currently an exhaustive list :)

- parsing
	- `__halt_compiler` & `__COMPILER_HALT_OFFSET__`
	- short open tags, etc.
	- expr{}
	- multiple const
- validation
	- statement levels
	- string contents
	- "foo$bar"->baz() should not be valid
	- integer overflow
	- ...
- analysis
	- phpunit code coverage
	- pseudo-nodes for (doc-)comments, shebang, ...
	- error-resistant parsing
	- losslessly reverting php-parser trees back to phi
	- serialization

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
