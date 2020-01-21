<?php

/** @noinspection ALL */

1 ?? 2; // CoalesceExpression(1, ??, 2)

// associativity
1 ?? 2 ?? 3; // CoalesceExpression(CoalesceExpression(1, ??, 2), ??, 3)

// precedence
1 + 2 ?? 3 + 4; // CoalesceExpression(AddExpression(1, +, 2), ??, AddExpression(3, +, 4))
1 or 2 ?? 3 or 4; // KeywordBooleanOrExpression(KeywordBooleanOrExpression(1, or, CoalesceExpression(2, ??, 3)), or, 4)

// errors
# 1 ?? // 1:5 - Expected expression, got EOF
