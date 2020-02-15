<?php

declare(strict_types=1);

namespace Phi;

use Phi\Exception\ParseException;
use Phi\Nodes;
use Phi\Nodes\Expression as Expr;
use Phi\TokenType as T;

class Parser
{
	/**
	 * @var int
	 * @see PhpVersion
	 */
	private $phpVersion;

	/** @var Token[] */
	private $tokens = [];
	/** @var int */
	private $i = 0;

	/** @var int[] */
	private $types = [];

	public function __construct(int $phpVersion)
	{
		PhpVersion::validate($phpVersion);
		$this->phpVersion = $phpVersion;
	}

	/**
	 * @throws ParseException
	 */
	public function parse(?string $filename, ?string $source = null): Nodes\RootNode
	{
		if ($source === null)
		{
			if ($filename === null)
			{
				throw new \InvalidArgumentException("You must pass either \$filename or \$source, or both");
			}

			$source = \file_get_contents($filename);
		}

		$this->init((new Lexer($this->phpVersion))->lex($filename, $source));

		try
		{
			$ast = $this->parseRoot();
			$ast->setPhpVersion($this->phpVersion);
			return $ast;
		}
		finally
		{
			$this->deinit();
		}
	}

	/**
	 * @throws ParseException
	 */
	public function parseStatement(string $source): Nodes\Statement
	{
		$this->init((new Lexer($this->phpVersion))->lexFragment($source));

		try
		{
			$ast = $this->statement();
			if ($this->peek()->getType() !== T::T_EOF)
			{
				throw ParseException::unexpected($this->peek());
			}
			$ast->setPhpVersion($this->phpVersion);
			return $ast;
		}
		finally
		{
			$this->deinit();
		}
	}

	/**
	 * @throws ParseException
	 */
	public function parseExpression(string $source): Nodes\Expression
	{
		$this->init((new Lexer($this->phpVersion))->lexFragment($source));

		try
		{
			$ast = $this->expression();
			if ($this->peek()->getType() !== T::T_EOF)
			{
				throw ParseException::unexpected($this->peek());
			}
			$ast->setPhpVersion($this->phpVersion);
			return $ast;
		}
		finally
		{
			$this->deinit();
		}
	}

	/**
	 * @param Token[] $tokens
	 */
	private function init(array $tokens): void
	{
		$this->tokens = $tokens;
		$this->i = 0;

		// push some extra eof tokens to eliminate the need for out of bounds checks when peeking
		$eof = \end($tokens);
		\assert($eof !== false);
		$this->tokens[] = $eof;
		$this->tokens[] = $eof;

		// prefetch an array of the types of $this->tokens
		// this is considerably faster than calling ->getType() everywhere
		$this->types = [];
		foreach ($this->tokens as $token)
		{
			$this->types[] = $token->getType();
		}
	}

	private function deinit(): void
	{
		$this->tokens = [];
		$this->types = [];
	}

	private function peek(int $ahead = 0): Token
	{
		return $this->tokens[$this->i + $ahead];
	}

	private function read(int $expectedTokenType = null): Token
	{
		if ($expectedTokenType !== null && $this->peek()->getType() !== $expectedTokenType)
		{
			throw ParseException::unexpected($this->peek());
		}

		return $this->tokens[$this->i++];
	}

	private function opt(int $tokenType): ?Token
	{
		if ($this->peek()->getType() === $tokenType)
		{
			return $this->read();
		}
		else
		{
			return null;
		}
	}

	/**
	 * @throws ParseException
	 */
	private function parseRoot(): Nodes\RootNode
	{
		$statements = [];
		while ($this->peek()->getType() !== T::T_EOF)
		{
			$statements[] = $this->statement();
		}
		$eof = $this->read(T::T_EOF);
		return Nodes\RootNode::__instantiateUnchecked($statements, $eof);
	}

	private function statement(): Nodes\Statement
	{
		switch ($this->peek()->getType())
		{
			case T::T_NAMESPACE:
				$keyword = $this->read(T::T_NAMESPACE);
				$name = null;
				if ($this->peek()->getType() !== T::S_LEFT_CURLY_BRACE)
				{
					$name = $this->name();
				}
				if ($this->peek()->getType() === T::S_LEFT_CURLY_BRACE)
				{
					$block = $this->regularBlock();
					$semiColon = null;
				}
				else
				{
					$block = null;
					$semiColon = $this->statementDelimiter();
				}
				return Nodes\Statements\NamespaceStatement::__instantiateUnchecked($keyword, $name, $block, $semiColon);

			case T::T_USE:
				return $this->use_();

			case T::T_ABSTRACT:
			case T::T_CLASS:
			case T::T_FINAL:
				$modifiers = [];
				while (\in_array($this->peek()->getType(), [T::T_ABSTRACT, T::T_FINAL], true))
				{
					$modifiers[] = $this->read();
				}
				$keyword = $this->read(T::T_CLASS);
				$name = $this->read(T::T_STRING);
				if ($extendsKeyword = $this->opt(T::T_EXTENDS))
				{
					$extendsNames = [];
					$extendsNames[] = $this->name();
					$extends = Nodes\Oop\Extends_::__instantiateUnchecked($extendsKeyword, $extendsNames);
				}
				else
				{
					$extends = null;
				}
				if ($implementsKeyword = $this->opt(T::T_IMPLEMENTS))
				{
					$implementsNames = [];
					do
					{
						$implementsNames[] = $this->name();
					}
					while ($implementsNames[] = $this->opt(T::S_COMMA));
					$implements = Nodes\Oop\Implements_::__instantiateUnchecked($implementsKeyword, $implementsNames);
				}
				else
				{
					$implements = null;
				}
				$leftBrace = $this->read(T::S_LEFT_CURLY_BRACE);
				$members = [];
				while ($this->peek()->getType() !== T::S_RIGHT_CURLY_BRACE)
				{
					$members[] = $this->oopMember();
				}
				$rightBrace = $this->read(T::S_RIGHT_CURLY_BRACE);
				return Nodes\Oop\ClassDeclaration::__instantiateUnchecked(
					$modifiers,
					$keyword,
					$name,
					$extends,
					$implements,
					$leftBrace,
					$members,
					$rightBrace
				);

			case T::T_INTERFACE:
				$keyword = $this->read(T::T_INTERFACE);
				$name = $this->read(T::T_STRING);
				$extends = null;
				if ($extendsKeyword = $this->opt(T::T_EXTENDS))
				{
					$extendsNames = [];
					do
					{
						$extendsNames[] = $this->name();
					}
					while ($extendsNames[] = $this->opt(T::S_COMMA));
					$extends = Nodes\Oop\Extends_::__instantiateUnchecked($extendsKeyword, $extendsNames);
				}
				$leftBrace = $this->read(T::S_LEFT_CURLY_BRACE);
				$members = [];
				while ($this->peek()->getType() !== T::S_RIGHT_CURLY_BRACE)
				{
					$members[] = $this->oopMember();
				}
				$rightBrace = $this->read(T::S_RIGHT_CURLY_BRACE);
				return Nodes\Oop\InterfaceDeclaration::__instantiateUnchecked(
					$keyword,
					$name,
					$extends,
					$leftBrace,
					$members,
					$rightBrace
				);

			case T::T_TRAIT:
				$keyword = $this->read(T::T_TRAIT);
				$name = $this->read(T::T_STRING);
				$leftBrace = $this->read(T::S_LEFT_CURLY_BRACE);
				$members = [];
				while ($this->peek()->getType() !== T::S_RIGHT_CURLY_BRACE)
				{
					$members[] = $this->oopMember();
				}
				$rightBrace = $this->read(T::S_RIGHT_CURLY_BRACE);
				return Nodes\Oop\TraitDeclaration::__instantiateUnchecked(
					$keyword,
					$name,
					$leftBrace,
					$members,
					$rightBrace
				);

			case T::T_BREAK:
				$keyword = $this->read();
				$levels = null;
				if ($this->peek()->getType() === T::T_LNUMBER)
				{
					$levels = Nodes\Expressions\IntegerLiteral::__instantiateUnchecked($this->read());
				}
				$semiColon = $this->statementDelimiter();
				return Nodes\Statements\BreakStatement::__instantiateUnchecked($keyword, $levels, $semiColon);

			case T::T_CONST:
				$keyword = $this->read();
				$name = $this->read(T::T_STRING);
				$equals = $this->read(T::S_EQUALS);
				$value = $this->expression();
				$delimiter = $this->statementDelimiter();
				return Nodes\Statements\ConstStatement::__instantiateUnchecked($keyword, $name, $equals, $value, $delimiter);

			case T::T_CONTINUE:
				$keyword = $this->read();
				$levels = null;
				if ($this->peek()->getType() === T::T_LNUMBER)
				{
					$levels = Nodes\Expressions\IntegerLiteral::__instantiateUnchecked($this->read());
				}
				$semiColon = $this->statementDelimiter();
				return Nodes\Statements\ContinueStatement::__instantiateUnchecked($keyword, $levels, $semiColon);

			case T::T_DECLARE:
				$keyword = $this->read();
				$leftParenthesis = $this->read(T::S_LEFT_PARENTHESIS);
				$directives = [];
				do
				{
					$directiveKey = $this->read(T::T_STRING);
					$directiveEquals = $this->read(T::S_EQUALS);
					$directiveValue = $this->expression();
					$directives[] = Nodes\Statements\DeclareDirective::__instantiateUnchecked($directiveKey, $directiveEquals, $directiveValue);
				}
				while ($directives[] = $this->opt(T::S_COMMA));
				$rightParenthesis = $this->read(T::S_RIGHT_PARENTHESIS);
				if ($this->peek()->getType() === T::S_LEFT_CURLY_BRACE)
				{
					$block = $this->block();
					$semiColon = null;
				}
				else
				{
					$block = null;
					$semiColon = $this->statementDelimiter();
				}
				return Nodes\Statements\DeclareStatement::__instantiateUnchecked(
					$keyword,
					$leftParenthesis,
					$directives,
					$rightParenthesis,
					$block,
					$semiColon
				);

			case T::T_DO:
				$keyword1 = $this->read();
				$block = $this->block();
				$keyword2 = $this->read(T::T_WHILE);
				$leftParenthesis = $this->read(T::S_LEFT_PARENTHESIS);
				$test = $this->expression();
				$rightParenthesis = $this->read(T::S_RIGHT_PARENTHESIS);
				$semiColon = $this->statementDelimiter();
				return Nodes\Statements\DoWhileStatement::__instantiateUnchecked(
					$keyword1,
					$block,
					$keyword2,
					$leftParenthesis,
					$test,
					$rightParenthesis,
					$semiColon
				);

			case T::T_ECHO:
			case T::T_OPEN_TAG_WITH_ECHO:
				$keyword = $this->read();
				$expressions = [];
				do
				{
					$expressions[] = $this->expression();
				}
				while ($expressions[] = $this->opt(T::S_COMMA));
				$semiColon = $this->statementDelimiter();
				return Nodes\Statements\EchoStatement::__instantiateUnchecked($keyword, $expressions, $semiColon);

			case T::T_FOR:
				$keyword = $this->read();
				$leftParenthesis = $this->read(T::S_LEFT_PARENTHESIS);

				$init = [];
				if ($this->peek()->getType() !== T::S_SEMICOLON)
				{
					do
					{
						$init[] = $this->expression();
					}
					while ($init[] = $this->opt(T::S_COMMA));
				}

				$separator1 = $this->opt(T::S_COMMA) ?? $this->read(T::S_SEMICOLON);

				$test = [];
				if ($this->peek()->getType() !== T::S_SEMICOLON)
				{
					do
					{
						$test[] = $this->expression();
					}
					while ($test[] = $this->opt(T::S_COMMA));
				}

				$separator2 = $this->opt(T::S_COMMA) ?? $this->read(T::S_SEMICOLON);

				$step = [];
				if ($this->peek()->getType() !== T::S_RIGHT_PARENTHESIS)
				{
					do
					{
						$step[] = $this->expression();
					}
					while ($step[] = $this->opt(T::S_COMMA));
				}

				$rightParenthesis = $this->read(T::S_RIGHT_PARENTHESIS);

				$block = $this->peek()->getType() === T::S_COLON ? $this->altBlock(T::T_ENDFOR) : $this->block();

				return Nodes\Statements\ForStatement::__instantiateUnchecked(
					$keyword,
					$leftParenthesis,
					$init,
					$separator1,
					$test,
					$separator2,
					$step,
					$rightParenthesis,
					$block
				);

			case T::T_FOREACH:
				$keyword = $this->read();
				$leftParenthesis = $this->read(T::S_LEFT_PARENTHESIS);
				$iterable = $this->expression();
				$as = $this->read(T::T_AS);
				$key = null;
				$byReference = $this->opt(T::S_AMPERSAND);
				$value = $this->simpleExpression();
				if ($this->peek()->getType() === T::T_DOUBLE_ARROW)
				{
					if ($byReference)
					{
						throw ParseException::unexpected($this->peek());
					}
					$key = Nodes\Helpers\Key::__instantiateUnchecked($value, $this->read());
					$byReference = $this->opt(T::S_AMPERSAND);
					$value = $this->simpleExpression();
				}
				$rightParenthesis = $this->read(T::S_RIGHT_PARENTHESIS);
				$block = $this->peek()->getType() === T::S_COLON ? $this->altBlock(T::T_ENDFOREACH) : $this->block();
				return Nodes\Statements\ForeachStatement::__instantiateUnchecked(
					$keyword,
					$leftParenthesis,
					$iterable,
					$as,
					$key,
					$byReference,
					$value,
					$rightParenthesis,
					$block
				);

			/** @noinspection PhpMissingBreakStatementInspection */
			case T::T_FUNCTION:
				if (!(
					$this->peek(1)->getType() === T::T_STRING
					|| $this->peek(1)->getType() === T::S_AMPERSAND && $this->peek(2)->getType() === T::T_STRING
				))
				{
					// no name, this is an anonymous function
					goto expressionStatement;
				}

				$keyword = $this->read();
				$byReference = $this->opt(T::S_AMPERSAND);
				$name = $this->read();
				$leftParenthesis = $this->read(T::S_LEFT_PARENTHESIS);
				$parameters = $this->parameters();
				$rightParenthesis = $this->read(T::S_RIGHT_PARENTHESIS);
				$returnType = $this->returnType();
				$body = $this->regularBlock();
				return Nodes\Statements\FunctionStatement::__instantiateUnchecked(
					$keyword,
					$byReference,
					$name,
					$leftParenthesis,
					$parameters,
					$rightParenthesis,
					$returnType,
					$body
				);

			case T::T_GOTO:
				$keyword = $this->read();
				$label = $this->read(T::T_STRING);
				$semiColon = $this->statementDelimiter();
				return Nodes\Statements\GotoStatement::__instantiateUnchecked(
					$keyword,
					$label,
					$semiColon
				);

			case T::T_IF:
				$keyword = $this->read();
				$leftParenthesis = $this->read(T::S_LEFT_PARENTHESIS);
				$test = $this->expression();
				$rightParenthesis = $this->read(T::S_RIGHT_PARENTHESIS);
				if ($altSyntax = ($this->peek()->getType() === T::S_COLON))
				{
					$block = $this->altBlock(T::T_ENDIF, [T::T_ELSE, T::T_ELSEIF]);
				}
				else
				{
					$block = $this->block();
				}
				$elseifs = [];
				while ($elseifKeyword = $this->opt(T::T_ELSEIF))
				{
					$elseifLeftParenthesis = $this->read(T::S_LEFT_PARENTHESIS);
					$elseifTest = $this->expression();
					$elseifRightParenthesis = $this->read(T::S_RIGHT_PARENTHESIS);
					$elseifBlock = $altSyntax ? $this->altBlock(T::T_ENDIF, [T::T_ELSE, T::T_ELSEIF]) : $this->block();
					$elseifs[] = Nodes\Statements\Elseif_::__instantiateUnchecked(
						$elseifKeyword,
						$elseifLeftParenthesis,
						$elseifTest,
						$elseifRightParenthesis,
						$elseifBlock
					);
				}
				$else = null;
				if ($elseKeyword = $this->opt(T::T_ELSE))
				{
					$elseBlock = $altSyntax ? $this->altBlock(T::T_ENDIF) : $this->block();
					$else = Nodes\Statements\Else_::__instantiateUnchecked($elseKeyword, $elseBlock);
				}
				return Nodes\Statements\IfStatement::__instantiateUnchecked(
					$keyword,
					$leftParenthesis,
					$test,
					$rightParenthesis,
					$block,
					$elseifs,
					$else
				);

			case T::T_RETURN:
				$keyword = $this->read();
				$expression = !$this->endOfStatement() ? $this->expression() : null;
				$semiColon = $this->statementDelimiter();
				return Nodes\Statements\ReturnStatement::__instantiateUnchecked($keyword, $expression, $semiColon);

			/** @noinspection PhpMissingBreakStatementInspection */
			case T::T_STATIC:
				if ($this->peek(1)->getType() !== T::T_VARIABLE)
				{
					// static function etc. -- anonymous function
					goto expressionStatement;
				}
				$keyword = $this->read();
				$variables = [];
				do
				{
					$variable = $this->read(T::T_VARIABLE);
					$default = $this->default_();
					$variables[] = Nodes\Statements\StaticVariable::__instantiateUnchecked($variable, $default);
				}
				while ($variables[] = $this->opt(T::S_COMMA));
				$semiColon = $this->statementDelimiter();
				return Nodes\Statements\StaticVariableStatement::__instantiateUnchecked($keyword, $variables, $semiColon);

			case T::T_SWITCH:
				$keyword = $this->read();
				$leftParenthesis = $this->read(T::S_LEFT_PARENTHESIS);
				$value = $this->expression();
				$rightParenthesis = $this->read(T::S_RIGHT_PARENTHESIS);
				$leftBrace = $this->read(T::S_LEFT_CURLY_BRACE);
				$cases = [];
				while ($this->peek()->getType() !== T::S_RIGHT_CURLY_BRACE)
				{
					if ($caseKeyword = $this->opt(T::T_DEFAULT))
					{
						$caseValue = null;
					}
					else
					{
						$caseKeyword = $this->read(T::T_CASE);
						$caseValue = $this->expression();
					}
					$caseDelimiter = $this->opt(T::S_SEMICOLON) ?? $this->read(T::S_COLON);
					$caseStatements = [];
					while ($this->peek()->getType() !== T::T_CASE && $this->peek()->getType() !== T::T_DEFAULT && $this->peek()->getType() !== T::S_RIGHT_CURLY_BRACE)
					{
						$caseStatements[] = $this->statement();
					}
					$cases[] = Nodes\Statements\SwitchCase::__instantiateUnchecked($caseKeyword, $caseValue, $caseDelimiter, $caseStatements);
				}
				$rightBrace = $this->read(T::S_RIGHT_CURLY_BRACE);
				return Nodes\Statements\SwitchStatement::__instantiateUnchecked(
					$keyword,
					$leftParenthesis,
					$value,
					$rightParenthesis,
					$leftBrace,
					$cases,
					$rightBrace
				);

			case T::T_THROW:
				$keyword = $this->read();
				$expression = $this->expression();
				$semiColon = $this->statementDelimiter();
				return Nodes\Statements\ThrowStatement::__instantiateUnchecked($keyword, $expression, $semiColon);

			case T::T_TRY:
				$keyword = $this->read();
				$block = $this->regularBlock();
				$catches = [];
				while ($catchKeyword = $this->opt(T::T_CATCH))
				{
					$catchLeftParenthesis = $this->read(T::S_LEFT_PARENTHESIS);
					$catchTypes = [];
					do
					{
						$catchTypes[] = Nodes\Types\NamedType::__instantiateUnchecked($this->name());
					}
					while ($catchTypes[] = $this->opt(T::S_VERTICAL_BAR));
					$catchVariable = $this->read(T::T_VARIABLE);
					$catchRightParenthesis = $this->read(T::S_RIGHT_PARENTHESIS);
					$catchBlock = $this->regularBlock();
					$catches[] = Nodes\Statements\Catch_::__instantiateUnchecked(
						$catchKeyword,
						$catchLeftParenthesis,
						$catchTypes,
						$catchVariable,
						$catchRightParenthesis,
						$catchBlock
					);
				}
				$finally = null;
				if ($finallyKeyword = $this->opt(T::T_FINALLY))
				{
					$finallyBlock = $this->regularBlock();
					$finally = Nodes\Statements\Finally_::__instantiateUnchecked($finallyKeyword, $finallyBlock);
				}
				return Nodes\Statements\TryStatement::__instantiateUnchecked($keyword, $block, $catches, $finally);

			case T::T_WHILE:
				$keyword = $this->read();
				$leftParenthesis = $this->read(T::S_LEFT_PARENTHESIS);
				$test = $this->expression();
				$rightParenthesis = $this->read(T::S_RIGHT_PARENTHESIS);
				$block = $this->peek()->getType() === T::S_COLON ? $this->altBlock(T::T_ENDWHILE) : $this->block();
				return Nodes\Statements\WhileStatement::__instantiateUnchecked($keyword, $leftParenthesis, $test, $rightParenthesis, $block);

			case T::T_UNSET:
				$keyword = $this->read();
				$leftParenthesis = $this->read(T::S_LEFT_PARENTHESIS);
				$expressions = [];
				do
				{
					$expressions[] = $this->simpleExpression();
				}
				while ($expressions[] = $this->opt(T::S_COMMA));
				$rightParenthesis = $this->read(T::S_RIGHT_PARENTHESIS);
				$semiColon = $this->statementDelimiter();
				return Nodes\Statements\UnsetStatement::__instantiateUnchecked($keyword, $leftParenthesis, $expressions, $rightParenthesis, $semiColon);

			case T::S_LEFT_CURLY_BRACE:
				return Nodes\Statements\BlockStatement::__instantiateUnchecked($this->regularBlock());

			/** @noinspection PhpMissingBreakStatementInspection */
			case T::T_STRING:
				if ($this->peek(1)->getType() !== T::S_COLON)
				{
					goto expressionStatement;
				}
				return Nodes\Statements\LabelStatement::__instantiateUnchecked($this->read(), $this->read());

			case T::T_INLINE_HTML:
			case T::T_OPEN_TAG:
				$content = $this->opt(T::T_INLINE_HTML);
				$open = $this->opt(T::T_OPEN_TAG);
				return Nodes\Statements\InlineHtmlStatement::__instantiateUnchecked($content, $open);

			case T::S_SEMICOLON:
			case T::T_CLOSE_TAG:
				return Nodes\Statements\NopStatement::__instantiateUnchecked($this->read());

			default:
				expressionStatement:
				$expression = $this->expression();
				$semiColon = $this->statementDelimiter();
				return Nodes\Statements\ExpressionStatement::__instantiateUnchecked($expression, $semiColon);
		}
	}

	private function use_(): Nodes\Statements\UseStatement
	{
		$keyword = $this->read(T::T_USE);
		$type = null;
		if ($this->peek()->getType() === T::T_FUNCTION || $this->peek()->getType() === T::T_CONST)
		{
			$type = $this->read();
		}

		$firstName = null;
		if ($this->peek()->getType() === T::T_NS_SEPARATOR || $this->peek()->getType() === T::T_STRING)
		{
			$firstName = $this->name();
		}

		if ($this->peek()->getType() === T::T_NS_SEPARATOR)
		{
			assert($firstName !== null);
			$prefix = $firstName;
			unset($firstName);
			$prefix->getParts()->add($this->read());
			$leftBrace = $this->read(T::S_LEFT_CURLY_BRACE);
			$rightBrace = null;
		}
		else
		{
			$prefix = null;
			$leftBrace = $rightBrace = null;
		}

		$declarations = [];
		while (true)
		{
			$name = $firstName ?? $this->name();
			unset($firstName);

			$alias = null;
			if ($aliasKeyword = $this->opt(T::T_AS))
			{
				$alias = Nodes\Statements\UseAlias::__instantiateUnchecked($aliasKeyword, $this->read(T::T_STRING));
			}

			$declarations[] = Nodes\Statements\UseDeclaration::__instantiateUnchecked($name, $alias);

			if (!($declarations[] = $this->opt(T::S_COMMA)))
			{
				break;
			}

			if ($leftBrace && $this->peek()->getType() === T::S_RIGHT_CURLY_BRACE)
			{
				$rightBrace = $this->read();
				break;
			}
		}

		$semiColon = $this->statementDelimiter();

		return Nodes\Statements\UseStatement::__instantiateUnchecked(
			$keyword,
			$type,
			$prefix,
			$leftBrace,
			$declarations,
			$rightBrace,
			$semiColon
		);
	}

	private function oopMember(): Nodes\Oop\OopMember
	{
		$modifiers = [];
		while (\in_array($this->peek()->getType(), [T::T_ABSTRACT, T::T_FINAL, T::T_PUBLIC, T::T_PROTECTED, T::T_PRIVATE, T::T_STATIC], true))
		{
			$modifiers[] = $this->read();
		}

		if ($keyword = $this->opt(T::T_FUNCTION))
		{
			$byReference = $this->opt(T::S_AMPERSAND);
			// TODO dedup
			if ($this->peek()->getType() === T::T_STRING)
			{
				$name = $this->read();
			}
			else if (\in_array($this->peek()->getType(), T::STRINGY_KEYWORDS, true))
			{
				$this->peek()->_fudgeType(T::T_STRING);
				$name = $this->read();
			}
			else
			{
				throw ParseException::unexpected($this->peek());
			}
			$leftParenthesis = $this->read(T::S_LEFT_PARENTHESIS);
			$parameters = $this->parameters();
			$rightParenthesis = $this->read(T::S_RIGHT_PARENTHESIS);
			$returnType = $this->returnType();
			$semiColon = $this->opt(T::S_SEMICOLON);
			$body = !$semiColon ? $this->regularBlock() : null;
			return Nodes\Oop\Method::__instantiateUnchecked(
				$modifiers,
				$keyword,
				$byReference,
				$name,
				$leftParenthesis,
				$parameters,
				$rightParenthesis,
				$returnType,
				$body,
				$semiColon
			);
		}
		else if ($keyword = $this->opt(T::T_CONST))
		{
			if ($this->peek()->getType() === T::T_STRING)
			{
				$name = $this->read();
			}
			else if (\in_array($this->peek()->getType(), T::STRINGY_KEYWORDS, true))
			{
				$this->peek()->_fudgeType(T::T_STRING);
				$name = $this->read();
			}
			else
			{
				throw ParseException::unexpected($this->peek());
			}
			$equals = $this->read(T::S_EQUALS);
			$value = $this->expression();
			$semiColon = $this->read(T::S_SEMICOLON);
			return Nodes\Oop\ClassConstant::__instantiateUnchecked($modifiers, $keyword, $name, $equals, $value, $semiColon);
		}
		else if ($keyword = $this->opt(T::T_USE))
		{
			$names = [];
			do
			{
				$names[] = $this->name();
			}
			while ($names[] = $this->opt(T::S_COMMA));
			$modifications = [];
			$rightBrace = $semiColon = null;
			if ($leftBrace = $this->opt(T::S_LEFT_CURLY_BRACE))
			{
				if ($this->peek(1)->getType() === T::T_AS || $this->peek()->getType() === T::T_INSTEADOF)
				{
					$ref = Nodes\Oop\TraitMethodRef::__instantiateUnchecked(null, null, $this->read(T::T_STRING));
				}
				else
				{
					$trait = $this->name();
					$doubleColon = $this->read(T::T_DOUBLE_COLON);
					$method = $this->read(T::T_STRING);
					$ref = Nodes\Oop\TraitMethodRef::__instantiateUnchecked($trait, $doubleColon, $method);
				}
				if ($modKeyword = $this->opt(T::T_INSTEADOF))
				{
					$excluded = [];
					do
					{
						$excluded[] = $this->name();
					}
					while ($excluded[] = $this->opt(T::S_COMMA));
					$modSemi = $this->read(T::S_SEMICOLON);
					$modifications[] = Nodes\Oop\TraitUseInsteadof::__instantiateUnchecked($ref, $modKeyword, $excluded, $modSemi);
				}
				else if ($modKeyword = $this->opt(T::T_AS))
				{
					$modifier = null;
					if ($this->peek()->getType() === T::T_PUBLIC || $this->peek()->getType() === T::T_PROTECTED || $this->peek()->getType() === T::T_PRIVATE)
					{
						$modifier = $this->read();
					}
					$alias = $this->read(T::T_STRING);
					$modSemi = $this->read(T::S_SEMICOLON);
					$modifications[] = Nodes\Oop\TraitUseAs::__instantiateUnchecked($ref, $modKeyword, $modifier, $alias, $modSemi);
				}
				else
				{
					throw ParseException::unexpected($this->peek());
				}
				$rightBrace = $this->read(T::S_RIGHT_CURLY_BRACE);
			}
			else
			{
				$semiColon = $this->read(T::S_SEMICOLON);
			}
			return Nodes\Oop\TraitUse::__instantiateUnchecked(
				$keyword,
				$names,
				$leftBrace,
				$modifications,
				$rightBrace,
				$semiColon
			);
		}
		else if ($variable = $this->opt(T::T_VARIABLE))
		{
			$default = $this->default_();
			$semiColon = $this->read(T::S_SEMICOLON);
			return Nodes\Oop\Property::__instantiateUnchecked($modifiers, $variable, $default, $semiColon);
		}
		else
		{
			throw ParseException::unexpected($this->peek());
		}
	}

	private function endOfStatement(): bool
	{
		$t = $this->peek()->getType();
		return $t === T::S_SEMICOLON || $t === T::T_CLOSE_TAG;
	}

	private function statementDelimiter(): Token
	{
		if ($this->peek()->getType() === T::T_CLOSE_TAG)
		{
			return $this->read();
		}
		else
		{
			return $this->read(T::S_SEMICOLON);
		}
	}

	/**
	 * @throws ParseException
	 */
	private function expression(int $minPrecedence = 0): Nodes\Expression
	{
		$left = $this->simpleExpression();

		while (true)
		{
			if ($minPrecedence <= Expr::PRECEDENCE_POW && $operator = $this->opt(T::T_POW))
			{
				$right = $this->expression(Expr::PRECEDENCE_POW);
				$left = Nodes\Expressions\PowerExpression::__instantiateUnchecked($left, $operator, $right);
			}
			else if ($minPrecedence <= Expr::PRECEDENCE_INSTANCEOF && $operator = $this->opt(T::T_INSTANCEOF))
			{
				$type = $this->simpleExpression(true);
				$left = Nodes\Expressions\InstanceofExpression::__instantiateUnchecked($left, $operator, $type);
			}
			else
			{
				break;
			}
		}

		if ($minPrecedence <= Expr::PRECEDENCE_MUL)
		{
			while (true)
			{
				if ($operator = $this->opt(T::S_ASTERISK))
				{
					$right = $this->expression(Expr::PRECEDENCE_MUL + 1);
					$left = Nodes\Expressions\MultiplyExpression::__instantiateUnchecked($left, $operator, $right);
				}
				else if ($operator = $this->opt(T::S_FORWARD_SLASH))
				{
					$right = $this->expression(Expr::PRECEDENCE_MUL + 1);
					$left = Nodes\Expressions\DivideExpression::__instantiateUnchecked($left, $operator, $right);
				}
				else if ($operator = $this->opt(T::S_MODULO))
				{
					$right = $this->expression(Expr::PRECEDENCE_MUL + 1);
					$left = Nodes\Expressions\ModuloExpression::__instantiateUnchecked($left, $operator, $right);
				}
				else
				{
					break;
				}
			}
		}

		if ($minPrecedence <= Expr::PRECEDENCE_ADD)
		{
			while (true)
			{
				if ($operator = $this->opt(T::S_PLUS))
				{
					$right = $this->expression(Expr::PRECEDENCE_ADD + 1);
					$left = Nodes\Expressions\AddExpression::__instantiateUnchecked($left, $operator, $right);
				}
				else if ($operator = $this->opt(T::S_MINUS))
				{
					$right = $this->expression(Expr::PRECEDENCE_ADD + 1);
					$left = Nodes\Expressions\SubtractExpression::__instantiateUnchecked($left, $operator, $right);
				}
				else if ($operator = $this->opt(T::S_DOT))
				{
					$right = $this->expression(Expr::PRECEDENCE_ADD + 1);
					$left = Nodes\Expressions\ConcatExpression::__instantiateUnchecked($left, $operator, $right);
				}
				else
				{
					break;
				}
			}
		}

		if ($minPrecedence <= Expr::PRECEDENCE_SHIFT)
		{
			while (true)
			{
				if ($operator = $this->opt(T::T_SL))
				{
					$right = $this->expression(Expr::PRECEDENCE_SHIFT + 1);
					$left = Nodes\Expressions\ShiftLeftExpression::__instantiateUnchecked($left, $operator, $right);
				}
				else if ($operator = $this->opt(T::T_SR))
				{
					$right = $this->expression(Expr::PRECEDENCE_SHIFT + 1);
					$left = Nodes\Expressions\ShiftRightExpression::__instantiateUnchecked($left, $operator, $right);
				}
				else
				{
					break;
				}
			}
		}

		if ($minPrecedence <= Expr::PRECEDENCE_COMPARISON2)
		{
			if ($operator = $this->opt(T::S_LT))
			{
				$right = $this->expression(Expr::PRECEDENCE_COMPARISON2 + 1);
				$left = Nodes\Expressions\LessThanExpression::__instantiateUnchecked($left, $operator, $right);
			}
			else if ($operator = $this->opt(T::T_IS_SMALLER_OR_EQUAL))
			{
				$right = $this->expression(Expr::PRECEDENCE_COMPARISON2 + 1);
				$left = Nodes\Expressions\LessThanOrEqualsExpression::__instantiateUnchecked($left, $operator, $right);
			}
			else if ($operator = $this->opt(T::S_GT))
			{
				$right = $this->expression(Expr::PRECEDENCE_COMPARISON2 + 1);
				$left = Nodes\Expressions\GreaterThanExpression::__instantiateUnchecked($left, $operator, $right);
			}
			else if ($operator = $this->opt(T::T_IS_GREATER_OR_EQUAL))
			{
				$right = $this->expression(Expr::PRECEDENCE_COMPARISON2 + 1);
				$left = Nodes\Expressions\GreaterThanOrEqualsExpression::__instantiateUnchecked($left, $operator, $right);
			}
		}

		if ($minPrecedence <= Expr::PRECEDENCE_COMPARISON1)
		{
			if ($operator = $this->opt(T::T_IS_IDENTICAL))
			{
				$right = $this->expression(Expr::PRECEDENCE_COMPARISON1 + 1);
				$left = Nodes\Expressions\IsIdenticalExpression::__instantiateUnchecked($left, $operator, $right);
			}
			else if ($operator = $this->opt(T::T_IS_NOT_IDENTICAL))
			{
				$right = $this->expression(Expr::PRECEDENCE_COMPARISON1 + 1);
				$left = Nodes\Expressions\IsNotIdenticalExpression::__instantiateUnchecked($left, $operator, $right);
			}
			else if ($operator = $this->opt(T::T_IS_EQUAL))
			{
				$right = $this->expression(Expr::PRECEDENCE_COMPARISON1 + 1);
				$left = Nodes\Expressions\IsEqualExpression::__instantiateUnchecked($left, $operator, $right);
			}
			else if ($operator = $this->opt(T::T_IS_NOT_EQUAL))
			{
				$right = $this->expression(Expr::PRECEDENCE_COMPARISON1 + 1);
				$left = Nodes\Expressions\IsNotEqualExpression::__instantiateUnchecked($left, $operator, $right);
			}
			else if ($operator = $this->opt(T::T_SPACESHIP))
			{
				$right = $this->expression(Expr::PRECEDENCE_COMPARISON1 + 1);
				$left = Nodes\Expressions\SpaceshipExpression::__instantiateUnchecked($left, $operator, $right);
			}
		}

		if ($minPrecedence <= Expr::PRECEDENCE_BITWISE_AND)
		{
			while ($operator = $this->opt(T::S_AMPERSAND))
			{
				$right = $this->expression(Expr::PRECEDENCE_BITWISE_AND + 1);
				$left = Nodes\Expressions\BitwiseAndExpression::__instantiateUnchecked($left, $operator, $right);
			}
		}

		if ($minPrecedence <= Expr::PRECEDENCE_BITWISE_XOR)
		{
			while ($operator = $this->opt(T::S_CARET))
			{
				$right = $this->expression(Expr::PRECEDENCE_BITWISE_XOR + 1);
				$left = Nodes\Expressions\BitwiseXorExpression::__instantiateUnchecked($left, $operator, $right);
			}
		}

		if ($minPrecedence <= Expr::PRECEDENCE_BITWISE_OR)
		{
			while ($operator = $this->opt(T::S_VERTICAL_BAR))
			{
				$right = $this->expression(Expr::PRECEDENCE_BITWISE_OR + 1);
				$left = Nodes\Expressions\BitwiseOrExpression::__instantiateUnchecked($left, $operator, $right);
			}
		}

		if ($minPrecedence <= Expr::PRECEDENCE_SYMBOL_AND)
		{
			while ($operator = $this->opt(T::T_BOOLEAN_AND))
			{
				$right = $this->expression(Expr::PRECEDENCE_SYMBOL_AND + 1);
				$left = Nodes\Expressions\SymbolAndExpression::__instantiateUnchecked($left, $operator, $right);
			}
		}

		if ($minPrecedence <= Expr::PRECEDENCE_SYMBOL_OR)
		{
			while ($operator = $this->opt(T::T_BOOLEAN_OR))
			{
				$right = $this->expression(Expr::PRECEDENCE_SYMBOL_OR + 1);
				$left = Nodes\Expressions\SymbolOrExpression::__instantiateUnchecked($left, $operator, $right);
			}
		}

		if ($minPrecedence <= Expr::PRECEDENCE_COALESCE)
		{
			if ($operator = $this->opt(T::T_COALESCE))
			{
				$right = $this->expression(Expr::PRECEDENCE_COALESCE);
				$left = Nodes\Expressions\CoalesceExpression::__instantiateUnchecked($left, $operator, $right);
			}
		}

		if ($minPrecedence <= Expr::PRECEDENCE_TERNARY)
		{
			while ($questionMark = $this->opt(T::S_QUESTION_MARK))
			{
				// note: no precedence is passed here, delimiters on both sides allow for e.g. 1 ? 2 and 3 : 4
				$then = $this->peek()->getType() !== T::S_COLON ? $this->expression() : null;
				$colon = $this->read(T::S_COLON);
				$else = $this->expression(Expr::PRECEDENCE_TERNARY + 1);
				$left = Nodes\Expressions\TernaryExpression::__instantiateUnchecked($left, $questionMark, $then, $colon, $else);
			}
		}

		if ($minPrecedence <= Expr::PRECEDENCE_KEYWORD_AND)
		{
			while ($operator = $this->opt(T::T_LOGICAL_AND))
			{
				$right = $this->expression(Expr::PRECEDENCE_KEYWORD_AND + 1);
				$left = Nodes\Expressions\KeywordAndExpression::__instantiateUnchecked($left, $operator, $right);
			}
		}

		if ($minPrecedence <= Expr::PRECEDENCE_KEYWORD_XOR)
		{
			while ($operator = $this->opt(T::T_LOGICAL_XOR))
			{
				$right = $this->expression(Expr::PRECEDENCE_KEYWORD_XOR + 1);
				$left = Nodes\Expressions\KeywordXorExpression::__instantiateUnchecked($left, $operator, $right);
			}
		}

		if ($minPrecedence <= Expr::PRECEDENCE_KEYWORD_OR)
		{
			while ($operator = $this->opt(T::T_LOGICAL_OR))
			{
				$right = $this->expression(Expr::PRECEDENCE_KEYWORD_OR + 1);
				$left = Nodes\Expressions\KeywordOrExpression::__instantiateUnchecked($left, $operator, $right);
			}
		}

		return $left;
	}

	private function simpleExpression(bool $newable = false): Nodes\Expression
	{
		switch ($this->peek()->getType())
		{
			case T::T_VARIABLE:
				$node = Nodes\Expressions\NormalVariableExpression::__instantiateUnchecked($this->read());
				break;

			case T::S_DOLLAR:
				$node = $this->variableVariable();
				break;

			case T::T_STRING:
			case T::T_NS_SEPARATOR:
				$node = Nodes\Expressions\NameExpression::__instantiateUnchecked($this->name());
				break;

			case T::T_CONSTANT_ENCAPSED_STRING:
				$node = Nodes\Expressions\SingleQuotedStringLiteral::__instantiateUnchecked($this->read());
				break;

			case T::S_DOUBLE_QUOTE:
				$leftDelimiter = $this->read();
				$parts = $this->stringParts(T::S_DOUBLE_QUOTE);
				$rightDelimiter = $this->read();
				$node = Nodes\Expressions\DoubleQuotedStringLiteral::__instantiateUnchecked($leftDelimiter, $parts, $rightDelimiter);
				break;

			case T::T_START_HEREDOC:
				$leftDelimiter = $this->read();
				if (\rtrim($leftDelimiter->getSource())[-1] === "'")
				{
					$content = $this->opt(T::T_ENCAPSED_AND_WHITESPACE);
					$rightDelimiter = $this->read(T::T_END_HEREDOC);
					$node = Nodes\Expressions\NowdocStringLiteral::__instantiateUnchecked($leftDelimiter, $content, $rightDelimiter);
				}
				else
				{
					$parts = $this->stringParts(T::T_END_HEREDOC);
					$rightDelimiter = $this->read();
					$node = Nodes\Expressions\HeredocStringLiteral::__instantiateUnchecked($leftDelimiter, $parts, $rightDelimiter);
				}
				break;

			case T::T_LNUMBER:
				$node = Nodes\Expressions\IntegerLiteral::__instantiateUnchecked($this->read());
				break;

			case T::T_DNUMBER:
				$node = Nodes\Expressions\FloatLiteral::__instantiateUnchecked($this->read());
				break;

			case T::T_NEW:
				$keyword = $this->read();
				$class = $this->simpleExpression(true);
				if ($leftParenthesis = $this->opt(T::S_LEFT_PARENTHESIS))
				{
					$arguments = $this->arguments();
					$rightParenthesis = $this->read(T::S_RIGHT_PARENTHESIS);
				}
				else
				{
					$arguments = [];
					$rightParenthesis = null;
				}
				$node = Nodes\Expressions\NewExpression::__instantiateUnchecked($keyword, $class, $leftParenthesis, $arguments, $rightParenthesis);
				break;

			case T::S_LEFT_SQUARE_BRACKET:
				$leftBracket = $this->read();
				$items = $this->arrayItems(T::S_RIGHT_SQUARE_BRACKET);
				$rightBracket = $this->read(T::S_RIGHT_SQUARE_BRACKET);
				$node = Nodes\Expressions\ShortArrayExpression::__instantiateUnchecked($leftBracket, $items, $rightBracket);
				break;

			case T::T_ARRAY:
				$keyword = $this->read();
				$leftParenthesis = $this->read(T::S_LEFT_PARENTHESIS);
				$items = $this->arrayItems(T::S_RIGHT_PARENTHESIS);
				$rightParenthesis = $this->read(T::S_RIGHT_PARENTHESIS);
				$node = Nodes\Expressions\LongArrayExpression::__instantiateUnchecked($keyword, $leftParenthesis, $items, $rightParenthesis);
				break;

			case T::S_LEFT_PARENTHESIS:
				$leftParenthesis = $this->read();
				$expression = $this->expression();
				$rightParenthesis = $this->read(T::S_RIGHT_PARENTHESIS);
				$node = Nodes\Expressions\ParenthesizedExpression::__instantiateUnchecked($leftParenthesis, $expression, $rightParenthesis);
				break;

			case T::T_ISSET:
				$keyword = $this->read();
				$leftParenthesis = $this->read(T::S_LEFT_PARENTHESIS);
				$items = [];
				do
				{
					$items[] = $this->expression();
				}
				while (
					($items[] = $this->opt(T::S_COMMA))
					&& $this->peek()->getType() !== T::S_RIGHT_PARENTHESIS
				);
				$rightParenthesis = $this->read(T::S_RIGHT_PARENTHESIS);
				$node = Nodes\Expressions\IssetExpression::__instantiateUnchecked($keyword, $leftParenthesis, $items, $rightParenthesis);
				break;

			case T::T_EMPTY:
				$keyword = $this->read();
				$leftParenthesis = $this->read(T::S_LEFT_PARENTHESIS);
				$expression = $this->expression();
				$rightParenthesis = $this->read(T::S_RIGHT_PARENTHESIS);
				$node = Nodes\Expressions\EmptyExpression::__instantiateUnchecked($keyword, $leftParenthesis, $expression, $rightParenthesis);
				break;

			case T::T_DEC:
				$operator = $this->read();
				$expression = $this->simpleExpression();
				$node = Nodes\Expressions\PreDecrementExpression::__instantiateUnchecked($operator, $expression);
				break;

			case T::T_INC:
				$operator = $this->read();
				$expression = $this->simpleExpression();
				$node = Nodes\Expressions\PreIncrementExpression::__instantiateUnchecked($operator, $expression);
				break;

			case T::T_DIR:
			case T::T_FILE:
			case T::T_LINE:
			case T::T_FUNC_C:
			case T::T_CLASS_C:
			case T::T_METHOD_C:
			case T::T_TRAIT_C:
			case T::T_NS_C:
				$node = Nodes\Expressions\MagicConstant::__instantiateUnchecked($this->read());
				break;

			case T::T_CLONE:
				$keyword = $this->read();
				$expression = $this->simpleExpression();
				$node = Nodes\Expressions\CloneExpression::__instantiateUnchecked($keyword, $expression);
				break;

			case T::S_EXCLAMATION_MARK:
				$operator = $this->read();
				$expression = $this->expression(Expr::PRECEDENCE_BOOLEAN_NOT);
				$node = Nodes\Expressions\NotExpression::__instantiateUnchecked($operator, $expression);
				break;

			case T::T_YIELD:
				$keyword = $this->read();
				$key = null;
				$next = $this->peek();
				$expression = null;
				try
				{
					$expression = $this->expression(Expr::PRECEDENCE_TERNARY);
				}
				catch (ParseException $e)
				{
					if ($this->peek() !== $next)
					{
						throw $e;
					}
				}
				if ($expression && $this->peek()->getType() === T::T_DOUBLE_ARROW)
				{
					$key = Nodes\Helpers\Key::__instantiateUnchecked($expression, $this->read());
					$expression = $this->expression(Expr::PRECEDENCE_TERNARY);
				}
				$node = Nodes\Expressions\YieldExpression::__instantiateUnchecked($keyword, $key, $expression);
				break;

			case T::T_YIELD_FROM:
				$keyword = $this->read();
				$expression = $this->expression(Expr::PRECEDENCE_TERNARY);
				$node = Nodes\Expressions\YieldFromExpression::__instantiateUnchecked($keyword, $expression);
				break;

			case T::T_INCLUDE:
			case T::T_INCLUDE_ONCE:
			case T::T_REQUIRE:
			case T::T_REQUIRE_ONCE:
				$keyword = $this->read();
				$expression = $this->expression();
				$node = Nodes\Expressions\IncludeLikeExpression::__instantiateUnchecked($keyword, $expression);
				break;

			case T::S_TILDE:
				$symbol = $this->read();
				$expression = $this->expression(Expr::PRECEDENCE_POW);
				$node = Nodes\Expressions\BitwiseNotExpression::__instantiateUnchecked($symbol, $expression);
				break;

			case T::S_MINUS:
				$symbol = $this->read();
				$expression = $this->expression(Expr::PRECEDENCE_POW);
				$node = Nodes\Expressions\UnaryMinusExpression::__instantiateUnchecked($symbol, $expression);
				break;

			case T::S_PLUS:
				$symbol = $this->read();
				$expression = $this->expression(Expr::PRECEDENCE_POW);
				$node = Nodes\Expressions\UnaryPlusExpression::__instantiateUnchecked($symbol, $expression);
				break;

			case T::T_ARRAY_CAST:
			case T::T_BOOL_CAST:
			case T::T_DOUBLE_CAST:
			case T::T_INT_CAST:
			case T::T_OBJECT_CAST:
			case T::T_STRING_CAST:
			case T::T_UNSET_CAST:
				$cast = $this->read();
				$expression = $this->expression(Expr::PRECEDENCE_POW);
				$node = Nodes\Expressions\CastExpression::__instantiateUnchecked($cast, $expression);
				break;

			case T::S_AT:
				$operator = $this->read();
				$expression = $this->expression(Expr::PRECEDENCE_POW);
				$node = Nodes\Expressions\SuppressErrorsExpression::__instantiateUnchecked($operator, $expression);
				break;

			case T::T_LIST:
				$keyword = $this->read();
				$leftParenthesis = $this->read(T::S_LEFT_PARENTHESIS);
				$items = $this->arrayItems(T::S_RIGHT_PARENTHESIS);
				$rightParenthesis = $this->read();
				$node = Nodes\Expressions\ListExpression::__instantiateUnchecked($keyword, $leftParenthesis, $items, $rightParenthesis);
				break;

			case T::T_EXIT:
				$keyword = $this->read();
				if ($this->peek()->getType() === T::S_LEFT_PARENTHESIS)
				{
					$leftParenthesis = $this->read(T::S_LEFT_PARENTHESIS);
					$expression = $this->peek()->getType() !== T::S_RIGHT_PARENTHESIS ? $this->expression() : null;
					$rightParenthesis = $this->read(T::S_RIGHT_PARENTHESIS);
				}
				else
				{
					$leftParenthesis = $expression = $rightParenthesis = null;
				}
				$node = Nodes\Expressions\ExitExpression::__instantiateUnchecked($keyword, $leftParenthesis, $expression, $rightParenthesis);
				break;

			case T::T_PRINT:
				$keyword = $this->read();
				$expression = $this->expression(Expr::PRECEDENCE_TERNARY);
				$node = Nodes\Expressions\PrintExpression::__instantiateUnchecked($keyword, $expression);
				break;

			case T::T_EVAL:
				$keyword = $this->read();
				$leftParenthesis = $this->read(T::S_LEFT_PARENTHESIS);
				$expression = $this->expression();
				$rightParenthesis = $this->read(T::S_RIGHT_PARENTHESIS);
				$node = Nodes\Expressions\EvalExpression::__instantiateUnchecked($keyword, $leftParenthesis, $expression, $rightParenthesis);
				break;

			case T::S_BACKTICK:
				$leftDelimiter = $this->read();
				$command = $this->read(T::T_ENCAPSED_AND_WHITESPACE);
				$rightDelimiter = $this->read(T::S_BACKTICK);
				$node = Nodes\Expressions\ExececutionExpression::__instantiateUnchecked($leftDelimiter, $command, $rightDelimiter);
				break;

			/** @noinspection PhpMissingBreakStatementInspection */
			case T::T_STATIC:
				if ($this->peek(1)->getType() === T::T_FUNCTION)
				{
					goto anonymousFunction;
				}
				$node = Nodes\Expressions\StaticExpression::__instantiateUnchecked($this->read());
				break;

			case T::T_FUNCTION:
				anonymousFunction:
				$static = $this->opt(T::T_STATIC);
				$keyword = $this->read();
				$byReference = $this->opt(T::S_AMPERSAND);
				$leftParenthesis = $this->read(T::S_LEFT_PARENTHESIS);
				$parameters = $this->parameters();
				$rightParenthesis = $this->read(T::S_RIGHT_PARENTHESIS);
				$use = null;
				if ($useKeyword = $this->opt(T::T_USE))
				{
					$useLeftParenthesis = $this->read(T::S_LEFT_PARENTHESIS);
					$useBindings = [];
					while (true)
					{
						$useBindingByReference = $this->opt(T::S_AMPERSAND);
						$useBindingVariable = $this->read(T::T_VARIABLE);
						$useBindings[] = Nodes\Expressions\AnonymousFunctionUseBinding::__instantiateUnchecked($useBindingByReference, $useBindingVariable);
						if (!($useBindings[] = $this->opt(T::S_COMMA)))
						{
							break;
						}
					}
					$useRightParenthesis = $this->read(T::S_RIGHT_PARENTHESIS);
					$use = Nodes\Expressions\AnonymousFunctionUse::__instantiateUnchecked($useKeyword, $useLeftParenthesis, $useBindings, $useRightParenthesis);
				}
				$returnType = $this->returnType();

				$body = $this->regularBlock();

				$node = Nodes\Expressions\AnonymousFunctionExpression::__instantiateUnchecked(
					$static,
					$keyword,
					$byReference,
					$leftParenthesis,
					$parameters,
					$rightParenthesis,
					$use,
					$returnType,
					$body
				);
				break;

			default:
				throw ParseException::unexpected($this->peek());
		}

		while (true)
		{
			switch ($this->peek()->getType())
			{
				case T::S_EQUALS:
					if ($this->peek(1)->getType() === T::S_AMPERSAND)
					{
						$operator1 = $this->read();
						$operator2 = $this->read();
						$right = $this->simpleExpression();
						$node = Nodes\Expressions\AliasExpression::__instantiateUnchecked($node, $operator1, $operator2, $right);
					}
					else
					{
						$operator = $this->read();
						$value = $this->expression(Expr::PRECEDENCE_TERNARY);
						$node = Nodes\Expressions\AssignExpression::__instantiateUnchecked($node, $operator, $value);
					}
					break;
				case T::T_PLUS_EQUAL:
					$operator = $this->read();
					$value = $this->expression(Expr::PRECEDENCE_TERNARY);
					$node = Nodes\Expressions\CombinedAssignment\AddAssignExpression::__instantiateUnchecked($node, $operator, $value);
					break;
				case T::T_MINUS_EQUAL:
					$operator = $this->read();
					$value = $this->expression(Expr::PRECEDENCE_TERNARY);
					$node = Nodes\Expressions\CombinedAssignment\SubtractAssignExpression::__instantiateUnchecked($node, $operator, $value);
					break;
				case T::T_CONCAT_EQUAL:
					$operator = $this->read();
					$value = $this->expression(Expr::PRECEDENCE_TERNARY);
					$node = Nodes\Expressions\CombinedAssignment\ConcatAssignExpression::__instantiateUnchecked($node, $operator, $value);
					break;
				case T::T_MUL_EQUAL:
					$operator = $this->read();
					$value = $this->expression(Expr::PRECEDENCE_TERNARY);
					$node = Nodes\Expressions\CombinedAssignment\MultiplyAssignExpression::__instantiateUnchecked($node, $operator, $value);
					break;
				case T::T_DIV_EQUAL:
					$operator = $this->read();
					$value = $this->expression(Expr::PRECEDENCE_TERNARY);
					$node = Nodes\Expressions\CombinedAssignment\DivideAssignExpression::__instantiateUnchecked($node, $operator, $value);
					break;
				case T::T_MOD_EQUAL:
					$operator = $this->read();
					$value = $this->expression(Expr::PRECEDENCE_TERNARY);
					$node = Nodes\Expressions\CombinedAssignment\ModuloAssignExpression::__instantiateUnchecked($node, $operator, $value);
					break;
				case T::T_POW_EQUAL:
					$operator = $this->read();
					$value = $this->expression(Expr::PRECEDENCE_TERNARY);
					$node = Nodes\Expressions\CombinedAssignment\PowerAssignExpression::__instantiateUnchecked($node, $operator, $value);
					break;
				case T::T_AND_EQUAL:
					$operator = $this->read();
					$value = $this->expression(Expr::PRECEDENCE_TERNARY);
					$node = Nodes\Expressions\CombinedAssignment\BitwiseAndAssignExpression::__instantiateUnchecked($node, $operator, $value);
					break;
				case T::T_OR_EQUAL:
					$operator = $this->read();
					$value = $this->expression(Expr::PRECEDENCE_TERNARY);
					$node = Nodes\Expressions\CombinedAssignment\BitwiseOrAssignExpression::__instantiateUnchecked($node, $operator, $value);
					break;
				case T::T_XOR_EQUAL:
					$operator = $this->read();
					$value = $this->expression(Expr::PRECEDENCE_TERNARY);
					$node = Nodes\Expressions\CombinedAssignment\BitwiseXorAssignExpression::__instantiateUnchecked($node, $operator, $value);
					break;
				case T::T_SL_EQUAL:
					$operator = $this->read();
					$value = $this->expression(Expr::PRECEDENCE_TERNARY);
					$node = Nodes\Expressions\CombinedAssignment\ShiftLeftAssignExpression::__instantiateUnchecked($node, $operator, $value);
					break;
				case T::T_SR_EQUAL:
					$operator = $this->read();
					$value = $this->expression(Expr::PRECEDENCE_TERNARY);
					$node = Nodes\Expressions\CombinedAssignment\ShiftRightAssignExpression::__instantiateUnchecked($node, $operator, $value);
					break;
				case T::T_COALESCE_EQUAL:
					$operator = $this->read();
					$value = $this->expression(Expr::PRECEDENCE_TERNARY);
					$node = Nodes\Expressions\CombinedAssignment\CoalesceAssignExpression::__instantiateUnchecked($node, $operator, $value);
					break;
				case T::T_OBJECT_OPERATOR:
					$operator = $this->read();
					$name = $this->memberName();
					// expr->v -> access the property named 'v'
					// new expr->v -> instantiate the class named by expr->v
					// new expr->v() -> same, () is part of the NewExpression
					if ($this->peek()->getType() !== T::S_LEFT_PARENTHESIS || $newable)
					{
						$node = Nodes\Expressions\PropertyAccessExpression::__instantiateUnchecked($node, $operator, $name);
					}
					// expr->v() -> call the method named v
					else
					{
						$leftParenthesis = $this->read();
						$arguments = $this->arguments();
						$rightParenthesis = $this->read(T::S_RIGHT_PARENTHESIS);
						$node = Nodes\Expressions\MethodCallExpression::__instantiateUnchecked($node, $operator, $name, $leftParenthesis, $arguments, $rightParenthesis);
					}
					break;
				case T::S_LEFT_PARENTHESIS:
					// new expr() -> () always belongs to new
					if ($newable)
					{
						break 2;
					}
					$leftParenthesis = $this->read();
					$arguments = $this->arguments();
					$rightParenthesis = $this->read(T::S_RIGHT_PARENTHESIS);
					$node = Nodes\Expressions\FunctionCallExpression::__instantiateUnchecked($node, $leftParenthesis, $arguments, $rightParenthesis);
					break;
				case T::S_LEFT_SQUARE_BRACKET:
					$leftBracket = $this->read();
					$index = $this->peek()->getType() === T::S_RIGHT_SQUARE_BRACKET ? null : $this->expression();
					$rightBracket = $this->read(T::S_RIGHT_SQUARE_BRACKET);
					$node = Nodes\Expressions\ArrayAccessExpression::__instantiateUnchecked($node, $leftBracket, $index, $rightBracket);
					break;
				case T::T_DOUBLE_COLON:
					$operator = $this->read();
					switch ($this->peek()->getType())
					{
						/** @noinspection PhpMissingBreakStatementInspection */
						case T::T_STRING:
							doubleColonString:
							$name = $this->read();
							// new expr::a -> parse error
							// new expr::a() -> parse error
							if ($newable)
							{
								throw ParseException::unexpected($this->peek());
							}
							// expr::a -> access constant 'a'
							else if ($this->peek()->getType() !== T::S_LEFT_PARENTHESIS)
							{
								$node = Nodes\Expressions\ConstantAccessExpression::__instantiateUnchecked($node, $operator, $name);
								break;
							}
							// expr::a() -> call static method 'a'
							else
							{
								$memberName = Nodes\Helpers\NormalMemberName::__instantiateUnchecked($name);
								goto staticCall;
							}

						/** @noinspection PhpMissingBreakStatementInspection */
						case T::T_VARIABLE:
							$variable = Nodes\Expressions\NormalVariableExpression::__instantiateUnchecked($this->read());
							foundVariable:
							// expr::$v -> access the static property named 'v'
							// new expr::$v -> instantiate the class named by the value of expr::$v
							// new expr::$v() -> same, () is part of the NewExpression
							if ($this->peek()->getType() !== T::S_LEFT_PARENTHESIS || $newable)
							{
								$node = Nodes\Expressions\StaticPropertyAccessExpression::__instantiateUnchecked($node, $operator, $variable);
								break;
							}
							// expr::$v() -> $v refers to method named by the value of the variable $v
							else
							{
								$memberName = Nodes\Helpers\VariableMemberName::__instantiateUnchecked(null, $variable, null);
								goto staticCall;
							}

						/** @noinspection PhpMissingBreakStatementInspection */
						case T::S_DOLLAR:
							$variable = $this->variableVariable();
							// all variations are the same as `expr::$v`, except the variable is variable
							goto foundVariable;

						/** @noinspection PhpMissingBreakStatementInspection */
						case T::S_LEFT_CURLY_BRACE:
							$memberName = $this->memberName();
							// expr::{expr} -> parse error
							// new expr::{expr} -> parse error
							// new expr::{expr}() -> parse error
							if ($this->peek()->getType() !== T::S_LEFT_PARENTHESIS || $newable)
							{
								throw ParseException::unexpected($this->peek());
							}
							// expr::{expr2}() -> call static method named by the value of expr2
							else
							{
								goto staticCall;
							}

						/** @noinspection PhpMissingBreakStatementInspection */
						case T::T_CLASS:
							if ($this->peek(1)->getType() === T::S_LEFT_PARENTHESIS)
							{
								// expr::class() is a static method call of the static method named 'class'
								goto fudgeSpecialNameToString;
							}
							$keyword = $this->read();
							$node = Nodes\Expressions\ClassNameResolutionExpression::__instantiateUnchecked($node, $operator, $keyword);
							break;

							staticCall:
							// we jump here when we positively decide on a static call, and have set up $memberName
							/** @var Nodes\Helpers\MemberName $memberName */
							$leftParenthesis = $this->read(T::S_LEFT_PARENTHESIS);
							$arguments = $this->arguments();
							$rightParenthesis = $this->read(T::S_RIGHT_PARENTHESIS);
							$node = Nodes\Expressions\StaticMethodCallExpression::__instantiateUnchecked($node, $operator, $memberName, $leftParenthesis, $arguments, $rightParenthesis);
							break;

						default:
							fudgeSpecialNameToString:
							if (\in_array($this->peek()->getType(), T::STRINGY_KEYWORDS, true))
							{
								$this->peek()->_fudgeType(T::T_STRING);
								goto doubleColonString;
							}
							else
							{
								throw ParseException::unexpected($this->peek());
							}
					}
					break;
				default:
					break 2;
			}
		}

		if ($operator = $this->opt(T::T_DEC))
		{
			$node = Nodes\Expressions\PostDecrementExpression::__instantiateUnchecked($node, $operator);
		}
		else if ($operator = $this->opt(T::T_INC))
		{
			$node = Nodes\Expressions\PostIncrementExpression::__instantiateUnchecked($node, $operator);
		}

		return $node;
	}

	private function block(): Nodes\Block
	{
		if ($this->peek()->getType() === T::S_LEFT_CURLY_BRACE)
		{
			return $this->regularBlock();
		}
		else
		{
			return Nodes\Blocks\ImplicitBlock::__instantiateUnchecked($this->statement());
		}
	}

	private function regularBlock(): Nodes\Blocks\RegularBlock
	{
		$leftBrace = $this->read();
		$statements = [];
		while ($this->peek()->getType() !== T::S_RIGHT_CURLY_BRACE)
		{
			$statements[] = $this->statement();
		}
		$rightBrace = $this->read(T::S_RIGHT_CURLY_BRACE);
		return Nodes\Blocks\RegularBlock::__instantiateUnchecked($leftBrace, $statements, $rightBrace);
	}

	/**
	 * @param array<int> $implicitEndKeywords
	 *
	 * TODO all blocks should accept all keywords as end and validation should handle the rest
	 */
	private function altBlock(int $endKeywordType, array $implicitEndKeywords = []): Nodes\Blocks\AlternativeFormatBlock
	{
		$colon = $this->read(T::S_COLON);
		$statements = [];
		while ($this->peek()->getType() !== $endKeywordType && !\in_array($this->peek()->getType(), $implicitEndKeywords, true))
		{
			$statements[] = $this->statement();
		}

		if ($endKeyword = $this->opt($endKeywordType))
		{
			$semiColon = $this->statementDelimiter();
		}
		else
		{
			$semiColon = null;
		}

		return Nodes\Blocks\AlternativeFormatBlock::__instantiateUnchecked($colon, $statements, $endKeyword, $semiColon);
	}

	/** @return array<\Phi\Nodes\Helpers\Parameter|Token|null> */
	private function parameters(): array
	{
		$nodes = [];
		while ($this->peek()->getType() !== T::S_RIGHT_PARENTHESIS)
		{
			$type = null;
			if ($this->peek()->getType() !== T::S_AMPERSAND && $this->peek()->getType() !== T::T_ELLIPSIS && $this->peek()->getType() !== T::T_VARIABLE)
			{
				$type = $this->type();
			}
			$byReference = $this->opt(T::S_AMPERSAND);
			$unpack = $this->opt(T::T_ELLIPSIS);
			$variable = $this->read(T::T_VARIABLE);
			$default = $this->default_();
			$nodes[] =  Nodes\Helpers\Parameter::__instantiateUnchecked($type, $byReference, $unpack, $variable, $default);
			if (!($nodes[] = $this->opt(T::S_COMMA)))
			{
				break;
			}
		}
		return $nodes;
	}

	/** @return array<Nodes\Helpers\Argument|Token|null> */
	private function arguments(): array
	{
		$arguments = [];
		while ($this->peek()->getType() !== T::S_RIGHT_PARENTHESIS)
		{
			$unpack = $this->opt(T::T_ELLIPSIS);
			$value = $this->expression();
			$arguments[] = Nodes\Helpers\Argument::__instantiateUnchecked($unpack, $value);
			if (!($arguments[] = $this->opt(T::S_COMMA)))
			{
				break;
			}
		}
		return $arguments;
	}

	private function returnType(): ?Nodes\Helpers\ReturnType
	{
		if ($symbol = $this->opt(T::S_COLON))
		{
			$type = $this->type();
			return Nodes\Helpers\ReturnType::__instantiateUnchecked($symbol, $type);
		}
		else
		{
			return null;
		}
	}

	private function type(): Nodes\Type
	{
		$nullableSymbol = $this->opt(T::S_QUESTION_MARK);
		switch ($this->peek()->getType())
		{
			case T::T_ARRAY:
			case T::T_CALLABLE:
			case T::T_STATIC:
				$type = Nodes\Types\SpecialType::__instantiateUnchecked($this->read());
				break;
			default:
				$type = Nodes\Types\NamedType::__instantiateUnchecked($this->name());
		}
		if ($nullableSymbol)
		{
			$type = Nodes\Types\NullableType::__instantiateUnchecked($nullableSymbol, $type);
		}
		return $type;
	}

	private function name(): Nodes\Helpers\Name
	{
		$parts = [];
		// TODO simplify loop?
		switch ($this->peek()->getType())
		{
			/** @noinspection PhpMissingBreakStatementInspection */
			case T::T_NS_SEPARATOR:
				$parts[] = $this->read();
			default:
				$parts[] = $this->read(T::T_STRING);
				while ($this->peek()->getType() === T::T_NS_SEPARATOR)
				{
					$parts[] = $this->read();
					$parts[] = $this->read(T::T_STRING);
				}
		}
		if (!$parts)
		{
			throw ParseException::unexpected($this->peek());
		}
		return Nodes\Helpers\Name::__instantiateUnchecked($parts);
	}

	private function default_(): ?Nodes\Helpers\Default_
	{
		if ($symbol = $this->opt(T::S_EQUALS))
		{
			$value = $this->expression();
			return Nodes\Helpers\Default_::__instantiateUnchecked($symbol, $value);
		}
		else
		{
			return null;
		}
	}

	private function memberName(): Nodes\Helpers\MemberName
	{
		if ($this->peek()->getType() === T::T_STRING)
		{
			return Nodes\Helpers\NormalMemberName::__instantiateUnchecked($this->read());
		}
		else if ($this->peek()->getType() === T::T_VARIABLE)
		{
			$expression = Nodes\Expressions\NormalVariableExpression::__instantiateUnchecked($this->read());
			return Nodes\Helpers\VariableMemberName::__instantiateUnchecked(null, $expression, null);
		}
		else if ($this->peek()->getType() === T::S_DOLLAR)
		{
			$expression = $this->variableVariable();
			return Nodes\Helpers\VariableMemberName::__instantiateUnchecked(null, $expression, null);
		}
		else if ($leftBrace = $this->opt(T::S_LEFT_CURLY_BRACE))
		{
			$expr = $this->expression();
			$rightBrace = $this->read(T::S_RIGHT_CURLY_BRACE);
			return Nodes\Helpers\VariableMemberName::__instantiateUnchecked($leftBrace, $expr, $rightBrace);
		}
		else
		{
			throw ParseException::unexpected($this->peek());
		}
	}

	private function variableVariable(): Nodes\Expressions\VariableVariableExpression
	{
		$dollar = $this->read(T::S_DOLLAR);
		$leftBrace = $rightBrace = null;
		if ($this->peek()->getType() === T::T_VARIABLE)
		{
			$expression = Nodes\Expressions\NormalVariableExpression::__instantiateUnchecked($this->read());
		}
		else if ($this->peek()->getType() === T::S_DOLLAR)
		{
			$expression = $this->variableVariable();
		}
		else if ($leftBrace = $this->opt(T::S_LEFT_CURLY_BRACE))
		{
			$expression = $this->expression();
			$rightBrace = $this->read(T::S_RIGHT_CURLY_BRACE);
		}
		else
		{
			throw ParseException::unexpected($this->peek());
		}

		return Nodes\Expressions\VariableVariableExpression::__instantiateUnchecked($dollar, $leftBrace, $expression, $rightBrace);
	}

	/** @return array<Node|null> */
	private function arrayItems(int $delimiter): array
	{
		$items = [];
		while ($this->peek()->getType() !== $delimiter)
		{
			$key = $unpack = $byReference = $value = null;

			if ($this->peek()->getType() !== T::S_COMMA && $this->peek()->getType() !== $delimiter)
			{
				$unpack = $this->opt(T::T_ELLIPSIS);
				$byReference = $this->opt(T::S_AMPERSAND);
				$value = $this->expression();

				if (!$unpack && !$byReference && $this->peek()->getType() === T::T_DOUBLE_ARROW)
				{
					$key = Nodes\Helpers\Key::__instantiateUnchecked($value, $this->read());
					$unpack = $this->opt(T::T_ELLIPSIS);
					$byReference = $this->opt(T::S_AMPERSAND);
					$value = $this->expression();
				}
			}

			$items[] = Nodes\Expressions\ArrayItem::__instantiateUnchecked($key, $unpack, $byReference, $value);

			if (!($items[] = $this->opt(T::S_COMMA)))
			{
				break;
			}
		}
		return $items;
	}

	/** @return Nodes\Expressions\StringInterpolation\InterpolatedStringPart[] */
	private function stringParts(int $delimiter): array
	{
		$parts = [];
		while ($this->peek()->getType() !== $delimiter)
		{
			if ($this->peek()->getType() === T::T_ENCAPSED_AND_WHITESPACE)
			{
				$parts[] = Nodes\Expressions\StringInterpolation\ConstantInterpolatedStringPart::__instantiateUnchecked($this->read());
			}
			else if ($this->peek()->getType() === T::T_VARIABLE)
			{
				$var = Nodes\Expressions\NormalVariableExpression::__instantiateUnchecked($this->read());
				if ($leftBracket = $this->opt(T::S_LEFT_SQUARE_BRACKET))
				{
					// TODO fix in lexer
					if ($this->peek()->getType() === T::T_NUM_STRING)
					{
						$this->peek()->_fudgeType(T::T_LNUMBER);
						$this->types[$this->i] = T::T_LNUMBER;
					}
					else if ($this->peek(1)->getType() === T::T_NUM_STRING)
					{
						$this->peek(1)->_fudgeType(T::T_LNUMBER);
						$this->types[$this->i + 1] = T::T_LNUMBER;
					}
					$index = $this->simpleExpression();
					$rightBracket = $this->read(T::S_RIGHT_SQUARE_BRACKET);
					$var = Nodes\Expressions\ArrayAccessExpression::__instantiateUnchecked($var, $leftBracket, $index, $rightBracket);
				}
				else if ($operator = $this->opt(T::T_OBJECT_OPERATOR))
				{
					$name = Nodes\Helpers\NormalMemberName::__instantiateUnchecked($this->read(T::T_STRING));
					$var = Nodes\Expressions\PropertyAccessExpression::__instantiateUnchecked($var, $operator, $name);
				}
				$parts[] = Nodes\Expressions\StringInterpolation\NormalInterpolatedStringVariable::__instantiateUnchecked(null, $var, null);
			}
			else if ($leftBrace = $this->opt(T::S_LEFT_CURLY_BRACE))
			{
				$expression = $this->simpleExpression();
				$rightBrace = $this->read(T::S_RIGHT_CURLY_BRACE);
				$parts[] = Nodes\Expressions\StringInterpolation\NormalInterpolatedStringVariable::__instantiateUnchecked($leftBrace, $expression, $rightBrace);
			}
			else if ($leftDelimiter = $this->opt(T::T_DOLLAR_OPEN_CURLY_BRACES))
			{
				if ($this->peek()->getType() === T::T_STRING_VARNAME)
				{
					$var = Nodes\Expressions\StringInterpolation\ConfusingInterpolatedStringVariableName::__instantiateUnchecked($this->read());
					if ($this->peek()->getType() === T::S_LEFT_SQUARE_BRACKET)
					{
						$leftBracket = $this->read();
						$index = $this->expression();
						$rightBracket = $this->read(T::S_RIGHT_SQUARE_BRACKET);
						$var = Nodes\Expressions\ArrayAccessExpression::__instantiateUnchecked($var, $leftBracket, $index, $rightBracket);
					}
					$rightDelimiter = $this->read(T::S_RIGHT_CURLY_BRACE);
					$parts[] = Nodes\Expressions\StringInterpolation\ConfusingInterpolatedStringVariable::__instantiateUnchecked($leftDelimiter, $var, $rightDelimiter);
				}
				else
				{
					$name = $this->expression();
					$rightDelimiter = $this->read(T::S_RIGHT_CURLY_BRACE);
					$parts[] = Nodes\Expressions\StringInterpolation\VariableInterpolatedStringVariable::__instantiateUnchecked($leftDelimiter, $name, $rightDelimiter);
				}
			}
			else
			{
				throw ParseException::unexpected($this->peek());
			}
		}
		return $parts;
	}
}
