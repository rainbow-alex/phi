<?php

/** @noinspection ALL */

$a =& $b; // AliasingExpression(RegularVariableExpression($a), =, &, RegularVariableExpression($b))
$a->foo =& $b; // AliasingExpression(MemberAccessExpression(RegularVariableExpression($a), ->, foo), =, &, RegularVariableExpression($b))
$a->foo->bar =& $b; // AliasingExpression(MemberAccessExpression(MemberAccessExpression(RegularVariableExpression($a), ->, foo), ->, bar), =, &, RegularVariableExpression($b))
$a =& $b->foo; // AliasingExpression(RegularVariableExpression($a), =, &, MemberAccessExpression(RegularVariableExpression($b), ->, foo))
$a =& $b->foo->bar; // AliasingExpression(RegularVariableExpression($a), =, &, MemberAccessExpression(MemberAccessExpression(RegularVariableExpression($b), ->, foo), ->, bar))
$a[1] =& $b; // AliasingExpression(ArrayAccessExpression(RegularVariableExpression($a), [, 1, ]), =, &, RegularVariableExpression($b))
$a[1][2] =& $b; // AliasingExpression(ArrayAccessExpression(ArrayAccessExpression(RegularVariableExpression($a), [, 1, ]), [, 2, ]), =, &, RegularVariableExpression($b))
$a =& $b[1]; // AliasingExpression(RegularVariableExpression($a), =, &, ArrayAccessExpression(RegularVariableExpression($b), [, 1, ]))
$a =& $b[1][2]; // AliasingExpression(RegularVariableExpression($a), =, &, ArrayAccessExpression(ArrayAccessExpression(RegularVariableExpression($b), [, 1, ]), [, 2, ]))
$a[] =& $b; // AliasingExpression(ArrayAccessExpression(RegularVariableExpression($a), [, ]), =, &, RegularVariableExpression($b))
$a[][] =& $b; // AliasingExpression(ArrayAccessExpression(ArrayAccessExpression(RegularVariableExpression($a), [, ]), [, ]), =, &, RegularVariableExpression($b))
$a =& $b[]; // AliasingExpression(RegularVariableExpression($a), =, &, ArrayAccessExpression(RegularVariableExpression($b), [, ]))
$a =& $b[][]; // AliasingExpression(RegularVariableExpression($a), =, &, ArrayAccessExpression(ArrayAccessExpression(RegularVariableExpression($b), [, ]), [, ]))
