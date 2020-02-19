<?php

use Phi\Meta\NodeDef;
use Phi\Nodes\Base\CompoundNode;
use Phi\Nodes\Blocks\AlternativeFormatBlock;
use Phi\Nodes\Blocks\ImplicitBlock;
use Phi\Nodes\Blocks\RegularBlock;
use Phi\Nodes\Expression;
use Phi\Nodes\Expressions\ArrayItem;
use Phi\Nodes\Expressions\NormalVariableExpression;
use Phi\Nodes\Expressions\StringInterpolation\ConfusingInterpolatedStringVariable;
use Phi\Nodes\Expressions\StringInterpolation\ConfusingInterpolatedStringVariableName;
use Phi\Nodes\Expressions\StringInterpolation\ConstantInterpolatedStringPart;
use Phi\Nodes\Expressions\StringInterpolation\InterpolatedStringPart;
use Phi\Nodes\Expressions\StringInterpolation\InterpolatedStringVariable;
use Phi\Nodes\Expressions\StringInterpolation\BracedInterpolatedStringVariable;
use Phi\Nodes\Expressions\StringInterpolation\NormalInterpolatedStringVariable;
use Phi\Nodes\Expressions\StringInterpolation\NormalInterpolatedStringVariableArrayAccess;
use Phi\Nodes\Expressions\StringInterpolation\VariableInterpolatedStringVariable;
use Phi\Nodes\Expressions\VariableExpression;
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
use PhpParser\Node\Expr\Variable;

return [
	(new NodeDef(Argument::class))
		->optToken("unpack", T::T_ELLIPSIS)
		->node("expression", Expression::class)
		->constructor("expression")
		->validateChildren(false), // expression context depends on unpack

	(new NodeDef(ArrayItem::class))
		->optNode("key", Key::class, CompoundNode::CTX_READ)
		->optToken("unpack", T::T_ELLIPSIS)
		->optToken("byReference", T::S_AMPERSAND)
		->optNode("value", Expression::class)
		->constructor("value"),

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
		->node("variable", Expression::class, CompoundNode::CTX_READ)
		->constructor("variable"),
	(new NodeDef(NormalInterpolatedStringVariableArrayAccess::class))
		->node("variable", NormalVariableExpression::class)
		->token("leftBracket", T::S_LEFT_SQUARE_BRACKET)
		->optToken("minus", T::S_MINUS)
		->token("index", [T::T_NUM_STRING, T::T_STRING, T::T_VARIABLE])
		->token("rightBracket", T::S_RIGHT_SQUARE_BRACKET)
		->constructor("variable", "index"),
	(new NodeDef(BracedInterpolatedStringVariable::class))
		->token("leftBrace", T::S_LEFT_CURLY_BRACE)
		->node("variable", Expression::class, CompoundNode::CTX_READ)
		->token("rightBrace", T::S_RIGHT_CURLY_BRACE)
		->constructor("variable"),
	(new Nodedef(ConfusingInterpolatedStringVariable::class))
		->token("leftDelimiter", T::T_DOLLAR_OPEN_CURLY_BRACES)
		->node("variable", Expression::class, CompoundNode::CTX_READ)
		->token("rightDelimiter", T::S_RIGHT_CURLY_BRACE)
		->constructor("variable"),
	(new NodeDef(ConfusingInterpolatedStringVariableName::class))
		->token("name", T::T_STRING_VARNAME)
		->constructor("name"),
	(new NodeDef(VariableInterpolatedStringVariable::class))
		->token("leftDelimiter", T::T_DOLLAR_OPEN_CURLY_BRACES)
		->node("name", Expression::class, CompoundNode::CTX_READ)
		->token("rightDelimiter", T::S_RIGHT_CURLY_BRACE)
		->constructor("name"),

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
