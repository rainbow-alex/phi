<?php

use Phi\Meta\NodeDef;
use Phi\Nodes\Helpers\Name;
use Phi\Nodes\Type;
use Phi\Nodes\Types\NamedType;
use Phi\Nodes\Types\NullableType;
use Phi\TokenType;

return [
    (new NodeDef(NullableType::class))
        ->token("questionMark", TokenType::S_QUESTION_MARK)
        ->node("type", Type::class)
        ->constructor("type"),

    (new NodeDef(NamedType::class))
        ->node("name", Name::class)
        ->constructor("name"),
];
