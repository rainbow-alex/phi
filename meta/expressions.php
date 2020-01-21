<?php

use Phi\Nodes\AddExpression;
use Phi\Nodes\AliasingExpression;
use Phi\Nodes\AnonymousFunctionExpression;
use Phi\Nodes\AnonymousFunctionUse;
use Phi\Nodes\AnonymousFunctionUseBinding;
use Phi\Nodes\Argument;
use Phi\Nodes\ArrayAccessExpression;
use Phi\Nodes\ArrayExpression;
use Phi\Nodes\ArrayItem;
use Phi\Nodes\BitwiseAndExpression;
use Phi\Nodes\BitwiseOrExpression;
use Phi\Nodes\BitwiseXorExpression;
use Phi\Nodes\Block;
use Phi\Nodes\BooleanNotExpression;
use Phi\Nodes\CallExpression;
use Phi\Nodes\CastExpression;
use Phi\Nodes\ClassNameResolutionExpression;
use Phi\Nodes\CloneExpression;
use Phi\Nodes\CoalesceExpression;
use Phi\Nodes\CombinedAssignmentExpression;
use Phi\Nodes\ConcatExpression;
use Phi\Nodes\ConstantStringLiteral;
use Phi\Nodes\DivideExpression;
use Phi\Nodes\EmptyExpression;
use Phi\Nodes\EvalExpression;
use Phi\Nodes\ExitExpression;
use Phi\Nodes\Expression;
use Phi\Nodes\GreaterThanExpression;
use Phi\Nodes\GreaterThanOrEqualsExpression;
use Phi\Nodes\IncludeLikeExpression;
use Phi\Nodes\InstanceofExpression;
use Phi\Nodes\InterpolatedString;
use Phi\Nodes\InterpolatedStringPart;
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
use Phi\Nodes\MemberAccessExpression;
use Phi\Nodes\ModuloExpression;
use Phi\Nodes\MultiplyExpression;
use Phi\Nodes\Name;
use Phi\Nodes\NameExpression;
use Phi\Nodes\NegationExpression;
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
use Phi\Nodes\RegularAssignmentExpression;
use Phi\Nodes\RegularVariableExpression;
use Phi\Nodes\ReturnType;
use Phi\Nodes\ShortArrayExpression;
use Phi\Nodes\SpaceshipExpression;
use Phi\Nodes\StaticMemberAccessExpression;
use Phi\Nodes\StaticPropertyAccessExpression;
use Phi\Nodes\SubtractExpression;
use Phi\Nodes\SuppressErrorsExpression;
use Phi\Nodes\SymbolBooleanAndExpression;
use Phi\Nodes\SymbolBooleanOrExpression;
use Phi\Nodes\TernaryExpression;
use Phi\Nodes\UnsetExpression;
use Phi\Nodes\YieldExpression;
use Phi\Nodes\YieldFromExpression;
use Phi\Specifications\EachItem;
use Phi\Specifications\IsAliasReadExpression;
use Phi\Specifications\IsAliasWriteExpression;
use Phi\Specifications\IsReadExpression;
use Phi\Specifications\IsWriteExpression;
use Phi\Token;

return [

    (new NodeDef(AddExpression::class))
        ->withImplements(Expression::class)
        ->withChild('left', Expression::class, [new IsReadExpression()])
        ->withToken('operator', '+')
        ->withChild('right', Expression::class, [new IsReadExpression()])
        ->withConstructor('left', 'right'),

    (new NodeDef(AliasingExpression::class))
        ->withImplements(Expression::class)
        ->withChild('alias', Expression::class, [new IsAliasWriteExpression])
        ->withToken('operator1', '=')
        ->withToken('operator2', '&')
        ->withChild('value', Expression::class, [new IsAliasReadExpression])
        ->withConstructor('alias', 'value'),

    (new NodeDef(ArrayAccessExpression::class))
        ->withImplements(Expression::class)
        ->withChild('accessee', Expression::class, [new IsReadExpression])
        ->withToken('leftBracket', '[')
        ->withOptChild('index', Expression::class, [new IsReadExpression])
        ->withToken('rightBracket', ']')
        ->withConstructor('accessee', 'index'),

    (new NodeDef(BitwiseAndExpression::class))
        ->withImplements(Expression::class)
        ->withChild('left', Expression::class, [new IsReadExpression])
        ->withToken('operator', '&')
        ->withChild('right', Expression::class, [new IsReadExpression])
        ->withConstructor('left', 'right'),

    (new NodeDef(BitwiseOrExpression::class))
        ->withImplements(Expression::class)
        ->withChild('left', Expression::class, [new IsReadExpression])
        ->withToken('operator', '|')
        ->withChild('right', Expression::class, [new IsReadExpression])
        ->withConstructor('left', 'right'),

    (new NodeDef(BitwiseXorExpression::class))
        ->withImplements(Expression::class)
        ->withChild('left', Expression::class, [new IsReadExpression])
        ->withToken('operator', '^')
        ->withChild('right', Expression::class, [new IsReadExpression])
        ->withConstructor('left', 'right'),

    (new NodeDef(BooleanNotExpression::class))
        ->withImplements(Expression::class)
        ->withToken('operator', '!')
        ->withChild('expression', Expression::class, [new IsReadExpression])
        ->withConstructor('expression'),

    (new NodeDef(CallExpression::class))
        ->withImplements(Expression::class)
        ->withChild('callee', Expression::class, [new IsReadExpression])
        ->withToken('leftParenthesis', '(')
        ->withSepList('arguments', Argument::class, ',') // TODO valid
        ->withToken('rightParenthesis', ')')
        ->withConstructor('callee'),

    (new NodeDef(CastExpression::class))
        ->withImplements(Expression::class)
        ->withToken('cast', Token::CASTS)
        ->withChild('expression', Expression::class, [new IsReadExpression])
        ->withConstructor('cast', 'expression'),

    (new NodeDef(ClassNameResolutionExpression::class))
        ->withImplements(Expression::class)
        ->withChild('class', Expression::class, [new IsReadExpression])
        ->withToken('operator', T_DOUBLE_COLON)
        ->withToken('keyword', T_CLASS)
        ->withConstructor('class'),

    (new NodeDef(CloneExpression::class))
        ->withImplements(Expression::class)
        ->withToken('keyword', T_CLONE)
        ->withChild('expression', Expression::class, [new IsReadExpression])
        ->withConstructor('expression'),

    (new NodeDef(CoalesceExpression::class))
        ->withImplements(Expression::class)
        ->withChild('left', Expression::class, [new IsReadExpression])
        ->withToken('operator', T_COALESCE)
        ->withChild('right', Expression::class, [new IsReadExpression])
        ->withConstructor('left', 'right'),

    (new NodeDef(CombinedAssignmentExpression::class))
        ->withImplements(Expression::class)
        ->withChild('lvalue', Expression::class, [new IsReadExpression, new IsWriteExpression])
        ->withToken('operator', Token::COMBINED_ASSIGNMENT)
        ->withChild('value', Expression::class, [new IsReadExpression])
        ->withConstructor('lvalue', 'value'),

    (new NodeDef(ConcatExpression::class))
        ->withImplements(Expression::class)
        ->withChild('left', Expression::class, [new IsReadExpression])
        ->withToken('operator', '.')
        ->withChild('right', Expression::class, [new IsReadExpression])
        ->withConstructor('left', 'right'),

    (new NodeDef(ConstantStringLiteral::class))
        ->withImplements(Expression::class)
        ->withToken('source', T_CONSTANT_ENCAPSED_STRING)
        ->withConstructor('source'),

    (new NodeDef(DivideExpression::class))
        ->withImplements(Expression::class)
        ->withChild('left', Expression::class, [new IsReadExpression])
        ->withToken('operator', '/')
        ->withChild('right', Expression::class, [new IsReadExpression])
        ->withConstructor('left', 'right'),

    (new NodeDef(EmptyExpression::class))
        ->withImplements(Expression::class)
        ->withToken('keyword', T_EMPTY)
        ->withToken('leftParenthesis', '(')
        ->withChild('expression', Expression::class) // TODO valid
        ->withToken('rightParenthesis', ')')
        ->withConstructor('expression'),

    (new NodeDef(EvalExpression::class))
        ->withImplements(Expression::class)
        ->withToken('keyword', T_EVAL)
        ->withToken('leftParenthesis', '(')
        ->withChild('expression', Expression::class, [new IsReadExpression])
        ->withToken('rightParenthesis', ')')
        ->withConstructor('expression'),

    (new NodeDef(ExitExpression::class))
        ->withImplements(Expression::class)
        ->withToken('keyword', T_EXIT)
        ->withToken('leftParenthesis', '(')
        ->withOptChild('expression', Expression::class, [new IsReadExpression])
        ->withToken('rightParenthesis', ')')
        ->withConstructor('expression'),

    (new NodeDef(GreaterThanExpression::class))
        ->withImplements(Expression::class)
        ->withChild('left', Expression::class, [new IsReadExpression])
        ->withToken('operator', '>')
        ->withChild('right', Expression::class, [new IsReadExpression])
        ->withConstructor('left', 'right'),

    (new NodeDef(GreaterThanOrEqualsExpression::class))
        ->withImplements(Expression::class)
        ->withChild('left', Expression::class, [new IsReadExpression])
        ->withToken('operator', T_IS_GREATER_OR_EQUAL)
        ->withChild('right', Expression::class, [new IsReadExpression])
        ->withConstructor('left', 'right'),

    (new NodeDef(AnonymousFunctionExpression::class))
        ->withImplements(Expression::class)
        ->withOptToken('static', T_STATIC)
        ->withToken('keyword', T_FUNCTION)
        ->withToken('leftParenthesis', '(')
        ->withSepList('parameters', Parameter::class, ',')
        ->withToken('rightParenthesis', ')')
        ->withOptChild('use', AnonymousFunctionUse::class)
        ->withOptChild('returnType', ReturnType::class)
        ->withChild('body', Block::class),

    (new NodeDef(AnonymousFunctionUse::class))
        ->withToken('keyword', T_USE)
        ->withToken('leftParenthesis', '(')
        ->withSepList('bindings', AnonymousFunctionUseBinding::class, ',')
        ->withToken('rightParenthesis', ')'),

    (new NodeDef(AnonymousFunctionUseBinding::class))
        ->withOptToken('byReference', '&')
        ->withToken('variable', T_VARIABLE),

    (new NodeDef(IncludeLikeExpression::class))
        ->withImplements(Expression::class)
        ->withToken('keyword', [T_INCLUDE, T_INCLUDE_ONCE, T_REQUIRE, T_REQUIRE_ONCE])
        ->withChild('expression', Expression::class, [new IsReadExpression])
        ->withConstructor('expression'),

    (new NodeDef(InstanceofExpression::class))
        ->withImplements(Expression::class)
        ->withChild('value', Expression::class, [new IsReadExpression])
        ->withToken('operator', T_INSTANCEOF)
        ->withChild('type', Expression::class, [new IsReadExpression])
        ->withConstructor('value', 'type'),

    (new NodeDef(InterpolatedString::class)) // TODO split?
        ->withImplements(Expression::class)
        ->withToken('leftDelimiter', ['"', T_START_HEREDOC])
        ->withList('parts', InterpolatedStringPart::class)
        ->withToken('rightDelimiter', ['"', T_END_HEREDOC]),

    (new NodeDef(IsEqualExpression::class))
        ->withImplements(Expression::class)
        ->withChild('left', Expression::class, [new IsReadExpression])
        ->withToken('operator', T_IS_EQUAL)
        ->withChild('right', Expression::class, [new IsReadExpression])
        ->withConstructor('left', 'right'),

    (new NodeDef(IsIdenticalExpression::class))
        ->withImplements(Expression::class)
        ->withChild('left', Expression::class, [new IsReadExpression])
        ->withToken('operator', T_IS_IDENTICAL)
        ->withChild('right', Expression::class, [new IsReadExpression])
        ->withConstructor('left', 'right'),

    (new NodeDef(IsNotEqualExpression::class))
        ->withImplements(Expression::class)
        ->withChild('left', Expression::class, [new IsReadExpression])
        ->withToken('operator', T_IS_NOT_EQUAL)
        ->withChild('right', Expression::class, [new IsReadExpression])
        ->withConstructor('left', 'right'),

    (new NodeDef(IsNotIdenticalExpression::class))
        ->withImplements(Expression::class)
        ->withChild('left', Expression::class, [new IsReadExpression])
        ->withToken('operator', T_IS_NOT_IDENTICAL)
        ->withChild('right', Expression::class, [new IsReadExpression])
        ->withConstructor('left', 'right'),

    (new NodeDef(IssetExpression::class))
        ->withImplements(Expression::class)
        ->withToken('keyword', T_ISSET)
        ->withToken('leftParenthesis', '(')
        ->withSepList('expressions', Expression::class, ',', [new EachItem(new IsReadExpression)])
        ->withToken('rightParenthesis', ')')
        ->withConstructor('expression'),

    (new NodeDef(KeywordBooleanAndExpression::class))
        ->withImplements(Expression::class)
        ->withChild('left', Expression::class, [new IsReadExpression])
        ->withToken('operator', T_LOGICAL_AND)
        ->withChild('right', Expression::class, [new IsReadExpression])
        ->withConstructor('left', 'right'),

    (new NodeDef(KeywordBooleanOrExpression::class))
        ->withImplements(Expression::class)
        ->withChild('left', Expression::class, [new IsReadExpression])
        ->withToken('operator', T_LOGICAL_OR)
        ->withChild('right', Expression::class, [new IsReadExpression])
        ->withConstructor('left', 'right'),

    (new NodeDef(KeywordBooleanXorExpression::class))
        ->withImplements(Expression::class)
        ->withChild('left', Expression::class, [new IsReadExpression])
        ->withToken('operator', T_LOGICAL_XOR)
        ->withChild('right', Expression::class, [new IsReadExpression])
        ->withConstructor('left', 'right'),

    (new NodeDef(LessThanExpression::class))
        ->withImplements(Expression::class)
        ->withChild('left', Expression::class, [new IsReadExpression])
        ->withToken('operator', '<')
        ->withChild('right', Expression::class, [new IsReadExpression])
        ->withConstructor('left', 'right'),

    (new NodeDef(LessThanOrEqualsExpression::class))
        ->withImplements(Expression::class)
        ->withChild('left', Expression::class, [new IsReadExpression])
        ->withToken('operator', T_IS_SMALLER_OR_EQUAL)
        ->withChild('right', Expression::class, [new IsReadExpression])
        ->withConstructor('left', 'right'),

    (new NodeDef(ListExpression::class))
        ->withImplements(Expression::class)
        ->withToken('keyword', T_LIST)
        ->withToken('leftParenthesis', '(')
        ->withSepList('expressions', Expression::class, ',') // TODO vali
        ->withToken('rightParenthesis', ')'),

    (new NodeDef(LongArrayExpression::class))
        ->withImplements(ArrayExpression::class)
        ->withToken('keyword', T_ARRAY)
        ->withToken('leftParenthesis', '(')
        ->withSepList('items', ArrayItem::class, ',') // TODO vali
        ->withToken('rightParenthesis', ')'),

    (new NodeDef(MagicConstant::class))
        ->withImplements(Expression::class)
        ->withToken('token', Token::MAGIC_CONSTANTS),

    (new NodeDef(MemberAccessExpression::class))
        ->withImplements(Expression::class)
        ->withChild('accessee', Expression::class, [new IsReadExpression])
        ->withToken('operator', T_OBJECT_OPERATOR)
        ->withToken('name', T_STRING)
        ->withConstructor('accessee', 'name'),

    (new NodeDef(ModuloExpression::class))
        ->withImplements(Expression::class)
        ->withChild('left', Expression::class, [new IsReadExpression])
        ->withToken('operator', '%')
        ->withChild('right', Expression::class, [new IsReadExpression])
        ->withConstructor('left', 'right'),

    (new NodeDef(MultiplyExpression::class))
        ->withImplements(Expression::class)
        ->withChild('left', Expression::class, [new IsReadExpression])
        ->withToken('operator', '*')
        ->withChild('right', Expression::class, [new IsReadExpression])
        ->withConstructor('left', 'right'),

    (new NodeDef(NegationExpression::class))
        ->withImplements(Expression::class)
        ->withToken('symbol', '-')
        ->withChild('expression', Expression::class, [new IsReadExpression])
        ->withConstructor('expression'),

    (new NodeDef(NewExpression::class))
        ->withImplements(Expression::class)
        ->withToken('keyword', T_NEW)
        ->withChild('class', Expression::class, [new IsReadExpression])
        ->withOptToken('leftParenthesis', '(')
        ->withSepList('arguments', Argument::class, ',')
        ->withOptToken('rightParenthesis', ')')
        ->withConstructor('class'),

    (new NodeDef(NumberLiteral::class))
        ->withImplements(Expression::class)
        ->withToken('token', [T_LNUMBER, T_DNUMBER])
        ->withConstructor('token'),

    (new NodeDef(ParenthesizedExpression::class))
        ->withImplements(Expression::class)
        ->withToken('leftParenthesis', '(')
        ->withChild('expression', Expression::class, [new IsReadExpression])
        ->withToken('rightParenthesis', ')')
        ->withConstructor('expression'),

    (new NodeDef(PostDecrementExpression::class))
        ->withImplements(Expression::class)
        ->withChild('expression', Expression::class, [new IsReadExpression, new IsWriteExpression])
        ->withToken('operator', T_DEC)
        ->withConstructor('expression'),

    (new NodeDef(PostIncrementExpression::class))
        ->withImplements(Expression::class)
        ->withChild('expression', Expression::class, [new IsReadExpression, new IsWriteExpression])
        ->withToken('operator', T_INC)
        ->withConstructor('expression'),

    (new NodeDef(PowerExpression::class))
        ->withImplements(Expression::class)
        ->withChild('left', Expression::class, [new IsReadExpression])
        ->withToken('operator', T_POW)
        ->withChild('right', Expression::class, [new IsReadExpression])
        ->withConstructor('left', 'right'),

    (new NodeDef(PreDecrementExpression::class))
        ->withImplements(Expression::class)
        ->withToken('operator', T_DEC)
        ->withChild('expression', Expression::class, [new IsReadExpression, new IsWriteExpression])
        ->withConstructor('expression'),

    (new NodeDef(PreIncrementExpression::class))
        ->withImplements(Expression::class)
        ->withToken('operator', T_INC)
        ->withChild('expression', Expression::class, [new IsReadExpression, new IsWriteExpression])
        ->withConstructor('expression'),

    (new NodeDef(PrintExpression::class))
        ->withImplements(Expression::class)
        ->withToken('keyword', T_PRINT)
        ->withChild('expression', Expression::class, [new IsReadExpression])
        ->withConstructor('expression'),

    (new NodeDef(RegularAssignmentExpression::class))
        ->withImplements(Expression::class)
        ->withChild('lvalue', Expression::class, [new IsWriteExpression])
        ->withToken('operator', '=')
        ->withChild('value', Expression::class, [new IsReadExpression])
        ->withConstructor('lvalue', 'value'),

    (new NodeDef(NameExpression::class))
        ->withImplements(Expression::class)
        ->withChild('name', Name::class)
        ->withConstructor('name'),

    (new NodeDef(RegularVariableExpression::class))
        ->withImplements(Expression::class)
        ->withToken('variable', T_VARIABLE)
        ->withConstructor('variable'),

    (new NodeDef(ShortArrayExpression::class))
        ->withImplements(ArrayExpression::class)
        ->withToken('leftBracket', '[')
        ->withSepList('items', ArrayItem::class, ',')
        ->withToken('rightBracket', ']')
        ->withConstructor('item'),

    (new NodeDef(SpaceshipExpression::class))
        ->withImplements(Expression::class)
        ->withChild('left', Expression::class, [new IsReadExpression])
        ->withToken('operator', T_SPACESHIP)
        ->withChild('right', Expression::class, [new IsReadExpression])
        ->withConstructor('left', 'right'),

    (new NodeDef(StaticMemberAccessExpression::class))
        ->withImplements(Expression::class)
        ->withChild('accessee', Expression::class, [new IsReadExpression])
        ->withToken('operator', T_DOUBLE_COLON)
        ->withToken('name', T_STRING)
        ->withConstructor('accessee', 'name'),

    (new NodeDef(StaticPropertyAccessExpression::class))
        ->withImplements(Expression::class)
        ->withChild('accessee', Expression::class, [new IsReadExpression])
        ->withToken('operator', T_DOUBLE_COLON)
        ->withToken('name', T_VARIABLE)
        ->withConstructor('accessee', 'name'),

    (new NodeDef(SubtractExpression::class))
        ->withImplements(Expression::class)
        ->withChild('left', Expression::class, [new IsReadExpression])
        ->withToken('operator', '-')
        ->withChild('right', Expression::class, [new IsReadExpression])
        ->withConstructor('left', 'right'),

    (new NodeDef(SuppressErrorsExpression::class))
        ->withImplements(Expression::class)
        ->withToken('operator', '@')
        ->withChild('expression', Expression::class, [new IsReadExpression]) // TODO valid
        ->withConstructor('expression'),

    (new NodeDef(SymbolBooleanAndExpression::class))
        ->withImplements(Expression::class)
        ->withChild('left', Expression::class, [new IsReadExpression])
        ->withToken('operator', T_BOOLEAN_AND)
        ->withChild('right', Expression::class, [new IsReadExpression])
        ->withConstructor('left', 'right'),

    (new NodeDef(SymbolBooleanOrExpression::class))
        ->withImplements(Expression::class)
        ->withChild('left', Expression::class, [new IsReadExpression])
        ->withToken('operator', T_BOOLEAN_OR)
        ->withChild('right', Expression::class, [new IsReadExpression])
        ->withConstructor('left', 'right'),

    (new NodeDef(TernaryExpression::class))
        ->withImplements(Expression::class)
        ->withChild('test', Expression::class, [new IsReadExpression])
        ->withToken('questionMark', '?')
        ->withOptChild('then', Expression::class, [new IsReadExpression])
        ->withToken('colon', ':')
        ->withChild('else', Expression::class, [new IsReadExpression])
        ->withConstructor('test', 'then', 'else'),

    (new NodeDef(UnsetExpression::class))
        ->withImplements(Expression::class)
        ->withToken('keyword', T_UNSET)
        ->withToken('leftParenthesis', '(')
        ->withSepList('expressions', Expression::class, ',') // TODO valid
        ->withToken('rightParenthesis', ')'),

    (new NodeDef(YieldExpression::class))
        ->withImplements(Expression::class)
        ->withToken('keyword', T_YIELD)
        ->withOptChild('key', Key::class)
        ->withChild('expression', Expression::class, [new IsReadExpression])
        ->withConstructor('expression'),

    (new NodeDef(YieldFromExpression::class))
        ->withImplements(Expression::class)
        ->withToken('keyword', T_YIELD_FROM)
        ->withChild('expression', Expression::class, [new IsReadExpression])
        ->withConstructor('expression'),
];
