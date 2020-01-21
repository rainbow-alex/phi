<?php

/** @noinspection ALL */

1 or 2; // KeywordBooleanOrExpression(1, or, 2)

// associativity
1 or 2 or 3; // KeywordBooleanOrExpression(KeywordBooleanOrExpression(1, or, 2), or, 3)

// precedence
1 or 2 xor 3; // KeywordBooleanOrExpression(1, or, KeywordBooleanXorExpression(2, xor, 3))
1 xor 2 or 3; // KeywordBooleanOrExpression(KeywordBooleanXorExpression(1, xor, 2), or, 3)

// errors
# 1 or // 1:5 - Expected expression, got EOF
# or 1 // 1:1 - Expected expression, got T_LOGICAL_OR
