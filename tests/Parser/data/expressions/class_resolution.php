<?php

/** @noinspection ALL */

\stdClass::class; // ClassNameResolutionExpression(NameExpression(RegularName([\, stdClass])), ::, class)
stdClass::class; // ClassNameResolutionExpression(NameExpression(RegularName([stdClass])), ::, class)
self::class; // ClassNameResolutionExpression(NameExpression(SpecialName(self)), ::, class)
static::class; // ClassNameResolutionExpression(NameExpression(SpecialName(static)), ::, class)
parent::class; // ClassNameResolutionExpression(NameExpression(SpecialName(parent)), ::, class)

// precedence
!stdClass::class; // BooleanNotExpression(!, ClassNameResolutionExpression(NameExpression(RegularName([stdClass])), ::, class))

// errors
// TODO # $a::class
# Foo::class::class // 1:11 - Unexpected T_DOUBLE_COLON
