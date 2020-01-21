<?php

/** @noinspection ALL */

2 + 3; // AddExpression(2, +, 3)

// associativity
2 + 3 + 4; // AddExpression(AddExpression(2, +, 3), +, 4)
2 + 3 + 4 + 5; // AddExpression(AddExpression(AddExpression(2, +, 3), +, 4), +, 5)

// precedence
2 + 3 * 4; // AddExpression(2, +, MultiplyExpression(3, *, 4))
2 * 3 + 4; // AddExpression(MultiplyExpression(2, *, 3), +, 4)
2 + 3 < 4; // LessThanExpression(AddExpression(2, +, 3), <, 4)
2 < 3 + 4; // LessThanExpression(2, <, AddExpression(3, +, 4))

// errors
# 2 + // 1:4 - Expected expression, got EOF
# + 2 // 1:1 - Expected expression, got "+"
