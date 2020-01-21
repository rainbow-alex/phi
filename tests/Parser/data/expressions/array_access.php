<?php

/** @noinspection ALL */

$a[3]; // ArrayAccessExpression(RegularVariableExpression($a), [, 3, ])
foo($a[]); // CallExpression(NameExpression(RegularName([foo])), `(`, [Argument(ArrayAccessExpression(RegularVariableExpression($a), [, ]))], `)`)
$a[] = 3; // RegularAssignmentExpression(ArrayAccessExpression(RegularVariableExpression($a), [, ]), =, 3)

// associativity
$a[1][2]; // ArrayAccessExpression(ArrayAccessExpression(RegularVariableExpression($a), [, 1, ]), [, 2, ])

// precedence
new $a[2]; // NewExpression(new, ArrayAccessExpression(RegularVariableExpression($a), [, 2, ]), [])
clone $a[2]; // CloneExpression(clone, ArrayAccessExpression(RegularVariableExpression($a), [, 2, ]))
$a[1](2); // CallExpression(ArrayAccessExpression(RegularVariableExpression($a), [, 1, ]), `(`, [Argument(2)], `)`)
$f(1)[2]; // ArrayAccessExpression(CallExpression(RegularVariableExpression($f), `(`, [Argument(1)], `)`), [, 2, ])
$a->b[2]; // ArrayAccessExpression(MemberAccessExpression(RegularVariableExpression($a), ->, b), [, 2, ])
3**$a[3]; // PowerExpression(3, **, ArrayAccessExpression(RegularVariableExpression($a), [, 3, ]))
++$a[2]; // PreIncrementExpression(++, ArrayAccessExpression(RegularVariableExpression($a), [, 2, ]))
$a[2]++; // PostIncrementExpression(ArrayAccessExpression(RegularVariableExpression($a), [, 2, ]), ++)
(int) $a[2]; // CastExpression((int), ArrayAccessExpression(RegularVariableExpression($a), [, 2, ]))
$x instanceof $a[2]; // InstanceofExpression(RegularVariableExpression($x), instanceof, ArrayAccessExpression(RegularVariableExpression($a), [, 2, ]))
!$a[2]; // BooleanNotExpression(!, ArrayAccessExpression(RegularVariableExpression($a), [, 2, ]))
$a*$b[3]; // MultiplyExpression(RegularVariableExpression($a), *, ArrayAccessExpression(RegularVariableExpression($b), [, 3, ]))

// errors
# $a[ // 1:4 - Expected expression, got EOF
# $a[] // 1:5 - Unexpected EOF
