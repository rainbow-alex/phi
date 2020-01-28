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
use Phi\TokenType;

return [
    (new NodeDef(ClassConstant::class))
        ->withExtends(ClassLikeMember::class)
        ->withTokenList("modifiers", [TokenType::T_PUBLIC, TokenType::T_PROTECTED, TokenType::T_PRIVATE])
        ->withToken("keyword", TokenType::T_CONST)
        ->withToken("name", TokenType::T_STRING)
        ->withToken("equals", TokenType::S_EQUALS)
        ->withChild("value", Expression::class)
        ->withToken("semiColon", TokenType::S_SEMICOLON)
        ->withConstructor("name", "value"),

    (new NodeDef(Method::class))
        ->withExtends(ClassLikeMember::class)
        ->withTokenList("modifiers", [TokenType::T_ABSTRACT, TokenType::T_FINAL, TokenType::T_PUBLIC, TokenType::T_PROTECTED, TokenType::T_PRIVATE, TokenType::T_STATIC])
        ->withToken("keyword", TokenType::T_FUNCTION)
        ->withOptToken("byReference", TokenType::S_AMPERSAND)
        ->withToken("name", TokenType::T_STRING)
        ->withToken("leftParenthesis", TokenType::S_LEFT_PAREN)
        ->withSepList("parameters", Parameter::class, TokenType::S_COMMA)
        ->withToken("rightParenthesis", TokenType::S_RIGHT_PAREN)
        ->withOptChild("returnType", ReturnType::class)
        ->withOptChild("body", RegularBlock::class)
        ->withOptToken("semiColon", TokenType::S_SEMICOLON)
        ->withConstructor("name"),

    (new NodeDef(Property::class))
        ->withExtends(ClassLikeMember::class)
        ->withTokenList("modifiers", [TokenType::T_PUBLIC, TokenType::T_PROTECTED, TokenType::T_PRIVATE, TokenType::T_STATIC])
        ->withToken("variable", TokenType::T_VARIABLE)
        ->withOptChild("default", Default_::class)
        ->withToken("semiColon", TokenType::S_SEMICOLON)
        ->withConstructor("variable"),

    (new NodeDef(TraitUse::class))
        ->withExtends(ClassLikeMember::class)
        ->withToken("keyword", TokenType::T_USE)
        ->withSepList("traits", Name::class, TokenType::S_COMMA)
        ->withOptToken("leftBrace", TokenType::S_LEFT_CURLY_BRACE)
        ->withList("modifications", TraitUseModification::class)
        ->withOptToken("rightBrace", TokenType::S_LEFT_CURLY_BRACE)
        ->withOptToken("semiColon", TokenType::S_SEMICOLON),

    (new NodeDef(TraitUseInsteadof::class))
        ->withExtends(TraitUseModification::class)
        ->withChild("trait", Name::class)
        ->withToken("doubleColon", TokenType::T_DOUBLE_COLON)
        ->withToken("member", TokenType::T_STRING)
        ->withToken("insteadof", TokenType::T_INSTEADOF)
        ->withChild("excluded", Name::class),

    (new NodeDef(TraitUseAs::class))
        ->withExtends(TraitUseModification::class)
        ->withChild("trait", Name::class)
        ->withToken("doubleColon", TokenType::T_DOUBLE_COLON)
        ->withToken("member", TokenType::T_STRING)
        ->withToken("as", TokenType::T_AS)
        ->withToken("alias", TokenType::T_STRING),
];
