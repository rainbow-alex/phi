# Phi

## To do

Issues that need to be addressed before 0.1 are marked with ***.

- parsing
	- anonymous class
	- statement level validation
	- global variable declaration
	- shebang
	- halt compiler
- features
	- conversion to php-parser nodes ***
	- tree & list manipulation api
	- tree searching
	- automatic node coercion
	- operator precedence validation
	- node-specific validation
	- autocorrect
- test coverage
	- more parser fuzz coverage
	- node fuzz coverage ***
	- ...
- documentation
	- readme ***
	- implementation notes ***
	- tutorial
	- api reference
- misc
	- clean up binaries & scripts ***
	- phpunit code coverage
	- CI
	- packagist
- further analysis
	- portability/emulation (7.x and 5.6)
	- pseudo-nodes for (doc-)comments
	- error-resistant parsing
	- serialization

## Caveats

- Parity with `php -l`: phi does not check if functions are already declared; php -l does.
