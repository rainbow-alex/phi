# Phi

## To do

$a->foo
$a->$foo
$a->foo()
$a->$foo()
A::foo
A::$foo
A::foo()
A::$foo()

- Modelling, parsing, validation & test coverage
	- Expressions
		- read binops
		- assign
		- alias
		- instanceof ***
		- calls ***
		- object/class access ***
		- array access
		- list
		- new *** ->isNewable, also used for instanceof right-hand side
		- clone
		- print
		- yield
		- yield from
		- include/require/once
		- pre/post inc/dec
		- ternary
		- eval
		- exit/die
		- isset/empty
		- cast
		- variables
		- array literals ***
		- number literals ***
		- string literals ***
		- true/false
		- assert
		- unary plus/minus
		- bool neg
		- bit neg
		- coalesce
		- error suppression
		- backticks
		- anonymous function
		- anonymous class
	- Statements
		- top level statements?
		- alternate block syntax ***
		- global
		- static
		- declare
		- namespace
		- use
		- if
		- try
		- for
		- foreach
		- while
		- do while
		- break
		- continue
		- label
		- goto
		- echo
		- throw
		- return
		- unset
		- const
		- switch
		- html
	- Oop
		- class
		- interface
		- trait
		- trait use
		- constants
		- properties
		- methods
		- parent call
	- Weirdness
		- shebang
		- halt compiler
	- naming: dereferencable
- parse vendors
 	- symfony/symfony
    - magento
    - wordpress
* Validation
    * implement better error messages for specifications
    * validate precedence
* Code quality
    * factor out `@internal` tags
* Manipulation
    * implement list manipulation primitives
    * implement more node conversions
* Autocorrect
    * correct precedence by adding parentheses
* Analysis needed
    * ~~should arrays not be an `LvalueExpression`, but instead `DestructuringExpression`?~~
    * (doc)comment pseudo-nodes
    * alternative parser implementations
    	* use `token_get_all` with `TOKEN_PARSE` to allow faster parsing (?)
    	* error-resistant parsing - could be awesome in combination with autocorrect
    * serialization and/or conversion to json
