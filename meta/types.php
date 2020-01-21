<?php

use Phi\Nodes\Name;
use Phi\Nodes\NamedType;
use Phi\Nodes\NullableType;
use Phi\Nodes\SpecialType;
use Phi\Nodes\Type;

return [
    (new NodeDef(NullableType::class))
        ->withImplements(Type::class)
        ->withToken('symbol', '?')
        ->withChild('type', Type::class)
        ->withConstructor('type'),

    (new NodeDef(NamedType::class))
        ->withImplements(Type::class)
        ->withChild('name', Name::class)
        ->withConstructor('name'),

    (new NodeDef(SpecialType::class))
        ->withImplements(Type::class)
        ->withToken('token', [T_ARRAY, T_CALLABLE]) // TODO Token constant
        ->withConstructor('token'),
];
