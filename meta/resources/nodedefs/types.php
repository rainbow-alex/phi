<?php

use Phi\Meta\NodeDef;
use Phi\Nodes\Name;
use Phi\Nodes\NamedType;
use Phi\Nodes\NullableType;
use Phi\Nodes\SpecialType;
use Phi\Nodes\Type;
use Phi\Token;

return [
    (new NodeDef(NullableType::class))
        ->withExtends(Type::class)
        ->withToken('symbol', Token::PH_S_QUESTION_MARK)
        ->withChild('type', Type::class)
        ->withConstructor('type'),

    (new NodeDef(NamedType::class))
        ->withExtends(Type::class)
        ->withChild('name', Name::class)
        ->withConstructor('name'),

    (new NodeDef(SpecialType::class))
        ->withExtends(Type::class)
        ->withToken('token', [Token::PH_T_ARRAY, Token::PH_T_CALLABLE]) // TODO Token constant
        ->withConstructor('token'),
];
