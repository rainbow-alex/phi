<?php

use Phi\Meta\NodeDef;
use Phi\Nodes\Block;
use Phi\Nodes\BlockStatement;
use Phi\Nodes\BreakStatement;
use Phi\Nodes\Catch_;
use Phi\Nodes\ClassLikeMember;
use Phi\Nodes\ClassLikeStatement;
use Phi\Nodes\ClassStatement;
use Phi\Nodes\ContinueStatement;
use Phi\Nodes\Name;
use Phi\Nodes\Type;
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
use Phi\Nodes\GotoStatement;
use Phi\Nodes\GroupedUsePrefix;
use Phi\Nodes\GroupedUseStatement;
use Phi\Nodes\IfStatement;
use Phi\Nodes\Implements_;
use Phi\Nodes\InlineHtmlStatement;
use Phi\Nodes\IntegerLiteral;
use Phi\Nodes\InterfaceStatement;
use Phi\Nodes\Key;
use Phi\Nodes\LabelStatement;
use Phi\Nodes\NamespaceStatement;
use Phi\Nodes\NopStatement;
use Phi\Nodes\Parameter;
use Phi\Nodes\RegularBlock;
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
use Phi\Nodes\UnsetStatement;
use Phi\Nodes\UseName;
use Phi\Nodes\UseStatement;
use Phi\Nodes\WhileStatement;
use Phi\Token;
use Phi\TokenType;

return [
    (new NodeDef(BlockStatement::class))
        ->withExtends(Statement::class)
        ->withChild("block", RegularBlock::class)
        ->withConstructor("block"),

    (new NodeDef(BreakStatement::class))
        ->withExtends(Statement::class)
        ->withToken("keyword", TokenType::T_BREAK)
        ->withOptChild("levels", IntegerLiteral::class) // TODO validate
        ->withToken("delimiter", [TokenType::S_SEMICOLON, TokenType::T_CLOSE_TAG]),

    (new NodeDef(ClassStatement::class))
        ->withExtends(ClassLikeStatement::class)
        ->withTokenList("modifiers", [TokenType::T_ABSTRACT, TokenType::T_FINAL])
        ->withToken("keyword", TokenType::T_CLASS)
        ->withToken("name", TokenType::T_STRING)
        ->withOptChild("extends", Extends_::class)
        ->withOptChild("implements", Implements_::class)
        ->withToken("leftBrace", TokenType::S_LEFT_CURLY_BRACE)
        ->withList("members", ClassLikeMember::class)
        ->withToken("rightBrace", TokenType::S_RIGHT_CURLY_BRACE)
        ->withConstructor("name"),

    (new NodeDef(ContinueStatement::class))
        ->withExtends(Statement::class)
        ->withToken("keyword", TokenType::T_CONTINUE)
        ->withOptChild("levels", IntegerLiteral::class) // TODO validate
        ->withToken("delimiter", [TokenType::S_SEMICOLON, TokenType::T_CLOSE_TAG]),

    (new NodeDef(DeclareStatement::class))
        ->withExtends(Statement::class)
        ->withToken("keyword", TokenType::T_DECLARE)
        ->withToken("leftParenthesis", TokenType::S_LEFT_PAREN)
        ->withSepList("directives", DeclareDirective::class, TokenType::S_COMMA)
        ->withToken("rightParenthesis", TokenType::S_RIGHT_PAREN)
        ->withOptChild("block", Block::class)
        ->withOptToken("delimiter", [TokenType::S_SEMICOLON, TokenType::T_CLOSE_TAG]),

    (new NodeDef(DeclareDirective::class))
        ->withToken("key", TokenType::T_STRING)
        ->withToken("equals", TokenType::S_EQUALS)
        ->withChild("value", Expression::class),

    (new NodeDef(DoWhileStatement::class))
        ->withExtends(Statement::class)
        ->withToken("keyword1", TokenType::T_DO)
        ->withChild("block", Block::class)
        ->withToken("keyword2", TokenType::T_WHILE)
        ->withToken("leftParenthesis", TokenType::S_LEFT_PAREN)
        ->withChild("test", Expression::class)
        ->withToken("rightParenthesis", TokenType::S_RIGHT_PAREN)
        ->withToken("delimiter", [TokenType::S_SEMICOLON, TokenType::T_CLOSE_TAG]),

    (new NodeDef(EchoStatement::class))
        ->withExtends(Statement::class)
        ->withToken("keyword", [TokenType::T_ECHO, TokenType::T_OPEN_TAG_WITH_ECHO])
        ->withSepList("expressions", Expression::class, TokenType::S_COMMA)
        ->withToken("delimiter", [TokenType::S_SEMICOLON, TokenType::T_CLOSE_TAG]),

    (new NodeDef(ExpressionStatement::class))
        ->withExtends(Statement::class)
        ->withChild("expression", Expression::class)
        ->withOptToken("semiColon", TokenType::S_SEMICOLON)
        ->withConstructor("expression"),

    (new NodeDef(ForStatement::class))
        ->withExtends(Statement::class)
        ->withToken("keyword", TokenType::T_FOR)
        ->withToken("leftParenthesis", TokenType::S_LEFT_PAREN)
        ->withSepList("inits", Expression::class, TokenType::S_COMMA)
        ->withToken("separator1", [TokenType::S_SEMICOLON, TokenType::S_COMMA])
        ->withSepList("tests", Expression::class, TokenType::S_COMMA)
        ->withToken("separator2", [TokenType::S_SEMICOLON, TokenType::S_COMMA])
        ->withSepList("steps", Expression::class, TokenType::S_COMMA)
        ->withToken("rightParenthesis", TokenType::S_RIGHT_PAREN)
        ->withChild("block", Block::class)
        ->withConstructor("init", "test", "step", "block"),

    (new NodeDef(ForeachStatement::class))
        ->withExtends(Statement::class)
        ->withToken("keyword", TokenType::T_FOREACH)
        ->withToken("leftParenthesis", TokenType::S_LEFT_PAREN)
        ->withChild("iterable",  Expression::class)
        ->withToken("as", TokenType::T_AS)
        ->withOptChild("key", Key::class)
        ->withOptToken("byReference", TokenType::S_AMPERSAND)
        ->withChild("value", Expression::class)
        ->withToken("rightParenthesis", TokenType::S_RIGHT_PAREN)
        ->withChild("block", Block::class)
        ->withConstructor("iterable", "key", "value", "block"),

    (new NodeDef(FunctionStatement::class))
        ->withExtends(Statement::class)
        ->withToken("keyword", TokenType::T_FUNCTION)
        ->withOptToken("byReference", TokenType::S_AMPERSAND)
        ->withToken("name", TokenType::T_STRING)
        ->withToken("leftParenthesis", TokenType::S_LEFT_PAREN)
        ->withSepList("parameters", Parameter::class, TokenType::S_COMMA)
        ->withToken("rightParenthesis", TokenType::S_RIGHT_PAREN)
        ->withOptChild("returnType", ReturnType::class)
        ->withChild("body", RegularBlock::class),

    (new NodeDef(GotoStatement::class))
        ->withExtends(Statement::class)
        ->withToken("keyword", TokenType::T_GOTO)
        ->withToken("label", TokenType::T_STRING)
        ->withOptToken("semiColon", TokenType::S_SEMICOLON)
        ->withConstructor("label"),

    (new NodeDef(GroupedUseStatement::class))
        ->withExtends(UseStatement::class)
        ->withToken("keyword", TokenType::T_USE)
        ->withOptToken("type", [TokenType::T_FUNCTION, TokenType::T_CONST])
        ->withOptChild("prefix", GroupedUsePrefix::class)
        ->withToken("leftBrace", TokenType::S_LEFT_CURLY_BRACE)
        ->withSepList("uses", UseName::class, TokenType::S_COMMA)
        ->withToken("rightBrace", TokenType::S_RIGHT_CURLY_BRACE)
        ->withToken("delimiter", [TokenType::S_SEMICOLON, TokenType::T_CLOSE_TAG]),

    (new NodeDef(GroupedUsePrefix::class))
        ->withChild("prefix", Name::class)
        ->withToken("separator", TokenType::T_NS_SEPARATOR),

    (new NodeDef(IfStatement::class))
        ->withExtends(Statement::class)
        ->withToken("keyword", TokenType::T_IF)
        ->withToken("leftParenthesis", TokenType::S_LEFT_PAREN)
        ->withChild("test", Expression::class)
        ->withToken("rightParenthesis", TokenType::S_RIGHT_PAREN)
        ->withChild("block", Block::class)
        ->withList("elseifs", Elseif_::class)
        ->withOptChild("else", Else_::class),

    (new NodeDef(Elseif_::class))
        ->withToken("keyword", TokenType::T_ELSEIF)
        ->withToken("leftParenthesis", TokenType::S_LEFT_PAREN)
        ->withChild("test", Expression::class)
        ->withToken("rightParenthesis", TokenType::S_RIGHT_PAREN)
        ->withChild("block", Block::class),

    (new NodeDef(Else_::class))
        ->withToken("keyword", TokenType::T_ELSE)
        ->withChild("block", Block::class),

    (new NodeDef(InlineHtmlStatement::class))
        ->withExtends(Statement::class)
        ->withOptToken("content", TokenType::T_INLINE_HTML)
        ->withOptToken("openingTag", TokenType::T_OPEN_TAG)
        ->withConstructor("content", "openingTag"),

    (new NodeDef(InterfaceStatement::class))
        ->withExtends(ClassLikeStatement::class)
        ->withToken("keyword", TokenType::T_INTERFACE)
        ->withToken("name", TokenType::T_STRING)
        ->withOptChild("extends", Extends_::class)
        ->withToken("leftBrace", TokenType::S_LEFT_CURLY_BRACE)
        ->withList("members", ClassLikeMember::class)
        ->withToken("rightBrace", TokenType::S_RIGHT_CURLY_BRACE)
        ->withConstructor("name"),

    (new NodeDef(LabelStatement::class))
        ->withExtends(Statement::class)
        ->withToken("label", TokenType::T_STRING)
        ->withToken("colon", TokenType::S_COLON)
        ->withConstructor("label"),

    (new NodeDef(NamespaceStatement::class))
        ->withExtends(Statement::class)
        ->withToken("keyword", TokenType::T_NAMESPACE)
        ->withOptChild("name", Name::class)
        ->withOptChild("block", RegularBlock::class)
        ->withOptToken("delimiter", [TokenType::S_SEMICOLON, TokenType::T_CLOSE_TAG]),

    (new NodeDef(NopStatement::class))
        ->withExtends(Statement::class)
        ->withToken("delimiter", [TokenType::S_SEMICOLON, TokenType::T_CLOSE_TAG]),

    (new NodeDef(RegularUseStatement::class))
        ->withExtends(UseStatement::class)
        ->withToken("keyword", TokenType::T_USE)
        ->withOptToken("type", [TokenType::T_FUNCTION, TokenType::T_CONST])
        ->withSepList("names", UseName::class, TokenType::S_COMMA)
        ->withOptToken("semiColon", TokenType::S_SEMICOLON)
        ->withConstructor("name"),

    (new NodeDef(ReturnStatement::class))
        ->withExtends(Statement::class)
        ->withToken("keyword", TokenType::T_RETURN)
        ->withOptChild("expression", Expression::class)
        ->withToken("delimiter", [TokenType::S_SEMICOLON, TokenType::T_CLOSE_TAG]),

    (new NodeDef(StaticVariableDeclaration::class))
        ->withExtends(Statement::class)
        ->withToken("keyword", TokenType::T_STATIC)
        ->withSepList("variables", StaticVariable::class, TokenType::S_COMMA)
        ->withToken("delimiter", [TokenType::S_SEMICOLON, TokenType::T_CLOSE_TAG]),

    (new NodeDef(StaticVariable::class))
        ->withToken("variable", TokenType::T_VARIABLE)
        ->withOptChild("default", Default_::class),

    (new NodeDef(SwitchStatement::class))
        ->withExtends(Statement::class)
        ->withToken("keyword", TokenType::T_SWITCH)
        ->withToken("leftParenthesis", TokenType::S_LEFT_PAREN)
        ->withChild("value", Expression::class)
        ->withToken("rightParenthesis", TokenType::S_RIGHT_PAREN)
        ->withToken("leftBrace", TokenType::S_LEFT_CURLY_BRACE)
        ->withList("cases", SwitchCase::class)
        ->withOptChild("default", SwitchDefault::class) // TODO default can appear anywhere
        ->withToken("rightBrace", TokenType::S_RIGHT_CURLY_BRACE),

    (new NodeDef(SwitchCase::class))
        ->withToken("keyword", TokenType::T_CASE)
        ->withChild("value", Expression::class)
        ->withToken("colon", [TokenType::S_COLON, TokenType::S_SEMICOLON])
        ->withList("statements", Statement::class),

    (new NodeDef(SwitchDefault::class))
        ->withToken("keyword", TokenType::T_DEFAULT)
        ->withToken("colon", TokenType::S_COLON)
        ->withList("statements", Statement::class),

    (new NodeDef(ThrowStatement::class))
        ->withExtends(Statement::class)
        ->withToken("keyword", TokenType::T_THROW)
        ->withChild("expression", Expression::class)
        ->withToken("delimiter", [TokenType::S_SEMICOLON, TokenType::T_CLOSE_TAG]),

    (new NodeDef(TraitStatement::class))
        ->withExtends(ClassLikeStatement::class)
        ->withToken("keyword", TokenType::T_TRAIT)
        ->withToken("name", TokenType::T_STRING)
        ->withToken("leftBrace", TokenType::S_LEFT_CURLY_BRACE)
        ->withList("members", ClassLikeMember::class)
        ->withToken("rightBrace", TokenType::S_RIGHT_CURLY_BRACE),

    (new NodeDef(TryStatement::class))
        ->withExtends(Statement::class)
        ->withToken("keyword", TokenType::T_TRY)
        ->withChild("block", RegularBlock::class)
        ->withList("catches", Catch_::class)
        ->withOptChild("finally", Finally_::class),

    (new NodeDef(Catch_::class))
        ->withToken("keyword", TokenType::T_CATCH)
        ->withToken("leftParenthesis", TokenType::S_LEFT_PAREN)
        ->withSepList("types", Type::class, TokenType::S_VERTICAL_BAR)
        ->withToken("variable", TokenType::T_VARIABLE)
        ->withToken("rightParenthesis", TokenType::S_RIGHT_PAREN)
        ->withChild("block", RegularBlock::class),

    (new NodeDef(Finally_::class))
        ->withToken("keyword", TokenType::T_FINALLY)
        ->withChild("block", RegularBlock::class),

    (new NodeDef(WhileStatement::class))
        ->withExtends(Statement::class)
        ->withToken("keyword", TokenType::T_WHILE)
        ->withToken("leftParenthesis", TokenType::S_LEFT_PAREN)
        ->withChild("test", Expression::class)
        ->withToken("rightParenthesis", TokenType::S_RIGHT_PAREN)
        ->withChild("block", Block::class)
        ->withConstructor("test"),

    (new NodeDef(UnsetStatement::class))
        ->withExtends(Statement::class)
        ->withToken("keyword", TokenType::T_UNSET)
        ->withToken("leftParenthesis", TokenType::S_LEFT_PAREN)
        ->withSepList("variables", Expression::class, ",")
        ->withToken("rightParenthesis", TokenType::S_RIGHT_PAREN)
        ->withOptToken("semiColon", TokenType::S_COMMA),
];
