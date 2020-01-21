<?php

/** @noinspection ALL */
1 ? 2 : 3; // TernaryExpression(1, ?, 2, :, 3)
1 ?: 2; // TernaryExpression(1, ?, :, 2)

// associativity
1 ? 2 ? 3 : 4 : 5; // TernaryExpression(1, ?, TernaryExpression(2, ?, 3, :, 4), :, 5)
1 ? 2 : 3 ? 4 : 5; // TernaryExpression(TernaryExpression(1, ?, 2, :, 3), ?, 4, :, 5)
1 ?: 3 ? 4 : 5; // TernaryExpression(TernaryExpression(1, ?, :, 3), ?, 4, :, 5)
1 ?: 3 ?: 5; // TernaryExpression(TernaryExpression(1, ?, :, 3), ?, :, 5)

// precedence
1 === 2 ? 3 : 4; // TernaryExpression(IsIdenticalExpression(1, ===, 2), ?, 3, :, 4)
$a = 2 ? 3 : 4; // RegularAssignmentExpression(RegularVariableExpression($a), =, TernaryExpression(2, ?, 3, :, 4))
1 and 2 ? 3 : 4; // KeywordBooleanAndExpression(1, and, TernaryExpression(2, ?, 3, :, 4))
1 ? 2 === 3 : 4; // TernaryExpression(1, ?, IsIdenticalExpression(2, ===, 3), :, 4)
1 ? $a = 2 : 3; // TernaryExpression(1, ?, RegularAssignmentExpression(RegularVariableExpression($a), =, 2), :, 3)
1 ? 2 and 3 : 4; // TernaryExpression(1, ?, KeywordBooleanAndExpression(2, and, 3), :, 4)
1 ? 2 : 3 === 4; // TernaryExpression(1, ?, 2, :, IsIdenticalExpression(3, ===, 4))
1 ? 2 : $a = 3; // TernaryExpression(1, ?, 2, :, RegularAssignmentExpression(RegularVariableExpression($a), =, 3))
1 ? 2 : 3 and 4; // KeywordBooleanAndExpression(TernaryExpression(1, ?, 2, :, 3), and, 4)

// errors
# 1 ? // 1:4 - Expected expression, got EOF
# 1 ? 2 : // 1:8 - Expected expression, got EOF
# 1 : 2 // 1:3 - Unexpected ":"
