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

return [
    (new NodeDef(BlockStatement::class))
        ->withExtends(Statement::class)
        ->withChild("block", RegularBlock::class)
        ->withConstructor("block"),

    (new NodeDef(BreakStatement::class))
        ->withExtends(Statement::class)
        ->withToken("keyword", Token::PH_T_BREAK)
        ->withOptChild("levels", IntegerLiteral::class) // TODO validate
        ->withOptToken("semiColon", Token::PH_S_SEMICOLON),

    (new NodeDef(ClassStatement::class))
        ->withExtends(ClassLikeStatement::class)
        ->withTokenList("modifiers", [Token::PH_T_ABSTRACT, Token::PH_T_FINAL])
        ->withToken("keyword", Token::PH_T_CLASS)
        ->withToken("name", Token::PH_T_STRING)
        ->withOptChild("extends", Extends_::class)
        ->withOptChild("implements", Implements_::class)
        ->withToken("leftBrace", Token::PH_S_LEFT_CURLY_BRACE)
        ->withList("members", ClassLikeMember::class)
        ->withToken("rightBrace", Token::PH_S_RIGHT_CURLY_BRACE)
        ->withConstructor("name"),

    (new NodeDef(ContinueStatement::class))
        ->withExtends(Statement::class)
        ->withToken("keyword", Token::PH_T_CONTINUE)
        ->withOptChild("levels", IntegerLiteral::class) // TODO validate
        ->withOptToken("semiColon", Token::PH_S_SEMICOLON),

    (new NodeDef(DeclareStatement::class))
        ->withExtends(Statement::class)
        ->withToken("keyword", Token::PH_T_DECLARE)
        ->withToken("leftParenthesis", Token::PH_S_LEFT_PAREN)
        ->withSepList("directives", DeclareDirective::class, Token::PH_S_COMMA)
        ->withToken("rightParenthesis", Token::PH_S_RIGHT_PAREN)
        ->withOptChild("block", Block::class)
        ->withOptToken("semiColon", Token::PH_S_SEMICOLON),

    (new NodeDef(DeclareDirective::class))
        ->withToken("key", Token::PH_T_STRING)
        ->withToken("equals", Token::PH_S_EQUALS)
        ->withChild("value", Expression::class),

    (new NodeDef(DoWhileStatement::class))
        ->withExtends(Statement::class)
        ->withToken("keyword1", Token::PH_T_DO)
        ->withChild("block", Block::class)
        ->withToken("keyword2", Token::PH_T_WHILE)
        ->withToken("leftParenthesis", Token::PH_S_LEFT_PAREN)
        ->withChild("test", Expression::class)
        ->withToken("rightParenthesis", Token::PH_S_RIGHT_PAREN)
        ->withOptToken("semiColon", Token::PH_S_SEMICOLON),

    (new NodeDef(EchoStatement::class))
        ->withExtends(Statement::class)
        ->withToken("keyword", Token::PH_T_ECHO)
        ->withSepList("expressions", Expression::class, Token::PH_S_COMMA)
        ->withOptToken("semiColon", Token::PH_S_SEMICOLON),

    (new NodeDef(ExpressionStatement::class))
        ->withExtends(Statement::class)
        ->withChild("expression", Expression::class)
        ->withOptToken("semiColon", Token::PH_S_SEMICOLON)
        ->withConstructor("expression"),

    (new NodeDef(ForStatement::class))
        ->withExtends(Statement::class)
        ->withToken("keyword", Token::PH_T_FOR)
        ->withToken("leftParenthesis", Token::PH_S_LEFT_PAREN)
        ->withOptChild("init", Expression::class) // TODO valid
        ->withToken("separator1", Token::PH_S_SEMICOLON)
        ->withOptChild("test", Expression::class) // TODO valid
        ->withToken("separator2", Token::PH_S_SEMICOLON)
        ->withOptChild("step", Expression::class)
        ->withToken("rightParenthesis", Token::PH_S_RIGHT_PAREN)
        ->withChild("block", Block::class)
        ->withConstructor("init", "test", "step", "block"),

    (new NodeDef(ForeachStatement::class))
        ->withExtends(Statement::class)
        ->withToken("keyword", Token::PH_T_FOREACH)
        ->withToken("leftParenthesis", Token::PH_S_LEFT_PAREN)
        ->withChild("iterable",  Expression::class)
        ->withToken("as", Token::PH_T_AS)
        ->withOptChild("key", Key::class)
        ->withOptToken("byReference", Token::PH_S_AMPERSAND)
        ->withChild("value", Expression::class)
        ->withToken("rightParenthesis", Token::PH_S_RIGHT_PAREN)
        ->withChild("block", Block::class)
        ->withConstructor("iterable", "key", "value", "block"),

    (new NodeDef(FunctionStatement::class))
        ->withExtends(Statement::class)
        ->withToken("keyword", Token::PH_T_FUNCTION)
        ->withOptToken("byReference", Token::PH_S_AMPERSAND)
        ->withToken("name", Token::PH_T_STRING)
        ->withToken("leftParenthesis", Token::PH_S_LEFT_PAREN)
        ->withSepList("parameters", Parameter::class, Token::PH_S_COMMA)
        ->withToken("rightParenthesis", Token::PH_S_RIGHT_PAREN)
        ->withOptChild("returnType", ReturnType::class)
        ->withChild("body", RegularBlock::class),

    (new NodeDef(GotoStatement::class))
        ->withExtends(Statement::class)
        ->withToken("keyword", Token::PH_T_GOTO)
        ->withToken("label", Token::PH_T_STRING)
        ->withOptToken("semiColon", Token::PH_S_SEMICOLON)
        ->withConstructor("label"),

    (new NodeDef(GroupedUseStatement::class))
        ->withExtends(UseStatement::class)
        ->withToken("keyword", Token::PH_T_USE)
        ->withOptToken("type", [Token::PH_T_FUNCTION, Token::PH_T_CONST])
        ->withOptChild("prefix", GroupedUsePrefix::class)
        ->withToken("leftBrace", Token::PH_S_LEFT_CURLY_BRACE)
        ->withSepList("uses", UseName::class, Token::PH_S_COMMA)
        ->withToken("rightBrace", Token::PH_S_RIGHT_CURLY_BRACE)
        ->withOptToken("semiColon", Token::PH_S_SEMICOLON),

    (new NodeDef(GroupedUsePrefix::class))
        ->withChild("prefix", Name::class)
        ->withToken("separator", Token::PH_T_NS_SEPARATOR),

    (new NodeDef(IfStatement::class))
        ->withExtends(Statement::class)
        ->withToken("keyword", Token::PH_T_IF)
        ->withToken("leftParenthesis", Token::PH_S_LEFT_PAREN)
        ->withChild("test", Expression::class)
        ->withToken("rightParenthesis", Token::PH_S_RIGHT_PAREN)
        ->withChild("block", Block::class)
        ->withList("elseifs", Elseif_::class)
        ->withOptChild("else", Else_::class),

    (new NodeDef(Elseif_::class))
        ->withToken("keyword", Token::PH_T_ELSEIF)
        ->withToken("leftParenthesis", Token::PH_S_LEFT_PAREN)
        ->withChild("test", Expression::class)
        ->withToken("rightParenthesis", Token::PH_S_RIGHT_PAREN)
        ->withChild("block", Block::class),

    (new NodeDef(Else_::class))
        ->withToken("keyword", Token::PH_T_ELSE)
        ->withChild("block", Block::class),

    (new NodeDef(InlineHtmlStatement::class))
        ->withExtends(Statement::class)
        ->withOptToken("closingTag", Token::PH_T_CLOSE_TAG)
        ->withOptToken("content", Token::PH_T_INLINE_HTML)
        ->withOptToken("openingTag", Token::PH_T_OPEN_TAG)
        ->withConstructor("closingTag", "content", "openingTag"),

    (new NodeDef(InterfaceStatement::class))
        ->withExtends(ClassLikeStatement::class)
        ->withToken("keyword", Token::PH_T_INTERFACE)
        ->withToken("name", Token::PH_T_STRING)
        ->withOptChild("extends", Extends_::class)
        ->withToken("leftBrace", Token::PH_S_LEFT_CURLY_BRACE)
        ->withList("members", ClassLikeMember::class)
        ->withToken("rightBrace", Token::PH_S_RIGHT_CURLY_BRACE)
        ->withConstructor("name"),

    (new NodeDef(LabelStatement::class))
        ->withExtends(Statement::class)
        ->withToken("label", Token::PH_T_STRING)
        ->withToken("colon", Token::PH_S_COLON)
        ->withConstructor("label"),

    (new NodeDef(NamespaceStatement::class))
        ->withExtends(Statement::class)
        ->withToken("keyword", Token::PH_T_NAMESPACE)
        ->withOptChild("name", Name::class)
        ->withOptChild("block", RegularBlock::class)
        ->withOptToken("semiColon", Token::PH_S_SEMICOLON),

    (new NodeDef(NopStatement::class))
        ->withExtends(Statement::class)
        ->withToken("semiColon", Token::PH_S_SEMICOLON),

    (new NodeDef(RegularUseStatement::class))
        ->withExtends(UseStatement::class)
        ->withToken("keyword", Token::PH_T_USE)
        ->withOptToken("type", [Token::PH_T_FUNCTION, Token::PH_T_CONST])
        ->withSepList("names", UseName::class, Token::PH_S_COMMA)
        ->withOptToken("semiColon", Token::PH_S_SEMICOLON)
        ->withConstructor("name"),

    (new NodeDef(ReturnStatement::class))
        ->withExtends(Statement::class)
        ->withToken("keyword", Token::PH_T_RETURN)
        ->withOptChild("expression", Expression::class)
        ->withOptToken("semiColon", Token::PH_S_SEMICOLON),

    (new NodeDef(StaticVariableDeclaration::class))
        ->withExtends(Statement::class)
        ->withToken("keyword", Token::PH_T_STATIC)
        ->withSepList("variables", StaticVariable::class, Token::PH_S_COMMA)
        ->withOptToken("semiColon", Token::PH_S_SEMICOLON),

    (new NodeDef(StaticVariable::class))
        ->withToken("variable", Token::PH_T_VARIABLE)
        ->withOptChild("default", Default_::class),

    (new NodeDef(SwitchStatement::class))
        ->withExtends(Statement::class)
        ->withToken("keyword", Token::PH_T_SWITCH)
        ->withToken("leftParenthesis", Token::PH_S_LEFT_PAREN)
        ->withChild("value", Expression::class)
        ->withToken("rightParenthesis", Token::PH_S_RIGHT_PAREN)
        ->withToken("leftBrace", Token::PH_S_LEFT_CURLY_BRACE)
        ->withList("cases", SwitchCase::class)
        ->withOptChild("default", SwitchDefault::class) // TODO default can appear anywhere
        ->withToken("rightBrace", Token::PH_S_RIGHT_CURLY_BRACE),

    (new NodeDef(SwitchCase::class))
        ->withToken("keyword", Token::PH_T_CASE)
        ->withChild("value", Expression::class)
        ->withToken("colon", [Token::PH_S_COLON, Token::PH_S_SEMICOLON])
        ->withList("statements", Statement::class),

    (new NodeDef(SwitchDefault::class))
        ->withToken("keyword", Token::PH_T_DEFAULT)
        ->withToken("colon", Token::PH_S_COLON)
        ->withList("statements", Statement::class),

    (new NodeDef(ThrowStatement::class))
        ->withExtends(Statement::class)
        ->withToken("keyword", Token::PH_T_THROW)
        ->withChild("expression", Expression::class)
        ->withOptToken("semiColon", Token::PH_S_SEMICOLON),

    (new NodeDef(TraitStatement::class))
        ->withExtends(ClassLikeStatement::class)
        ->withToken("keyword", Token::PH_T_TRAIT)
        ->withToken("name", Token::PH_T_STRING)
        ->withToken("leftBrace", Token::PH_S_LEFT_CURLY_BRACE)
        ->withList("members", ClassLikeMember::class)
        ->withToken("rightBrace", Token::PH_S_RIGHT_CURLY_BRACE),

    (new NodeDef(TryStatement::class))
        ->withExtends(Statement::class)
        ->withToken("keyword", Token::PH_T_TRY)
        ->withChild("block", RegularBlock::class)
        ->withList("catches", Catch_::class)
        ->withOptChild("finally", Finally_::class),

    (new NodeDef(Catch_::class))
        ->withToken("keyword", Token::PH_T_CATCH)
        ->withToken("leftParenthesis", Token::PH_S_LEFT_PAREN)
        ->withSepList("types", Type::class, Token::PH_S_VERTICAL_BAR)
        ->withToken("variable", Token::PH_T_VARIABLE)
        ->withToken("rightParenthesis", Token::PH_S_RIGHT_PAREN)
        ->withChild("block", RegularBlock::class),

    (new NodeDef(Finally_::class))
        ->withToken("keyword", Token::PH_T_FINALLY)
        ->withChild("block", RegularBlock::class),

    (new NodeDef(WhileStatement::class))
        ->withExtends(Statement::class)
        ->withToken("keyword", Token::PH_T_WHILE)
        ->withToken("leftParenthesis", Token::PH_S_LEFT_PAREN)
        ->withChild("test", Expression::class)
        ->withToken("rightParenthesis", Token::PH_S_RIGHT_PAREN)
        ->withChild("block", Block::class)
        ->withConstructor("test"),

    (new NodeDef(UnsetStatement::class))
        ->withExtends(Statement::class)
        ->withToken("keyword", Token::PH_T_UNSET)
        ->withToken("leftParenthesis", Token::PH_S_LEFT_PAREN)
        ->withSepList("variables", Expression::class, ",")
        ->withToken("rightParenthesis", Token::PH_S_RIGHT_PAREN)
        ->withOptToken("semiColon", Token::PH_S_COMMA),
];
