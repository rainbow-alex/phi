<?php

/** @noinspection ALL */

'foo'; // ConstantStringLiteral('foo')
"bar"; // ConstantStringLiteral("bar")
"{$a}"; // InterpolatedString(", [ComplexInterpolatedStringExpression({, RegularVariableExpression($a), })], ")

// TODO more
