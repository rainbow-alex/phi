<?php

/** @noinspection ALL */

$a = 3; // RegularAssignmentExpression(RegularVariableExpression($a), =, 3)
$a->b = 3; // RegularAssignmentExpression(MemberAccessExpression(RegularVariableExpression($a), ->, b), =, 3)
$a[2] = 4; // RegularAssignmentExpression(ArrayAccessExpression(RegularVariableExpression($a), [, 2, ]), =, 4)
$a[] = 4; // RegularAssignmentExpression(ArrayAccessExpression(RegularVariableExpression($a), [, ]), =, 4)
$a->b->c = 5; // RegularAssignmentExpression(MemberAccessExpression(MemberAccessExpression(RegularVariableExpression($a), ->, b), ->, c), =, 5)

// associativity
$a = $b = 3; // RegularAssignmentExpression(RegularVariableExpression($a), =, RegularAssignmentExpression(RegularVariableExpression($b), =, 3))
false === $a = 3; // IsIdenticalExpression(NameExpression(RegularName([false])), ===, RegularAssignmentExpression(RegularVariableExpression($a), =, 3))

// precedence
$a = 1 ?: 2; // RegularAssignmentExpression(RegularVariableExpression($a), =, TernaryExpression(1, ?, :, 2))
$a = 1 and 2; // KeywordBooleanAndExpression(RegularAssignmentExpression(RegularVariableExpression($a), =, 1), and, 2)

// errors
# 3 = 4 // 1:3 - Unexpected "="
# $a = // 1:5 - Expected expression, got EOF
