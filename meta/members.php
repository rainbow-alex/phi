<?php

use Phi\Nodes\Block;
use Phi\Nodes\ClassConstant;
use Phi\Nodes\ClassLikeMember;
use Phi\Nodes\Default_;
use Phi\Nodes\Expression;
use Phi\Nodes\Method;
use Phi\Nodes\Name;
use Phi\Nodes\Parameter;
use Phi\Nodes\Property;
use Phi\Nodes\ReturnType;
use Phi\Nodes\TraitUse;
use Phi\Nodes\TraitUseAs;
use Phi\Nodes\TraitUseInsteadof;
use Phi\Nodes\TraitUseModification;
use Phi\Specifications\EachItem;
use Phi\Specifications\IsToken;
use Phi\Token;

return [
    (new NodeDef(ClassConstant::class))
        ->withImplements(ClassLikeMember::class)
        ->withTokenList('modifiers', [T_PUBLIC, T_PROTECTED, T_PRIVATE])
        ->withToken('keyword', T_CONST)
        ->withToken('name', T_STRING)
        ->withToken('equals', '=')
        ->withChild('value', Expression::class)
        ->withToken('semiColon', ';')
        ->withConstructor('name', 'value'),

    (new NodeDef(Method::class))
        ->withImplements(ClassLikeMember::class)
        ->withTokenList('modifiers', [T_ABSTRACT, T_FINAL, T_PUBLIC, T_PROTECTED, T_PRIVATE, T_STATIC])
        ->withToken('keyword', T_FUNCTION)
        ->withOptToken('byReference', '&')
        ->withToken('name', T_STRING)
        ->withToken('leftParenthesis', '(')
        ->withSepList('parameters', Parameter::class, ',')
        ->withToken('rightParenthesis', ')')
        ->withOptChild('returnType', ReturnType::class)
        ->withOptChild('body', Block::class)
        ->withOptToken('semiColon', ';')
        ->withConstructor('name'),

    (new NodeDef(Property::class))
        ->withImplements(ClassLikeMember::class)
        ->withTokenList('modifiers', [T_PUBLIC, T_PROTECTED, T_PRIVATE, T_STATIC])
        ->withToken('variable', T_VARIABLE)
        ->withOptChild('default', Default_::class)
        ->withToken('semiColon', ';')
        ->withConstructor('variable'),

    (new NodeDef(TraitUse::class))
        ->withImplements(ClassLikeMember::class)
        ->withToken('keyword', T_USE)
        ->withSepList('traits', Name::class, ',')
        ->withOptToken('leftBrace', '{')
        ->withList('modifications', TraitUseModification::class)
        ->withOptToken('rightBrace', '{')
        ->withOptToken('semiColon', ';'),

    (new NodeDef(TraitUseInsteadof::class))
        ->withImplements(TraitUseModification::class)
        ->withChild('trait', Name::class)
        ->withToken('doubleColon', T_DOUBLE_COLON)
        ->withToken('member', T_STRING)
        ->withToken('insteadof', T_INSTEADOF)
        ->withChild('excluded', Name::class),

    (new NodeDef(TraitUseAs::class))
        ->withImplements(TraitUseModification::class)
        ->withChild('trait', Name::class)
        ->withToken('doubleColon', T_DOUBLE_COLON)
        ->withToken('member', T_STRING)
        ->withToken('as', T_AS)
        ->withToken('alias', T_STRING),
];
