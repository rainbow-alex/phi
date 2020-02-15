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
			if ($this->types[$this->i] !== 999)
			{
				throw ParseException::unexpected($this->tokens[$this->i]);
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
			if ($this->types[$this->i] !== 999)
			{
				throw ParseException::unexpected($this->tokens[$this->i]);
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
		if ($expectedTokenType !== null && $this->types[$this->i] !== $expectedTokenType)
		{
			throw ParseException::unexpected($this->tokens[$this->i]);
		}

		return $this->tokens[$this->i++];
	}

	private function opt(int $tokenType): ?Token
	{
		if ($this->types[$this->i] === $tokenType)
		{
			return $this->tokens[$this->i++];
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
		while ($this->types[$this->i] !== 999)
		{
			$statements[] = $this->statement();
		}
		$eof = $this->read(999);
		return Nodes\RootNode::__instantiateUnchecked($statements, $eof);
	}

	private function statement(): Nodes\Statement
	{
		switch ($this->types[$this->i])
		{
			case 218:
				$keyword = $this->read(218);
				$name = null;
				if ($this->types[$this->i] !== 124)
				{
					$name = $this->name();
				}
				if ($this->types[$this->i] === 124)
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

			case 255:
				return $this->use_();

			case 128:
			case 140:
			case 181:
				$modifiers = [];
				while (\in_array($this->types[$this->i], [128, 181], true))
				{
					$modifiers[] = $this->tokens[$this->i++];
				}
				$keyword = $this->read(140);
				$name = $this->read(245);
				if ($extendsKeyword = ($this->types[$this->i] === 179 ? $this->tokens[$this->i++] : null))
				{
					$extendsNames = [];
					$extendsNames[] = $this->name();
					$extends = Nodes\Oop\Extends_::__instantiateUnchecked($extendsKeyword, $extendsNames);
				}
				else
				{
					$extends = null;
				}
				if ($implementsKeyword = ($this->types[$this->i] === 192 ? $this->tokens[$this->i++] : null))
				{
					$implementsNames = [];
					do
					{
						$implementsNames[] = $this->name();
					}
					while ($implementsNames[] = ($this->types[$this->i] === 109 ? $this->tokens[$this->i++] : null));
					$implements = Nodes\Oop\Implements_::__instantiateUnchecked($implementsKeyword, $implementsNames);
				}
				else
				{
					$implements = null;
				}
				$leftBrace = $this->read(124);
				$members = [];
				while ($this->types[$this->i] !== 126)
				{
					$members[] = $this->oopMember();
				}
				$rightBrace = $this->read(126);
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

			case 199:
				$keyword = $this->read(199);
				$name = $this->read(245);
				$extends = null;
				if ($extendsKeyword = ($this->types[$this->i] === 179 ? $this->tokens[$this->i++] : null))
				{
					$extendsNames = [];
					do
					{
						$extendsNames[] = $this->name();
					}
					while ($extendsNames[] = ($this->types[$this->i] === 109 ? $this->tokens[$this->i++] : null));
					$extends = Nodes\Oop\Extends_::__instantiateUnchecked($extendsKeyword, $extendsNames);
				}
				$leftBrace = $this->read(124);
				$members = [];
				while ($this->types[$this->i] !== 126)
				{
					$members[] = $this->oopMember();
				}
				$rightBrace = $this->read(126);
				return Nodes\Oop\InterfaceDeclaration::__instantiateUnchecked(
					$keyword,
					$name,
					$extends,
					$leftBrace,
					$members,
					$rightBrace
				);

			case 250:
				$keyword = $this->read(250);
				$name = $this->read(245);
				$leftBrace = $this->read(124);
				$members = [];
				while ($this->types[$this->i] !== 126)
				{
					$members[] = $this->oopMember();
				}
				$rightBrace = $this->read(126);
				return Nodes\Oop\TraitDeclaration::__instantiateUnchecked(
					$keyword,
					$name,
					$leftBrace,
					$members,
					$rightBrace
				);

			case 136:
				$keyword = $this->tokens[$this->i++];
				$levels = null;
				if ($this->types[$this->i] === 210)
				{
					$levels = Nodes\Expressions\IntegerLiteral::__instantiateUnchecked($this->tokens[$this->i++]);
				}
				$semiColon = $this->statementDelimiter();
				return Nodes\Statements\BreakStatement::__instantiateUnchecked($keyword, $levels, $semiColon);

			case 148:
				$keyword = $this->tokens[$this->i++];
				$name = $this->read(245);
				$equals = $this->read(116);
				$value = $this->expression();
				$delimiter = $this->statementDelimiter();
				return Nodes\Statements\ConstStatement::__instantiateUnchecked($keyword, $name, $equals, $value, $delimiter);

			case 150:
				$keyword = $this->tokens[$this->i++];
				$levels = null;
				if ($this->types[$this->i] === 210)
				{
					$levels = Nodes\Expressions\IntegerLiteral::__instantiateUnchecked($this->tokens[$this->i++]);
				}
				$semiColon = $this->statementDelimiter();
				return Nodes\Statements\ContinueStatement::__instantiateUnchecked($keyword, $levels, $semiColon);

			case 153:
				$keyword = $this->tokens[$this->i++];
				$leftParenthesis = $this->read(105);
				$directives = [];
				do
				{
					$directiveKey = $this->read(245);
					$directiveEquals = $this->read(116);
					$directiveValue = $this->expression();
					$directives[] = Nodes\Statements\DeclareDirective::__instantiateUnchecked($directiveKey, $directiveEquals, $directiveValue);
				}
				while ($directives[] = ($this->types[$this->i] === 109 ? $this->tokens[$this->i++] : null));
				$rightParenthesis = $this->read(106);
				if ($this->types[$this->i] === 124)
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

			case 158:
				$keyword1 = $this->tokens[$this->i++];
				$block = $this->block();
				$keyword2 = $this->read(258);
				$leftParenthesis = $this->read(105);
				$test = $this->expression();
				$rightParenthesis = $this->read(106);
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

			case 164:
			case 226:
				$keyword = $this->tokens[$this->i++];
				$expressions = [];
				do
				{
					$expressions[] = $this->expression();
				}
				while ($expressions[] = ($this->types[$this->i] === 109 ? $this->tokens[$this->i++] : null));
				$semiColon = $this->statementDelimiter();
				return Nodes\Statements\EchoStatement::__instantiateUnchecked($keyword, $expressions, $semiColon);

			case 184:
				$keyword = $this->tokens[$this->i++];
				$leftParenthesis = $this->read(105);

				$init = [];
				if ($this->types[$this->i] !== 114)
				{
					do
					{
						$init[] = $this->expression();
					}
					while ($init[] = ($this->types[$this->i] === 109 ? $this->tokens[$this->i++] : null));
				}

				$separator1 = ($this->types[$this->i] === 109 ? $this->tokens[$this->i++] : null) ?? $this->read(114);

				$test = [];
				if ($this->types[$this->i] !== 114)
				{
					do
					{
						$test[] = $this->expression();
					}
					while ($test[] = ($this->types[$this->i] === 109 ? $this->tokens[$this->i++] : null));
				}

				$separator2 = ($this->types[$this->i] === 109 ? $this->tokens[$this->i++] : null) ?? $this->read(114);

				$step = [];
				if ($this->types[$this->i] !== 106)
				{
					do
					{
						$step[] = $this->expression();
					}
					while ($step[] = ($this->types[$this->i] === 109 ? $this->tokens[$this->i++] : null));
				}

				$rightParenthesis = $this->read(106);

				$block = $this->types[$this->i] === 113 ? $this->altBlock(171) : $this->block();

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

			case 185:
				$keyword = $this->tokens[$this->i++];
				$leftParenthesis = $this->read(105);
				$iterable = $this->expression();
				$as = $this->read(132);
				$key = null;
				$byReference = ($this->types[$this->i] === 104 ? $this->tokens[$this->i++] : null);
				$value = $this->simpleExpression();
				if ($this->types[$this->i] === 161)
				{
					if ($byReference)
					{
						throw ParseException::unexpected($this->tokens[$this->i]);
					}
					$key = Nodes\Helpers\Key::__instantiateUnchecked($value, $this->tokens[$this->i++]);
					$byReference = ($this->types[$this->i] === 104 ? $this->tokens[$this->i++] : null);
					$value = $this->simpleExpression();
				}
				$rightParenthesis = $this->read(106);
				$block = $this->types[$this->i] === 113 ? $this->altBlock(172) : $this->block();
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
			case 186:
				if (!(
					$this->types[$this->i + 1] === 245
					|| $this->types[$this->i + 1] === 104 && $this->types[$this->i + 2] === 245
				))
				{
					// no name, this is an anonymous function
					goto expressionStatement;
				}

				$keyword = $this->tokens[$this->i++];
				$byReference = ($this->types[$this->i] === 104 ? $this->tokens[$this->i++] : null);
				$name = $this->tokens[$this->i++];
				$leftParenthesis = $this->read(105);
				$parameters = $this->parameters();
				$rightParenthesis = $this->read(106);
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

			case 189:
				$keyword = $this->tokens[$this->i++];
				$label = $this->read(245);
				$semiColon = $this->statementDelimiter();
				return Nodes\Statements\GotoStatement::__instantiateUnchecked(
					$keyword,
					$label,
					$semiColon
				);

			case 191:
				$keyword = $this->tokens[$this->i++];
				$leftParenthesis = $this->read(105);
				$test = $this->expression();
				$rightParenthesis = $this->read(106);
				if ($altSyntax = ($this->types[$this->i] === 113))
				{
					$block = $this->altBlock(173, [166, 167]);
				}
				else
				{
					$block = $this->block();
				}
				$elseifs = [];
				while ($elseifKeyword = ($this->types[$this->i] === 167 ? $this->tokens[$this->i++] : null))
				{
					$elseifLeftParenthesis = $this->read(105);
					$elseifTest = $this->expression();
					$elseifRightParenthesis = $this->read(106);
					$elseifBlock = $altSyntax ? $this->altBlock(173, [166, 167]) : $this->block();
					$elseifs[] = Nodes\Statements\Elseif_::__instantiateUnchecked(
						$elseifKeyword,
						$elseifLeftParenthesis,
						$elseifTest,
						$elseifRightParenthesis,
						$elseifBlock
					);
				}
				$else = null;
				if ($elseKeyword = ($this->types[$this->i] === 166 ? $this->tokens[$this->i++] : null))
				{
					$elseBlock = $altSyntax ? $this->altBlock(173) : $this->block();
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

			case 237:
				$keyword = $this->tokens[$this->i++];
				$expression = !$this->endOfStatement() ? $this->expression() : null;
				$semiColon = $this->statementDelimiter();
				return Nodes\Statements\ReturnStatement::__instantiateUnchecked($keyword, $expression, $semiColon);

			/** @noinspection PhpMissingBreakStatementInspection */
			case 244:
				if ($this->types[$this->i + 1] !== 257)
				{
					// static function etc. -- anonymous function
					goto expressionStatement;
				}
				$keyword = $this->tokens[$this->i++];
				$variables = [];
				do
				{
					$variable = $this->read(257);
					$default = $this->default_();
					$variables[] = Nodes\Statements\StaticVariable::__instantiateUnchecked($variable, $default);
				}
				while ($variables[] = ($this->types[$this->i] === 109 ? $this->tokens[$this->i++] : null));
				$semiColon = $this->statementDelimiter();
				return Nodes\Statements\StaticVariableStatement::__instantiateUnchecked($keyword, $variables, $semiColon);

			case 248:
				$keyword = $this->tokens[$this->i++];
				$leftParenthesis = $this->read(105);
				$value = $this->expression();
				$rightParenthesis = $this->read(106);
				$leftBrace = $this->read(124);
				$cases = [];
				while ($this->types[$this->i] !== 126)
				{
					if ($caseKeyword = ($this->types[$this->i] === 154 ? $this->tokens[$this->i++] : null))
					{
						$caseValue = null;
					}
					else
					{
						$caseKeyword = $this->read(138);
						$caseValue = $this->expression();
					}
					$caseDelimiter = ($this->types[$this->i] === 114 ? $this->tokens[$this->i++] : null) ?? $this->read(113);
					$caseStatements = [];
					while ($this->types[$this->i] !== 138 && $this->types[$this->i] !== 154 && $this->types[$this->i] !== 126)
					{
						$caseStatements[] = $this->statement();
					}
					$cases[] = Nodes\Statements\SwitchCase::__instantiateUnchecked($caseKeyword, $caseValue, $caseDelimiter, $caseStatements);
				}
				$rightBrace = $this->read(126);
				return Nodes\Statements\SwitchStatement::__instantiateUnchecked(
					$keyword,
					$leftParenthesis,
					$value,
					$rightParenthesis,
					$leftBrace,
					$cases,
					$rightBrace
				);

			case 249:
				$keyword = $this->tokens[$this->i++];
				$expression = $this->expression();
				$semiColon = $this->statementDelimiter();
				return Nodes\Statements\ThrowStatement::__instantiateUnchecked($keyword, $expression, $semiColon);

			case 252:
				$keyword = $this->tokens[$this->i++];
				$block = $this->regularBlock();
				$catches = [];
				while ($catchKeyword = ($this->types[$this->i] === 139 ? $this->tokens[$this->i++] : null))
				{
					$catchLeftParenthesis = $this->read(105);
					$catchTypes = [];
					do
					{
						$catchTypes[] = Nodes\Types\NamedType::__instantiateUnchecked($this->name());
					}
					while ($catchTypes[] = ($this->types[$this->i] === 125 ? $this->tokens[$this->i++] : null));
					$catchVariable = $this->read(257);
					$catchRightParenthesis = $this->read(106);
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
				if ($finallyKeyword = ($this->types[$this->i] === 182 ? $this->tokens[$this->i++] : null))
				{
					$finallyBlock = $this->regularBlock();
					$finally = Nodes\Statements\Finally_::__instantiateUnchecked($finallyKeyword, $finallyBlock);
				}
				return Nodes\Statements\TryStatement::__instantiateUnchecked($keyword, $block, $catches, $finally);

			case 258:
				$keyword = $this->tokens[$this->i++];
				$leftParenthesis = $this->read(105);
				$test = $this->expression();
				$rightParenthesis = $this->read(106);
				$block = $this->types[$this->i] === 113 ? $this->altBlock(175) : $this->block();
				return Nodes\Statements\WhileStatement::__instantiateUnchecked($keyword, $leftParenthesis, $test, $rightParenthesis, $block);

			case 253:
				$keyword = $this->tokens[$this->i++];
				$leftParenthesis = $this->read(105);
				$expressions = [];
				do
				{
					$expressions[] = $this->simpleExpression();
				}
				while ($expressions[] = ($this->types[$this->i] === 109 ? $this->tokens[$this->i++] : null));
				$rightParenthesis = $this->read(106);
				$semiColon = $this->statementDelimiter();
				return Nodes\Statements\UnsetStatement::__instantiateUnchecked($keyword, $leftParenthesis, $expressions, $rightParenthesis, $semiColon);

			case 124:
				return Nodes\Statements\BlockStatement::__instantiateUnchecked($this->regularBlock());

			/** @noinspection PhpMissingBreakStatementInspection */
			case 245:
				if ($this->types[$this->i + 1] !== 113)
				{
					goto expressionStatement;
				}
				return Nodes\Statements\LabelStatement::__instantiateUnchecked($this->tokens[$this->i++], $this->tokens[$this->i++]);

			case 196:
			case 225:
				$content = ($this->types[$this->i] === 196 ? $this->tokens[$this->i++] : null);
				$open = ($this->types[$this->i] === 225 ? $this->tokens[$this->i++] : null);
				return Nodes\Statements\InlineHtmlStatement::__instantiateUnchecked($content, $open);

			case 114:
			case 143:
				return Nodes\Statements\NopStatement::__instantiateUnchecked($this->tokens[$this->i++]);

			default:
				expressionStatement:
				$expression = $this->expression();
				$semiColon = $this->statementDelimiter();
				return Nodes\Statements\ExpressionStatement::__instantiateUnchecked($expression, $semiColon);
		}
	}

	private function use_(): Nodes\Statements\UseStatement
	{
		$keyword = $this->read(255);
		$type = null;
		if ($this->types[$this->i] === 186 || $this->types[$this->i] === 148)
		{
			$type = $this->tokens[$this->i++];
		}

		$firstName = null;
		if ($this->types[$this->i] === 221 || $this->types[$this->i] === 245)
		{
			$firstName = $this->name();
		}

		if ($this->types[$this->i] === 221)
		{
			assert($firstName !== null);
			$prefix = $firstName;
			unset($firstName);
			$prefix->getParts()->add($this->tokens[$this->i++]);
			$leftBrace = $this->read(124);
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
			if ($aliasKeyword = ($this->types[$this->i] === 132 ? $this->tokens[$this->i++] : null))
			{
				$alias = Nodes\Statements\UseAlias::__instantiateUnchecked($aliasKeyword, $this->read(245));
			}

			$declarations[] = Nodes\Statements\UseDeclaration::__instantiateUnchecked($name, $alias);

			if (!($declarations[] = ($this->types[$this->i] === 109 ? $this->tokens[$this->i++] : null)))
			{
				break;
			}

			if ($leftBrace && $this->types[$this->i] === 126)
			{
				$rightBrace = $this->tokens[$this->i++];
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
		while (\in_array($this->types[$this->i], [128, 181, 234, 233, 232, 244], true))
		{
			$modifiers[] = $this->tokens[$this->i++];
		}

		if ($keyword = ($this->types[$this->i] === 186 ? $this->tokens[$this->i++] : null))
		{
			$byReference = ($this->types[$this->i] === 104 ? $this->tokens[$this->i++] : null);
			// TODO dedup
			if ($this->types[$this->i] === 245)
			{
				$name = $this->tokens[$this->i++];
			}
			else if (\in_array($this->types[$this->i], T::STRINGY_KEYWORDS, true))
			{
				$this->tokens[$this->i]->_fudgeType(245);
				$name = $this->tokens[$this->i++];
			}
			else
			{
				throw ParseException::unexpected($this->tokens[$this->i]);
			}
			$leftParenthesis = $this->read(105);
			$parameters = $this->parameters();
			$rightParenthesis = $this->read(106);
			$returnType = $this->returnType();
			$semiColon = ($this->types[$this->i] === 114 ? $this->tokens[$this->i++] : null);
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
		else if ($keyword = ($this->types[$this->i] === 148 ? $this->tokens[$this->i++] : null))
		{
			if ($this->types[$this->i] === 245)
			{
				$name = $this->tokens[$this->i++];
			}
			else if (\in_array($this->types[$this->i], T::STRINGY_KEYWORDS, true))
			{
				$this->tokens[$this->i]->_fudgeType(245);
				$name = $this->tokens[$this->i++];
			}
			else
			{
				throw ParseException::unexpected($this->tokens[$this->i]);
			}
			$equals = $this->read(116);
			$value = $this->expression();
			$semiColon = $this->read(114);
			return Nodes\Oop\ClassConstant::__instantiateUnchecked($modifiers, $keyword, $name, $equals, $value, $semiColon);
		}
		else if ($keyword = ($this->types[$this->i] === 255 ? $this->tokens[$this->i++] : null))
		{
			$names = [];
			do
			{
				$names[] = $this->name();
			}
			while ($names[] = ($this->types[$this->i] === 109 ? $this->tokens[$this->i++] : null));
			$modifications = [];
			$rightBrace = $semiColon = null;
			if ($leftBrace = ($this->types[$this->i] === 124 ? $this->tokens[$this->i++] : null))
			{
				if ($this->types[$this->i + 1] === 132 || $this->types[$this->i] === 198)
				{
					$ref = Nodes\Oop\TraitMethodRef::__instantiateUnchecked(null, null, $this->read(245));
				}
				else
				{
					$trait = $this->name();
					$doubleColon = $this->read(163);
					$method = $this->read(245);
					$ref = Nodes\Oop\TraitMethodRef::__instantiateUnchecked($trait, $doubleColon, $method);
				}
				if ($modKeyword = ($this->types[$this->i] === 198 ? $this->tokens[$this->i++] : null))
				{
					$excluded = [];
					do
					{
						$excluded[] = $this->name();
					}
					while ($excluded[] = ($this->types[$this->i] === 109 ? $this->tokens[$this->i++] : null));
					$modSemi = $this->read(114);
					$modifications[] = Nodes\Oop\TraitUseInsteadof::__instantiateUnchecked($ref, $modKeyword, $excluded, $modSemi);
				}
				else if ($modKeyword = ($this->types[$this->i] === 132 ? $this->tokens[$this->i++] : null))
				{
					$modifier = null;
					if ($this->types[$this->i] === 234 || $this->types[$this->i] === 233 || $this->types[$this->i] === 232)
					{
						$modifier = $this->tokens[$this->i++];
					}
					$alias = $this->read(245);
					$modSemi = $this->read(114);
					$modifications[] = Nodes\Oop\TraitUseAs::__instantiateUnchecked($ref, $modKeyword, $modifier, $alias, $modSemi);
				}
				else
				{
					throw ParseException::unexpected($this->tokens[$this->i]);
				}
				$rightBrace = $this->read(126);
			}
			else
			{
				$semiColon = $this->read(114);
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
		else if ($variable = ($this->types[$this->i] === 257 ? $this->tokens[$this->i++] : null))
		{
			$default = $this->default_();
			$semiColon = $this->read(114);
			return Nodes\Oop\Property::__instantiateUnchecked($modifiers, $variable, $default, $semiColon);
		}
		else
		{
			throw ParseException::unexpected($this->tokens[$this->i]);
		}
	}

	private function endOfStatement(): bool
	{
		$t = $this->types[$this->i];
		return $t === 114 || $t === 143;
	}

	private function statementDelimiter(): Token
	{
		if ($this->types[$this->i] === 143)
		{
			return $this->tokens[$this->i++];
		}
		else
		{
			return $this->read(114);
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
			if ($minPrecedence <= 70 && $operator = ($this->types[$this->i] === 229 ? $this->tokens[$this->i++] : null))
			{
				$right = $this->expression(70);
				$left = Nodes\Expressions\PowerExpression::__instantiateUnchecked($left, $operator, $right);
			}
			else if ($minPrecedence <= 60 && $operator = ($this->types[$this->i] === 197 ? $this->tokens[$this->i++] : null))
			{
				$type = $this->simpleExpression(true);
				$left = Nodes\Expressions\InstanceofExpression::__instantiateUnchecked($left, $operator, $type);
			}
			else
			{
				break;
			}
		}

		if ($minPrecedence <= 49)
		{
			while (true)
			{
				if ($operator = ($this->types[$this->i] === 107 ? $this->tokens[$this->i++] : null))
				{
					$right = $this->expression(49 + 1);
					$left = Nodes\Expressions\MultiplyExpression::__instantiateUnchecked($left, $operator, $right);
				}
				else if ($operator = ($this->types[$this->i] === 112 ? $this->tokens[$this->i++] : null))
				{
					$right = $this->expression(49 + 1);
					$left = Nodes\Expressions\DivideExpression::__instantiateUnchecked($left, $operator, $right);
				}
				else if ($operator = ($this->types[$this->i] === 103 ? $this->tokens[$this->i++] : null))
				{
					$right = $this->expression(49 + 1);
					$left = Nodes\Expressions\ModuloExpression::__instantiateUnchecked($left, $operator, $right);
				}
				else
				{
					break;
				}
			}
		}

		if ($minPrecedence <= 48)
		{
			while (true)
			{
				if ($operator = ($this->types[$this->i] === 108 ? $this->tokens[$this->i++] : null))
				{
					$right = $this->expression(48 + 1);
					$left = Nodes\Expressions\AddExpression::__instantiateUnchecked($left, $operator, $right);
				}
				else if ($operator = ($this->types[$this->i] === 110 ? $this->tokens[$this->i++] : null))
				{
					$right = $this->expression(48 + 1);
					$left = Nodes\Expressions\SubtractExpression::__instantiateUnchecked($left, $operator, $right);
				}
				else if ($operator = ($this->types[$this->i] === 111 ? $this->tokens[$this->i++] : null))
				{
					$right = $this->expression(48 + 1);
					$left = Nodes\Expressions\ConcatExpression::__instantiateUnchecked($left, $operator, $right);
				}
				else
				{
					break;
				}
			}
		}

		if ($minPrecedence <= 47)
		{
			while (true)
			{
				if ($operator = ($this->types[$this->i] === 238 ? $this->tokens[$this->i++] : null))
				{
					$right = $this->expression(47 + 1);
					$left = Nodes\Expressions\ShiftLeftExpression::__instantiateUnchecked($left, $operator, $right);
				}
				else if ($operator = ($this->types[$this->i] === 241 ? $this->tokens[$this->i++] : null))
				{
					$right = $this->expression(47 + 1);
					$left = Nodes\Expressions\ShiftRightExpression::__instantiateUnchecked($left, $operator, $right);
				}
				else
				{
					break;
				}
			}
		}

		if ($minPrecedence <= 37)
		{
			if ($operator = ($this->types[$this->i] === 115 ? $this->tokens[$this->i++] : null))
			{
				$right = $this->expression(37 + 1);
				$left = Nodes\Expressions\LessThanExpression::__instantiateUnchecked($left, $operator, $right);
			}
			else if ($operator = ($this->types[$this->i] === 207 ? $this->tokens[$this->i++] : null))
			{
				$right = $this->expression(37 + 1);
				$left = Nodes\Expressions\LessThanOrEqualsExpression::__instantiateUnchecked($left, $operator, $right);
			}
			else if ($operator = ($this->types[$this->i] === 117 ? $this->tokens[$this->i++] : null))
			{
				$right = $this->expression(37 + 1);
				$left = Nodes\Expressions\GreaterThanExpression::__instantiateUnchecked($left, $operator, $right);
			}
			else if ($operator = ($this->types[$this->i] === 203 ? $this->tokens[$this->i++] : null))
			{
				$right = $this->expression(37 + 1);
				$left = Nodes\Expressions\GreaterThanOrEqualsExpression::__instantiateUnchecked($left, $operator, $right);
			}
		}

		if ($minPrecedence <= 36)
		{
			if ($operator = ($this->types[$this->i] === 204 ? $this->tokens[$this->i++] : null))
			{
				$right = $this->expression(36 + 1);
				$left = Nodes\Expressions\IsIdenticalExpression::__instantiateUnchecked($left, $operator, $right);
			}
			else if ($operator = ($this->types[$this->i] === 206 ? $this->tokens[$this->i++] : null))
			{
				$right = $this->expression(36 + 1);
				$left = Nodes\Expressions\IsNotIdenticalExpression::__instantiateUnchecked($left, $operator, $right);
			}
			else if ($operator = ($this->types[$this->i] === 202 ? $this->tokens[$this->i++] : null))
			{
				$right = $this->expression(36 + 1);
				$left = Nodes\Expressions\IsEqualExpression::__instantiateUnchecked($left, $operator, $right);
			}
			else if ($operator = ($this->types[$this->i] === 205 ? $this->tokens[$this->i++] : null))
			{
				$right = $this->expression(36 + 1);
				$left = Nodes\Expressions\IsNotEqualExpression::__instantiateUnchecked($left, $operator, $right);
			}
			else if ($operator = ($this->types[$this->i] === 240 ? $this->tokens[$this->i++] : null))
			{
				$right = $this->expression(36 + 1);
				$left = Nodes\Expressions\SpaceshipExpression::__instantiateUnchecked($left, $operator, $right);
			}
		}

		if ($minPrecedence <= 35)
		{
			while ($operator = ($this->types[$this->i] === 104 ? $this->tokens[$this->i++] : null))
			{
				$right = $this->expression(35 + 1);
				$left = Nodes\Expressions\BitwiseAndExpression::__instantiateUnchecked($left, $operator, $right);
			}
		}

		if ($minPrecedence <= 34)
		{
			while ($operator = ($this->types[$this->i] === 122 ? $this->tokens[$this->i++] : null))
			{
				$right = $this->expression(34 + 1);
				$left = Nodes\Expressions\BitwiseXorExpression::__instantiateUnchecked($left, $operator, $right);
			}
		}

		if ($minPrecedence <= 33)
		{
			while ($operator = ($this->types[$this->i] === 125 ? $this->tokens[$this->i++] : null))
			{
				$right = $this->expression(33 + 1);
				$left = Nodes\Expressions\BitwiseOrExpression::__instantiateUnchecked($left, $operator, $right);
			}
		}

		if ($minPrecedence <= 32)
		{
			while ($operator = ($this->types[$this->i] === 133 ? $this->tokens[$this->i++] : null))
			{
				$right = $this->expression(32 + 1);
				$left = Nodes\Expressions\SymbolAndExpression::__instantiateUnchecked($left, $operator, $right);
			}
		}

		if ($minPrecedence <= 31)
		{
			while ($operator = ($this->types[$this->i] === 134 ? $this->tokens[$this->i++] : null))
			{
				$right = $this->expression(31 + 1);
				$left = Nodes\Expressions\SymbolOrExpression::__instantiateUnchecked($left, $operator, $right);
			}
		}

		if ($minPrecedence <= 26)
		{
			if ($operator = ($this->types[$this->i] === 144 ? $this->tokens[$this->i++] : null))
			{
				$right = $this->expression(26);
				$left = Nodes\Expressions\CoalesceExpression::__instantiateUnchecked($left, $operator, $right);
			}
		}

		if ($minPrecedence <= 25)
		{
			while ($questionMark = ($this->types[$this->i] === 118 ? $this->tokens[$this->i++] : null))
			{
				// note: no precedence is passed here, delimiters on both sides allow for e.g. 1 ? 2 and 3 : 4
				$then = $this->types[$this->i] !== 113 ? $this->expression() : null;
				$colon = $this->read(113);
				$else = $this->expression(25 + 1);
				$left = Nodes\Expressions\TernaryExpression::__instantiateUnchecked($left, $questionMark, $then, $colon, $else);
			}
		}

		if ($minPrecedence <= 13)
		{
			while ($operator = ($this->types[$this->i] === 211 ? $this->tokens[$this->i++] : null))
			{
				$right = $this->expression(13 + 1);
				$left = Nodes\Expressions\KeywordAndExpression::__instantiateUnchecked($left, $operator, $right);
			}
		}

		if ($minPrecedence <= 12)
		{
			while ($operator = ($this->types[$this->i] === 213 ? $this->tokens[$this->i++] : null))
			{
				$right = $this->expression(12 + 1);
				$left = Nodes\Expressions\KeywordXorExpression::__instantiateUnchecked($left, $operator, $right);
			}
		}

		if ($minPrecedence <= 11)
		{
			while ($operator = ($this->types[$this->i] === 212 ? $this->tokens[$this->i++] : null))
			{
				$right = $this->expression(11 + 1);
				$left = Nodes\Expressions\KeywordOrExpression::__instantiateUnchecked($left, $operator, $right);
			}
		}

		return $left;
	}

	private function simpleExpression(bool $newable = false): Nodes\Expression
	{
		switch ($this->types[$this->i])
		{
			case 257:
				$node = Nodes\Expressions\NormalVariableExpression::__instantiateUnchecked($this->tokens[$this->i++]);
				break;

			case 102:
				$node = $this->variableVariable();
				break;

			case 245:
			case 221:
				$node = Nodes\Expressions\NameExpression::__instantiateUnchecked($this->name());
				break;

			case 149:
				$node = Nodes\Expressions\SingleQuotedStringLiteral::__instantiateUnchecked($this->tokens[$this->i++]);
				break;

			case 101:
				$leftDelimiter = $this->tokens[$this->i++];
				$parts = $this->stringParts(101);
				$rightDelimiter = $this->tokens[$this->i++];
				$node = Nodes\Expressions\DoubleQuotedStringLiteral::__instantiateUnchecked($leftDelimiter, $parts, $rightDelimiter);
				break;

			case 243:
				$leftDelimiter = $this->tokens[$this->i++];
				if (\rtrim($leftDelimiter->getSource())[-1] === "'")
				{
					$content = ($this->types[$this->i] === 169 ? $this->tokens[$this->i++] : null);
					$rightDelimiter = $this->read(176);
					$node = Nodes\Expressions\NowdocStringLiteral::__instantiateUnchecked($leftDelimiter, $content, $rightDelimiter);
				}
				else
				{
					$parts = $this->stringParts(176);
					$rightDelimiter = $this->tokens[$this->i++];
					$node = Nodes\Expressions\HeredocStringLiteral::__instantiateUnchecked($leftDelimiter, $parts, $rightDelimiter);
				}
				break;

			case 210:
				$node = Nodes\Expressions\IntegerLiteral::__instantiateUnchecked($this->tokens[$this->i++]);
				break;

			case 157:
				$node = Nodes\Expressions\FloatLiteral::__instantiateUnchecked($this->tokens[$this->i++]);
				break;

			case 219:
				$keyword = $this->tokens[$this->i++];
				$class = $this->simpleExpression(true);
				if ($leftParenthesis = ($this->types[$this->i] === 105 ? $this->tokens[$this->i++] : null))
				{
					$arguments = $this->arguments();
					$rightParenthesis = $this->read(106);
				}
				else
				{
					$arguments = [];
					$rightParenthesis = null;
				}
				$node = Nodes\Expressions\NewExpression::__instantiateUnchecked($keyword, $class, $leftParenthesis, $arguments, $rightParenthesis);
				break;

			case 120:
				$leftBracket = $this->tokens[$this->i++];
				$items = $this->arrayItems(121);
				$rightBracket = $this->read(121);
				$node = Nodes\Expressions\ShortArrayExpression::__instantiateUnchecked($leftBracket, $items, $rightBracket);
				break;

			case 130:
				$keyword = $this->tokens[$this->i++];
				$leftParenthesis = $this->read(105);
				$items = $this->arrayItems(106);
				$rightParenthesis = $this->read(106);
				$node = Nodes\Expressions\LongArrayExpression::__instantiateUnchecked($keyword, $leftParenthesis, $items, $rightParenthesis);
				break;

			case 105:
				$leftParenthesis = $this->tokens[$this->i++];
				$expression = $this->expression();
				$rightParenthesis = $this->read(106);
				$node = Nodes\Expressions\ParenthesizedExpression::__instantiateUnchecked($leftParenthesis, $expression, $rightParenthesis);
				break;

			case 201:
				$keyword = $this->tokens[$this->i++];
				$leftParenthesis = $this->read(105);
				$items = [];
				do
				{
					$items[] = $this->expression();
				}
				while (
					($items[] = ($this->types[$this->i] === 109 ? $this->tokens[$this->i++] : null))
					&& $this->types[$this->i] !== 106
				);
				$rightParenthesis = $this->read(106);
				$node = Nodes\Expressions\IssetExpression::__instantiateUnchecked($keyword, $leftParenthesis, $items, $rightParenthesis);
				break;

			case 168:
				$keyword = $this->tokens[$this->i++];
				$leftParenthesis = $this->read(105);
				$expression = $this->expression();
				$rightParenthesis = $this->read(106);
				$node = Nodes\Expressions\EmptyExpression::__instantiateUnchecked($keyword, $leftParenthesis, $expression, $rightParenthesis);
				break;

			case 152:
				$operator = $this->tokens[$this->i++];
				$expression = $this->simpleExpression();
				$node = Nodes\Expressions\PreDecrementExpression::__instantiateUnchecked($operator, $expression);
				break;

			case 193:
				$operator = $this->tokens[$this->i++];
				$expression = $this->simpleExpression();
				$node = Nodes\Expressions\PreIncrementExpression::__instantiateUnchecked($operator, $expression);
				break;

			case 155:
			case 180:
			case 208:
			case 187:
			case 141:
			case 214:
			case 251:
			case 220:
				$node = Nodes\Expressions\MagicConstant::__instantiateUnchecked($this->tokens[$this->i++]);
				break;

			case 142:
				$keyword = $this->tokens[$this->i++];
				$expression = $this->simpleExpression();
				$node = Nodes\Expressions\CloneExpression::__instantiateUnchecked($keyword, $expression);
				break;

			case 100:
				$operator = $this->tokens[$this->i++];
				$expression = $this->expression(50);
				$node = Nodes\Expressions\NotExpression::__instantiateUnchecked($operator, $expression);
				break;

			case 261:
				$keyword = $this->tokens[$this->i++];
				$key = null;
				$next = $this->tokens[$this->i];
				$expression = null;
				try
				{
					$expression = $this->expression(25);
				}
				catch (ParseException $e)
				{
					if ($this->tokens[$this->i] !== $next)
					{
						throw $e;
					}
				}
				if ($expression && $this->types[$this->i] === 161)
				{
					$key = Nodes\Helpers\Key::__instantiateUnchecked($expression, $this->tokens[$this->i++]);
					$expression = $this->expression(25);
				}
				$node = Nodes\Expressions\YieldExpression::__instantiateUnchecked($keyword, $key, $expression);
				break;

			case 262:
				$keyword = $this->tokens[$this->i++];
				$expression = $this->expression(25);
				$node = Nodes\Expressions\YieldFromExpression::__instantiateUnchecked($keyword, $expression);
				break;

			case 194:
			case 195:
			case 235:
			case 236:
				$keyword = $this->tokens[$this->i++];
				$expression = $this->expression();
				$node = Nodes\Expressions\IncludeLikeExpression::__instantiateUnchecked($keyword, $expression);
				break;

			case 127:
				$symbol = $this->tokens[$this->i++];
				$expression = $this->expression(70);
				$node = Nodes\Expressions\BitwiseNotExpression::__instantiateUnchecked($symbol, $expression);
				break;

			case 110:
				$symbol = $this->tokens[$this->i++];
				$expression = $this->expression(70);
				$node = Nodes\Expressions\UnaryMinusExpression::__instantiateUnchecked($symbol, $expression);
				break;

			case 108:
				$symbol = $this->tokens[$this->i++];
				$expression = $this->expression(70);
				$node = Nodes\Expressions\UnaryPlusExpression::__instantiateUnchecked($symbol, $expression);
				break;

			case 131:
			case 135:
			case 162:
			case 200:
			case 223:
			case 246:
			case 254:
				$cast = $this->tokens[$this->i++];
				$expression = $this->expression(70);
				$node = Nodes\Expressions\CastExpression::__instantiateUnchecked($cast, $expression);
				break;

			case 119:
				$operator = $this->tokens[$this->i++];
				$expression = $this->expression(70);
				$node = Nodes\Expressions\SuppressErrorsExpression::__instantiateUnchecked($operator, $expression);
				break;

			case 209:
				$keyword = $this->tokens[$this->i++];
				$leftParenthesis = $this->read(105);
				$items = $this->arrayItems(106);
				$rightParenthesis = $this->tokens[$this->i++];
				$node = Nodes\Expressions\ListExpression::__instantiateUnchecked($keyword, $leftParenthesis, $items, $rightParenthesis);
				break;

			case 178:
				$keyword = $this->tokens[$this->i++];
				if ($this->types[$this->i] === 105)
				{
					$leftParenthesis = $this->read(105);
					$expression = $this->types[$this->i] !== 106 ? $this->expression() : null;
					$rightParenthesis = $this->read(106);
				}
				else
				{
					$leftParenthesis = $expression = $rightParenthesis = null;
				}
				$node = Nodes\Expressions\ExitExpression::__instantiateUnchecked($keyword, $leftParenthesis, $expression, $rightParenthesis);
				break;

			case 231:
				$keyword = $this->tokens[$this->i++];
				$expression = $this->expression(25);
				$node = Nodes\Expressions\PrintExpression::__instantiateUnchecked($keyword, $expression);
				break;

			case 177:
				$keyword = $this->tokens[$this->i++];
				$leftParenthesis = $this->read(105);
				$expression = $this->expression();
				$rightParenthesis = $this->read(106);
				$node = Nodes\Expressions\EvalExpression::__instantiateUnchecked($keyword, $leftParenthesis, $expression, $rightParenthesis);
				break;

			case 123:
				$leftDelimiter = $this->tokens[$this->i++];
				$command = $this->read(169);
				$rightDelimiter = $this->read(123);
				$node = Nodes\Expressions\ExececutionExpression::__instantiateUnchecked($leftDelimiter, $command, $rightDelimiter);
				break;

			/** @noinspection PhpMissingBreakStatementInspection */
			case 244:
				if ($this->types[$this->i + 1] === 186)
				{
					goto anonymousFunction;
				}
				$node = Nodes\Expressions\StaticExpression::__instantiateUnchecked($this->tokens[$this->i++]);
				break;

			case 186:
				anonymousFunction:
				$static = ($this->types[$this->i] === 244 ? $this->tokens[$this->i++] : null);
				$keyword = $this->tokens[$this->i++];
				$byReference = ($this->types[$this->i] === 104 ? $this->tokens[$this->i++] : null);
				$leftParenthesis = $this->read(105);
				$parameters = $this->parameters();
				$rightParenthesis = $this->read(106);
				$use = null;
				if ($useKeyword = ($this->types[$this->i] === 255 ? $this->tokens[$this->i++] : null))
				{
					$useLeftParenthesis = $this->read(105);
					$useBindings = [];
					while (true)
					{
						$useBindingByReference = ($this->types[$this->i] === 104 ? $this->tokens[$this->i++] : null);
						$useBindingVariable = $this->read(257);
						$useBindings[] = Nodes\Expressions\AnonymousFunctionUseBinding::__instantiateUnchecked($useBindingByReference, $useBindingVariable);
						if (!($useBindings[] = ($this->types[$this->i] === 109 ? $this->tokens[$this->i++] : null)))
						{
							break;
						}
					}
					$useRightParenthesis = $this->read(106);
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
				throw ParseException::unexpected($this->tokens[$this->i]);
		}

		while (true)
		{
			switch ($this->types[$this->i])
			{
				case 116:
					if ($this->types[$this->i + 1] === 104)
					{
						$operator1 = $this->tokens[$this->i++];
						$operator2 = $this->tokens[$this->i++];
						$right = $this->simpleExpression();
						$node = Nodes\Expressions\AliasExpression::__instantiateUnchecked($node, $operator1, $operator2, $right);
					}
					else
					{
						$operator = $this->tokens[$this->i++];
						$value = $this->expression(25);
						$node = Nodes\Expressions\AssignExpression::__instantiateUnchecked($node, $operator, $value);
					}
					break;
				case 228:
					$operator = $this->tokens[$this->i++];
					$value = $this->expression(25);
					$node = Nodes\Expressions\CombinedAssignment\AddAssignExpression::__instantiateUnchecked($node, $operator, $value);
					break;
				case 215:
					$operator = $this->tokens[$this->i++];
					$value = $this->expression(25);
					$node = Nodes\Expressions\CombinedAssignment\SubtractAssignExpression::__instantiateUnchecked($node, $operator, $value);
					break;
				case 147:
					$operator = $this->tokens[$this->i++];
					$value = $this->expression(25);
					$node = Nodes\Expressions\CombinedAssignment\ConcatAssignExpression::__instantiateUnchecked($node, $operator, $value);
					break;
				case 217:
					$operator = $this->tokens[$this->i++];
					$value = $this->expression(25);
					$node = Nodes\Expressions\CombinedAssignment\MultiplyAssignExpression::__instantiateUnchecked($node, $operator, $value);
					break;
				case 156:
					$operator = $this->tokens[$this->i++];
					$value = $this->expression(25);
					$node = Nodes\Expressions\CombinedAssignment\DivideAssignExpression::__instantiateUnchecked($node, $operator, $value);
					break;
				case 216:
					$operator = $this->tokens[$this->i++];
					$value = $this->expression(25);
					$node = Nodes\Expressions\CombinedAssignment\ModuloAssignExpression::__instantiateUnchecked($node, $operator, $value);
					break;
				case 230:
					$operator = $this->tokens[$this->i++];
					$value = $this->expression(25);
					$node = Nodes\Expressions\CombinedAssignment\PowerAssignExpression::__instantiateUnchecked($node, $operator, $value);
					break;
				case 129:
					$operator = $this->tokens[$this->i++];
					$value = $this->expression(25);
					$node = Nodes\Expressions\CombinedAssignment\BitwiseAndAssignExpression::__instantiateUnchecked($node, $operator, $value);
					break;
				case 227:
					$operator = $this->tokens[$this->i++];
					$value = $this->expression(25);
					$node = Nodes\Expressions\CombinedAssignment\BitwiseOrAssignExpression::__instantiateUnchecked($node, $operator, $value);
					break;
				case 260:
					$operator = $this->tokens[$this->i++];
					$value = $this->expression(25);
					$node = Nodes\Expressions\CombinedAssignment\BitwiseXorAssignExpression::__instantiateUnchecked($node, $operator, $value);
					break;
				case 239:
					$operator = $this->tokens[$this->i++];
					$value = $this->expression(25);
					$node = Nodes\Expressions\CombinedAssignment\ShiftLeftAssignExpression::__instantiateUnchecked($node, $operator, $value);
					break;
				case 242:
					$operator = $this->tokens[$this->i++];
					$value = $this->expression(25);
					$node = Nodes\Expressions\CombinedAssignment\ShiftRightAssignExpression::__instantiateUnchecked($node, $operator, $value);
					break;
				case 145:
					$operator = $this->tokens[$this->i++];
					$value = $this->expression(25);
					$node = Nodes\Expressions\CombinedAssignment\CoalesceAssignExpression::__instantiateUnchecked($node, $operator, $value);
					break;
				case 224:
					$operator = $this->tokens[$this->i++];
					$name = $this->memberName();
					// expr->v -> access the property named 'v'
					// new expr->v -> instantiate the class named by expr->v
					// new expr->v() -> same, () is part of the NewExpression
					if ($this->types[$this->i] !== 105 || $newable)
					{
						$node = Nodes\Expressions\PropertyAccessExpression::__instantiateUnchecked($node, $operator, $name);
					}
					// expr->v() -> call the method named v
					else
					{
						$leftParenthesis = $this->tokens[$this->i++];
						$arguments = $this->arguments();
						$rightParenthesis = $this->read(106);
						$node = Nodes\Expressions\MethodCallExpression::__instantiateUnchecked($node, $operator, $name, $leftParenthesis, $arguments, $rightParenthesis);
					}
					break;
				case 105:
					// new expr() -> () always belongs to new
					if ($newable)
					{
						break 2;
					}
					$leftParenthesis = $this->tokens[$this->i++];
					$arguments = $this->arguments();
					$rightParenthesis = $this->read(106);
					$node = Nodes\Expressions\FunctionCallExpression::__instantiateUnchecked($node, $leftParenthesis, $arguments, $rightParenthesis);
					break;
				case 120:
					$leftBracket = $this->tokens[$this->i++];
					$index = $this->types[$this->i] === 121 ? null : $this->expression();
					$rightBracket = $this->read(121);
					$node = Nodes\Expressions\ArrayAccessExpression::__instantiateUnchecked($node, $leftBracket, $index, $rightBracket);
					break;
				case 163:
					$operator = $this->tokens[$this->i++];
					switch ($this->types[$this->i])
					{
						/** @noinspection PhpMissingBreakStatementInspection */
						case 245:
							doubleColonString:
							$name = $this->tokens[$this->i++];
							// new expr::a -> parse error
							// new expr::a() -> parse error
							if ($newable)
							{
								throw ParseException::unexpected($this->tokens[$this->i]);
							}
							// expr::a -> access constant 'a'
							else if ($this->types[$this->i] !== 105)
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
						case 257:
							$variable = Nodes\Expressions\NormalVariableExpression::__instantiateUnchecked($this->tokens[$this->i++]);
							foundVariable:
							// expr::$v -> access the static property named 'v'
							// new expr::$v -> instantiate the class named by the value of expr::$v
							// new expr::$v() -> same, () is part of the NewExpression
							if ($this->types[$this->i] !== 105 || $newable)
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
						case 102:
							$variable = $this->variableVariable();
							// all variations are the same as `expr::$v`, except the variable is variable
							goto foundVariable;

						/** @noinspection PhpMissingBreakStatementInspection */
						case 124:
							$memberName = $this->memberName();
							// expr::{expr} -> parse error
							// new expr::{expr} -> parse error
							// new expr::{expr}() -> parse error
							if ($this->types[$this->i] !== 105 || $newable)
							{
								throw ParseException::unexpected($this->tokens[$this->i]);
							}
							// expr::{expr2}() -> call static method named by the value of expr2
							else
							{
								goto staticCall;
							}

						/** @noinspection PhpMissingBreakStatementInspection */
						case 140:
							if ($this->types[$this->i + 1] === 105)
							{
								// expr::class() is a static method call of the static method named 'class'
								goto fudgeSpecialNameToString;
							}
							$keyword = $this->tokens[$this->i++];
							$node = Nodes\Expressions\ClassNameResolutionExpression::__instantiateUnchecked($node, $operator, $keyword);
							break;

							staticCall:
							// we jump here when we positively decide on a static call, and have set up $memberName
							/** @var Nodes\Helpers\MemberName $memberName */
							$leftParenthesis = $this->read(105);
							$arguments = $this->arguments();
							$rightParenthesis = $this->read(106);
							$node = Nodes\Expressions\StaticMethodCallExpression::__instantiateUnchecked($node, $operator, $memberName, $leftParenthesis, $arguments, $rightParenthesis);
							break;

						default:
							fudgeSpecialNameToString:
							if (\in_array($this->types[$this->i], T::STRINGY_KEYWORDS, true))
							{
								$this->tokens[$this->i]->_fudgeType(245);
								goto doubleColonString;
							}
							else
							{
								throw ParseException::unexpected($this->tokens[$this->i]);
							}
					}
					break;
				default:
					break 2;
			}
		}

		if ($operator = ($this->types[$this->i] === 152 ? $this->tokens[$this->i++] : null))
		{
			$node = Nodes\Expressions\PostDecrementExpression::__instantiateUnchecked($node, $operator);
		}
		else if ($operator = ($this->types[$this->i] === 193 ? $this->tokens[$this->i++] : null))
		{
			$node = Nodes\Expressions\PostIncrementExpression::__instantiateUnchecked($node, $operator);
		}

		return $node;
	}

	private function block(): Nodes\Block
	{
		if ($this->types[$this->i] === 124)
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
		$leftBrace = $this->tokens[$this->i++];
		$statements = [];
		while ($this->types[$this->i] !== 126)
		{
			$statements[] = $this->statement();
		}
		$rightBrace = $this->read(126);
		return Nodes\Blocks\RegularBlock::__instantiateUnchecked($leftBrace, $statements, $rightBrace);
	}

	/**
	 * @param array<int> $implicitEndKeywords
	 *
	 * TODO all blocks should accept all keywords as end and validation should handle the rest
	 */
	private function altBlock(int $endKeywordType, array $implicitEndKeywords = []): Nodes\Blocks\AlternativeFormatBlock
	{
		$colon = $this->read(113);
		$statements = [];
		while ($this->types[$this->i] !== $endKeywordType && !\in_array($this->types[$this->i], $implicitEndKeywords, true))
		{
			$statements[] = $this->statement();
		}

		if ($endKeyword = ($this->types[$this->i] === $endKeywordType ? $this->tokens[$this->i++] : null))
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
		while ($this->types[$this->i] !== 106)
		{
			$type = null;
			if ($this->types[$this->i] !== 104 && $this->types[$this->i] !== 165 && $this->types[$this->i] !== 257)
			{
				$type = $this->type();
			}
			$byReference = ($this->types[$this->i] === 104 ? $this->tokens[$this->i++] : null);
			$unpack = ($this->types[$this->i] === 165 ? $this->tokens[$this->i++] : null);
			$variable = $this->read(257);
			$default = $this->default_();
			$nodes[] =  Nodes\Helpers\Parameter::__instantiateUnchecked($type, $byReference, $unpack, $variable, $default);
			if (!($nodes[] = ($this->types[$this->i] === 109 ? $this->tokens[$this->i++] : null)))
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
		while ($this->types[$this->i] !== 106)
		{
			$unpack = ($this->types[$this->i] === 165 ? $this->tokens[$this->i++] : null);
			$value = $this->expression();
			$arguments[] = Nodes\Helpers\Argument::__instantiateUnchecked($unpack, $value);
			if (!($arguments[] = ($this->types[$this->i] === 109 ? $this->tokens[$this->i++] : null)))
			{
				break;
			}
		}
		return $arguments;
	}

	private function returnType(): ?Nodes\Helpers\ReturnType
	{
		if ($symbol = ($this->types[$this->i] === 113 ? $this->tokens[$this->i++] : null))
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
		$nullableSymbol = ($this->types[$this->i] === 118 ? $this->tokens[$this->i++] : null);
		switch ($this->types[$this->i])
		{
			case 130:
			case 137:
			case 244:
				$type = Nodes\Types\SpecialType::__instantiateUnchecked($this->tokens[$this->i++]);
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
		switch ($this->types[$this->i])
		{
			/** @noinspection PhpMissingBreakStatementInspection */
			case 221:
				$parts[] = $this->tokens[$this->i++];
			default:
				$parts[] = $this->read(245);
				while ($this->types[$this->i] === 221)
				{
					$parts[] = $this->tokens[$this->i++];
					$parts[] = $this->read(245);
				}
		}
		if (!$parts)
		{
			throw ParseException::unexpected($this->tokens[$this->i]);
		}
		return Nodes\Helpers\Name::__instantiateUnchecked($parts);
	}

	private function default_(): ?Nodes\Helpers\Default_
	{
		if ($symbol = ($this->types[$this->i] === 116 ? $this->tokens[$this->i++] : null))
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
		if ($this->types[$this->i] === 245)
		{
			return Nodes\Helpers\NormalMemberName::__instantiateUnchecked($this->tokens[$this->i++]);
		}
		else if ($this->types[$this->i] === 257)
		{
			$expression = Nodes\Expressions\NormalVariableExpression::__instantiateUnchecked($this->tokens[$this->i++]);
			return Nodes\Helpers\VariableMemberName::__instantiateUnchecked(null, $expression, null);
		}
		else if ($this->types[$this->i] === 102)
		{
			$expression = $this->variableVariable();
			return Nodes\Helpers\VariableMemberName::__instantiateUnchecked(null, $expression, null);
		}
		else if ($leftBrace = ($this->types[$this->i] === 124 ? $this->tokens[$this->i++] : null))
		{
			$expr = $this->expression();
			$rightBrace = $this->read(126);
			return Nodes\Helpers\VariableMemberName::__instantiateUnchecked($leftBrace, $expr, $rightBrace);
		}
		else
		{
			throw ParseException::unexpected($this->tokens[$this->i]);
		}
	}

	private function variableVariable(): Nodes\Expressions\VariableVariableExpression
	{
		$dollar = $this->read(102);
		$leftBrace = $rightBrace = null;
		if ($this->types[$this->i] === 257)
		{
			$expression = Nodes\Expressions\NormalVariableExpression::__instantiateUnchecked($this->tokens[$this->i++]);
		}
		else if ($this->types[$this->i] === 102)
		{
			$expression = $this->variableVariable();
		}
		else if ($leftBrace = ($this->types[$this->i] === 124 ? $this->tokens[$this->i++] : null))
		{
			$expression = $this->expression();
			$rightBrace = $this->read(126);
		}
		else
		{
			throw ParseException::unexpected($this->tokens[$this->i]);
		}

		return Nodes\Expressions\VariableVariableExpression::__instantiateUnchecked($dollar, $leftBrace, $expression, $rightBrace);
	}

	/** @return array<Node|null> */
	private function arrayItems(int $delimiter): array
	{
		$items = [];
		while ($this->types[$this->i] !== $delimiter)
		{
			$key = $unpack = $byReference = $value = null;

			if ($this->types[$this->i] !== 109 && $this->types[$this->i] !== $delimiter)
			{
				$unpack = ($this->types[$this->i] === 165 ? $this->tokens[$this->i++] : null);
				$byReference = ($this->types[$this->i] === 104 ? $this->tokens[$this->i++] : null);
				$value = $this->expression();

				if (!$unpack && !$byReference && $this->types[$this->i] === 161)
				{
					$key = Nodes\Helpers\Key::__instantiateUnchecked($value, $this->tokens[$this->i++]);
					$unpack = ($this->types[$this->i] === 165 ? $this->tokens[$this->i++] : null);
					$byReference = ($this->types[$this->i] === 104 ? $this->tokens[$this->i++] : null);
					$value = $this->expression();
				}
			}

			$items[] = Nodes\Expressions\ArrayItem::__instantiateUnchecked($key, $unpack, $byReference, $value);

			if (!($items[] = ($this->types[$this->i] === 109 ? $this->tokens[$this->i++] : null)))
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
		while ($this->types[$this->i] !== $delimiter)
		{
			if ($this->types[$this->i] === 169)
			{
				$parts[] = Nodes\Expressions\StringInterpolation\ConstantInterpolatedStringPart::__instantiateUnchecked($this->tokens[$this->i++]);
			}
			else if ($this->types[$this->i] === 257)
			{
				$var = Nodes\Expressions\NormalVariableExpression::__instantiateUnchecked($this->tokens[$this->i++]);
				if ($leftBracket = ($this->types[$this->i] === 120 ? $this->tokens[$this->i++] : null))
				{
					// TODO fix in lexer
					if ($this->types[$this->i] === 222)
					{
						$this->tokens[$this->i]->_fudgeType(210);
						$this->types[$this->i] = 210;
					}
					else if ($this->types[$this->i + 1] === 222)
					{
						$this->tokens[$this->i + 1]->_fudgeType(210);
						$this->types[$this->i + 1] = 210;
					}
					$index = $this->simpleExpression();
					$rightBracket = $this->read(121);
					$var = Nodes\Expressions\ArrayAccessExpression::__instantiateUnchecked($var, $leftBracket, $index, $rightBracket);
				}
				else if ($operator = ($this->types[$this->i] === 224 ? $this->tokens[$this->i++] : null))
				{
					$name = Nodes\Helpers\NormalMemberName::__instantiateUnchecked($this->read(245));
					$var = Nodes\Expressions\PropertyAccessExpression::__instantiateUnchecked($var, $operator, $name);
				}
				$parts[] = Nodes\Expressions\StringInterpolation\NormalInterpolatedStringVariable::__instantiateUnchecked(null, $var, null);
			}
			else if ($leftBrace = ($this->types[$this->i] === 124 ? $this->tokens[$this->i++] : null))
			{
				$expression = $this->simpleExpression();
				$rightBrace = $this->read(126);
				$parts[] = Nodes\Expressions\StringInterpolation\NormalInterpolatedStringVariable::__instantiateUnchecked($leftBrace, $expression, $rightBrace);
			}
			else if ($leftDelimiter = ($this->types[$this->i] === 160 ? $this->tokens[$this->i++] : null))
			{
				if ($this->types[$this->i] === 247)
				{
					$var = Nodes\Expressions\StringInterpolation\ConfusingInterpolatedStringVariableName::__instantiateUnchecked($this->tokens[$this->i++]);
					if ($this->types[$this->i] === 120)
					{
						$leftBracket = $this->tokens[$this->i++];
						$index = $this->expression();
						$rightBracket = $this->read(121);
						$var = Nodes\Expressions\ArrayAccessExpression::__instantiateUnchecked($var, $leftBracket, $index, $rightBracket);
					}
					$rightDelimiter = $this->read(126);
					$parts[] = Nodes\Expressions\StringInterpolation\ConfusingInterpolatedStringVariable::__instantiateUnchecked($leftDelimiter, $var, $rightDelimiter);
				}
				else
				{
					$name = $this->expression();
					$rightDelimiter = $this->read(126);
					$parts[] = Nodes\Expressions\StringInterpolation\VariableInterpolatedStringVariable::__instantiateUnchecked($leftDelimiter, $name, $rightDelimiter);
				}
			}
			else
			{
				throw ParseException::unexpected($this->tokens[$this->i]);
			}
		}
		return $parts;
	}
}
