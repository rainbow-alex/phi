<?php

/** @noinspection ALL */

foreach ([] as $v) {} // ForeachStatement(foreach, `(`, ShortArrayExpression([, [], ]), as, RegularVariableExpression($v), `)`, Block({, [], }))
foreach ([] as $k => $v) {} // ForeachStatement(foreach, `(`, ShortArrayExpression([, [], ]), as, Key(RegularVariableExpression($k), =>), RegularVariableExpression($v), `)`, Block({, [], }))
foreach ([] as $v) continue; // ForeachStatement(foreach, `(`, ShortArrayExpression([, [], ]), as, RegularVariableExpression($v), `)`, ContinueStatement(continue, ;))
// TODO foreach ([] as list($v)) {}
// TODO foreach ([] as &$v) {}
// TODO foreach ([] as &$k => &$v) {}

// errors
# foreach // 1:8 - Unexpected EOF
# foreach ( // 1:10 - Expected expression, got EOF
# foreach ([] // 1:12 - Unexpected EOF
# foreach ([] as // 1:15 - Expected expression, got EOF
# foreach ([] as $k // 1:18 - Unexpected EOF
# foreach ([] as $k => // 1:21 - Expected expression, got EOF
# foreach (as $k) // 1:10 - Expected expression, got T_AS
