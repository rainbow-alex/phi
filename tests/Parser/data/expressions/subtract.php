<?php

/** @noinspection ALL */

2 - 3; // SubtractExpression(2, -, 3)

// associativity
2 - 3 - 4; // SubtractExpression(SubtractExpression(2, -, 3), -, 4)
2 - 3 - 4 - 5; // SubtractExpression(SubtractExpression(SubtractExpression(2, -, 3), -, 4), -, 5)

// precedence
2 - 3 * 4; // SubtractExpression(2, -, MultiplyExpression(3, *, 4))
2 * 3 - 4; // SubtractExpression(MultiplyExpression(2, *, 3), -, 4)
2 - 3 < 4; // LessThanExpression(SubtractExpression(2, -, 3), <, 4)
2 < 3 - 4; // LessThanExpression(2, <, SubtractExpression(3, -, 4))

// errors
# 2 - // 1:4 - Expected expression, got EOF
// TODO unary minus
