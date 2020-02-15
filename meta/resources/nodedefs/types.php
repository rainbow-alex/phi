<?php

use Phi\Meta\NodeDef;
use Phi\Nodes\Helpers\Name;
use Phi\Nodes\Type;
use Phi\Nodes\Types\NamedType;
use Phi\Nodes\Types\NullableType;
use Phi\Nodes\Types\SpecialType;
use Phi\TokenType as T;

return [
	(new NodeDef(NullableType::class))
		->token("questionMark", T::S_QUESTION_MARK)
		->node("type", Type::class)
		->constructor("type"),

	(new NodeDef(NamedType::class))
		->node("name", Name::class)
		->constructor("name"),

	(new NodeDef(SpecialType::class))
		->token("name", [T::T_ARRAY, T::T_CALLABLE, T::T_STATIC])
		->constructor("name"),
];
