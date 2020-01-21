<?php

use Phi\Nodes\Block;
use Phi\Nodes\BreakStatement;
use Phi\Nodes\Catch_;
use Phi\Nodes\ClassLikeMember;
use Phi\Nodes\ClassLikeStatement;
use Phi\Nodes\ClassStatement;
use Phi\Nodes\ContinueStatement;
use Phi\Nodes\DeclareDirective;
use Phi\Nodes\DeclareStatement;
use Phi\Nodes\Default_;
use Phi\Nodes\DoWhileStatement;
use Phi\Nodes\EchoStatement;
use Phi\Nodes\Else_;
use Phi\Nodes\Elseif_;
use Phi\Nodes\Expression;
use Phi\Nodes\ExpressionStatement;
use Phi\Nodes\Extends_;
use Phi\Nodes\Finally_;
use Phi\Nodes\ForeachStatement;
use Phi\Nodes\ForStatement;
use Phi\Nodes\FunctionStatement;
use Phi\Nodes\GroupedUsePrefix;
use Phi\Nodes\GroupedUseStatement;
use Phi\Nodes\IfStatement;
use Phi\Nodes\Implements_;
use Phi\Nodes\InlineHtmlStatement;
use Phi\Nodes\InterfaceStatement;
use Phi\Nodes\Key;
use Phi\Nodes\NamespaceStatement;
use Phi\Nodes\NopStatement;
use Phi\Nodes\Parameter;
use Phi\Nodes\RegularName;
use Phi\Nodes\RegularUseStatement;
use Phi\Nodes\ReturnStatement;
use Phi\Nodes\ReturnType;
use Phi\Nodes\Statement;
use Phi\Nodes\StaticVariable;
use Phi\Nodes\StaticVariableDeclaration;
use Phi\Nodes\SwitchCase;
use Phi\Nodes\SwitchDefault;
use Phi\Nodes\SwitchStatement;
use Phi\Nodes\ThrowStatement;
use Phi\Nodes\TraitStatement;
use Phi\Nodes\TryStatement;
use Phi\Nodes\Type;
use Phi\Nodes\UseName;
use Phi\Nodes\UseStatement;
use Phi\Nodes\WhileStatement;
use Phi\Specifications\EachItem;
use Phi\Specifications\IsReadExpression;
use Phi\Specifications\IsToken;
use Phi\Token;

return [
    (new NodeDef(Block::class))
        ->withImplements(Statement::class)
        ->withToken('leftBrace', '{')
        ->withList('statements', Statement::class)
        ->withToken('rightBrace', '}')
        ->withConstructor('statement'),

    (new NodeDef(BreakStatement::class))
        ->withImplements(Statement::class)
        ->withToken('keyword', T_BREAK)
        ->withOptChild('levels', Expression::class) // TODO valid
        ->withOptToken('semiColon',';'),

    (new NodeDef(ClassStatement::class))
        ->withImplements(ClassLikeStatement::class)
        ->withTokenList('modifiers', [T_ABSTRACT, T_FINAL])
        ->withToken('keyword', T_CLASS)
        ->withToken('name', T_STRING)
        ->withOptChild('extends', Extends_::class)
        ->withOptChild('implements', Implements_::class)
        ->withToken('leftBrace', '{')
        ->withList('members', ClassLikeMember::class)
        ->withToken('rightBrace', '}')
        ->withConstructor('name'),

    (new NodeDef(ContinueStatement::class))
        ->withImplements(Statement::class)
        ->withToken('keyword', T_CONTINUE)
        ->withOptChild('levels',Expression::class)
        ->withOptToken('semiColon',';'),

    (new NodeDef(DeclareStatement::class))
        ->withImplements(Statement::class)
        ->withToken('keyword', T_DECLARE)
        ->withToken('leftParenthesis', '(')
        ->withSepList('directives', DeclareDirective::class, ',')
        ->withToken('rightParenthesis', ')')
        ->withOptChild('block', Block::class)
        ->withOptToken('semiColon',';'),

    (new NodeDef(DeclareDirective::class))
        ->withToken('key', T_STRING)
        ->withToken('equals', '=')
        ->withChild('value', Expression::class),

    (new NodeDef(DoWhileStatement::class))
        ->withImplements(Statement::class)
        ->withToken('keyword1', T_DO)
        ->withChild('block', Statement::class)
        ->withToken('keyword2', T_WHILE)
        ->withToken('leftParenthesis', '(')
        ->withChild('test', Expression::class, [new IsReadExpression])
        ->withToken('rightParenthesis', ')')
        ->withOptToken('semiColon',';'),

    (new NodeDef(EchoStatement::class))
        ->withImplements(Statement::class)
        ->withToken('keyword', T_ECHO)
        ->withSepList('expressions', Expression::class, ',', [new EachItem(new IsReadExpression)])
        ->withOptToken('semiColon',';'),

    (new NodeDef(ExpressionStatement::class))
        ->withImplements(Statement::class)
        ->withChild('expression', Expression::class, [new IsReadExpression])
        ->withOptToken('semiColon',';')
        ->withConstructor('expression'),

    (new NodeDef(ForStatement::class))
        ->withImplements(Statement::class)
        ->withToken('keyword', T_FOR)
        ->withToken('leftParenthesis', '(')
        ->withOptChild('init', Expression::class) // TODO valid
        ->withToken('separator1', ';')
        ->withOptChild('test', Expression::class) // TODO valid
        ->withToken('separator2', ';')
        ->withOptChild('step', Expression::class)
        ->withToken('rightParenthesis', ')')
        ->withChild('block', Statement::class)
        ->withConstructor('init', 'test', 'step', 'block'),

    (new NodeDef(ForeachStatement::class))
        ->withImplements(Statement::class)
        ->withToken('keyword', T_FOREACH)
        ->withToken('leftParenthesis', '(')
        ->withChild('iterable',  Expression::class, [new IsReadExpression])
        ->withToken('as', T_AS)
        ->withOptChild('key', Key::class)
        ->withOptToken('byReference', '&')
        ->withChild('value', Expression::class)
        ->withToken('rightParenthesis', ')')
        ->withChild('block', Statement::class)
        ->withConstructor('iterable', 'key', 'value', 'block'),

    (new NodeDef(FunctionStatement::class))
        ->withImplements(Statement::class)
        ->withToken('keyword', T_FUNCTION)
        ->withOptToken('byReference', '&')
        ->withToken('name', T_STRING)
        ->withToken('leftParenthesis', '(')
        ->withSepList('parameters', Parameter::class, ',')
        ->withToken('rightParenthesis', ')')
        ->withOptChild('returnType', ReturnType::class)
        ->withChild('body', Block::class),

    (new NodeDef(GroupedUseStatement::class))
        ->withImplements(UseStatement::class)
        ->withToken('keyword', T_USE)
        ->withOptToken('type', [T_FUNCTION, T_CONST])
        ->withOptChild('prefix', GroupedUsePrefix::class)
        ->withToken('leftBrace', '{')
        ->withSepList('uses', UseName::class, ',')
        ->withToken('rightBrace', '}')
        ->withOptToken('semiColon', ';'),

    (new NodeDef(GroupedUsePrefix::class))
        ->withChild('prefix', RegularName::class)
        ->withToken('separator', T_NS_SEPARATOR),

    (new NodeDef(IfStatement::class))
        ->withImplements(Statement::class)
        ->withToken('keyword', T_IF)
        ->withToken('leftParenthesis', '(')
        ->withChild('test', Expression::class, [new IsReadExpression])
        ->withToken('rightParenthesis', ')')
        ->withChild('block', Statement::class)
        ->withList('elseifs', Elseif_::class)
        ->withOptChild('else', Else_::class),

    (new NodeDef(Elseif_::class))
        ->withToken('keyword', T_ELSEIF)
        ->withToken('leftParenthesis', '(')
        ->withChild('test', Expression::class, [new IsReadExpression])
        ->withToken('rightParenthesis', ')')
        ->withChild('block', Statement::class),

    (new NodeDef(Else_::class))
        ->withToken('keyword', T_ELSE)
        ->withChild('block', Statement::class),

    (new NodeDef(InlineHtmlStatement::class))
        ->withImplements(Statement::class)
        ->withOptToken('closingTag', T_CLOSE_TAG)
        ->withOptToken('content', T_INLINE_HTML)
        ->withOptToken('openingTag', T_OPEN_TAG)
        ->withConstructor('closingTag', 'content', 'openingTag'),

    (new NodeDef(InterfaceStatement::class))
        ->withImplements(ClassLikeStatement::class)
        ->withToken('keyword', T_INTERFACE)
        ->withToken('name', T_STRING)
        ->withOptChild('extends', Extends_::class)
        ->withToken('leftBrace', '{')
        ->withList('members', ClassLikeMember::class)
        ->withToken('rightBrace', '}')
        ->withConstructor('name'),

    (new NodeDef(NamespaceStatement::class))
        ->withImplements(Statement::class)
        ->withToken('keyword', T_NAMESPACE)
        ->withOptChild('name', RegularName::class)
        ->withOptChild('block', Block::class)
        ->withOptToken('semiColon',';'),

    (new NodeDef(NopStatement::class))
        ->withImplements(Statement::class)
        ->withToken('semiColon', ';'),

    (new NodeDef(RegularUseStatement::class))
        ->withImplements(UseStatement::class)
        ->withToken('keyword', T_USE)
        ->withOptToken('type', [T_FUNCTION, T_CONST])
        ->withSepList('names', UseName::class, ',')
        ->withOptToken('semiColon',';')
        ->withConstructor('name'),

    (new NodeDef(ReturnStatement::class))
        ->withImplements(Statement::class)
        ->withToken('keyword', T_RETURN)
        ->withOptChild('expression', Expression::class, [new IsReadExpression])
        ->withOptToken('semiColon',';'),

    (new NodeDef(StaticVariableDeclaration::class))
        ->withImplements(Statement::class)
        ->withToken('keyword', T_STATIC)
        ->withSepList('variables', StaticVariable::class, ',')
        ->withOptToken('semiColon',';'),

    (new NodeDef(StaticVariable::class))
        ->withToken('variable', T_VARIABLE)
        ->withOptChild('default', Default_::class),

    (new NodeDef(SwitchStatement::class))
        ->withImplements(Statement::class)
        ->withToken('keyword', T_SWITCH)
        ->withToken('leftParenthesis', '(')
        ->withChild('value', Expression::class, [new IsReadExpression])
        ->withToken('rightParenthesis', ')')
        ->withToken('leftBrace', '{')
        ->withList('cases', SwitchCase::class)
        ->withOptChild('default', SwitchDefault::class) // TODO default can appear anywhere
        ->withToken('rightBrace', '}'),

    (new NodeDef(SwitchCase::class))
        ->withToken('keyword', T_CASE)
        ->withChild('value', Expression::class, [new IsReadExpression])
        ->withToken('colon', [':', ';'])
        ->withList('statements', Statement::class),

    (new NodeDef(SwitchDefault::class))
        ->withToken('keyword', T_DEFAULT)
        ->withToken('colon', ':')
        ->withList('statements', Statement::class),

    (new NodeDef(ThrowStatement::class))
        ->withImplements(Statement::class)
        ->withToken('keyword', T_THROW)
        ->withChild('expression', Expression::class, [new IsReadExpression])
        ->withOptToken('semiColon',';'),

    (new NodeDef(TraitStatement::class))
        ->withImplements(Statement::class)
        ->withToken('keyword', T_TRAIT)
        ->withToken('name', T_STRING)
        ->withToken('leftBrace', '{')
        ->withList('members', ClassLikeMember::class)
        ->withToken('rightBrace', '}'),

    (new NodeDef(TryStatement::class))
        ->withImplements(Statement::class)
        ->withToken('keyword', T_TRY)
        ->withChild('block', Block::class)
        ->withList('catches', Catch_::class)
        ->withOptChild('finally', Finally_::class),

    (new NodeDef(Catch_::class))
        ->withToken('keyword', T_CATCH)
        ->withToken('leftParenthesis', '(')
        ->withSepList('types', Type::class, '|')
        ->withToken('variable', T_VARIABLE)
        ->withToken('rightParenthesis', ')')
        ->withChild('block', Block::class),

    (new NodeDef(Finally_::class))
        ->withToken('keyword', T_FINALLY)
        ->withChild('block', Block::class),

    (new NodeDef(WhileStatement::class))
        ->withImplements(Statement::class)
        ->withToken('keyword', T_WHILE)
        ->withToken('leftParenthesis', '(')
        ->withChild('test', Expression::class, [new IsReadExpression])
        ->withToken('rightParenthesis', ')')
        ->withChild('block', Statement::class)
        ->withConstructor('test'),
];
