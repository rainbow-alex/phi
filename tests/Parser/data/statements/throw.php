<?php

/** @noinspection ALL */

throw $x; // ThrowStatement(throw, RegularVariableExpression($x), ;)
throw $x // ThrowStatement(throw, RegularVariableExpression($x))
; // NopStatement(;)

// errors
# throw // 1:6 - Expected expression, got EOF
# { throw $a } // 1:12 - Unexpected "}"
