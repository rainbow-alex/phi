<?php

/** @noinspection ALL */

!0; // BooleanNotExpression(!, 0)

// associativity
!!0; // BooleanNotExpression(!, BooleanNotExpression(!, 0))
!!!0; // BooleanNotExpression(!, BooleanNotExpression(!, BooleanNotExpression(!, 0)))

// precedence
!$x instanceof stdClass; // BooleanNotExpression(!, InstanceofExpression(RegularVariableExpression($x), instanceof, NameExpression(RegularName([stdClass]))))
!$x * 3; // MultiplyExpression(BooleanNotExpression(!, RegularVariableExpression($x)), *, 3)

// errors
# ! // 1:2 - Expected expression, got EOF
# 1! // 1:2 - Unexpected "!"
