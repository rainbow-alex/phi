<?php

use Phi\Meta\NodeDef;
use Phi\Nodes\AlternativeFormatBlock;
use Phi\Nodes\Argument;
use Phi\Nodes\ArrayItem;
use Phi\Nodes\Block;
use Phi\Nodes\InterpolatedStringExpression;
use Phi\Nodes\InterpolatedStringPart;
use Phi\Nodes\ComplexInterpolatedStringExpression;
use Phi\Nodes\ConstantInterpolatedStringPart;
use Phi\Nodes\Default_;
use Phi\Nodes\Expression;
use Phi\Nodes\VariableMemberName;
use Phi\Nodes\Extends_;
use Phi\Nodes\Implements_;
use Phi\Nodes\ImplicitBlock;
use Phi\Nodes\Key;
use Phi\Nodes\MemberName;
use Phi\Nodes\Name;
use Phi\Nodes\Parameter;
use Phi\Nodes\RegularBlock;
use Phi\Nodes\RegularMemberName;
use Phi\Nodes\RegularName;
use Phi\Nodes\ReturnType;
use Phi\Nodes\RootNode;
use Phi\Nodes\SimpleInterpolatedStringExpression;
use Phi\Nodes\SpecialName;
use Phi\Nodes\Statement;
use Phi\Nodes\Type;
use Phi\Nodes\UseAlias;
use Phi\Nodes\UseName;
use Phi\Token;
use Phi\TokenType;

return [
    (new NodeDef(Argument::class))
        ->withOptToken("unpack", TokenType::T_ELLIPSIS)
        ->withChild("expression", Expression::class)
        ->withConstructor("expression"),

    (new NodeDef(ArrayItem::class))
        ->withOptChild("key", Key::class)
        ->withOptToken("byReference", TokenType::S_AMPERSAND)
        ->withOptChild("value", Expression::class)
        ->withConstructor("value"),

    /** @see Block */
    (new NodeDef(RegularBlock::class))
        ->withExtends(Block::class)
        ->withToken("leftBrace", TokenType::S_LEFT_CURLY_BRACE)
        ->withList("statements", Statement::class)
        ->withToken("rightBrace", TokenType::S_RIGHT_CURLY_BRACE)
        ->withConstructor("statement"),
    (new NodeDef(ImplicitBlock::class))
        ->withExtends(Block::class)
        ->withChild("statement", Statement::class)
        ->withConstructor("statement"),
    (new NodeDef(AlternativeFormatBlock::class))
        ->withExtends(Block::class)
        ->withToken("colon", TokenType::S_COLON)
        ->withList("statements", Statement::class)
        // opt because e.g. if (0): else: endif;
        ->withOptToken("endKeyword", [TokenType::T_ENDDECLARE, TokenType::T_ENDFOR, TokenType::T_ENDFOREACH, TokenType::T_ENDIF, TokenType::T_ENDSWITCH, TokenType::T_ENDWHILE])
        ->withOptToken("delimiter", [TokenType::S_SEMICOLON, TokenType::T_CLOSE_TAG])
        ->withConstructor("statement"),

    (new NodeDef(Default_::class))
        ->withToken("symbol", TokenType::S_EQUALS)
        ->withChild("value", Expression::class)
        ->withConstructor("value"),

    (new NodeDef(Extends_::class))
        ->withToken("keyword", TokenType::T_EXTENDS)
        ->withSepList("names", Name::class, TokenType::S_COMMA)
        ->withConstructor("name"),

    /** @see InterpolatedStringPart */
    /** @see InterpolatedStringExpression */
    (new NodeDef(ConstantInterpolatedStringPart::class))
        ->withExtends(InterpolatedStringPart::class)
        ->withToken("content", TokenType::T_ENCAPSED_AND_WHITESPACE)
        ->withConstructor("content"),
    (new NodeDef(SimpleInterpolatedStringExpression::class))
        ->withExtends(InterpolatedStringExpression::class)
        ->withChild("expression", Expression::class)
        ->withConstructor("expression"),
    (new NodeDef(ComplexInterpolatedStringExpression::class))
        ->withExtends(InterpolatedStringExpression::class)
        ->withToken("leftBrace", TokenType::T_CURLY_OPEN)
        ->withChild("expression", Expression::class)
        ->withToken("rightBrace", TokenType::S_RIGHT_CURLY_BRACE)
        ->withConstructor("expression"),

    (new NodeDef(Implements_::class))
        ->withToken("keyword", TokenType::T_IMPLEMENTS)
        ->withSepList("names", Name::class, TokenType::S_COMMA)
        ->withConstructor("name"),

    (new NodeDef(Key::class))
        ->withChild("value", Expression::class)
        ->withToken("arrow", TokenType::T_DOUBLE_ARROW)
        ->withConstructor("value"),

    /** @see MemberName */
    (new NodeDef(RegularMemberName::class))
        ->withExtends(MemberName::class)
        ->withToken("token", TokenType::T_STRING)
        ->withConstructor("token"),
    (new NodeDef(VariableMemberName::class))
        ->withExtends(MemberName::class)
        ->withOptToken("leftBrace", TokenType::T_STRING) // TODO validate braces
        ->withChild("expression", Expression::class)
        ->withOptToken("rightBrace", TokenType::T_STRING)
        ->withConstructor("expression"),

    /** @see Name */
    (new NodeDef(RegularName::class))
        ->withExtends(Name::class)
        ->withSepTokenList("parts", Token::class, TokenType::T_NS_SEPARATOR),
    (new NodeDef(SpecialName::class))
        ->withExtends(Name::class)
        ->withToken("token", TokenType::T_STATIC)
        ->withConstructor("token"),

    (new NodeDef(Parameter::class))
        ->withOptChild("type", Type::class)
        ->withOptToken("byReference", TokenType::S_AMPERSAND)
        ->withOptToken("ellipsis", TokenType::T_ELLIPSIS)
        ->withToken("variable", TokenType::T_VARIABLE)
        ->withOptChild("default", Default_::class)
        ->withConstructor("variable"),

    (new NodeDef(ReturnType::class))
        ->withToken("symbol", TokenType::S_COLON)
        ->withChild("type", Type::class)
        ->withConstructor("type"),

    (new NodeDef(RootNode::class))
        ->withList("statements", Statement::class)
        ->withOptToken("eof", TokenType::T_EOF)
        ->withConstructor("statement"),

    /** @see UseStatement */
    (new NodeDef(UseName::class))
        ->withChild("name", Name::class)
        ->withOptChild("alias", UseAlias::class),
    (new NodeDef(UseAlias::class))
        ->withToken("keyword", TokenType::T_AS)
        ->withToken("name", TokenType::T_STRING),
];
