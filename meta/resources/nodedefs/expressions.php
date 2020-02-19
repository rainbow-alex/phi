<?php

use Phi\Meta\NodeDef;
use Phi\Nodes\Base\CompoundNode as C;
use Phi\Nodes\Blocks\RegularBlock;
use Phi\Nodes\Expression;
use Phi\Nodes\Expressions\AddExpression;
use Phi\Nodes\Expressions\AliasExpression;
use Phi\Nodes\Expressions\AnonymousClassNewExpression;
use Phi\Nodes\Expressions\AnonymousFunctionUse;
use Phi\Nodes\Expressions\AnonymousFunctionUseBinding;
use Phi\Nodes\Expressions\ArrayAccessExpression;
use Phi\Nodes\Expressions\ArrayItem;
use Phi\Nodes\Expressions\ArrowFunctionExpression;
use Phi\Nodes\Expressions\AssignExpression;
use Phi\Nodes\Expressions\BitwiseAndExpression;
use Phi\Nodes\Expressions\BitwiseNotExpression;
use Phi\Nodes\Expressions\BitwiseOrExpression;
use Phi\Nodes\Expressions\BitwiseXorExpression;
use Phi\Nodes\Expressions\Cast\ArrayCastExpression;
use Phi\Nodes\Expressions\Cast\BooleanCastExpression;
use Phi\Nodes\Expressions\Cast\FloatCastExpression;
use Phi\Nodes\Expressions\Cast\IntegerCastExpression;
use Phi\Nodes\Expressions\Cast\ObjectCastExpression;
use Phi\Nodes\Expressions\Cast\StringCastExpression;
use Phi\Nodes\Expressions\Cast\UnsetCastExpression;
use Phi\Nodes\Expressions\CastExpression;
use Phi\Nodes\Expressions\ClassNameResolutionExpression;
use Phi\Nodes\Expressions\CloneExpression;
use Phi\Nodes\Expressions\CoalesceExpression;
use Phi\Nodes\Expressions\CombinedAssignExpression;
use Phi\Nodes\Expressions\CombinedAssignment\AddAssignExpression;
use Phi\Nodes\Expressions\CombinedAssignment\BitwiseAndAssignExpression;
use Phi\Nodes\Expressions\CombinedAssignment\BitwiseOrAssignExpression;
use Phi\Nodes\Expressions\CombinedAssignment\BitwiseXorAssignExpression;
use Phi\Nodes\Expressions\CombinedAssignment\CoalesceAssignExpression;
use Phi\Nodes\Expressions\CombinedAssignment\ConcatAssignExpression;
use Phi\Nodes\Expressions\CombinedAssignment\DivideAssignExpression;
use Phi\Nodes\Expressions\CombinedAssignment\ModuloAssignExpression;
use Phi\Nodes\Expressions\CombinedAssignment\MultiplyAssignExpression;
use Phi\Nodes\Expressions\CombinedAssignment\PowerAssignExpression;
use Phi\Nodes\Expressions\CombinedAssignment\ShiftLeftAssignExpression;
use Phi\Nodes\Expressions\CombinedAssignment\ShiftRightAssignExpression;
use Phi\Nodes\Expressions\CombinedAssignment\SubtractAssignExpression;
use Phi\Nodes\Expressions\ConcatExpression;
use Phi\Nodes\Expressions\ConstantAccessExpression;
use Phi\Nodes\Expressions\DivideExpression;
use Phi\Nodes\Expressions\DoubleQuotedStringLiteral;
use Phi\Nodes\Expressions\EmptyExpression;
use Phi\Nodes\Expressions\EvalExpression;
use Phi\Nodes\Expressions\ExececutionExpression;
use Phi\Nodes\Expressions\ExitExpression;
use Phi\Nodes\Expressions\FloatLiteral;
use Phi\Nodes\Expressions\FunctionCallExpression;
use Phi\Nodes\Expressions\GreaterThanExpression;
use Phi\Nodes\Expressions\GreaterThanOrEqualsExpression;
use Phi\Nodes\Expressions\HeredocStringLiteral;
use Phi\Nodes\Expressions\IncludeExpression;
use Phi\Nodes\Expressions\IncludeLikeExpression;
use Phi\Nodes\Expressions\IncludeOnceExpression;
use Phi\Nodes\Expressions\InstanceofExpression;
use Phi\Nodes\Expressions\IntegerLiteral;
use Phi\Nodes\Expressions\IsEqualExpression;
use Phi\Nodes\Expressions\IsIdenticalExpression;
use Phi\Nodes\Expressions\IsNotEqualExpression;
use Phi\Nodes\Expressions\IsNotIdenticalExpression;
use Phi\Nodes\Expressions\IssetExpression;
use Phi\Nodes\Expressions\KeywordAndExpression;
use Phi\Nodes\Expressions\KeywordOrExpression;
use Phi\Nodes\Expressions\KeywordXorExpression;
use Phi\Nodes\Expressions\LessThanExpression;
use Phi\Nodes\Expressions\LessThanOrEqualsExpression;
use Phi\Nodes\Expressions\ListExpression;
use Phi\Nodes\Expressions\LongArrayExpression;
use Phi\Nodes\Expressions\MagicConstant;
use Phi\Nodes\Expressions\MagicConstant\ClassMagicConstant;
use Phi\Nodes\Expressions\MagicConstant\DirMagicConstant;
use Phi\Nodes\Expressions\MagicConstant\FileMagicConstant;
use Phi\Nodes\Expressions\MagicConstant\FunctionMagicConstant;
use Phi\Nodes\Expressions\MagicConstant\LineMagicConstant;
use Phi\Nodes\Expressions\MagicConstant\MethodMagicConstant;
use Phi\Nodes\Expressions\MagicConstant\NamespaceMagicConstant;
use Phi\Nodes\Expressions\MagicConstant\TraitMagicConstant;
use Phi\Nodes\Expressions\MethodCallExpression;
use Phi\Nodes\Expressions\ModuloExpression;
use Phi\Nodes\Expressions\MultiplyExpression;
use Phi\Nodes\Expressions\NameExpression;
use Phi\Nodes\Expressions\NewExpression;
use Phi\Nodes\Expressions\NormalAnonymousFunctionExpression;
use Phi\Nodes\Expressions\NormalNewExpression;
use Phi\Nodes\Expressions\NormalVariableExpression;
use Phi\Nodes\Expressions\NotExpression;
use Phi\Nodes\Expressions\NowdocStringLiteral;
use Phi\Nodes\Expressions\NumberLiteral;
use Phi\Nodes\Expressions\ParenthesizedExpression;
use Phi\Nodes\Expressions\PostDecrementExpression;
use Phi\Nodes\Expressions\PostIncrementExpression;
use Phi\Nodes\Expressions\PowerExpression;
use Phi\Nodes\Expressions\PreDecrementExpression;
use Phi\Nodes\Expressions\PreIncrementExpression;
use Phi\Nodes\Expressions\PrintExpression;
use Phi\Nodes\Expressions\PropertyAccessExpression;
use Phi\Nodes\Expressions\RequireExpression;
use Phi\Nodes\Expressions\RequireOnceExpression;
use Phi\Nodes\Expressions\ShiftLeftExpression;
use Phi\Nodes\Expressions\ShiftRightExpression;
use Phi\Nodes\Expressions\ShortArrayExpression;
use Phi\Nodes\Expressions\SingleQuotedStringLiteral;
use Phi\Nodes\Expressions\SpaceshipExpression;
use Phi\Nodes\Expressions\StaticExpression;
use Phi\Nodes\Expressions\StaticMethodCallExpression;
use Phi\Nodes\Expressions\StaticPropertyAccessExpression;
use Phi\Nodes\Expressions\StringInterpolation\InterpolatedStringPart;
use Phi\Nodes\Expressions\StringLiteral;
use Phi\Nodes\Expressions\SubtractExpression;
use Phi\Nodes\Expressions\SuppressErrorsExpression;
use Phi\Nodes\Expressions\SymbolAndExpression;
use Phi\Nodes\Expressions\SymbolOrExpression;
use Phi\Nodes\Expressions\TernaryExpression;
use Phi\Nodes\Expressions\UnaryMinusExpression;
use Phi\Nodes\Expressions\UnaryPlusExpression;
use Phi\Nodes\Expressions\VariableExpression;
use Phi\Nodes\Expressions\VariableVariableExpression;
use Phi\Nodes\Expressions\YieldExpression;
use Phi\Nodes\Expressions\YieldFromExpression;
use Phi\Nodes\Helpers\Argument;
use Phi\Nodes\Helpers\Key;
use Phi\Nodes\Helpers\MemberName;
use Phi\Nodes\Helpers\Name;
use Phi\Nodes\Helpers\Parameter;
use Phi\Nodes\Helpers\ReturnType;
use Phi\Nodes\Oop\Extends_;
use Phi\Nodes\Oop\Implements_;
use Phi\Nodes\Oop\OopMember;
use Phi\TokenType as T;

return [

	(new NodeDef(AddExpression::class))
		->node("left", Expression::class, C::CTX_READ)
		->token("operator", T::S_PLUS)
		->node("right", Expression::class, C::CTX_READ)
		->constructor("left", "right")
		->invalidContexts(C::CTX_WRITE | C::CTX_ALIAS_READ | C::CTX_ALIAS_WRITE),

	(new NodeDef(AliasExpression::class))
		->node("left", Expression::class, C::CTX_ALIAS_WRITE)
		->token("operator1", T::S_EQUALS)
		->token("operator2", T::S_AMPERSAND)
		->node("right", Expression::class, C::CTX_ALIAS_READ)
		->constructor("left", "right")
		->invalidContexts(C::CTX_WRITE | C::CTX_ALIAS_READ | C::CTX_ALIAS_WRITE),

	(new NodeDef(NormalAnonymousFunctionExpression::class))
		->optToken("staticModifier", T::T_STATIC)
		->token("keyword", T::T_FUNCTION)
		->optToken("byReference", T::S_AMPERSAND)
		->token("leftParenthesis", T::S_LEFT_PARENTHESIS)
		->sepNodeList("parameters", Parameter::class, T::S_COMMA)
		->token("rightParenthesis", T::S_RIGHT_PARENTHESIS)
		->optNode("use", AnonymousFunctionUse::class)
		->optNode("returnType", ReturnType::class)
		->node("body", RegularBlock::class)
		->invalidContexts(C::CTX_WRITE | C::CTX_ALIAS_READ | C::CTX_ALIAS_WRITE),
	(new NodeDef(AnonymousFunctionUse::class))
		->token("keyword", T::T_USE)
		->token("leftParenthesis", T::S_LEFT_PARENTHESIS)
		->sepNodeList("bindings", AnonymousFunctionUseBinding::class, T::S_COMMA)
		->token("rightParenthesis", T::S_RIGHT_PARENTHESIS),
	(new NodeDef(AnonymousFunctionUseBinding::class))
		->optToken("byReference", T::S_AMPERSAND)
		->token("variable", T::T_VARIABLE),

	(new NodeDef(ArrayAccessExpression::class))
		->node("expression", Expression::class, C::CTX_READ)
		->token("leftBracket", T::S_LEFT_SQUARE_BRACKET)
		->optNode("index", Expression::class, C::CTX_READ)
		->token("rightBracket", T::S_RIGHT_SQUARE_BRACKET)
		->constructor("expression", "index"),

	(new NodeDef(AssignExpression::class))
		->node("left", Expression::class, C::CTX_WRITE)
		->token("operator", T::S_EQUALS)
		->node("right", Expression::class, C::CTX_READ)
		->constructor("left", "right")
		->invalidContexts(C::CTX_WRITE | C::CTX_ALIAS_READ | C::CTX_ALIAS_WRITE),

	/** @see ArrayExpression */
	(new NodeDef(LongArrayExpression::class))
		->token("keyword", T::T_ARRAY)
		->token("leftParenthesis", T::S_LEFT_PARENTHESIS)
		->sepNodeList("items", ArrayItem::class, T::S_COMMA)
		->token("rightParenthesis", T::S_RIGHT_PARENTHESIS)
		->invalidContexts(C::CTX_ALIAS_READ | C::CTX_ALIAS_WRITE)
		->validateChildren(false),
	(new NodeDef(ShortArrayExpression::class))
		->token("leftBracket", T::S_LEFT_SQUARE_BRACKET)
		->sepNodeList("items", ArrayItem::class, T::S_COMMA)
		->token("rightBracket", T::S_RIGHT_SQUARE_BRACKET)
		->constructor("item")
		->invalidContexts(C::CTX_ALIAS_READ | C::CTX_ALIAS_WRITE)
		->validateChildren(false),

	(new NodeDef(ArrowFunctionExpression::class))
		->optToken("staticKeyword", T::T_STATIC)
		->token("keyword", T::T_FN)
		->optToken("byReference", T::S_AMPERSAND)
		->token("leftParenthesis", T::S_LEFT_PARENTHESIS)
		->sepNodeList("parameters", Parameter::class, T::S_COMMA)
		->token("rightParenthesis", T::S_RIGHT_PARENTHESIS)
		->optNode("returnType", ReturnType::class)
		->token("arrow", T::T_DOUBLE_ARROW)
		->node("body", Expression::class, C::CTX_READ)
		->invalidContexts(C::CTX_WRITE | C::CTX_ALIAS_READ | C::CTX_ALIAS_WRITE),

	(new NodeDef(BitwiseAndExpression::class))
		->node("left", Expression::class, C::CTX_READ)
		->token("operator", T::S_AMPERSAND)
		->node("right", Expression::class, C::CTX_READ)
		->constructor("left", "right")
		->invalidContexts(C::CTX_WRITE | C::CTX_ALIAS_READ | C::CTX_ALIAS_WRITE),
	(new NodeDef(BitwiseOrExpression::class))
		->node("left", Expression::class, C::CTX_READ)
		->token("operator", T::S_VERTICAL_BAR)
		->node("right", Expression::class, C::CTX_READ)
		->constructor("left", "right")
		->invalidContexts(C::CTX_WRITE | C::CTX_ALIAS_READ | C::CTX_ALIAS_WRITE),
	(new NodeDef(BitwiseNotExpression::class))
		->token("operator", T::S_TILDE)
		->node("expression", Expression::class, C::CTX_READ)
		->constructor("expression")
		->invalidContexts(C::CTX_WRITE | C::CTX_ALIAS_READ | C::CTX_ALIAS_WRITE),
	(new NodeDef(BitwiseXorExpression::class))
		->node("left", Expression::class, C::CTX_READ)
		->token("operator", T::S_CARET)
		->node("right", Expression::class, C::CTX_READ)
		->constructor("left", "right")
		->invalidContexts(C::CTX_WRITE | C::CTX_ALIAS_READ | C::CTX_ALIAS_WRITE),

	(new NodeDef(NotExpression::class))
		->token("operator", T::S_EXCLAMATION_MARK)
		->node("expression", Expression::class, C::CTX_READ)
		->constructor("expression")
		->invalidContexts(C::CTX_WRITE | C::CTX_ALIAS_READ | C::CTX_ALIAS_WRITE),

	(new NodeDef(FunctionCallExpression::class))
		->node("callable", Expression::class, C::CTX_READ)
		->token("leftParenthesis", T::S_LEFT_PARENTHESIS)
		->sepNodeList("arguments", Argument::class, T::S_COMMA)
		->token("rightParenthesis", T::S_RIGHT_PARENTHESIS)
		->constructor("callable")
		->invalidContexts(C::CTX_WRITE | C::CTX_ALIAS_WRITE),

	/** @see CastExpression */
	(new NodeDef(ArrayCastExpression::class))
		->token("operator", T::T_ARRAY_CAST)
		->node("expression", Expression::class, C::CTX_READ)
		->constructor("operator", "expression")
		->invalidContexts(C::CTX_WRITE | C::CTX_ALIAS_READ | C::CTX_ALIAS_WRITE),
	(new NodeDef(BooleanCastExpression::class))
		->token("operator", T::T_BOOL_CAST)
		->node("expression", Expression::class, C::CTX_READ)
		->constructor("operator", "expression")
		->invalidContexts(C::CTX_WRITE | C::CTX_ALIAS_READ | C::CTX_ALIAS_WRITE),
	(new NodeDef(FloatCastExpression::class))
		->token("operator", T::T_DOUBLE_CAST)
		->node("expression", Expression::class, C::CTX_READ)
		->constructor("operator", "expression")
		->invalidContexts(C::CTX_WRITE | C::CTX_ALIAS_READ | C::CTX_ALIAS_WRITE),
	(new NodeDef(IntegerCastExpression::class))
		->token("operator", T::T_INT_CAST)
		->node("expression", Expression::class, C::CTX_READ)
		->constructor("operator", "expression")
		->invalidContexts(C::CTX_WRITE | C::CTX_ALIAS_READ | C::CTX_ALIAS_WRITE),
	(new NodeDef(ObjectCastExpression::class))
		->token("operator", T::T_OBJECT_CAST)
		->node("expression", Expression::class, C::CTX_READ)
		->constructor("operator", "expression")
		->invalidContexts(C::CTX_WRITE | C::CTX_ALIAS_READ | C::CTX_ALIAS_WRITE),
	(new NodeDef(StringCastExpression::class))
		->token("operator", T::T_STRING_CAST)
		->node("expression", Expression::class, C::CTX_READ)
		->constructor("operator", "expression")
		->invalidContexts(C::CTX_WRITE | C::CTX_ALIAS_READ | C::CTX_ALIAS_WRITE),
	(new NodeDef(UnsetCastExpression::class))
		->token("operator", T::T_UNSET_CAST)
		->node("expression", Expression::class, C::CTX_READ)
		->constructor("operator", "expression")
		->invalidContexts(C::CTX_WRITE | C::CTX_ALIAS_READ | C::CTX_ALIAS_WRITE),

	(new NodeDef(ClassNameResolutionExpression::class))
		->node("class", Expression::class, C::CTX_READ)
		->token("operator", T::T_DOUBLE_COLON)
		->token("keyword", T::T_CLASS)
		->constructor("class")
		->invalidContexts(C::CTX_WRITE | C::CTX_ALIAS_READ | C::CTX_ALIAS_WRITE),

	(new NodeDef(CloneExpression::class))
		->token("keyword", T::T_CLONE)
		->node("expression", Expression::class, C::CTX_READ)
		->constructor("expression")
		->invalidContexts(C::CTX_WRITE | C::CTX_ALIAS_READ | C::CTX_ALIAS_WRITE),

	(new NodeDef(CoalesceExpression::class))
		->node("left", Expression::class, C::CTX_READ)
		->token("operator", T::T_COALESCE)
		->node("right", Expression::class, C::CTX_READ)
		->constructor("left", "right")
		->invalidContexts(C::CTX_WRITE | C::CTX_ALIAS_READ | C::CTX_ALIAS_WRITE),

	/** @see CombinedAssignExpression */
	(new NodeDef(AddAssignExpression::class))
		->node("left", Expression::class, C::CTX_READ | C::CTX_WRITE | C::CTX_LENIENT_READ)
		->token("operator", T::T_PLUS_EQUAL)
		->node("right", Expression::class, C::CTX_READ)
		->constructor("left", "right")
		->invalidContexts(C::CTX_WRITE | C::CTX_ALIAS_READ | C::CTX_ALIAS_WRITE),
	(new NodeDef(BitwiseAndAssignExpression::class))
		->node("left", Expression::class, C::CTX_READ | C::CTX_WRITE | C::CTX_LENIENT_READ)
		->token("operator", T::T_AND_EQUAL)
		->node("right", Expression::class, C::CTX_READ)
		->constructor("left", "right")
		->invalidContexts(C::CTX_WRITE | C::CTX_ALIAS_READ | C::CTX_ALIAS_WRITE),
	(new NodeDef(BitwiseOrAssignExpression::class))
		->node("left", Expression::class, C::CTX_READ | C::CTX_WRITE | C::CTX_LENIENT_READ)
		->token("operator", T::T_OR_EQUAL)
		->node("right", Expression::class, C::CTX_READ)
		->constructor("left", "right")
		->invalidContexts(C::CTX_WRITE | C::CTX_ALIAS_READ | C::CTX_ALIAS_WRITE),
	(new NodeDef(BitwiseXorAssignExpression::class))
		->node("left", Expression::class, C::CTX_READ | C::CTX_WRITE | C::CTX_LENIENT_READ)
		->token("operator", T::T_XOR_EQUAL)
		->node("right", Expression::class, C::CTX_READ)
		->constructor("left", "right")
		->invalidContexts(C::CTX_WRITE | C::CTX_ALIAS_READ | C::CTX_ALIAS_WRITE),
	(new NodeDef(CoalesceAssignExpression::class))
		->node("left", Expression::class, C::CTX_READ | C::CTX_WRITE) // only combo assign without lenient read
		->token("operator", T::T_COALESCE_EQUAL)
		->node("right", Expression::class, C::CTX_READ)
		->constructor("left", "right")
		->invalidContexts(C::CTX_WRITE | C::CTX_ALIAS_READ | C::CTX_ALIAS_WRITE),
	(new NodeDef(ConcatAssignExpression::class))
		->node("left", Expression::class, C::CTX_READ | C::CTX_WRITE | C::CTX_LENIENT_READ)
		->token("operator", T::T_CONCAT_EQUAL)
		->node("right", Expression::class, C::CTX_READ)
		->constructor("left", "right")
		->invalidContexts(C::CTX_WRITE | C::CTX_ALIAS_READ | C::CTX_ALIAS_WRITE),
	(new NodeDef(DivideAssignExpression::class))
		->node("left", Expression::class, C::CTX_READ | C::CTX_WRITE | C::CTX_LENIENT_READ)
		->token("operator", T::T_DIV_EQUAL)
		->node("right", Expression::class, C::CTX_READ)
		->constructor("left", "right")
		->invalidContexts(C::CTX_WRITE | C::CTX_ALIAS_READ | C::CTX_ALIAS_WRITE),
	(new NodeDef(ModuloAssignExpression::class))
		->node("left", Expression::class, C::CTX_READ | C::CTX_WRITE | C::CTX_LENIENT_READ)
		->token("operator", T::T_MOD_EQUAL)
		->node("right", Expression::class, C::CTX_READ)
		->constructor("left", "right")
		->invalidContexts(C::CTX_WRITE | C::CTX_ALIAS_READ | C::CTX_ALIAS_WRITE),
	(new NodeDef(MultiplyAssignExpression::class))
		->node("left", Expression::class, C::CTX_READ | C::CTX_WRITE | C::CTX_LENIENT_READ)
		->token("operator", T::T_MUL_EQUAL)
		->node("right", Expression::class, C::CTX_READ)
		->constructor("left", "right")
		->invalidContexts(C::CTX_WRITE | C::CTX_ALIAS_READ | C::CTX_ALIAS_WRITE),
	(new NodeDef(PowerAssignExpression::class))
		->node("left", Expression::class, C::CTX_READ | C::CTX_WRITE | C::CTX_LENIENT_READ)
		->token("operator", T::T_POW_EQUAL)
		->node("right", Expression::class, C::CTX_READ)
		->constructor("left", "right")
		->invalidContexts(C::CTX_WRITE | C::CTX_ALIAS_READ | C::CTX_ALIAS_WRITE),
	(new NodeDef(ShiftLeftAssignExpression::class))
		->node("left", Expression::class, C::CTX_READ | C::CTX_WRITE | C::CTX_LENIENT_READ)
		->token("operator", T::T_SL_EQUAL)
		->node("right", Expression::class, C::CTX_READ)
		->constructor("left", "right")
		->invalidContexts(C::CTX_WRITE | C::CTX_ALIAS_READ | C::CTX_ALIAS_WRITE),
	(new NodeDef(ShiftRightAssignExpression::class))
		->node("left", Expression::class, C::CTX_READ | C::CTX_WRITE | C::CTX_LENIENT_READ)
		->token("operator", T::T_SR_EQUAL)
		->node("right", Expression::class, C::CTX_READ)
		->constructor("left", "right")
		->invalidContexts(C::CTX_WRITE | C::CTX_ALIAS_READ | C::CTX_ALIAS_WRITE),
	(new NodeDef(SubtractAssignExpression::class))
		->node("left", Expression::class, C::CTX_READ | C::CTX_WRITE | C::CTX_LENIENT_READ)
		->token("operator", T::T_MINUS_EQUAL)
		->node("right", Expression::class, C::CTX_READ)
		->constructor("left", "right")
		->invalidContexts(C::CTX_WRITE | C::CTX_ALIAS_READ | C::CTX_ALIAS_WRITE),

	(new NodeDef(ConcatExpression::class))
		->node("left", Expression::class, C::CTX_READ)
		->token("operator", T::S_DOT)
		->node("right", Expression::class, C::CTX_READ)
		->constructor("left", "right")
		->invalidContexts(C::CTX_WRITE | C::CTX_ALIAS_READ | C::CTX_ALIAS_WRITE),

	(new NodeDef(ConstantAccessExpression::class))
		->node("class", Expression::class, C::CTX_READ)
		->token("operator", T::T_DOUBLE_COLON)
		->token("name", T::T_STRING)
		->constructor("class", "name")
		->invalidContexts(C::CTX_WRITE | C::CTX_ALIAS_READ | C::CTX_ALIAS_WRITE),

	(new NodeDef(DivideExpression::class))
		->node("left", Expression::class, C::CTX_READ)
		->token("operator", T::S_FORWARD_SLASH)
		->node("right", Expression::class, C::CTX_READ)
		->constructor("left", "right")
		->invalidContexts(C::CTX_WRITE | C::CTX_ALIAS_READ | C::CTX_ALIAS_WRITE),

	(new NodeDef(EmptyExpression::class))
		->token("keyword", T::T_EMPTY)
		->token("leftParenthesis", T::S_LEFT_PARENTHESIS)
		->node("expression", Expression::class, C::CTX_READ)
		->token("rightParenthesis", T::S_RIGHT_PARENTHESIS)
		->constructor("expression")
		->invalidContexts(C::CTX_WRITE | C::CTX_ALIAS_READ | C::CTX_ALIAS_WRITE),

	(new NodeDef(EvalExpression::class))
		->token("keyword", T::T_EVAL)
		->token("leftParenthesis", T::S_LEFT_PARENTHESIS)
		->node("expression", Expression::class, C::CTX_READ)
		->token("rightParenthesis", T::S_RIGHT_PARENTHESIS)
		->constructor("expression")
		->invalidContexts(C::CTX_WRITE | C::CTX_ALIAS_READ | C::CTX_ALIAS_WRITE),

	(new NodeDef(ExececutionExpression::class))
		->token("leftDelimiter", T::S_BACKTICK)
		->nodeList("parts", InterpolatedStringPart::class)
		->token("rightDelimiter", T::S_BACKTICK)
		->invalidContexts(C::CTX_WRITE | C::CTX_ALIAS_READ | C::CTX_ALIAS_WRITE),

	(new NodeDef(ExitExpression::class))
		->token("keyword", T::T_EXIT)
		->optToken("leftParenthesis", T::S_LEFT_PARENTHESIS)
		->optNode("expression", Expression::class, C::CTX_READ)
		->optToken("rightParenthesis", T::S_RIGHT_PARENTHESIS)
		->constructor("expression")
		->invalidContexts(C::CTX_WRITE | C::CTX_ALIAS_READ | C::CTX_ALIAS_WRITE),

	(new NodeDef(GreaterThanExpression::class))
		->node("left", Expression::class, C::CTX_READ)
		->token("operator", T::S_GT)
		->node("right", Expression::class, C::CTX_READ)
		->constructor("left", "right")
		->invalidContexts(C::CTX_WRITE | C::CTX_ALIAS_READ | C::CTX_ALIAS_WRITE),

	(new NodeDef(GreaterThanOrEqualsExpression::class))
		->node("left", Expression::class, C::CTX_READ)
		->token("operator", T::T_IS_GREATER_OR_EQUAL)
		->node("right", Expression::class, C::CTX_READ)
		->constructor("left", "right")
		->invalidContexts(C::CTX_WRITE | C::CTX_ALIAS_READ | C::CTX_ALIAS_WRITE),

	/** @see IncludeLikeExpression */
	(new NodeDef(IncludeExpression::class))
		->token("keyword", T::T_INCLUDE)
		->node("expression", Expression::class, C::CTX_READ)
		->constructor("expression")
		->invalidContexts(C::CTX_WRITE | C::CTX_ALIAS_READ | C::CTX_ALIAS_WRITE),
	(new NodeDef(IncludeOnceExpression::class))
		->token("keyword", T::T_INCLUDE_ONCE)
		->node("expression", Expression::class, C::CTX_READ)
		->constructor("expression")
		->invalidContexts(C::CTX_WRITE | C::CTX_ALIAS_READ | C::CTX_ALIAS_WRITE),
	(new NodeDef(RequireExpression::class))
		->token("keyword", T::T_REQUIRE)
		->node("expression", Expression::class, C::CTX_READ)
		->constructor("expression")
		->invalidContexts(C::CTX_WRITE | C::CTX_ALIAS_READ | C::CTX_ALIAS_WRITE),
	(new NodeDef(RequireOnceExpression::class))
		->token("keyword", T::T_REQUIRE_ONCE)
		->node("expression", Expression::class, C::CTX_READ)
		->constructor("expression")
		->invalidContexts(C::CTX_WRITE | C::CTX_ALIAS_READ | C::CTX_ALIAS_WRITE),

	(new NodeDef(InstanceofExpression::class))
		->node("expression", Expression::class, C::CTX_READ)
		->token("operator", T::T_INSTANCEOF)
		->node("class", Expression::class, C::CTX_READ)
		->constructor("expression", "class")
		->invalidContexts(C::CTX_WRITE | C::CTX_ALIAS_READ | C::CTX_ALIAS_WRITE),

	(new NodeDef(IsEqualExpression::class))
		->node("left", Expression::class, C::CTX_READ)
		->token("operator", T::T_IS_EQUAL)
		->node("right", Expression::class, C::CTX_READ)
		->constructor("left", "right")
		->invalidContexts(C::CTX_WRITE | C::CTX_ALIAS_READ | C::CTX_ALIAS_WRITE),

	(new NodeDef(IsIdenticalExpression::class))
		->node("left", Expression::class, C::CTX_READ)
		->token("operator", T::T_IS_IDENTICAL)
		->node("right", Expression::class, C::CTX_READ)
		->constructor("left", "right")
		->invalidContexts(C::CTX_WRITE | C::CTX_ALIAS_READ | C::CTX_ALIAS_WRITE),

	(new NodeDef(IsNotEqualExpression::class))
		->node("left", Expression::class, C::CTX_READ)
		->token("operator", T::T_IS_NOT_EQUAL)
		->node("right", Expression::class, C::CTX_READ)
		->constructor("left", "right")
		->invalidContexts(C::CTX_WRITE | C::CTX_ALIAS_READ | C::CTX_ALIAS_WRITE),

	(new NodeDef(IsNotIdenticalExpression::class))
		->node("left", Expression::class, C::CTX_READ)
		->token("operator", T::T_IS_NOT_IDENTICAL)
		->node("right", Expression::class, C::CTX_READ)
		->constructor("left", "right")
		->invalidContexts(C::CTX_WRITE | C::CTX_ALIAS_READ | C::CTX_ALIAS_WRITE),

	(new NodeDef(IssetExpression::class))
		->token("keyword", T::T_ISSET)
		->token("leftParenthesis", T::S_LEFT_PARENTHESIS)
		->sepNodeList("expressions", Expression::class, T::S_COMMA, C::CTX_READ)
		->token("rightParenthesis", T::S_RIGHT_PARENTHESIS)
		->constructor("expression")
		->invalidContexts(C::CTX_WRITE | C::CTX_ALIAS_READ | C::CTX_ALIAS_WRITE),

	(new NodeDef(KeywordAndExpression::class))
		->node("left", Expression::class, C::CTX_READ)
		->token("operator", T::T_LOGICAL_AND)
		->node("right", Expression::class, C::CTX_READ)
		->constructor("left", "right")
		->invalidContexts(C::CTX_WRITE | C::CTX_ALIAS_READ | C::CTX_ALIAS_WRITE),

	(new NodeDef(KeywordOrExpression::class))
		->node("left", Expression::class, C::CTX_READ)
		->token("operator", T::T_LOGICAL_OR)
		->node("right", Expression::class, C::CTX_READ)
		->constructor("left", "right")
		->invalidContexts(C::CTX_WRITE | C::CTX_ALIAS_READ | C::CTX_ALIAS_WRITE),

	(new NodeDef(KeywordXorExpression::class))
		->node("left", Expression::class, C::CTX_READ)
		->token("operator", T::T_LOGICAL_XOR)
		->node("right", Expression::class, C::CTX_READ)
		->constructor("left", "right")
		->invalidContexts(C::CTX_WRITE | C::CTX_ALIAS_READ | C::CTX_ALIAS_WRITE),

	(new NodeDef(LessThanExpression::class))
		->node("left", Expression::class, C::CTX_READ)
		->token("operator", T::S_LT)
		->node("right", Expression::class, C::CTX_READ)
		->constructor("left", "right")
		->invalidContexts(C::CTX_WRITE | C::CTX_ALIAS_READ | C::CTX_ALIAS_WRITE),

	(new NodeDef(LessThanOrEqualsExpression::class))
		->node("left", Expression::class, C::CTX_READ)
		->token("operator", T::T_IS_SMALLER_OR_EQUAL)
		->node("right", Expression::class, C::CTX_READ)
		->constructor("left", "right")
		->invalidContexts(C::CTX_WRITE | C::CTX_ALIAS_READ | C::CTX_ALIAS_WRITE),

	(new NodeDef(ListExpression::class))
		->token("keyword", T::T_LIST)
		->token("leftParenthesis", T::S_LEFT_PARENTHESIS)
		->sepNodeList("items", ArrayItem::class, T::S_COMMA, C::CTX_WRITE)
		->token("rightParenthesis", T::S_RIGHT_PARENTHESIS)
		->invalidContexts(C::CTX_READ | C::CTX_ALIAS_READ | C::CTX_ALIAS_WRITE)
		->validateChildren(false),

	/** @see MagicConstant */
	(new NodeDef(ClassMagicConstant::class))
		->token("token", T::T_CLASS_C)
		->constructor("token")
		->invalidContexts(C::CTX_WRITE | C::CTX_ALIAS_READ | C::CTX_ALIAS_WRITE),
	(new NodeDef(DirMagicConstant::class))
		->token("token", T::T_DIR)
		->constructor("token")
		->invalidContexts(C::CTX_WRITE | C::CTX_ALIAS_READ | C::CTX_ALIAS_WRITE),
	(new NodeDef(FileMagicConstant::class))
		->token("token", T::T_FILE)
		->constructor("token")
		->invalidContexts(C::CTX_WRITE | C::CTX_ALIAS_READ | C::CTX_ALIAS_WRITE),
	(new NodeDef(FunctionMagicConstant::class))
		->token("token", T::T_FUNC_C)
		->constructor("token")
		->invalidContexts(C::CTX_WRITE | C::CTX_ALIAS_READ | C::CTX_ALIAS_WRITE),
	(new NodeDef(LineMagicConstant::class))
		->token("token", T::T_LINE)
		->constructor("token")
		->invalidContexts(C::CTX_WRITE | C::CTX_ALIAS_READ | C::CTX_ALIAS_WRITE),
	(new NodeDef(MethodMagicConstant::class))
		->token("token", T::T_METHOD_C)
		->constructor("token")
		->invalidContexts(C::CTX_WRITE | C::CTX_ALIAS_READ | C::CTX_ALIAS_WRITE),
	(new NodeDef(NamespaceMagicConstant::class))
		->token("token", T::T_NS_C)
		->constructor("token")
		->invalidContexts(C::CTX_WRITE | C::CTX_ALIAS_READ | C::CTX_ALIAS_WRITE),
	(new NodeDef(TraitMagicConstant::class))
		->token("token", T::T_TRAIT_C)
		->constructor("token")
		->invalidContexts(C::CTX_WRITE | C::CTX_ALIAS_READ | C::CTX_ALIAS_WRITE),

	(new NodeDef(MethodCallExpression::class))
		->node("object", Expression::class, C::CTX_READ)
		->token("operator", T::T_OBJECT_OPERATOR)
		->node("name", MemberName::class)
		->token("leftParenthesis", T::S_LEFT_PARENTHESIS)
		->sepNodeList("arguments", Argument::class, T::S_COMMA)
		->token("rightParenthesis", T::S_RIGHT_PARENTHESIS)
		->constructor("object", "name")
		->invalidContexts(C::CTX_WRITE | C::CTX_ALIAS_WRITE),

	(new NodeDef(ModuloExpression::class))
		->node("left", Expression::class, C::CTX_READ)
		->token("operator", T::S_MODULO)
		->node("right", Expression::class, C::CTX_READ)
		->constructor("left", "right")
		->invalidContexts(C::CTX_WRITE | C::CTX_ALIAS_READ | C::CTX_ALIAS_WRITE),

	(new NodeDef(MultiplyExpression::class))
		->node("left", Expression::class, C::CTX_READ)
		->token("operator", T::S_ASTERISK)
		->node("right", Expression::class, C::CTX_READ)
		->constructor("left", "right")
		->invalidContexts(C::CTX_WRITE | C::CTX_ALIAS_READ | C::CTX_ALIAS_WRITE),

	(new NodeDef(NameExpression::class))
		->node("name", Name::class)
		->constructor("name")
		->invalidContexts(C::CTX_WRITE | C::CTX_ALIAS_READ | C::CTX_ALIAS_WRITE),

	/** @see NewExpression */
	(new NodeDef(NormalNewExpression::class))
		->token("keyword", T::T_NEW)
		->node("class", Expression::class, C::CTX_READ)
		->optToken("leftParenthesis", T::S_LEFT_PARENTHESIS)
		->sepNodeList("arguments", Argument::class, T::S_COMMA)
		->optToken("rightParenthesis", T::S_RIGHT_PARENTHESIS)
		->constructor("class")
		->invalidContexts(C::CTX_WRITE | C::CTX_ALIAS_READ | C::CTX_ALIAS_WRITE),
	(new NodeDef(AnonymousClassNewExpression::class))
		->token("keyword", T::T_NEW)
		->token("classKeyword", T::T_CLASS)
		->optToken("leftParenthesis", T::S_LEFT_PARENTHESIS)
		->sepNodeList("arguments", Argument::class, T::S_COMMA)
		->optToken("rightParenthesis", T::S_RIGHT_PARENTHESIS)
		->optNode("extends", Extends_::class)
		->optNode("implements", Implements_::class)
		->token("leftBrace", T::S_LEFT_CURLY_BRACE)
		->nodeList("members", OopMember::class)
		->token("rightBrace", T::S_RIGHT_CURLY_BRACE)
		->invalidContexts(C::CTX_WRITE | C::CTX_ALIAS_READ | C::CTX_ALIAS_WRITE),

	/** @see NumberLiteral */
	(new NodeDef(FloatLiteral::class))
		->token("token", T::T_DNUMBER)
		->constructor("token")
		->invalidContexts(C::CTX_WRITE | C::CTX_ALIAS_READ | C::CTX_ALIAS_WRITE),
	(new NodeDef(IntegerLiteral::class))
		->token("token", T::T_LNUMBER)
		->constructor("token")
		->invalidContexts(C::CTX_WRITE | C::CTX_ALIAS_READ | C::CTX_ALIAS_WRITE),

	(new NodeDef(ParenthesizedExpression::class))
		->token("leftParenthesis", T::S_LEFT_PARENTHESIS)
		->node("expression", Expression::class, '$flags')
		->token("rightParenthesis", T::S_RIGHT_PARENTHESIS)
		->constructor("expression")
		->invalidContexts(C::CTX_ALIAS_READ | C::CTX_ALIAS_WRITE),

	(new NodeDef(PostDecrementExpression::class))
		->node("expression", Expression::class, C::CTX_READ | C::CTX_WRITE | C::CTX_LENIENT_READ)
		->token("operator", T::T_DEC)
		->constructor("expression")
		->invalidContexts(C::CTX_WRITE | C::CTX_ALIAS_READ | C::CTX_ALIAS_WRITE),
	(new NodeDef(PostIncrementExpression::class))
		->node("expression", Expression::class, C::CTX_READ | C::CTX_WRITE | C::CTX_LENIENT_READ)
		->token("operator", T::T_INC)
		->constructor("expression")
		->invalidContexts(C::CTX_WRITE | C::CTX_ALIAS_READ | C::CTX_ALIAS_WRITE),

	(new NodeDef(PowerExpression::class))
		->node("left", Expression::class, C::CTX_READ)
		->token("operator", T::T_POW)
		->node("right", Expression::class, C::CTX_READ)
		->constructor("left", "right")
		->invalidContexts(C::CTX_WRITE | C::CTX_ALIAS_READ | C::CTX_ALIAS_WRITE),

	(new NodeDef(PreDecrementExpression::class))
		->token("operator", T::T_DEC)
		->node("expression", Expression::class, C::CTX_READ | C::CTX_WRITE | C::CTX_LENIENT_READ)
		->constructor("expression")
		->invalidContexts(C::CTX_WRITE | C::CTX_ALIAS_READ | C::CTX_ALIAS_WRITE),
	(new NodeDef(PreIncrementExpression::class))
		->token("operator", T::T_INC)
		->node("expression", Expression::class, C::CTX_READ | C::CTX_WRITE | C::CTX_LENIENT_READ)
		->constructor("expression")
		->invalidContexts(C::CTX_WRITE | C::CTX_ALIAS_READ | C::CTX_ALIAS_WRITE),

	(new NodeDef(PrintExpression::class))
		->token("keyword", T::T_PRINT)
		->node("expression", Expression::class, C::CTX_READ)
		->constructor("expression")
		->invalidContexts(C::CTX_WRITE | C::CTX_ALIAS_READ | C::CTX_ALIAS_WRITE),

	(new NodeDef(PropertyAccessExpression::class))
		->node("object", Expression::class, C::CTX_READ)
		->token("operator", T::T_OBJECT_OPERATOR)
		->node("name", MemberName::class)
		->constructor("object", "name"),

	(new NodeDef(ShiftLeftExpression::class))
		->node("left", Expression::class, C::CTX_READ)
		->token("operator", T::T_SL)
		->node("right", Expression::class, C::CTX_READ)
		->constructor("left", "right")
		->invalidContexts(C::CTX_WRITE | C::CTX_ALIAS_READ | C::CTX_ALIAS_WRITE),
	(new NodeDef(ShiftRightExpression::class))
		->node("left", Expression::class, C::CTX_READ)
		->token("operator", T::T_SR)
		->node("right", Expression::class, C::CTX_READ)
		->constructor("left", "right")
		->invalidContexts(C::CTX_WRITE | C::CTX_ALIAS_READ | C::CTX_ALIAS_WRITE),

	(new NodeDef(SpaceshipExpression::class))
		->node("left", Expression::class, C::CTX_READ)
		->token("operator", T::T_SPACESHIP)
		->node("right", Expression::class, C::CTX_READ)
		->constructor("left", "right")
		->invalidContexts(C::CTX_WRITE | C::CTX_ALIAS_READ | C::CTX_ALIAS_WRITE),

	(new NodeDef(StaticMethodCallExpression::class))
		->node("class", Expression::class, C::CTX_READ)
		->token("operator", T::T_DOUBLE_COLON)
		->node("name", MemberName::class)
		->token("leftParenthesis", T::S_LEFT_PARENTHESIS)
		->sepNodeList("arguments", Argument::class, T::S_COMMA)
		->token("rightParenthesis", T::S_RIGHT_PARENTHESIS)
		->constructor("class", "name")
		->invalidContexts(C::CTX_WRITE | C::CTX_ALIAS_WRITE),

	(new NodeDef(StaticPropertyAccessExpression::class))
		->node("class", Expression::class, C::CTX_READ)
		->token("operator", T::T_DOUBLE_COLON)
		->node("name", VariableExpression::class)
		->constructor("class", "name"),

	/** @see StringLiteral */
	(new NodeDef(SingleQuotedStringLiteral::class))
		->token("token", T::T_CONSTANT_ENCAPSED_STRING)
		->constructor("token")
		->invalidContexts(C::CTX_WRITE | C::CTX_ALIAS_READ | C::CTX_ALIAS_WRITE),
	(new NodeDef(DoubleQuotedStringLiteral::class))
		->token("leftDelimiter", T::S_DOUBLE_QUOTE)
		->nodeList("parts", InterpolatedStringPart::class)
		->token("rightDelimiter", T::S_DOUBLE_QUOTE)
		->invalidContexts(C::CTX_WRITE | C::CTX_ALIAS_READ | C::CTX_ALIAS_WRITE),
	(new NodeDef(HeredocStringLiteral::class))
		->token("leftDelimiter", T::T_START_HEREDOC)
		->nodeList("parts", InterpolatedStringPart::class)
		->token("rightDelimiter", T::T_END_HEREDOC)
		->invalidContexts(C::CTX_WRITE | C::CTX_ALIAS_READ | C::CTX_ALIAS_WRITE),
	(new NodeDef(NowdocStringLiteral::class))
		->token("leftDelimiter", T::T_START_HEREDOC)
		->optToken("content", T::T_ENCAPSED_AND_WHITESPACE)
		->token("rightDelimiter", T::T_END_HEREDOC)
		->constructor("content")
		->invalidContexts(C::CTX_WRITE | C::CTX_ALIAS_READ | C::CTX_ALIAS_WRITE),

	(new NodeDef(StaticExpression::class))
		->token("token", T::T_STATIC)
		->constructor("token")
		->invalidContexts(C::CTX_WRITE | C::CTX_ALIAS_READ | C::CTX_ALIAS_WRITE),

	(new NodeDef(SubtractExpression::class))
		->node("left", Expression::class, C::CTX_READ)
		->token("operator", T::S_MINUS)
		->node("right", Expression::class, C::CTX_READ)
		->constructor("left", "right")
		->invalidContexts(C::CTX_WRITE | C::CTX_ALIAS_READ | C::CTX_ALIAS_WRITE),

	(new NodeDef(SuppressErrorsExpression::class))
		->token("operator", T::S_AT)
		->node("expression", Expression::class, C::CTX_READ)
		->constructor("expression")
		->invalidContexts(C::CTX_WRITE | C::CTX_ALIAS_READ | C::CTX_ALIAS_WRITE),

	(new NodeDef(SymbolAndExpression::class))
		->node("left", Expression::class, C::CTX_READ)
		->token("operator", T::T_BOOLEAN_AND)
		->node("right", Expression::class, C::CTX_READ)
		->constructor("left", "right")
		->invalidContexts(C::CTX_WRITE | C::CTX_ALIAS_READ | C::CTX_ALIAS_WRITE),

	(new NodeDef(SymbolOrExpression::class))
		->node("left", Expression::class, C::CTX_READ)
		->token("operator", T::T_BOOLEAN_OR)
		->node("right", Expression::class, C::CTX_READ)
		->constructor("left", "right")
		->invalidContexts(C::CTX_WRITE | C::CTX_ALIAS_READ | C::CTX_ALIAS_WRITE),

	(new NodeDef(TernaryExpression::class))
		->node("condition", Expression::class, C::CTX_READ)
		->token("operator1", T::S_QUESTION_MARK)
		->optNode("if", Expression::class, C::CTX_READ)
		->token("operator2", T::S_COLON)
		->node("else", Expression::class, C::CTX_READ)
		->constructor("condition", "if", "else")
		->invalidContexts(C::CTX_WRITE | C::CTX_ALIAS_READ | C::CTX_ALIAS_WRITE),

	(new NodeDef(UnaryMinusExpression::class))
		->token("operator", T::S_MINUS)
		->node("expression", Expression::class, C::CTX_READ)
		->constructor("expression")
		->invalidContexts(C::CTX_WRITE | C::CTX_ALIAS_READ | C::CTX_ALIAS_WRITE),

	(new NodeDef(UnaryPlusExpression::class))
		->token("operator", T::S_PLUS)
		->node("expression", Expression::class, C::CTX_READ)
		->constructor("expression")
		->invalidContexts(C::CTX_WRITE | C::CTX_ALIAS_READ | C::CTX_ALIAS_WRITE),

	/** @see VariableExpression */
	(new NodeDef(NormalVariableExpression::class))
		->token("token", T::T_VARIABLE)
		->constructor("token"),
	(new NodeDef(VariableVariableExpression::class))
		->token("dollar", T::S_DOLLAR)
		->optToken("leftBrace", T::S_LEFT_CURLY_BRACE)
		->node("name", Expression::class, C::CTX_READ)
		->optToken("rightBrace", T::S_RIGHT_CURLY_BRACE)
		->constructor("name"),

	(new NodeDef(YieldExpression::class))
		->token("keyword", T::T_YIELD)
		->optNode("key", Key::class, C::CTX_READ)
		->optNode("expression", Expression::class, C::CTX_READ)
		->constructor("expression")
		->invalidContexts(C::CTX_WRITE | C::CTX_ALIAS_READ | C::CTX_ALIAS_WRITE),

	(new NodeDef(YieldFromExpression::class))
		->token("keyword", T::T_YIELD_FROM)
		->node("expression", Expression::class, C::CTX_READ)
		->constructor("expression")
		->invalidContexts(C::CTX_WRITE | C::CTX_ALIAS_READ | C::CTX_ALIAS_WRITE),
];
