<?php

use Phi\Meta\NodeDef;
use Phi\Nodes\Base\CompoundNode;
use Phi\Nodes\Blocks\AlternativeFormatBlock;
use Phi\Nodes\Blocks\ImplicitBlock;
use Phi\Nodes\Blocks\RegularBlock;
use Phi\Nodes\Expression;
use Phi\Nodes\Expressions\StringInterpolation\ArrayAccessInterpolatedStringVariable;
use Phi\Nodes\Expressions\StringInterpolation\BracedInterpolatedStringVariable;
use Phi\Nodes\Expressions\StringInterpolation\ConstantInterpolatedStringPart;
use Phi\Nodes\Expressions\StringInterpolation\InterpolatedArrayAccessIndex;
use Phi\Nodes\Expressions\StringInterpolation\InterpolatedStringExpression;
use Phi\Nodes\Expressions\StringInterpolation\InterpolatedStringPart;
use Phi\Nodes\Expressions\StringInterpolation\InterpolatedStringVariable;
use Phi\Nodes\Expressions\StringInterpolation\NormalInterpolatedStringVariable;
use Phi\Nodes\Expressions\StringInterpolation\PropertyAccessInterpolatedStringVariable;
use Phi\Nodes\Expressions\StringInterpolation\VariableInterpolatedStringVariable;
use Phi\Nodes\Helpers\Argument;
use Phi\Nodes\Helpers\Default_;
use Phi\Nodes\Helpers\Key;
use Phi\Nodes\Helpers\MemberName;
use Phi\Nodes\Helpers\Name;
use Phi\Nodes\Helpers\NormalMemberName;
use Phi\Nodes\Helpers\Parameter;
use Phi\Nodes\Helpers\ReturnType;
use Phi\Nodes\Helpers\VariableMemberName;
use Phi\Nodes\Oop\Extends_;
use Phi\Nodes\Oop\Implements_;
use Phi\Nodes\RootNode;
use Phi\Nodes\Statement;
use Phi\Nodes\Type;
use Phi\TokenType as T;

return [
    (new NodeDef(Argument::class))
        ->optToken("unpack", T::T_ELLIPSIS)
        ->node("expression", Expression::class, '$this->unpack ? self::CTX_READ : self::CTX_READ|self::CTX_READ_IMPLICIT_BY_REF')
        ->constructor("expression"),

    /** @see Block */
    (new NodeDef(RegularBlock::class))
        ->token("leftBrace", T::S_LEFT_CURLY_BRACE)
        ->nodeList("statements", Statement::class)
        ->token("rightBrace", T::S_RIGHT_CURLY_BRACE)
        ->constructor("statement"),
    (new NodeDef(ImplicitBlock::class))
        ->node("statement", Statement::class)
        ->constructor("statement"),
    (new NodeDef(AlternativeFormatBlock::class))
        ->token("colon", T::S_COLON)
        ->nodeList("statements", Statement::class)
        // opt because e.g. if (0): else: endif;
        ->optToken("endKeyword", [T::T_ENDDECLARE, T::T_ENDFOR, T::T_ENDFOREACH, T::T_ENDIF, T::T_ENDSWITCH, T::T_ENDWHILE])
        ->optToken("delimiter", [T::S_SEMICOLON, T::T_CLOSE_TAG])
        ->constructor("statement"),

    (new NodeDef(Default_::class))
        ->token("equals", T::S_EQUALS)
        ->node("value", Expression::class, CompoundNode::CTX_READ)
        ->constructor("value"),

    (new NodeDef(Extends_::class))
        ->token("keyword", T::T_EXTENDS)
        ->sepNodeList("names", Name::class, T::S_COMMA)
        ->constructor("name"),

    /** @see InterpolatedStringPart */
    (new NodeDef(ConstantInterpolatedStringPart::class))
        ->token("content", T::T_ENCAPSED_AND_WHITESPACE)
        ->constructor("content"),
    /** @see InterpolatedStringVariable */
    (new NodeDef(NormalInterpolatedStringVariable::class))
        ->token("variable", T::T_VARIABLE)
        ->constructor("variable"),
    (new NodeDef(BracedInterpolatedStringVariable::class))
        ->token("leftDelimiter", T::T_DOLLAR_OPEN_CURLY_BRACES)
        ->token("name", T::T_STRING_VARNAME)
        ->token("rightDelimiter", T::S_RIGHT_CURLY_BRACE)
        ->constructor("name"),
    (new NodeDef(VariableInterpolatedStringVariable::class))
        ->token("leftDelimiter", T::T_DOLLAR_OPEN_CURLY_BRACES)
        ->node("name", Expression::class, CompoundNode::CTX_READ)
        ->token("rightDelimiter", T::S_RIGHT_CURLY_BRACE)
        ->constructor("name"),
    (new NodeDef(ArrayAccessInterpolatedStringVariable::class))
        ->node("variable", NormalInterpolatedStringVariable::class)
        ->token("leftBracket", T::S_LEFT_SQUARE_BRACKET)
        ->node("index", InterpolatedArrayAccessIndex::class)
        ->token("rightBracket", T::S_RIGHT_SQUARE_BRACKET)
        ->constructor("variable", "index"),
    (new NodeDef(InterpolatedArrayAccessIndex::class))
        ->optToken("minus", T::S_MINUS)
        ->token("value", [T::T_NUM_STRING, T::T_STRING, T::T_VARIABLE])
        ->constructor("value"),
    (new NodeDef(PropertyAccessInterpolatedStringVariable::class))
        ->node("variable", NormalInterpolatedStringVariable::class)
        ->token("operator", T::T_OBJECT_OPERATOR)
        ->token("name", T::T_STRING)
        ->constructor("variable", "name"),
    (new NodeDef(InterpolatedStringExpression::class))
        ->token("leftDelimiter", T::S_LEFT_CURLY_BRACE)
        ->node("expression", Expression::class, CompoundNode::CTX_READ)
        ->token("rightDelimiter", T::S_RIGHT_CURLY_BRACE)
        ->constructor("expression"),

    (new NodeDef(Implements_::class))
        ->token("keyword", T::T_IMPLEMENTS)
        ->sepNodeList("names", Name::class, T::S_COMMA)
        ->constructor("name"),

    (new NodeDef(Key::class))
        ->node("expression", Expression::class, '$flags')
        ->token("arrow", T::T_DOUBLE_ARROW)
        ->constructor("expression"),

    /** @see MemberName */
    (new NodeDef(NormalMemberName::class))
        ->token("token", T::T_STRING)
        ->constructor("token"),
    (new NodeDef(VariableMemberName::class))
        ->optToken("leftBrace", T::S_LEFT_CURLY_BRACE)
        ->node("expression", Expression::class, CompoundNode::CTX_READ)
        ->optToken("rightBrace", T::S_RIGHT_CURLY_BRACE)
        ->constructor("expression"),

    (new NodeDef(Name::class))
        ->withTokenList("parts", [
            T::T_STRING, T::T_NS_SEPARATOR,
            T::T_STATIC,
            T::T_ARRAY, T::T_CALLABLE,
        ])
        ->constructor("part"),

    (new NodeDef(Parameter::class))
        ->optNode("type", Type::class)
        ->optToken("byReference", T::S_AMPERSAND)
        ->optToken("unpack", T::T_ELLIPSIS)
        ->token("variable", T::T_VARIABLE)
        ->optNode("default", Default_::class)
        ->constructor("variable"),

    (new NodeDef(ReturnType::class))
        ->token("colon", T::S_COLON)
        ->node("type", Type::class)
        ->constructor("type"),

    (new NodeDef(RootNode::class))
        ->nodeList("statements", Statement::class)
        ->optToken("eof", T::T_EOF)
        ->constructor("statement"),
];
