<?php

use Phi\Meta\NodeDef;
use Phi\Nodes\Blocks\RegularBlock;
use Phi\Nodes\Expression;
use Phi\Nodes\Helpers\Default_;
use Phi\Nodes\Helpers\Name;
use Phi\Nodes\Helpers\Parameter;
use Phi\Nodes\Helpers\ReturnType;
use Phi\Nodes\Oop\ClassConstant;
use Phi\Nodes\Oop\Method;
use Phi\Nodes\Oop\Property;
use Phi\Nodes\Oop\TraitMethodRef;
use Phi\Nodes\Oop\TraitUse;
use Phi\Nodes\Oop\TraitUseAs;
use Phi\Nodes\Oop\TraitUseInsteadof;
use Phi\Nodes\Oop\TraitUseModification;
use Phi\TokenType as T;

return [
    (new NodeDef(ClassConstant::class))
        ->withTokenList("modifiers", [T::T_PUBLIC, T::T_PROTECTED, T::T_PRIVATE])
        ->token("keyword", T::T_CONST)
        ->token("name", T::T_STRING)
        ->token("equals", T::S_EQUALS)
        ->node("value", Expression::class)
        ->token("semiColon", T::S_SEMICOLON)
        ->constructor("name", "value"),

    (new NodeDef(Method::class))
        ->withTokenList("modifiers", [T::T_ABSTRACT, T::T_FINAL, T::T_PUBLIC, T::T_PROTECTED, T::T_PRIVATE, T::T_STATIC])
        ->token("keyword", T::T_FUNCTION)
        ->optToken("byReference", T::S_AMPERSAND)
        ->token("name", T::T_STRING)
        ->token("leftParenthesis", T::S_LEFT_PAREN)
        ->sepNodeList("parameters", Parameter::class, T::S_COMMA)
        ->token("rightParenthesis", T::S_RIGHT_PAREN)
        ->optNode("returnType", ReturnType::class)
        ->optNode("body", RegularBlock::class)
        ->optToken("semiColon", T::S_SEMICOLON)
        ->constructor("name"),

    (new NodeDef(Property::class))
        ->withTokenList("modifiers", [T::T_PUBLIC, T::T_PROTECTED, T::T_PRIVATE, T::T_STATIC])
        ->token("name", T::T_VARIABLE)
        ->optNode("default", Default_::class)
        ->token("semiColon", T::S_SEMICOLON)
        ->constructor("name"),

    (new NodeDef(TraitUse::class))
        ->token("keyword", T::T_USE)
        ->sepNodeList("traits", Name::class, T::S_COMMA)
        ->optToken("leftBrace", T::S_LEFT_CURLY_BRACE)
        ->nodeList("modifications", TraitUseModification::class)
        ->optToken("rightBrace", T::S_RIGHT_CURLY_BRACE)
        ->optToken("semiColon", T::S_SEMICOLON),

    (new NodeDef(TraitUseInsteadof::class))
        ->node("method", TraitMethodRef::class)
        ->token("keyword", T::T_INSTEADOF)
        ->sepNodeList("excluded", Name::class, T::S_COMMA)
        ->token("semiColon", T::S_SEMICOLON),
    (new NodeDef(TraitUseAs::class))
        ->node("method", TraitMethodRef::class)
        ->token("keyword", T::T_AS)
        ->optToken("modifier", [T::T_PUBLIC, T::T_PROTECTED, T::T_PRIVATE])
        ->optToken("alias", T::T_STRING)
        ->token("semiColon", T::S_SEMICOLON),
    (new NodeDef(TraitMethodRef::class))
        ->optNode("name", Name::class)
        ->optToken("doubleColon", T::T_DOUBLE_COLON)
        ->token("method", T::T_STRING),
];
