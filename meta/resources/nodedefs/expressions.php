<?php

use Phi\Meta\NodeDef;
use Phi\Nodes\AddExpression;
use Phi\Nodes\AliasingExpression;
use Phi\Nodes\AnonymousFunctionExpression;
use Phi\Nodes\AnonymousFunctionUse;
use Phi\Nodes\AnonymousFunctionUseBinding;
use Phi\Nodes\Argument;
use Phi\Nodes\ArrayAccessExpression;
use Phi\Nodes\ArrayItem;
use Phi\Nodes\BinopExpression;
use Phi\Nodes\BitwiseAndExpression;
use Phi\Nodes\BitwiseNotExpression;
use Phi\Nodes\BitwiseOrExpression;
use Phi\Nodes\BitwiseXorExpression;
use Phi\Nodes\BooleanNotExpression;
use Phi\Nodes\CastExpression;
use Phi\Nodes\CInterpolatedStringPart;
use Phi\Nodes\ClassNameResolutionExpression;
use Phi\Nodes\CloneExpression;
use Phi\Nodes\CoalesceExpression;
use Phi\Nodes\CombinedAssignmentExpression;
use Phi\Nodes\ConcatExpression;
use Phi\Nodes\ConstantAccessExpression;
use Phi\Nodes\ConstantStringLiteral;
use Phi\Nodes\CrementExpression;
use Phi\Nodes\DivideExpression;
use Phi\Nodes\EmptyExpression;
use Phi\Nodes\EvalExpression;
use Phi\Nodes\ExitExpression;
use Phi\Nodes\Expression;
use Phi\Nodes\FloatLiteral;
use Phi\Nodes\FunctionCallExpression;
use Phi\Nodes\GreaterThanExpression;
use Phi\Nodes\GreaterThanOrEqualsExpression;
use Phi\Nodes\IncludeLikeExpression;
use Phi\Nodes\InstanceofExpression;
use Phi\Nodes\IntegerLiteral;
use Phi\Nodes\InterpolatedString;
use Phi\Nodes\IsEqualExpression;
use Phi\Nodes\IsIdenticalExpression;
use Phi\Nodes\IsNotEqualExpression;
use Phi\Nodes\IsNotIdenticalExpression;
use Phi\Nodes\IssetExpression;
use Phi\Nodes\Key;
use Phi\Nodes\KeywordBooleanAndExpression;
use Phi\Nodes\KeywordBooleanOrExpression;
use Phi\Nodes\KeywordBooleanXorExpression;
use Phi\Nodes\LessThanExpression;
use Phi\Nodes\LessThanOrEqualsExpression;
use Phi\Nodes\ListExpression;
use Phi\Nodes\LongArrayExpression;
use Phi\Nodes\MagicConstant;
use Phi\Nodes\MemberName;
use Phi\Nodes\MethodCallExpression;
use Phi\Nodes\ModuloExpression;
use Phi\Nodes\MultiplyExpression;
use Phi\Nodes\Name;
use Phi\Nodes\NameExpression;
use Phi\Nodes\NewExpression;
use Phi\Nodes\NumberLiteral;
use Phi\Nodes\Parameter;
use Phi\Nodes\ParenthesizedExpression;
use Phi\Nodes\PostDecrementExpression;
use Phi\Nodes\PostIncrementExpression;
use Phi\Nodes\PowerExpression;
use Phi\Nodes\PreDecrementExpression;
use Phi\Nodes\PreIncrementExpression;
use Phi\Nodes\PrintExpression;
use Phi\Nodes\PropertyAccessExpression;
use Phi\Nodes\RegularAssignmentExpression;
use Phi\Nodes\RegularBlock;
use Phi\Nodes\RegularVariableExpression;
use Phi\Nodes\ReturnType;
use Phi\Nodes\ShiftLeftExpression;
use Phi\Nodes\ShiftRightExpression;
use Phi\Nodes\ShortArrayExpression;
use Phi\Nodes\SpaceshipExpression;
use Phi\Nodes\StaticMethodCallExpression;
use Phi\Nodes\StaticPropertyAccessExpression;
use Phi\Nodes\SubtractExpression;
use Phi\Nodes\SuppressErrorsExpression;
use Phi\Nodes\SymbolBooleanAndExpression;
use Phi\Nodes\SymbolBooleanOrExpression;
use Phi\Nodes\TernaryExpression;
use Phi\Nodes\UnaryMinusExpression;
use Phi\Nodes\UnaryPlusExpression;
use Phi\Nodes\Variable;
use Phi\Nodes\VariableVariableExpression;
use Phi\Nodes\YieldExpression;
use Phi\Nodes\YieldFromExpression;
use Phi\Token;
use PhpParser\Node\Expr;

return [

    (new NodeDef(AddExpression::class))
        ->withExtends(BinopExpression::class)
        ->withChild("left", Expression::class)
        ->withToken("operator", Token::PH_S_PLUS)
        ->withChild("right", Expression::class)
        ->withConstructor("left", "right"),

    (new NodeDef(AliasingExpression::class))
        ->withExtends(Expression::class)
        ->withChild("alias", Expression::class)
        ->withToken("operator1", Token::PH_S_EQUALS)
        ->withToken("operator2", Token::PH_S_AMPERSAND)
        ->withChild("value", Expression::class)
        ->withConstructor("alias", "value"),

    (new NodeDef(ArrayAccessExpression::class))
        ->withExtends(Expression::class)
        ->withChild("accessee", Expression::class)
        ->withToken("leftBracket", Token::PH_S_LEFT_SQUARE_BRACKET)
        ->withOptChild("index", Expression::class)
        ->withToken("rightBracket", Token::PH_S_RIGHT_SQUARE_BRACKET)
        ->withConstructor("accessee", "index"),

    (new NodeDef(BitwiseAndExpression::class))
        ->withExtends(Expression::class)
        ->withChild("left", Expression::class)
        ->withToken("operator", Token::PH_S_AMPERSAND)
        ->withChild("right", Expression::class)
        ->withConstructor("left", "right"),
    (new NodeDef(BitwiseOrExpression::class))
        ->withExtends(Expression::class)
        ->withChild("left", Expression::class)
        ->withToken("operator", Token::PH_S_VERTICAL_BAR)
        ->withChild("right", Expression::class)
        ->withConstructor("left", "right"),
    (new NodeDef(BitwiseNotExpression::class))
        ->withExtends(Expression::class)
        ->withToken("operator", Token::PH_S_TILDE)
        ->withChild("expression", Expression::class)
        ->withConstructor("expression"),
    (new NodeDef(BitwiseXorExpression::class))
        ->withExtends(Expression::class)
        ->withChild("left", Expression::class)
        ->withToken("operator", Token::PH_S_CARAT)
        ->withChild("right", Expression::class)
        ->withConstructor("left", "right"),

    (new NodeDef(BooleanNotExpression::class))
        ->withExtends(Expression::class)
        ->withToken("operator", Token::PH_S_EXCLAMATION_MARK)
        ->withChild("expression", Expression::class)
        ->withConstructor("expression"),

    (new NodeDef(FunctionCallExpression::class))
        ->withExtends(Expression::class)
        ->withChild("callee", Expression::class)
        ->withToken("leftParenthesis", Token::PH_S_LEFT_PAREN)
        ->withSepList("arguments", Argument::class, Token::PH_S_COMMA) // TODO valid
        ->withToken("rightParenthesis", Token::PH_S_RIGHT_PAREN)
        ->withConstructor("callee"),

    (new NodeDef(CastExpression::class))
        ->withExtends(Expression::class)
        ->withToken("cast", Token::CASTS)
        ->withChild("expression", Expression::class)
        ->withConstructor("cast", "expression"),

    (new NodeDef(ClassNameResolutionExpression::class))
        ->withExtends(Expression::class)
        ->withChild("class", Expression::class)
        ->withToken("operator", Token::PH_T_DOUBLE_COLON)
        ->withToken("keyword", Token::PH_T_CLASS)
        ->withConstructor("class"),

    (new NodeDef(CloneExpression::class))
        ->withExtends(Expression::class)
        ->withToken("keyword", Token::PH_T_CLONE)
        ->withChild("expression", Expression::class)
        ->withConstructor("expression"),

    (new NodeDef(CoalesceExpression::class))
        ->withExtends(Expression::class)
        ->withChild("left", Expression::class)
        ->withToken("operator", Token::PH_T_COALESCE)
        ->withChild("right", Expression::class)
        ->withConstructor("left", "right"),

    (new NodeDef(CombinedAssignmentExpression::class))
        ->withExtends(Expression::class)
        ->withChild("lvalue", Expression::class)
        ->withToken("operator", Token::COMBINED_ASSIGNMENT)
        ->withChild("value", Expression::class)
        ->withConstructor("lvalue", "value"),

    (new NodeDef(ConcatExpression::class))
        ->withExtends(Expression::class)
        ->withChild("left", Expression::class)
        ->withToken("operator", Token::PH_S_DOT)
        ->withChild("right", Expression::class)
        ->withConstructor("left", "right"),

    (new NodeDef(ConstantAccessExpression::class))
        ->withExtends(Expression::class)
        ->withChild("accessee", Expression::class)
        ->withToken("operator", Token::PH_T_DOUBLE_COLON)
        ->withToken("name", Token::PH_T_STRING)
        ->withConstructor("accessee", "name"),

    (new NodeDef(ConstantStringLiteral::class))
        ->withExtends(Expression::class)
        ->withToken("source", Token::PH_T_CONSTANT_ENCAPSED_STRING)
        ->withConstructor("source"),

    (new NodeDef(DivideExpression::class))
        ->withExtends(Expression::class)
        ->withChild("left", Expression::class)
        ->withToken("operator", Token::PH_S_FORWARD_SLASH)
        ->withChild("right", Expression::class)
        ->withConstructor("left", "right"),

    (new NodeDef(EmptyExpression::class))
        ->withExtends(Expression::class)
        ->withToken("keyword", Token::PH_T_EMPTY)
        ->withToken("leftParenthesis", Token::PH_S_LEFT_PAREN)
        ->withChild("expression", Expression::class) // TODO valid
        ->withToken("rightParenthesis", Token::PH_S_RIGHT_PAREN)
        ->withConstructor("expression"),

    (new NodeDef(EvalExpression::class))
        ->withExtends(Expression::class)
        ->withToken("keyword", Token::PH_T_EVAL)
        ->withToken("leftParenthesis", Token::PH_S_LEFT_PAREN)
        ->withChild("expression", Expression::class)
        ->withToken("rightParenthesis", Token::PH_S_RIGHT_PAREN)
        ->withConstructor("expression"),

    (new NodeDef(ExitExpression::class))
        ->withExtends(Expression::class)
        ->withToken("keyword", Token::PH_T_EXIT)
        ->withToken("leftParenthesis", Token::PH_S_LEFT_PAREN)
        ->withOptChild("expression", Expression::class)
        ->withToken("rightParenthesis", Token::PH_S_RIGHT_PAREN)
        ->withConstructor("expression"),

    (new NodeDef(GreaterThanExpression::class))
        ->withExtends(Expression::class)
        ->withChild("left", Expression::class)
        ->withToken("operator", Token::PH_S_GT)
        ->withChild("right", Expression::class)
        ->withConstructor("left", "right"),

    (new NodeDef(GreaterThanOrEqualsExpression::class))
        ->withExtends(Expression::class)
        ->withChild("left", Expression::class)
        ->withToken("operator", Token::PH_T_IS_GREATER_OR_EQUAL)
        ->withChild("right", Expression::class)
        ->withConstructor("left", "right"),

    (new NodeDef(AnonymousFunctionExpression::class))
        ->withExtends(Expression::class)
        ->withOptToken("static", Token::PH_T_STATIC)
        ->withToken("keyword", Token::PH_T_FUNCTION)
        ->withOptToken("byReference", Token::PH_S_AMPERSAND)
        ->withToken("leftParenthesis", Token::PH_S_LEFT_PAREN)
        ->withSepList("parameters", Parameter::class, Token::PH_S_COMMA)
        ->withToken("rightParenthesis", Token::PH_S_RIGHT_PAREN)
        ->withOptChild("use", AnonymousFunctionUse::class)
        ->withOptChild("returnType", ReturnType::class)
        ->withChild("body", RegularBlock::class),

    (new NodeDef(AnonymousFunctionUse::class))
        ->withToken("keyword", Token::PH_T_USE)
        ->withToken("leftParenthesis", Token::PH_S_LEFT_PAREN)
        ->withSepList("bindings", AnonymousFunctionUseBinding::class, Token::PH_S_COMMA)
        ->withToken("rightParenthesis", Token::PH_S_RIGHT_PAREN),

    (new NodeDef(AnonymousFunctionUseBinding::class))
        ->withOptToken("byReference", Token::PH_S_AMPERSAND)
        ->withToken("variable", Token::PH_T_VARIABLE),

    (new NodeDef(IncludeLikeExpression::class))
        ->withExtends(Expression::class)
        ->withToken("keyword", [Token::PH_T_INCLUDE, Token::PH_T_INCLUDE_ONCE, Token::PH_T_REQUIRE, Token::PH_T_REQUIRE_ONCE])
        ->withChild("expression", Expression::class)
        ->withConstructor("expression"),

    (new NodeDef(InstanceofExpression::class))
        ->withExtends(Expression::class)
        ->withChild("value", Expression::class)
        ->withToken("operator", Token::PH_T_INSTANCEOF)
        ->withChild("type", Expression::class)
        ->withConstructor("value", "type"),

    (new NodeDef(InterpolatedString::class)) // TODO split
        ->withExtends(Expression::class)
        ->withToken("leftDelimiter", [Token::PH_S_DOUBLE_QUOTE, Token::PH_T_START_HEREDOC])
        ->withList("parts", CInterpolatedStringPart::class)
        ->withToken("rightDelimiter", [Token::PH_S_DOUBLE_QUOTE, Token::PH_T_END_HEREDOC]),

    (new NodeDef(IsEqualExpression::class))
        ->withExtends(BinopExpression::class)
        ->withChild("left", Expression::class)
        ->withToken("operator", Token::PH_T_IS_EQUAL)
        ->withChild("right", Expression::class)
        ->withConstructor("left", "right"),

    (new NodeDef(IsIdenticalExpression::class))
        ->withExtends(BinopExpression::class)
        ->withChild("left", Expression::class)
        ->withToken("operator", Token::PH_T_IS_IDENTICAL)
        ->withChild("right", Expression::class)
        ->withConstructor("left", "right"),

    (new NodeDef(IsNotEqualExpression::class))
        ->withExtends(BinopExpression::class)
        ->withChild("left", Expression::class)
        ->withToken("operator", Token::PH_T_IS_NOT_EQUAL)
        ->withChild("right", Expression::class)
        ->withConstructor("left", "right"),

    (new NodeDef(IsNotIdenticalExpression::class))
        ->withExtends(BinopExpression::class)
        ->withChild("left", Expression::class)
        ->withToken("operator", Token::PH_T_IS_NOT_IDENTICAL)
        ->withChild("right", Expression::class)
        ->withConstructor("left", "right"),

    (new NodeDef(IssetExpression::class))
        ->withExtends(Expression::class)
        ->withToken("keyword", Token::PH_T_ISSET)
        ->withToken("leftParenthesis", Token::PH_S_LEFT_PAREN)
        ->withSepList("expressions", Expression::class, Token::PH_S_COMMA)
        ->withToken("rightParenthesis", Token::PH_S_RIGHT_PAREN)
        ->withConstructor("expression"),

    (new NodeDef(KeywordBooleanAndExpression::class))
        ->withExtends(Expression::class)
        ->withChild("left", Expression::class)
        ->withToken("operator", Token::PH_T_LOGICAL_AND)
        ->withChild("right", Expression::class)
        ->withConstructor("left", "right"),

    (new NodeDef(KeywordBooleanOrExpression::class))
        ->withExtends(Expression::class)
        ->withChild("left", Expression::class)
        ->withToken("operator", Token::PH_T_LOGICAL_OR)
        ->withChild("right", Expression::class)
        ->withConstructor("left", "right"),

    (new NodeDef(KeywordBooleanXorExpression::class))
        ->withExtends(Expression::class)
        ->withChild("left", Expression::class)
        ->withToken("operator", Token::PH_T_LOGICAL_XOR)
        ->withChild("right", Expression::class)
        ->withConstructor("left", "right"),

    (new NodeDef(LessThanExpression::class))
        ->withExtends(Expression::class)
        ->withChild("left", Expression::class)
        ->withToken("operator", Token::PH_S_LT)
        ->withChild("right", Expression::class)
        ->withConstructor("left", "right"),

    (new NodeDef(LessThanOrEqualsExpression::class))
        ->withExtends(Expression::class)
        ->withChild("left", Expression::class)
        ->withToken("operator", Token::PH_T_IS_SMALLER_OR_EQUAL)
        ->withChild("right", Expression::class)
        ->withConstructor("left", "right"),

    (new NodeDef(ListExpression::class))
        ->withExtends(Expression::class)
        ->withToken("keyword", Token::PH_T_LIST)
        ->withToken("leftParenthesis", Token::PH_S_LEFT_PAREN)
        ->withSepList("expressions", Expression::class, Token::PH_S_COMMA) // TODO vali
        ->withToken("rightParenthesis", Token::PH_S_RIGHT_PAREN),

    (new NodeDef(LongArrayExpression::class))
        ->withToken("keyword", Token::PH_T_ARRAY)
        ->withToken("leftParenthesis", Token::PH_S_LEFT_PAREN)
        ->withSepList("items", ArrayItem::class, Token::PH_S_COMMA) // TODO vali
        ->withToken("rightParenthesis", Token::PH_S_RIGHT_PAREN),

    (new NodeDef(MagicConstant::class))
        ->withExtends(Expression::class)
        ->withToken("token", Token::MAGIC_CONSTANTS),

    (new NodeDef(MethodCallExpression::class))
        ->withExtends(Expression::class)
        ->withChild("object", Expression::class) // TODO name
        ->withToken("operator", Token::PH_T_OBJECT_OPERATOR)
        ->withChild("name", MemberName::class)
        ->withToken("leftParenthesis", Token::PH_S_LEFT_PAREN)
        ->withSepList("arguments", Argument::class, Token::PH_S_COMMA)
        ->withToken("rightParenthesis", Token::PH_S_RIGHT_PAREN)
        ->withConstructor("object", "operator"),

    /** @see NumberLiteral */
    (new NodeDef(IntegerLiteral::class))
        ->withExtends(NumberLiteral::class)
        ->withToken("token", Token::PH_T_LNUMBER)
        ->withConstructor("token"),
    (new NodeDef(FloatLiteral::class))
        ->withExtends(NumberLiteral::class)
        ->withToken("token", Token::PH_T_DNUMBER)
        ->withConstructor("token"),

    (new NodeDef(PropertyAccessExpression::class))
        ->withExtends(Expression::class)
        ->withChild("accessee", Expression::class)
        ->withToken("operator", Token::PH_T_OBJECT_OPERATOR)
        ->withChild("name", MemberName::class)
        ->withConstructor("accessee", "name"),

    (new NodeDef(ModuloExpression::class))
        ->withExtends(Expression::class)
        ->withChild("left", Expression::class)
        ->withToken("operator", Token::PH_S_MODULO)
        ->withChild("right", Expression::class)
        ->withConstructor("left", "right"),

    (new NodeDef(MultiplyExpression::class))
        ->withExtends(Expression::class)
        ->withChild("left", Expression::class)
        ->withToken("operator", Token::PH_S_ASTERISK)
        ->withChild("right", Expression::class)
        ->withConstructor("left", "right"),

    (new NodeDef(NewExpression::class))
        ->withExtends(Expression::class)
        ->withToken("keyword", Token::PH_T_NEW)
        ->withChild("class", Expression::class)
        ->withOptToken("leftParenthesis", Token::PH_S_LEFT_PAREN)
        ->withSepList("arguments", Argument::class, Token::PH_S_COMMA)
        ->withOptToken("rightParenthesis", Token::PH_S_RIGHT_PAREN)
        ->withConstructor("class"),

    (new NodeDef(ParenthesizedExpression::class))
        ->withExtends(Expression::class)
        ->withToken("leftParenthesis", Token::PH_S_LEFT_PAREN)
        ->withChild("expression", Expression::class)
        ->withToken("rightParenthesis", Token::PH_S_RIGHT_PAREN)
        ->withConstructor("expression"),

    (new NodeDef(PostDecrementExpression::class))
        ->withExtends(CrementExpression::class)
        ->withChild("expression", Expression::class)
        ->withToken("operator", Token::PH_T_DEC)
        ->withConstructor("expression"),
    (new NodeDef(PostIncrementExpression::class))
        ->withExtends(CrementExpression::class)
        ->withChild("expression", Expression::class)
        ->withToken("operator", Token::PH_T_INC)
        ->withConstructor("expression"),

    (new NodeDef(PowerExpression::class))
        ->withExtends(Expression::class)
        ->withChild("left", Expression::class)
        ->withToken("operator", Token::PH_T_POW)
        ->withChild("right", Expression::class)
        ->withConstructor("left", "right"),

    (new NodeDef(PreDecrementExpression::class))
        ->withExtends(CrementExpression::class)
        ->withToken("operator", Token::PH_T_DEC)
        ->withChild("expression", Expression::class)
        ->withConstructor("expression"),
    (new NodeDef(PreIncrementExpression::class))
        ->withExtends(CrementExpression::class)
        ->withToken("operator", Token::PH_T_INC)
        ->withChild("expression", Expression::class)
        ->withConstructor("expression"),

    (new NodeDef(PrintExpression::class))
        ->withExtends(Expression::class)
        ->withToken("keyword", Token::PH_T_PRINT)
        ->withChild("expression", Expression::class)
        ->withConstructor("expression"),

    (new NodeDef(RegularAssignmentExpression::class))
        ->withExtends(Expression::class)
        ->withChild("lvalue", Expression::class)
        ->withToken("operator", Token::PH_S_EQUALS)
        ->withChild("value", Expression::class)
        ->withConstructor("lvalue", "value"),

    (new NodeDef(NameExpression::class))
        ->withExtends(Expression::class)
        ->withChild("name", Name::class)
        ->withConstructor("name"),

    (new NodeDef(RegularVariableExpression::class))
        ->withExtends(Variable::class)
        ->withToken("variable", Token::PH_T_VARIABLE)
        ->withConstructor("variable"),

    (new NodeDef(ShiftLeftExpression::class))
        ->withExtends(Expression::class)
        ->withChild("left", Expression::class)
        ->withToken("operator", Token::PH_T_SL)
        ->withChild("right", Expression::class)
        ->withConstructor("left", "right"),

    (new NodeDef(ShiftRightExpression::class))
        ->withExtends(Expression::class)
        ->withChild("left", Expression::class)
        ->withToken("operator", Token::PH_T_SL)
        ->withChild("right", Expression::class)
        ->withConstructor("left", "right"),

    (new NodeDef(ShortArrayExpression::class))
        ->withExtends(Expression::class)
        ->withToken("leftBracket", Token::PH_S_LEFT_SQUARE_BRACKET)
        ->withSepList("items", ArrayItem::class, Token::PH_S_COMMA)
        ->withToken("rightBracket", Token::PH_S_RIGHT_SQUARE_BRACKET)
        ->withConstructor("item"),

    (new NodeDef(SpaceshipExpression::class))
        ->withExtends(Expression::class)
        ->withChild("left", Expression::class)
        ->withToken("operator", Token::PH_T_SPACESHIP)
        ->withChild("right", Expression::class)
        ->withConstructor("left", "right"),

    (new NodeDef(StaticMethodCallExpression::class))
        ->withExtends(Expression::class)
        ->withChild("class", Expression::class)
        ->withToken("operator", Token::PH_T_DOUBLE_COLON)
        ->withChild("name", MemberName::class)
        ->withToken("leftParenthesis", Token::PH_S_LEFT_PAREN)
        ->withSepList("arguments", Argument::class, Token::PH_S_COMMA)
        ->withToken("rightParenthesis", Token::PH_S_RIGHT_PAREN)
        ->withConstructor("class", "name"),

    (new NodeDef(StaticPropertyAccessExpression::class))
        ->withExtends(Expression::class)
        ->withChild("accessee", Expression::class)
        ->withToken("operator", Token::PH_T_DOUBLE_COLON)
        ->withChild("name", Variable::class) // TODO! this should be a token!!!
        ->withConstructor("accessee", "name"),

    (new NodeDef(SubtractExpression::class))
        ->withExtends(Expression::class)
        ->withChild("left", Expression::class)
        ->withToken("operator", Token::PH_S_MINUS)
        ->withChild("right", Expression::class)
        ->withConstructor("left", "right"),

    (new NodeDef(SuppressErrorsExpression::class))
        ->withExtends(Expression::class)
        ->withToken("operator", Token::PH_S_AT)
        ->withChild("expression", Expression::class)
        ->withConstructor("expression"),

    (new NodeDef(SymbolBooleanAndExpression::class))
        ->withExtends(Expression::class)
        ->withChild("left", Expression::class)
        ->withToken("operator", Token::PH_T_BOOLEAN_AND)
        ->withChild("right", Expression::class)
        ->withConstructor("left", "right"),

    (new NodeDef(SymbolBooleanOrExpression::class))
        ->withExtends(Expression::class)
        ->withChild("left", Expression::class)
        ->withToken("operator", Token::PH_T_BOOLEAN_OR)
        ->withChild("right", Expression::class)
        ->withConstructor("left", "right"),

    (new NodeDef(TernaryExpression::class))
        ->withExtends(Expression::class)
        ->withChild("test", Expression::class)
        ->withToken("questionMark", Token::PH_S_QUESTION_MARK)
        ->withOptChild("then", Expression::class)
        ->withToken("colon", Token::PH_S_COLON)
        ->withChild("else", Expression::class)
        ->withConstructor("test", "then", "else"),

    (new NodeDef(UnaryMinusExpression::class))
        ->withExtends(Expression::class)
        ->withToken("symbol", Token::PH_S_MINUS)
        ->withChild("expression", Expression::class)
        ->withConstructor("expression"),

    (new NodeDef(UnaryPlusExpression::class))
        ->withExtends(Expression::class)
        ->withToken("symbol", Token::PH_S_PLUS)
        ->withChild("expression", Expression::class)
        ->withConstructor("expression"),

    (new NodeDef(VariableVariableExpression::class))
        ->withExtends(Variable::class)
        ->withToken("dollar", Token::PH_S_DOLLAR)
        ->withOptToken("leftBrace", Token::PH_S_LEFT_CURLY_BRACE)
        ->withChild("name", Expression::class)
        ->withOptToken("rightBrace", Token::PH_S_RIGHT_CURLY_BRACE)
        ->withConstructor("name"),

    (new NodeDef(YieldExpression::class)) // TODO by ref
        ->withExtends(Expression::class)
        ->withToken("keyword", Token::PH_T_YIELD)
        ->withOptChild("key", Key::class)
        ->withChild("expression", Expression::class)
        ->withConstructor("expression"),

    (new NodeDef(YieldFromExpression::class))
        ->withExtends(Expression::class)
        ->withToken("keyword", Token::PH_T_YIELD_FROM)
        ->withChild("expression", Expression::class)
        ->withConstructor("expression"),
];
