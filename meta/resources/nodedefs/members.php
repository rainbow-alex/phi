<?php

use Phi\Meta\NodeDef;
use Phi\Nodes\TraitUseModification;
use Phi\Nodes\Expression;
use Phi\Nodes\ClassConstant;
use Phi\Nodes\ClassLikeMember;
use Phi\Nodes\Default_;
use Phi\Nodes\Method;
use Phi\Nodes\Name;
use Phi\Nodes\Parameter;
use Phi\Nodes\Property;
use Phi\Nodes\RegularBlock;
use Phi\Nodes\ReturnType;
use Phi\Nodes\TraitUse;
use Phi\Nodes\TraitUseAs;
use Phi\Nodes\TraitUseInsteadof;
use Phi\Token;

return [
    (new NodeDef(ClassConstant::class))
        ->withExtends(ClassLikeMember::class)
        ->withTokenList('modifiers', [Token::PH_T_PUBLIC, Token::PH_T_PROTECTED, Token::PH_T_PRIVATE])
        ->withToken('keyword', Token::PH_T_CONST)
        ->withToken('name', Token::PH_T_STRING)
        ->withToken('equals', Token::PH_S_EQUALS)
        ->withChild('value', Expression::class)
        ->withToken('semiColon', Token::PH_S_SEMICOLON)
        ->withConstructor('name', 'value'),

    (new NodeDef(Method::class))
        ->withExtends(ClassLikeMember::class)
        ->withTokenList('modifiers', [Token::PH_T_ABSTRACT, Token::PH_T_FINAL, Token::PH_T_PUBLIC, Token::PH_T_PROTECTED, Token::PH_T_PRIVATE, Token::PH_T_STATIC])
        ->withToken('keyword', Token::PH_T_FUNCTION)
        ->withOptToken('byReference', Token::PH_S_AMPERSAND)
        ->withToken('name', Token::PH_T_STRING)
        ->withToken('leftParenthesis', Token::PH_S_LEFT_PAREN)
        ->withSepList('parameters', Parameter::class, Token::PH_S_COMMA)
        ->withToken('rightParenthesis', Token::PH_S_RIGHT_PAREN)
        ->withOptChild('returnType', ReturnType::class)
        ->withOptChild('body', RegularBlock::class)
        ->withOptToken('semiColon', Token::PH_S_SEMICOLON)
        ->withConstructor('name'),

    (new NodeDef(Property::class))
        ->withExtends(ClassLikeMember::class)
        ->withTokenList('modifiers', [Token::PH_T_PUBLIC, Token::PH_T_PROTECTED, Token::PH_T_PRIVATE, Token::PH_T_STATIC])
        ->withToken('variable', Token::PH_T_VARIABLE)
        ->withOptChild('default', Default_::class)
        ->withToken('semiColon', Token::PH_S_SEMICOLON)
        ->withConstructor('variable'),

    (new NodeDef(TraitUse::class))
        ->withExtends(ClassLikeMember::class)
        ->withToken('keyword', Token::PH_T_USE)
        ->withSepList('traits', Name::class, Token::PH_S_COMMA)
        ->withOptToken('leftBrace', Token::PH_S_LEFT_CURLY_BRACE)
        ->withList('modifications', TraitUseModification::class)
        ->withOptToken('rightBrace', Token::PH_S_LEFT_CURLY_BRACE)
        ->withOptToken('semiColon', Token::PH_S_SEMICOLON),

    (new NodeDef(TraitUseInsteadof::class))
        ->withExtends(TraitUseModification::class)
        ->withChild('trait', Name::class)
        ->withToken('doubleColon', Token::PH_T_DOUBLE_COLON)
        ->withToken('member', Token::PH_T_STRING)
        ->withToken('insteadof', Token::PH_T_INSTEADOF)
        ->withChild('excluded', Name::class),

    (new NodeDef(TraitUseAs::class))
        ->withExtends(TraitUseModification::class)
        ->withChild('trait', Name::class)
        ->withToken('doubleColon', Token::PH_T_DOUBLE_COLON)
        ->withToken('member', Token::PH_T_STRING)
        ->withToken('as', Token::PH_T_AS)
        ->withToken('alias', Token::PH_T_STRING),
];
