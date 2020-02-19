<?php

use Phi\Meta\NodeDef;
use Phi\Nodes\Base\CompoundNode;
use Phi\Nodes\Block;
use Phi\Nodes\Blocks\RegularBlock;
use Phi\Nodes\Expression;
use Phi\Nodes\Expressions\NumberLiteral;
use Phi\Nodes\Expressions\VariableExpression;
use Phi\Nodes\Helpers\Default_;
use Phi\Nodes\Helpers\Key;
use Phi\Nodes\Helpers\Name;
use Phi\Nodes\Helpers\Parameter;
use Phi\Nodes\Helpers\ReturnType;
use Phi\Nodes\Oop\ClassDeclaration;
use Phi\Nodes\Oop\Extends_;
use Phi\Nodes\Oop\Implements_;
use Phi\Nodes\Oop\InterfaceDeclaration;
use Phi\Nodes\Oop\OopMember;
use Phi\Nodes\Oop\TraitDeclaration;
use Phi\Nodes\Statement;
use Phi\Nodes\Statements\BlockStatement;
use Phi\Nodes\Statements\BreakStatement;
use Phi\Nodes\Statements\Catch_;
use Phi\Nodes\Statements\ConstStatement;
use Phi\Nodes\Statements\ContinueStatement;
use Phi\Nodes\Statements\DeclareDirective;
use Phi\Nodes\Statements\DeclareStatement;
use Phi\Nodes\Statements\DoWhileStatement;
use Phi\Nodes\Statements\EchoStatement;
use Phi\Nodes\Statements\Else_;
use Phi\Nodes\Statements\Elseif_;
use Phi\Nodes\Statements\ExpressionStatement;
use Phi\Nodes\Statements\Finally_;
use Phi\Nodes\Statements\ForeachStatement;
use Phi\Nodes\Statements\ForStatement;
use Phi\Nodes\Statements\FunctionStatement;
use Phi\Nodes\Statements\GlobalStatement;
use Phi\Nodes\Statements\GotoStatement;
use Phi\Nodes\Statements\IfStatement;
use Phi\Nodes\Statements\InlineHtmlStatement;
use Phi\Nodes\Statements\LabelStatement;
use Phi\Nodes\Statements\NamespaceStatement;
use Phi\Nodes\Statements\NopStatement;
use Phi\Nodes\Statements\ReturnStatement;
use Phi\Nodes\Statements\StaticVariable;
use Phi\Nodes\Statements\StaticVariableStatement;
use Phi\Nodes\Statements\SwitchCase;
use Phi\Nodes\Statements\SwitchStatement;
use Phi\Nodes\Statements\ThrowStatement;
use Phi\Nodes\Statements\TryStatement;
use Phi\Nodes\Statements\UnsetStatement;
use Phi\Nodes\Statements\UseAlias;
use Phi\Nodes\Statements\UseDeclaration;
use Phi\Nodes\Statements\UseStatement;
use Phi\Nodes\Statements\WhileStatement;
use Phi\Nodes\Type;
use Phi\TokenType as T;

return [
	(new NodeDef(BlockStatement::class))
		->node("block", RegularBlock::class)
		->constructor("block"),

	(new NodeDef(BreakStatement::class))
		->token("keyword", T::T_BREAK)
		->optNode("levels", NumberLiteral::class)
		->token("delimiter", [T::S_SEMICOLON, T::T_CLOSE_TAG]),

	(new NodeDef(ClassDeclaration::class))
		->withTokenList("modifiers", [T::T_ABSTRACT, T::T_FINAL])
		->token("keyword", T::T_CLASS)
		->token("name", T::T_STRING)
		->optNode("extends", Extends_::class)
		->optNode("implements", Implements_::class)
		->token("leftBrace", T::S_LEFT_CURLY_BRACE)
		->nodeList("members", OopMember::class)
		->token("rightBrace", T::S_RIGHT_CURLY_BRACE)
		->constructor("name"),

	(new NodeDef(ConstStatement::class))
		->token("keyword", T::T_CONST)
		->token("name", T::T_STRING)
		->token("equals", T::S_EQUALS)
		->node("value", Expression::class, CompoundNode::CTX_READ)
		->token("delimiter", [T::S_SEMICOLON, T::T_CLOSE_TAG])
		->constructor("name", "value"),

	(new NodeDef(ContinueStatement::class))
		->token("keyword", T::T_CONTINUE)
		->optNode("levels", NumberLiteral::class)
		->token("delimiter", [T::S_SEMICOLON, T::T_CLOSE_TAG]),

	(new NodeDef(DeclareStatement::class))
		->token("keyword", T::T_DECLARE)
		->token("leftParenthesis", T::S_LEFT_PARENTHESIS)
		->sepNodeList("directives", DeclareDirective::class, T::S_COMMA)
		->token("rightParenthesis", T::S_RIGHT_PARENTHESIS)
		->optNode("block", Block::class)
		->optToken("delimiter", [T::S_SEMICOLON, T::T_CLOSE_TAG]),

	(new NodeDef(DeclareDirective::class))
		->token("key", T::T_STRING)
		->token("equals", T::S_EQUALS)
		->node("value", Expression::class),

	(new NodeDef(DoWhileStatement::class))
		->token("doKeyword", T::T_DO)
		->node("block", Block::class)
		->token("whileKeyword", T::T_WHILE)
		->token("leftParenthesis", T::S_LEFT_PARENTHESIS)
		->node("condition", Expression::class, CompoundNode::CTX_READ)
		->token("rightParenthesis", T::S_RIGHT_PARENTHESIS)
		->token("delimiter", [T::S_SEMICOLON, T::T_CLOSE_TAG]),

	(new NodeDef(EchoStatement::class))
		->token("keyword", [T::T_ECHO, T::T_OPEN_TAG_WITH_ECHO])
		->sepNodeList("expressions", Expression::class, T::S_COMMA, CompoundNode::CTX_READ)
		->token("delimiter", [T::S_SEMICOLON, T::T_CLOSE_TAG]),

	(new NodeDef(ExpressionStatement::class))
		->node("expression", Expression::class, CompoundNode::CTX_READ)
		->optToken("semiColon", [T::S_SEMICOLON, T::T_CLOSE_TAG])
		->constructor("expression"),

	(new NodeDef(ForStatement::class))
		->token("keyword", T::T_FOR)
		->token("leftParenthesis", T::S_LEFT_PARENTHESIS)
		->sepNodeList("initExpressions", Expression::class, T::S_COMMA, CompoundNode::CTX_READ)
		->token("separator1", T::S_SEMICOLON)
		->sepNodeList("conditionExpressions", Expression::class, T::S_COMMA, CompoundNode::CTX_READ)
		->token("separator2", T::S_SEMICOLON)
		->sepNodeList("stepExpressions", Expression::class, T::S_COMMA, CompoundNode::CTX_READ)
		->token("rightParenthesis", T::S_RIGHT_PARENTHESIS)
		->node("block", Block::class)
		->constructor("initExpression", "conditionExpression", "stepExpression", "block"),

	(new NodeDef(ForeachStatement::class))
		->token("keyword", T::T_FOREACH)
		->token("leftParenthesis", T::S_LEFT_PARENTHESIS)
		->node("iterable",  Expression::class, CompoundNode::CTX_READ)
		->token("asKeyword", T::T_AS)
		->optNode("key", Key::class, CompoundNode::CTX_WRITE)
		->optToken("byReference", T::S_AMPERSAND)
		->node("value", Expression::class, '$this->byReference ? self::CTX_ALIAS_WRITE : self::CTX_WRITE')
		->token("rightParenthesis", T::S_RIGHT_PARENTHESIS)
		->node("block", Block::class)
		->constructor("iterable", "key", "value", "block"),

	(new NodeDef(FunctionStatement::class))
		->token("keyword", T::T_FUNCTION)
		->optToken("byReference", T::S_AMPERSAND)
		->token("name", T::T_STRING)
		->token("leftParenthesis", T::S_LEFT_PARENTHESIS)
		->sepNodeList("parameters", Parameter::class, T::S_COMMA)
		->token("rightParenthesis", T::S_RIGHT_PARENTHESIS)
		->optNode("returnType", ReturnType::class)
		->node("body", RegularBlock::class),

	(new NodeDef(GlobalStatement::class))
		->token("keyword", T::T_GLOBAL)
		->sepNodeList("variables", VariableExpression::class, T::S_COMMA)
		->token("delimiter", [T::S_SEMICOLON, T::T_CLOSE_TAG])
		->constructor("variable"),

	(new NodeDef(GotoStatement::class))
		->token("keyword", T::T_GOTO)
		->token("label", T::T_STRING)
		->token("delimiter", [T::S_SEMICOLON, T::T_CLOSE_TAG])
		->constructor("label"),

	(new NodeDef(IfStatement::class))
		->token("keyword", T::T_IF)
		->token("leftParenthesis", T::S_LEFT_PARENTHESIS)
		->node("condition", Expression::class, CompoundNode::CTX_READ)
		->token("rightParenthesis", T::S_RIGHT_PARENTHESIS)
		->node("block", Block::class)
		->nodeList("elseifClauses", Elseif_::class)
		->optNode("elseClause", Else_::class)
		->constructor("condition", "block"),
	(new NodeDef(Elseif_::class))
		->token("keyword", T::T_ELSEIF)
		->token("leftParenthesis", T::S_LEFT_PARENTHESIS)
		->node("condition", Expression::class, CompoundNode::CTX_READ)
		->token("rightParenthesis", T::S_RIGHT_PARENTHESIS)
		->node("block", Block::class)
		->constructor("condition", "block"),
	(new NodeDef(Else_::class))
		->token("keyword", T::T_ELSE)
		->node("block", Block::class)
		->constructor("block"),

	(new NodeDef(InlineHtmlStatement::class))
		->optToken("content", T::T_INLINE_HTML)
		->optToken("openingTag", T::T_OPEN_TAG)
		->constructor("content", "openingTag"),

	(new NodeDef(InterfaceDeclaration::class))
		->token("keyword", T::T_INTERFACE)
		->token("name", T::T_STRING)
		->optNode("extends", Extends_::class)
		->token("leftBrace", T::S_LEFT_CURLY_BRACE)
		->nodeList("members", OopMember::class)
		->token("rightBrace", T::S_RIGHT_CURLY_BRACE)
		->constructor("name"),

	(new NodeDef(LabelStatement::class))
		->token("label", T::T_STRING)
		->token("colon", T::S_COLON)
		->constructor("label"),

	(new NodeDef(NamespaceStatement::class))
		->token("keyword", T::T_NAMESPACE)
		->optNode("name", Name::class)
		->optNode("block", RegularBlock::class)
		->optToken("delimiter", [T::S_SEMICOLON, T::T_CLOSE_TAG]),

	(new NodeDef(NopStatement::class))
		->token("delimiter", [T::S_SEMICOLON, T::T_CLOSE_TAG]),

	(new NodeDef(ReturnStatement::class))
		->token("keyword", T::T_RETURN)
		->optNode("expression", Expression::class, CompoundNode::CTX_READ)
		->token("delimiter", [T::S_SEMICOLON, T::T_CLOSE_TAG]),

	(new NodeDef(StaticVariableStatement::class))
		->token("keyword", T::T_STATIC)
		->sepNodeList("variables", StaticVariable::class, T::S_COMMA)
		->token("delimiter", [T::S_SEMICOLON, T::T_CLOSE_TAG]),

	(new NodeDef(StaticVariable::class))
		->token("variable", T::T_VARIABLE)
		->optNode("default", Default_::class),

	(new NodeDef(SwitchStatement::class))
		->token("keyword", T::T_SWITCH)
		->token("leftParenthesis", T::S_LEFT_PARENTHESIS)
		->node("value", Expression::class, CompoundNode::CTX_READ)
		->token("rightParenthesis", T::S_RIGHT_PARENTHESIS)
		->token("leftBrace", T::S_LEFT_CURLY_BRACE)
		->nodeList("cases", SwitchCase::class)
		->token("rightBrace", T::S_RIGHT_CURLY_BRACE),

	(new NodeDef(SwitchCase::class))
		->token("keyword", [T::T_CASE, T::T_DEFAULT])
		->optNode("value", Expression::class, CompoundNode::CTX_READ)
		->token("colon", [T::S_COLON, T::S_SEMICOLON])
		->nodeList("statements", Statement::class),

	(new NodeDef(ThrowStatement::class))
		->token("keyword", T::T_THROW)
		->node("expression", Expression::class, CompoundNode::CTX_READ)
		->token("delimiter", [T::S_SEMICOLON, T::T_CLOSE_TAG]),

	(new NodeDef(TraitDeclaration::class))
		->token("keyword", T::T_TRAIT)
		->token("name", T::T_STRING)
		->token("leftBrace", T::S_LEFT_CURLY_BRACE)
		->nodeList("members", OopMember::class)
		->token("rightBrace", T::S_RIGHT_CURLY_BRACE),

	(new NodeDef(TryStatement::class))
		->token("keyword", T::T_TRY)
		->node("block", RegularBlock::class)
		->nodeList("catchClauses", Catch_::class)
		->optNode("finallyClause", Finally_::class),
	(new NodeDef(Catch_::class))
		->token("keyword", T::T_CATCH)
		->token("leftParenthesis", T::S_LEFT_PARENTHESIS)
		->sepNodeList("types", Type::class, T::S_VERTICAL_BAR)
		->token("variable", T::T_VARIABLE)
		->token("rightParenthesis", T::S_RIGHT_PARENTHESIS)
		->node("block", RegularBlock::class),
	(new NodeDef(Finally_::class))
		->token("keyword", T::T_FINALLY)
		->node("block", RegularBlock::class),

	(new NodeDef(WhileStatement::class))
		->token("keyword", T::T_WHILE)
		->token("leftParenthesis", T::S_LEFT_PARENTHESIS)
		->node("condition", Expression::class, CompoundNode::CTX_READ)
		->token("rightParenthesis", T::S_RIGHT_PARENTHESIS)
		->node("block", Block::class)
		->constructor("condition"),

	(new NodeDef(UnsetStatement::class))
		->token("keyword", T::T_UNSET)
		->token("leftParenthesis", T::S_LEFT_PARENTHESIS)
		->sepNodeList("variables", Expression::class, T::S_COMMA)
		->token("rightParenthesis", T::S_RIGHT_PARENTHESIS)
		->optToken("delimiter", [T::S_SEMICOLON, T::T_CLOSE_TAG]),

	(new NodeDef(UseStatement::class))
		->token("keyword", T::T_USE)
		->optToken("type", [T::T_FUNCTION, T::T_CONST])
		->optNode("prefix", Name::class)
		->optToken("leftBrace", T::S_LEFT_CURLY_BRACE)
		->sepNodeList("declarations", UseDeclaration::class, T::S_COMMA)
		->optToken("rightBrace", T::S_RIGHT_CURLY_BRACE)
		->token("delimiter", [T::S_SEMICOLON, T::T_CLOSE_TAG])
		->constructor("declaration"),
	(new NodeDef(UseDeclaration::class))
		->node("name", Name::class)
		->optNode("alias", UseAlias::class),
	(new NodeDef(UseAlias::class))
		->token("keyword", T::T_AS)
		->token("name", T::T_STRING),
];
