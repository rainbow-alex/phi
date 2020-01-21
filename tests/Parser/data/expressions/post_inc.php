<?php

/** @noinspection ALL */

$a++; // PostIncrementExpression(RegularVariableExpression($a), ++)
$b->a++; // PostIncrementExpression(MemberAccessExpression(RegularVariableExpression($b), ->, a), ++)
$c[3]++; // PostIncrementExpression(ArrayAccessExpression(RegularVariableExpression($c), [, 3, ]), ++)

// TODO more
