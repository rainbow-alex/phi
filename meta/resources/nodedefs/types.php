<?php

use Phi\Meta\NodeDef;
use Phi\Nodes\Name;
use Phi\Nodes\NamedType;
use Phi\Nodes\NullableType;
use Phi\Nodes\SpecialType;
use Phi\Nodes\Type;
use Phi\Token;
use Phi\TokenType;

return [
    (new NodeDef(NullableType::class))
        ->withExtends(Type::class)
        ->withToken("symbol", TokenType::S_QUESTION_MARK)
        ->withChild("type", Type::class)
        ->withConstructor("type"),

    (new NodeDef(NamedType::class))
        ->withExtends(Type::class)
        ->withChild("name", Name::class)
        ->withConstructor("name"),

    (new NodeDef(SpecialType::class))
        ->withExtends(Type::class)
        ->withToken("token", [TokenType::T_ARRAY, TokenType::T_CALLABLE])
        ->withConstructor("token"),
];
