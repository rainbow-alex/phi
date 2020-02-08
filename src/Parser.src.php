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

    /** @var (int|string)[] */
    private $types = [];

    public function __construct(int $phpVersion)
    {
        PhpVersion::validate($phpVersion);
        $this->phpVersion = $phpVersion;
    }

    /**
     * @throws ParseException
     */
    public function parse(?string $filename, string $source): Nodes\RootNode
    {
        $this->init((new Lexer($this->phpVersion))->lex($filename, $source));

        try
        {
            return $this->parseRoot();
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
            $ast = $this->statement(self::STMT_LEVEL_TOP);
            if ($this->peek()->getType() !== T::T_EOF)
            {
                throw ParseException::unexpected($this->peek());
            }
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
        // this is considerably faster than calling ->getType2() everywhere
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

    private function parseRoot(): Nodes\RootNode
    {
        $statements = [];

        while ($this->peek()->getType() !== T::T_EOF)
        {
            $statements[] = $this->statement(self::STMT_LEVEL_TOP);
        }

        $eof = $this->read(T::T_EOF);

        return Nodes\RootNode::__instantiateUnchecked($statements, $eof);
    }

    private const STMT_LEVEL_TOP = 1;
    private const STMT_LEVEL_NAMESPACE = 2;
    private const STMT_LEVEL_OTHER = 3;

    private function statement(int $level = self::STMT_LEVEL_OTHER): Nodes\Statement
    {
        if ($this->peek()->getType() === T::T_NAMESPACE)
        {
            if ($level !== self::STMT_LEVEL_TOP)
            {
                throw ParseException::unexpected($this->peek());
            }

            return $this->namespace_();
        }
        else if ($this->peek()->getType() === T::T_USE)
        {
            if ($level === self::STMT_LEVEL_OTHER)
            {
                throw ParseException::unexpected($this->peek());
            }

            return $this->use_();
        }
        else if ($this->peek()->getType() === T::T_ABSTRACT || $this->peek()->getType() === T::T_CLASS || $this->peek()->getType() === T::T_FINAL)
        {
            return $this->class_();
        }
        else if ($this->peek()->getType() === T::T_INTERFACE)
        {
            return $this->interface_();
        }
        else if ($this->peek()->getType() === T::T_TRAIT)
        {
            return $this->trait_();
        }
        else if ($this->peek()->getType() === T::T_BREAK)
        {
            $keyword = $this->read();

            $levels = null;
            if ($this->peek()->getType() === T::T_LNUMBER)
            {
                $levels = Nodes\Expressions\IntegerLiteral::__instantiateUnchecked($this->read());
            }

            $semiColon = $this->statementDelimiter();
            return Nodes\Statements\BreakStatement::__instantiateUnchecked($keyword, $levels, $semiColon);
        }
        else if ($this->peek()->getType() === T::T_CONST)
        {
            $keyword = $this->read();
            $name = $this->read(T::T_STRING);
            $equals = $this->read(T::S_EQUALS);
            $value = $this->expression();
            $delimiter = $this->statementDelimiter();
            return Nodes\Statements\ConstStatement::__instantiateUnchecked($keyword, $name, $equals, $value, $delimiter);
        }
        else if ($this->peek()->getType() === T::T_CONTINUE)
        {
            $keyword = $this->read();

            $levels = null;
            if ($this->peek()->getType() === T::T_LNUMBER)
            {
                $levels = Nodes\Expressions\IntegerLiteral::__instantiateUnchecked($this->read());
            }

            $semiColon = $this->statementDelimiter();
            return Nodes\Statements\ContinueStatement::__instantiateUnchecked($keyword, $levels, $semiColon);
        }
        else if ($this->peek()->getType() === T::T_DECLARE)
        {
            $keyword = $this->read();
            $leftParenthesis = $this->read(T::S_LEFT_PAREN);
            $directives = [];
            do
            {
                $directiveKey = $this->read(T::T_STRING);
                $directiveEquals = $this->read(T::S_EQUALS);
                $directiveValue = $this->expression();
                $directives[] = Nodes\Statements\DeclareDirective::__instantiateUnchecked($directiveKey, $directiveEquals, $directiveValue);
            }
            while ($this->peek()->getType() === T::S_COMMA && $directives[] = $this->read());
            $rightParenthesis = $this->read(T::S_RIGHT_PAREN);
            if (!$this->endOfStatement())
            {
                $block = $this->block($level);
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
        }
        else if ($this->peek()->getType() === T::T_DO)
        {
            $keyword1 = $this->read();
            $block = $this->block();
            $keyword2 = $this->read(T::T_WHILE);
            $leftParenthesis = $this->read(T::S_LEFT_PAREN);
            $test = $this->expression();
            $rightParenthesis = $this->read(T::S_RIGHT_PAREN);
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
        }
        else if ($this->peek()->getType() === T::T_ECHO || $this->peek()->getType() === T::T_OPEN_TAG_WITH_ECHO)
        {
            $keyword = $this->read();
            $expressions = [];
            $expressions[] = $this->expression();
            while ($this->peek()->getType() === T::S_COMMA)
            {
                $expressions[] = $this->read();
                $expressions[] = $this->expression();
            }
            $semiColon = $this->statementDelimiter();
            return Nodes\Statements\EchoStatement::__instantiateUnchecked($keyword, $expressions, $semiColon);
        }
        else if ($this->peek()->getType() === T::T_FOR)
        {
            $keyword = $this->read();
            $leftParenthesis = $this->read(T::S_LEFT_PAREN);

            $init = [];
            if ($this->peek()->getType() !== T::S_SEMICOLON)
            {
                $init[] = $this->expression();
                while ($this->peek()->getType() === T::S_COMMA)
                {
                    $init[] = $this->read();
                    $init[] = $this->expression();
                }
            }

            $separator1 = $this->peek()->getType() === T::S_COMMA ? $this->read() : $this->read(T::S_SEMICOLON);

            $test = [];
            if ($this->peek()->getType() !== T::S_SEMICOLON)
            {
                $test[] = $this->expression();
                while ($this->peek()->getType() === T::S_COMMA)
                {
                    $test[] = $this->read();
                    $test[] = $this->expression();
                }
            }

            $separator2 = $this->peek()->getType() === T::S_COMMA ? $this->read() : $this->read(T::S_SEMICOLON);

            $step = [];
            if ($this->peek()->getType() !== T::S_RIGHT_PAREN)
            {
                $step[] = $this->expression();
                while ($this->peek()->getType() === T::S_COMMA)
                {
                    $step[] = $this->read();
                    $step[] = $this->expression();
                }
            }

            $rightParenthesis = $this->read(T::S_RIGHT_PAREN);

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
        }
        else if ($this->peek()->getType() === T::T_FOREACH)
        {
            $keyword = $this->read();
            $leftParenthesis = $this->read(T::S_LEFT_PAREN);
            $iterable = $this->expression();
            $as = $this->read(T::T_AS);

            $key = $byReference = null;
            $byReference = $this->peek()->getType() === T::S_AMPERSAND ? $this->read() : null;
            $value = $this->simpleExpression();

            if ($this->peek()->getType() === T::T_DOUBLE_ARROW)
            {
                if ($byReference)
                {
                    throw ParseException::unexpected($this->peek());
                }

                $key = Nodes\Helpers\Key::__instantiateUnchecked($value, $this->read());

                if ($this->peek()->getType() === T::S_AMPERSAND)
                {
                    $byReference = $this->read();
                }

                $value = $this->simpleExpression();
            }

            $rightParenthesis = $this->read(T::S_RIGHT_PAREN);

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
        }
        else if (
            $this->peek()->getType() === T::T_FUNCTION
            && (
                $this->peek(1)->getType() === T::T_STRING
                || $this->peek(1)->getType() === T::S_AMPERSAND && $this->peek(2)->getType() === T::T_STRING
            )
        )
        {
            $keyword = $this->read();
            $byReference = $this->peek()->getType() === T::S_AMPERSAND ? $this->read() : null;
            $name = $this->read();
            $leftParenthesis = $this->read(T::S_LEFT_PAREN);
            $parameters = $this->parameters();
            $rightParenthesis = $this->read(T::S_RIGHT_PAREN);
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
        }
        else if ($this->peek()->getType() === T::T_GOTO)
        {
            $keyword = $this->read();
            $label = $this->read(T::T_STRING);
            $semiColon = $this->statementDelimiter();
            return Nodes\Statements\GotoStatement::__instantiateUnchecked(
                $keyword,
                $label,
                $semiColon
            );
        }
        else if ($this->peek()->getType() === T::T_IF)
        {
            $keyword = $this->read();
            $leftParenthesis = $this->read(T::S_LEFT_PAREN);
            $test = $this->expression();
            $rightParenthesis = $this->read(T::S_RIGHT_PAREN);
            if ($altSyntax = ($this->peek()->getType() === T::S_COLON))
            {
                $block = $this->altBlock(T::T_ENDIF, [T::T_ELSE, T::T_ELSEIF]);
            }
            else
            {
                $block = $this->block();
            }
            $elseifs = [];
            while ($this->peek()->getType() === T::T_ELSEIF)
            {
                $elseifKeyword = $this->read();
                $elseifLeftParenthesis = $this->read(T::S_LEFT_PAREN);
                $elseifTest = $this->expression();
                $elseifRightParenthesis = $this->read(T::S_RIGHT_PAREN);
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
            if ($this->peek()->getType() === T::T_ELSE)
            {
                $elseKeyword = $this->read();
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
        }
        else if ($this->peek()->getType() === T::T_RETURN)
        {
            $keyword = $this->read();
            $expression = !$this->endOfStatement() ? $this->expression() : null;
            $semiColon = $this->statementDelimiter();
            return Nodes\Statements\ReturnStatement::__instantiateUnchecked($keyword, $expression, $semiColon);
        }
        else if ($this->peek()->getType() === T::T_STATIC && $this->peek(1)->getType() === T::T_VARIABLE)
        {
            $keyword = $this->read();
            $variables = [];
            do
            {
                $variable = $this->read(T::T_VARIABLE);
                $default = $this->default_();
                $variables[] = Nodes\Statements\StaticVariable::__instantiateUnchecked($variable, $default);
            }
            while ($this->peek()->getType() === T::S_COMMA && $variables[] = $this->read());
            $semiColon = $this->statementDelimiter();
            return Nodes\Statements\StaticVariableStatement::__instantiateUnchecked($keyword, $variables, $semiColon);
        }
        else if ($this->peek()->getType() === T::T_SWITCH)
        {
            $keyword = $this->read();

            $leftParenthesis = $this->read(T::S_LEFT_PAREN);
            $value = $this->expression();
            $rightParenthesis = $this->read(T::S_RIGHT_PAREN);

            $leftBrace = $this->read(T::S_LEFT_CURLY_BRACE);

            $cases = [];
            while ($this->peek()->getType() !== T::S_RIGHT_CURLY_BRACE)
            {
                if ($this->peek()->getType() === T::T_DEFAULT)
                {
                    $caseKeyword = $this->read();
                }
                else
                {
                    $caseKeyword = $this->read(T::T_CASE);
                }
                $caseValue = null;
                if ($this->peek()->getType() !== T::S_COLON)
                {
                    $caseValue = $this->expression();
                }
                $caseDelimiter = $this->peek()->getType() === T::S_SEMICOLON ? $this->read() : $this->read(T::S_COLON);
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
        }
        else if ($this->peek()->getType() === T::T_THROW)
        {
            $keyword = $this->read();
            $expression = $this->expression();
            $semiColon = $this->statementDelimiter();
            return Nodes\Statements\ThrowStatement::__instantiateUnchecked($keyword, $expression, $semiColon);
        }
        else if ($this->peek()->getType() === T::T_TRY)
        {
            $keyword = $this->read();
            $block = $this->regularBlock();
            $catches = [];
            while ($this->peek()->getType() === T::T_CATCH)
            {
                $catchKeyword = $this->read();
                $catchLeftParenthesis = $this->read(T::S_LEFT_PAREN);
                $catchTypes = [];
                $catchTypes[] = Nodes\Types\NamedType::__instantiateUnchecked($this->name());
                while ($this->peek()->getType() === T::S_VERTICAL_BAR)
                {
                    $catchTypes[] = $this->read();
                    $catchTypes[] = Nodes\Types\NamedType::__instantiateUnchecked($this->name());
                }
                $catchVariable = $this->read(T::T_VARIABLE);
                $catchRightParenthesis = $this->read(T::S_RIGHT_PAREN);
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
            if ($this->peek()->getType() === T::T_FINALLY)
            {
                $finallyKeyword = $this->read();
                $finallyBlock = $this->regularBlock();
                $finally = Nodes\Statements\Finally_::__instantiateUnchecked($finallyKeyword, $finallyBlock);
            }
            return Nodes\Statements\TryStatement::__instantiateUnchecked($keyword, $block, $catches, $finally);
        }
        else if ($this->peek()->getType() === T::T_WHILE)
        {
            $keyword = $this->read();
            $leftParenthesis = $this->read(T::S_LEFT_PAREN);
            $test = $this->expression();
            $rightParenthesis = $this->read(T::S_RIGHT_PAREN);

            $block = $this->peek()->getType() === T::S_COLON ? $this->altBlock(T::T_ENDWHILE) : $this->block();

            return Nodes\Statements\WhileStatement::__instantiateUnchecked($keyword, $leftParenthesis, $test, $rightParenthesis, $block);
        }
        else if ($this->peek()->getType() === T::T_UNSET)
        {
            $keyword = $this->read();
            $leftParenthesis = $this->read(T::S_LEFT_PAREN);
            $expressions = [];
            do
            {
                $expressions[] = $this->simpleExpression();
            }
            while ($this->peek()->getType() === T::S_COMMA && $expressions[] = $this->read());
            $rightParenthesis = $this->read(T::S_RIGHT_PAREN);
            $semiColon = $this->statementDelimiter();
            return Nodes\Statements\UnsetStatement::__instantiateUnchecked($keyword, $leftParenthesis, $expressions, $rightParenthesis, $semiColon);
        }
        else if ($this->peek()->getType() === T::S_LEFT_CURLY_BRACE)
        {
            return Nodes\Statements\BlockStatement::__instantiateUnchecked($this->regularBlock());
        }
        else if ($this->peek()->getType() === T::T_STRING && $this->peek(1)->getType() === T::S_COLON)
        {
            return Nodes\Statements\LabelStatement::__instantiateUnchecked($this->read(), $this->read());
        }
        else if ($this->peek()->getType() === T::T_INLINE_HTML || $this->peek()->getType() === T::T_OPEN_TAG)
        {
            $content = $this->peek()->getType() === T::T_INLINE_HTML ? $this->read() : null;
            $open = $this->peek()->getType() === T::T_OPEN_TAG ? $this->read() :     null;
            return Nodes\Statements\InlineHtmlStatement::__instantiateUnchecked($content, $open);
        }
        else if ($this->peek()->getType() === T::S_SEMICOLON || $this->peek()->getType() === T::T_CLOSE_TAG)
        {
            return Nodes\Statements\NopStatement::__instantiateUnchecked($this->read());
        }
        else
        {
            $expression = $this->expression();
            $semiColon = $this->statementDelimiter();
            return Nodes\Statements\ExpressionStatement::__instantiateUnchecked($expression, $semiColon);
        }
    }

    private function namespace_(): Nodes\Statements\NamespaceStatement
    {
        $keyword = $this->read(T::T_NAMESPACE);
        $name = null;
        if ($this->peek()->getType() !== T::S_LEFT_CURLY_BRACE)
        {
            $name = $this->name();
        }
        if ($this->peek()->getType() === T::S_LEFT_CURLY_BRACE)
        {
            $block = $this->regularBlock(self::STMT_LEVEL_NAMESPACE);
            $semiColon = null;
        }
        else
        {
            $block = null;
            $semiColon = $this->statementDelimiter();
        }
        return Nodes\Statements\NamespaceStatement::__instantiateUnchecked($keyword, $name, $block, $semiColon);
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
            if ($this->peek()->getType() === T::T_AS)
            {
                $aliasKeyword = $this->read();
                $alias = Nodes\Statements\UseAlias::__instantiateUnchecked($aliasKeyword, $this->read(T::T_STRING));
            }

            $declarations[] = Nodes\Statements\UseDeclaration::__instantiateUnchecked($name, $alias);

            if ($this->peek()->getType() === T::S_COMMA)
            {
                $declarations[] = $this->read();
            }
            else
            {
                $declarations[] = null;
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

    private function class_(): Nodes\Oop\ClassDeclaration
    {
        $modifiers = [];
        while (in_array($this->peek()->getType(), [T::T_ABSTRACT, T::T_FINAL], true))
        {
            $modifiers[] = $this->read();
        }
        $keyword = $this->read(T::T_CLASS);
        $name = $this->read(T::T_STRING);
        if ($this->peek()->getType() === T::T_EXTENDS)
        {
            $extendsKeyword = $this->read();
            $extendsNames = [];
            $extendsNames[] = $this->name();
            $extends = Nodes\Oop\Extends_::__instantiateUnchecked($extendsKeyword, $extendsNames);
        }
        else
        {
            $extends = null;
        }
        if ($this->peek()->getType() === T::T_IMPLEMENTS)
        {
            $implementsKeyword = $this->read();
            $implementsNames = [];
            $implementsNames[] = $this->name();
            while ($this->peek()->getType() === T::S_COMMA)
            {
                $implementsNames[] = $this->read();
                $implementsNames[] = $this->name();
            }
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
            $members[] = $this->classLikeMember();
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
    }

    private function interface_(): Nodes\Oop\InterfaceDeclaration
    {
        $keyword = $this->read(T::T_INTERFACE);
        $name = $this->read(T::T_STRING);
        if ($this->peek()->getType() === T::T_EXTENDS)
        {
            $extendsKeyword = $this->read();
            $extendsNames = [];
            $extendsNames[] = $this->name();
            while ($this->peek()->getType() === T::S_COMMA)
            {
                $extendsNames[] = $this->read();
                $extendsNames[] = $this->name();
            }
            $extends = Nodes\Oop\Extends_::__instantiateUnchecked($extendsKeyword, $extendsNames);
        }
        else
        {
            $extends = null;
        }
        $leftBrace = $this->read(T::S_LEFT_CURLY_BRACE);
        $members = [];
        while ($this->peek()->getType() !== T::S_RIGHT_CURLY_BRACE)
        {
            $members[] = $this->classLikeMember();
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
    }

    private function trait_(): Nodes\Oop\TraitDeclaration
    {
        $keyword = $this->read(T::T_TRAIT);
        $name = $this->read(T::T_STRING);
        $leftBrace = $this->read(T::S_LEFT_CURLY_BRACE);
        $members = [];
        while ($this->peek()->getType() !== T::S_RIGHT_CURLY_BRACE)
        {
            $members[] = $this->classLikeMember();
        }
        $rightBrace = $this->read(T::S_RIGHT_CURLY_BRACE);
        return Nodes\Oop\TraitDeclaration::__instantiateUnchecked(
            $keyword,
            $name,
            $leftBrace,
            $members,
            $rightBrace
        );
    }

    private function classLikeMember(): Nodes\Oop\OopMember
    {
        $modifiers = [];
        while (\in_array($this->peek()->getType(), [T::T_ABSTRACT, T::T_FINAL, T::T_PUBLIC, T::T_PROTECTED, T::T_PRIVATE, T::T_STATIC], true))
        {
            $modifiers[] = $this->read();
        }

        if ($this->peek()->getType() === T::T_FUNCTION)
        {
            $keyword = $this->read();
            $byReference = $this->peek()->getType() === T::S_AMPERSAND ? $this->read() : null;
            if ($this->peek()->getType() === T::T_STRING)
            {
                $name = $this->read();
            }
            else if (\in_array($this->peek()->getType(), T::SEMI_RESERVED, true))
            {
                $this->peek()->_fudgeType(T::T_STRING);
                $name = $this->read();
            }
            else
            {
                throw ParseException::unexpected($this->peek());
            }
            $leftParenthesis = $this->read(T::S_LEFT_PAREN);
            $parameters = $this->parameters();
            $rightParenthesis = $this->read(T::S_RIGHT_PAREN);
            $returnType = $this->returnType();
            if ($this->peek()->getType() === T::S_SEMICOLON)
            {
                $body = null;
                $semiColon = $this->read();
            }
            else
            {
                $body = $this->regularBlock();
                $semiColon = null;
            }
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
        else if ($this->peek()->getType() === T::T_CONST)
        {
            $keyword = $this->read();
            if ($this->peek()->getType() === T::T_STRING)
            {
                $name = $this->read();
            }
            else if (\in_array($this->peek()->getType(), T::SEMI_RESERVED, true))
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
        else if ($this->peek()->getType() === T::T_USE)
        {
            $keyword = $this->read();
            $names = [$this->name()];
            while ($this->peek()->getType() === T::S_COMMA)
            {
                $names[] = $this->read();
                $names[] = $this->name();
            }
            $leftBrace = $rightBrace = $semiColon = null;
            $modifications = [];
            if ($this->peek()->getType() === T::S_LEFT_CURLY_BRACE)
            {
                $leftBrace = $this->read();
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
                if ($this->peek()->getType() === T::T_INSTEADOF)
                {
                    $modKeyword = $this->read();
                    $excluded = [$this->name()];
                    while ($this->peek()->getType() === T::S_COMMA)
                    {
                        $excluded[] = $this->read();
                        $excluded[] = $this->name();
                    }
                    $modSemi = $this->read(T::S_SEMICOLON);
                    $modifications[] = Nodes\Oop\TraitUseInsteadof::__instantiateUnchecked($ref, $modKeyword, $excluded, $modSemi);
                }
                else if ($this->peek()->getType() === T::T_AS)
                {
                    $modKeyword = $this->read();
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
        else
        {
            $variable = $this->read(T::T_VARIABLE);
            $default = $this->default_();
            $semiColon = $this->read(T::S_SEMICOLON);
            return Nodes\Oop\Property::__instantiateUnchecked($modifiers, $variable, $default, $semiColon);
        }
    }

    private function endOfStatement(): bool
    {
        $t = $this->peek()->getType();
        return $t === T::S_SEMICOLON || $t === T::T_CLOSE_TAG || $t === T::T_EOF;
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

    private function expression(int $minPrecedence = 0): Nodes\Expression
    {
        $left = $this->simpleExpression();

        if ($this->peek()->getType() === T::S_EQUALS)
        {
            if ($this->peek(1)->getType() === T::S_AMPERSAND)
            {
                $operator1 = $this->read();
                $operator2 = $this->read();
                $right = $this->simpleExpression();
                $left = Nodes\Expressions\AliasingExpression::__instantiateUnchecked($left, $operator1, $operator2, $right);
            }
            else
            {
                $operator = $this->read();
                $value = $this->expression(Expr::PRECEDENCE_TERNARY);
                $left = Nodes\Expressions\AssignmentExpression::__instantiateUnchecked($left, $operator, $value);
            }
        }
        else if (\in_array($this->peek()->getType(), T::COMBINED_ASSIGNMENTS, true))
        {
            $operator = $this->read();
            $value = $this->expression(Expr::PRECEDENCE_TERNARY);
            $left = Nodes\Expressions\CombinedAssignmentExpression::__instantiateUnchecked($left, $operator, $value);
        }

        while (true)
        {
            if ($minPrecedence <= Expr::PRECEDENCE_POW && $this->peek()->getType() === T::T_POW)
            {
                $operator = $this->read();
                $right = $this->expression(Expr::PRECEDENCE_POW);
                $left = Nodes\Expressions\PowerExpression::__instantiateUnchecked($left, $operator, $right);
            }
            else if ($minPrecedence <= Expr::PRECEDENCE_INSTANCEOF && $this->peek()->getType() === T::T_INSTANCEOF)
            {
                $operator = $this->read();
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
                if ($this->peek()->getType() === T::S_ASTERISK)
                {
                    $operator = $this->read();
                    $right = $this->expression(Expr::PRECEDENCE_MUL + 1);
                    $left = Nodes\Expressions\MultiplyExpression::__instantiateUnchecked($left, $operator, $right);
                }
                else if ($this->peek()->getType() === T::S_FORWARD_SLASH)
                {
                    $operator = $this->read();
                    $right = $this->expression(Expr::PRECEDENCE_MUL + 1);
                    $left = Nodes\Expressions\DivideExpression::__instantiateUnchecked($left, $operator, $right);
                }
                else if ($this->peek()->getType() === T::S_MODULO)
                {
                    $operator = $this->read();
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
                if ($this->peek()->getType() === T::S_PLUS)
                {
                    $operator = $this->read();
                    $right = $this->expression(Expr::PRECEDENCE_ADD + 1);
                    $left = Nodes\Expressions\AddExpression::__instantiateUnchecked($left, $operator, $right);
                }
                else if ($this->peek()->getType() === T::S_MINUS)
                {
                    $operator = $this->read();
                    $right = $this->expression(Expr::PRECEDENCE_ADD + 1);
                    $left = Nodes\Expressions\SubtractExpression::__instantiateUnchecked($left, $operator, $right);
                }
                else if ($this->peek()->getType() === T::S_DOT)
                {
                    $operator = $this->read();
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
                if ($this->peek()->getType() === T::T_SL)
                {
                    $operator = $this->read();
                    $right = $this->expression(Expr::PRECEDENCE_SHIFT + 1);
                    $left = Nodes\Expressions\ShiftLeftExpression::__instantiateUnchecked($left, $operator, $right);
                }
                else if ($this->peek()->getType() === T::T_SR)
                {
                    $operator = $this->read();
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
            if ($this->peek()->getType() === T::S_LT)
            {
                $operator = $this->read();
                $right = $this->expression(Expr::PRECEDENCE_COMPARISON2 + 1);
                $left = Nodes\Expressions\LessThanExpression::__instantiateUnchecked($left, $operator, $right);
            }
            else if ($this->peek()->getType() === T::T_IS_SMALLER_OR_EQUAL)
            {
                $operator = $this->read();
                $right = $this->expression(Expr::PRECEDENCE_COMPARISON2 + 1);
                $left = Nodes\Expressions\LessThanOrEqualsExpression::__instantiateUnchecked($left, $operator, $right);
            }
            else if ($this->peek()->getType() === T::S_GT)
            {
                $operator = $this->read();
                $right = $this->expression(Expr::PRECEDENCE_COMPARISON2 + 1);
                $left = Nodes\Expressions\GreaterThanExpression::__instantiateUnchecked($left, $operator, $right);
            }
            else if ($this->peek()->getType() === T::T_IS_GREATER_OR_EQUAL)
            {
                $operator = $this->read();
                $right = $this->expression(Expr::PRECEDENCE_COMPARISON2 + 1);
                $left = Nodes\Expressions\GreaterThanOrEqualsExpression::__instantiateUnchecked($left, $operator, $right);
            }
        }

        if ($minPrecedence <= Expr::PRECEDENCE_COMPARISON1)
        {
            if ($this->peek()->getType() === T::T_IS_IDENTICAL)
            {
                $operator = $this->read();
                $right = $this->expression(Expr::PRECEDENCE_COMPARISON1 + 1);
                $left = Nodes\Expressions\IsIdenticalExpression::__instantiateUnchecked($left, $operator, $right);
            }
            else if ($this->peek()->getType() === T::T_IS_NOT_IDENTICAL)
            {
                $operator = $this->read();
                $right = $this->expression(Expr::PRECEDENCE_COMPARISON1 + 1);
                $left = Nodes\Expressions\IsNotIdenticalExpression::__instantiateUnchecked($left, $operator, $right);
            }
            else if ($this->peek()->getType() === T::T_IS_EQUAL)
            {
                $operator = $this->read();
                $right = $this->expression(Expr::PRECEDENCE_COMPARISON1 + 1);
                $left = Nodes\Expressions\IsEqualExpression::__instantiateUnchecked($left, $operator, $right);
            }
            else if ($this->peek()->getType() === T::T_IS_NOT_EQUAL)
            {
                $operator = $this->read();
                $right = $this->expression(Expr::PRECEDENCE_COMPARISON1 + 1);
                $left = Nodes\Expressions\IsNotEqualExpression::__instantiateUnchecked($left, $operator, $right);
            }
            else if ($this->peek()->getType() === T::T_SPACESHIP)
            {
                $operator = $this->read();
                $right = $this->expression(Expr::PRECEDENCE_COMPARISON1 + 1);
                $left = Nodes\Expressions\SpaceshipExpression::__instantiateUnchecked($left, $operator, $right);
            }
        }

        if ($minPrecedence <= Expr::PRECEDENCE_BITWISE_AND)
        {
            while ($this->peek()->getType() === T::S_AMPERSAND)
            {
                $operator = $this->read();
                $right = $this->expression(Expr::PRECEDENCE_BITWISE_AND + 1);
                $left = Nodes\Expressions\BitwiseAndExpression::__instantiateUnchecked($left, $operator, $right);
            }
        }

        if ($minPrecedence <= Expr::PRECEDENCE_BITWISE_XOR)
        {
            while ($this->peek()->getType() === T::S_CARET)
            {
                $operator = $this->read();
                $right = $this->expression(Expr::PRECEDENCE_BITWISE_XOR + 1);
                $left = Nodes\Expressions\BitwiseXorExpression::__instantiateUnchecked($left, $operator, $right);
            }
        }

        if ($minPrecedence <= Expr::PRECEDENCE_BITWISE_OR)
        {
            while ($this->peek()->getType() === T::S_VERTICAL_BAR)
            {
                $operator = $this->read();
                $right = $this->expression(Expr::PRECEDENCE_BITWISE_OR + 1);
                $left = Nodes\Expressions\BitwiseOrExpression::__instantiateUnchecked($left, $operator, $right);
            }
        }

        if ($minPrecedence <= Expr::PRECEDENCE_SYMBOL_AND)
        {
            while ($this->peek()->getType() === T::T_BOOLEAN_AND)
            {
                $operator = $this->read();
                $right = $this->expression(Expr::PRECEDENCE_SYMBOL_AND + 1);
                $left = Nodes\Expressions\SymbolAndExpression::__instantiateUnchecked($left, $operator, $right);
            }
        }

        if ($minPrecedence <= Expr::PRECEDENCE_SYMBOL_OR)
        {
            while ($this->peek()->getType() === T::T_BOOLEAN_OR)
            {
                $operator = $this->read();
                $right = $this->expression(Expr::PRECEDENCE_SYMBOL_OR + 1);
                $left = Nodes\Expressions\SymbolOrExpression::__instantiateUnchecked($left, $operator, $right);
            }
        }

        if ($minPrecedence <= Expr::PRECEDENCE_COALESCE)
        {
            if ($this->peek()->getType() === T::T_COALESCE)
            {
                $operator = $this->read();
                $right = $this->expression(Expr::PRECEDENCE_COALESCE);
                $left = Nodes\Expressions\CoalesceExpression::__instantiateUnchecked($left, $operator, $right);
            }
        }

        if ($minPrecedence <= Expr::PRECEDENCE_TERNARY)
        {
            while ($this->peek()->getType() === T::S_QUESTION_MARK)
            {
                $questionMark = $this->read();
                // note: no precedence is passed here, delimiters on both sides allow for e.g. 1 ? 2 and 3 : 4
                $then = $this->peek()->getType() !== T::S_COLON ? $this->expression() : null;
                $colon = $this->read(T::S_COLON);
                $else = $this->expression(Expr::PRECEDENCE_TERNARY + 1);
                $left = Nodes\Expressions\TernaryExpression::__instantiateUnchecked($left, $questionMark, $then, $colon, $else);
            }
        }

        if ($minPrecedence <= Expr::PRECEDENCE_KEYWORD_AND)
        {
            while ($this->peek()->getType() === T::T_LOGICAL_AND)
            {
                $operator = $this->read();
                $right = $this->expression(Expr::PRECEDENCE_KEYWORD_AND + 1);
                $left = Nodes\Expressions\KeywordAndExpression::__instantiateUnchecked($left, $operator, $right);
            }
        }

        if ($minPrecedence <= Expr::PRECEDENCE_KEYWORD_XOR)
        {
            while ($this->peek()->getType() === T::T_LOGICAL_XOR)
            {
                $operator = $this->read();
                $right = $this->expression(Expr::PRECEDENCE_KEYWORD_XOR + 1);
                $left = Nodes\Expressions\KeywordXorExpression::__instantiateUnchecked($left, $operator, $right);
            }
        }

        if ($minPrecedence <= Expr::PRECEDENCE_KEYWORD_OR)
        {
            while ($this->peek()->getType() === T::T_LOGICAL_OR)
            {
                $operator = $this->read();
                $right = $this->expression(Expr::PRECEDENCE_KEYWORD_OR + 1);
                $left = Nodes\Expressions\KeywordOrExpression::__instantiateUnchecked($left, $operator, $right);
            }
        }

        return $left;
    }

    private function simpleExpression(bool $newable = false): Nodes\Expression
    {
        if ($this->peek()->getType() === T::T_VARIABLE)
        {
            $node = Nodes\Expressions\NormalVariableExpression::__instantiateUnchecked($this->read());
        }
        else if ($this->peek()->getType() === T::S_DOLLAR)
        {
            $node = $this->variableVariable();
        }
        else if ($this->peek()->getType() === T::T_STRING || $this->peek()->getType() === T::T_NS_SEPARATOR)
        {
            $node = Nodes\Expressions\NameExpression::__instantiateUnchecked($this->name());
        }
        else if ($this->peek()->getType() === T::T_STATIC && $this->peek(1)->getType() !== T::T_FUNCTION)
        {
            $node = Nodes\Expressions\NameExpression::__instantiateUnchecked($this->name());
        }
        else if ($newable)
        {
            throw ParseException::unexpected($this->peek());
        }
        else if ($this->peek()->getType() === T::T_CONSTANT_ENCAPSED_STRING)
        {
            $node = Nodes\Expressions\SingleQuotedStringLiteral::__instantiateUnchecked($this->read());
        }
        else if ($this->peek()->getType() === T::S_DOUBLE_QUOTE)
        {
            $leftDelimiter = $this->read();
            $parts = $this->stringParts(T::S_DOUBLE_QUOTE);
            $rightDelimiter = $this->read();
            $node = Nodes\Expressions\DoubleQuotedStringLiteral::__instantiateUnchecked($leftDelimiter, $parts, $rightDelimiter);
        }
        else if ($this->peek()->getType() === T::T_START_HEREDOC)
        {
            $leftDelimiter = $this->read();
            if ($leftDelimiter->getSource()[3] === "'")
            {
                if ($this->peek()->getType() === T::T_END_HEREDOC)
                {
                    $content = null;
                    $rightDelimiter = $this->read();
                }
                else
                {
                    $content = $this->read(T::T_ENCAPSED_AND_WHITESPACE);
                    $rightDelimiter = $this->read(T::T_END_HEREDOC);
                }
                $node = Nodes\Expressions\NowdocStringLiteral::__instantiateUnchecked($leftDelimiter, $content, $rightDelimiter);
            }
            else
            {
                $parts = $this->stringParts(T::T_END_HEREDOC);
                $rightDelimiter = $this->read();
                $node = Nodes\Expressions\HeredocStringLiteral::__instantiateUnchecked($leftDelimiter, $parts, $rightDelimiter);
            }
        }
        else if ($this->peek()->getType() === T::T_LNUMBER)
        {
            $node = Nodes\Expressions\IntegerLiteral::__instantiateUnchecked($this->read());
        }
        else if ($this->peek()->getType() === T::T_DNUMBER)
        {
            $node = Nodes\Expressions\FloatLiteral::__instantiateUnchecked($this->read());
        }
        else if ($this->peek()->getType() === T::T_NEW)
        {
            $keyword = $this->read();
            $class = $this->simpleExpression(true);
            $leftParenthesis = $rightParenthesis = null;
            $arguments = [];
            if ($this->peek()->getType() === T::S_LEFT_PAREN)
            {
                $leftParenthesis = $this->read();
                $arguments = $this->arguments();
                $rightParenthesis = $this->read(T::S_RIGHT_PAREN);
            }
            $node = Nodes\Expressions\NewExpression::__instantiateUnchecked($keyword, $class, $leftParenthesis, $arguments, $rightParenthesis);
        }
        else if ($this->peek()->getType() === T::S_LEFT_SQUARE_BRACKET)
        {
            $leftBracket = $this->read();
            $items = $this->arrayItems(T::S_RIGHT_SQUARE_BRACKET);
            $rightBracket = $this->read(T::S_RIGHT_SQUARE_BRACKET);
            $node = Nodes\Expressions\ShortArrayExpression::__instantiateUnchecked($leftBracket, $items, $rightBracket);
        }
        else if ($this->peek()->getType() === T::T_ARRAY)
        {
            $keyword = $this->read();
            $leftParenthesis = $this->read(T::S_LEFT_PAREN);
            $items = $this->arrayItems(T::S_RIGHT_PAREN);
            $rightParenthesis = $this->read(T::S_RIGHT_PAREN);
            $node = Nodes\Expressions\LongArrayExpression::__instantiateUnchecked($keyword, $leftParenthesis, $items, $rightParenthesis);
        }
        else if ($this->peek()->getType() === T::S_LEFT_PAREN)
        {
            $leftParenthesis = $this->read();
            $expression = $this->expression();
            $rightParenthesis = $this->read(T::S_RIGHT_PAREN);
            $node = Nodes\Expressions\ParenthesizedExpression::__instantiateUnchecked($leftParenthesis, $expression, $rightParenthesis);
        }
        else if ($this->peek()->getType() === T::T_ISSET)
        {
            $keyword = $this->read();
            $leftParenthesis = $this->read(T::S_LEFT_PAREN);
            $items = [];
            do
            {
                $items[] = $this->expression();
            }
            while ($this->peek()->getType() === T::S_COMMA && $items[] = $this->read());
            $rightParenthesis = $this->read(T::S_RIGHT_PAREN);
            $node = Nodes\Expressions\IssetExpression::__instantiateUnchecked($keyword, $leftParenthesis, $items, $rightParenthesis);
        }
        else if ($this->peek()->getType() === T::T_EMPTY)
        {
            $keyword = $this->read();
            $leftParenthesis = $this->read(T::S_LEFT_PAREN);
            $expression = $this->expression();
            $rightParenthesis = $this->read(T::S_RIGHT_PAREN);
            $node = Nodes\Expressions\EmptyExpression::__instantiateUnchecked($keyword, $leftParenthesis, $expression, $rightParenthesis);
        }
        else if ($this->peek()->getType() === T::T_DEC)
        {
            $operator = $this->read();
            $expression = $this->simpleExpression();
            $node = Nodes\Expressions\PreDecrementExpression::__instantiateUnchecked($operator, $expression);
        }
        else if ($this->peek()->getType() === T::T_INC)
        {
            $operator = $this->read();
            $expression = $this->simpleExpression();
            $node = Nodes\Expressions\PreIncrementExpression::__instantiateUnchecked($operator, $expression);
        }
        else if (\in_array($this->peek()->getType(), T::MAGIC_CONSTANTS, true))
        {
            $node = Nodes\Expressions\MagicConstant::__instantiateUnchecked($this->read());
        }
        else if ($this->peek()->getType() === T::T_CLONE)
        {
            $keyword = $this->read();
            $expression = $this->expression(Expr::PRECEDENCE_CLONE);
            $node = Nodes\Expressions\CloneExpression::__instantiateUnchecked($keyword, $expression);
        }
        else if ($this->peek()->getType() === T::S_EXCLAMATION_MARK)
        {
            $operator = $this->read();
            $expression = $this->expression(Expr::PRECEDENCE_BOOLEAN_NOT);
            $node = Nodes\Expressions\NotExpression::__instantiateUnchecked($operator, $expression);
        }
        else if ($this->peek()->getType() === T::T_YIELD)
        {
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
        }
        else if ($this->peek()->getType() === T::T_YIELD_FROM)
        {
            $keyword = $this->read();
            $expression = $this->expression(Expr::PRECEDENCE_TERNARY);
            $node = Nodes\Expressions\YieldFromExpression::__instantiateUnchecked($keyword, $expression);
        }
        else if (in_array($this->peek()->getType(), [T::T_INCLUDE, T::T_INCLUDE_ONCE, T::T_REQUIRE, T::T_REQUIRE_ONCE], true))
        {
            $keyword = $this->read();
            $expression = $this->expression();
            $node = Nodes\Expressions\IncludeLikeExpression::__instantiateUnchecked($keyword, $expression);
        }
        else if ($this->peek()->getType() === T::S_TILDE)
        {
            $symbol = $this->read();
            $expression = $this->expression(Expr::PRECEDENCE_POW);
            $node = Nodes\Expressions\BitwiseNotExpression::__instantiateUnchecked($symbol, $expression);
        }
        else if ($this->peek()->getType() === T::S_MINUS)
        {
            $symbol = $this->read();
            $expression = $this->expression(Expr::PRECEDENCE_POW);
            $node = Nodes\Expressions\UnaryMinusExpression::__instantiateUnchecked($symbol, $expression);
        }
        else if ($this->peek()->getType() === T::S_PLUS)
        {
            $symbol = $this->read();
            $expression = $this->expression(Expr::PRECEDENCE_POW);
            $node = Nodes\Expressions\UnaryPlusExpression::__instantiateUnchecked($symbol, $expression);
        }
        else if (\in_array($this->peek()->getType(), T::CASTS, true))
        {
            $cast = $this->read();
            $expression = $this->expression(Expr::PRECEDENCE_CAST);
            $node = Nodes\Expressions\CastExpression::__instantiateUnchecked($cast, $expression);
        }
        else if ($this->peek()->getType() === T::S_AT)
        {
            $operator = $this->read();
            $expression = $this->expression(Expr::PRECEDENCE_POW);
            $node = Nodes\Expressions\SuppressErrorsExpression::__instantiateUnchecked($operator, $expression);
        }
        else if ($this->peek()->getType() === T::T_LIST)
        {
            $keyword = $this->read();
            $leftParenthesis = $this->read(T::S_LEFT_PAREN);
            $items = $this->arrayItems(T::S_RIGHT_PAREN);
            $rightParenthesis = $this->read();
            $node = Nodes\Expressions\ListExpression::__instantiateUnchecked($keyword, $leftParenthesis, $items, $rightParenthesis);
        }
        else if ($this->peek()->getType() === T::T_EXIT)
        {
            $keyword = $this->read();
            if ($this->peek()->getType() === T::S_LEFT_PAREN)
            {
                $leftParenthesis = $this->read(T::S_LEFT_PAREN);
                $expression = $this->peek()->getType() !== T::S_RIGHT_PAREN ? $this->expression() : null;
                $rightParenthesis = $this->read(T::S_RIGHT_PAREN);
            }
            else
            {
                $leftParenthesis = $expression = $rightParenthesis = null;
            }
            $node = Nodes\Expressions\ExitExpression::__instantiateUnchecked($keyword, $leftParenthesis, $expression, $rightParenthesis);
        }
        else if ($this->peek()->getType() === T::T_PRINT)
        {
            $keyword = $this->read();
            $expression = $this->expression(Expr::PRECEDENCE_TERNARY);
            $node = Nodes\Expressions\PrintExpression::__instantiateUnchecked($keyword, $expression);
        }
        else if ($this->peek()->getType() === T::T_EVAL)
        {
            $keyword = $this->read();
            $leftParenthesis = $this->read(T::S_LEFT_PAREN);
            $expression = $this->expression();
            $rightParenthesis = $this->read(T::S_RIGHT_PAREN);
            $node = Nodes\Expressions\EvalExpression::__instantiateUnchecked($keyword, $leftParenthesis, $expression, $rightParenthesis);
        }
        else if ($this->peek()->getType() === T::S_BACKTICK)
        {
            $leftDelimiter = $this->read();
            $command = $this->read(T::T_ENCAPSED_AND_WHITESPACE);
            $rightDelimiter = $this->read(T::S_BACKTICK);
            $node = Nodes\Expressions\ExecExpression::__instantiateUnchecked($leftDelimiter, $command, $rightDelimiter);
        }
        else if (
            $this->peek()->getType() === T::T_FUNCTION ||
            ($this->peek()->getType() === T::T_STATIC && $this->peek(1)->getType() === T::T_FUNCTION)
        )
        {
            $static = $this->peek()->getType() === T::T_STATIC ? $this->read() : null;
            $keyword = $this->read();
            $byReference = $this->peek()->getType() === T::S_AMPERSAND ? $this->read() : null;
            $leftParenthesis = $this->read(T::S_LEFT_PAREN);
            $parameters = $this->parameters();
            $rightParenthesis = $this->read(T::S_RIGHT_PAREN);
            $use = null;
            if ($this->peek()->getType() === T::T_USE)
            {
                $useKeyword = $this->read();
                $useLeftParenthesis = $this->read(T::S_LEFT_PAREN);
                $useBindings = [];
                while (true)
                {
                    $useBindingByReference = null;
                    if ($this->peek()->getType() === T::S_AMPERSAND)
                    {
                        $useBindingByReference = $this->read();
                    }
                    $useBindingVariable = $this->read(T::T_VARIABLE);
                    $useBindings[] = Nodes\Expressions\AnonymousFunctionUseBinding::__instantiateUnchecked($useBindingByReference, $useBindingVariable);

                    if ($this->peek()->getType() === T::S_COMMA)
                    {
                        $useBindings[] = $this->read();
                    }
                    else
                    {
                        break;
                    }
                }
                $useRightParenthesis = $this->read(T::S_RIGHT_PAREN);
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
        }
        else
        {
            throw ParseException::unexpected($this->peek());
        }

        while (true)
        {
            if ($this->peek()->getType() === T::T_OBJECT_OPERATOR)
            {
                $operator = $this->read();

                $name = $this->memberName();
                // expr->v -> access the property named 'v'
                // new expr->v -> instantiate the class named by expr->v
                // new expr->v() -> same, () is part of the NewExpression
                if ($this->peek()->getType() !== T::S_LEFT_PAREN || $newable)
                {
                    $node = Nodes\Expressions\PropertyAccessExpression::__instantiateUnchecked($node, $operator, $name);
                }
                // expr->v() -> call the method named v
                else
                {
                    $leftParenthesis = $this->read();
                    $arguments = $this->arguments();
                    $rightParenthesis = $this->read(T::S_RIGHT_PAREN);
                    $node = Nodes\Expressions\MethodCallExpression::__instantiateUnchecked($node, $operator, $name, $leftParenthesis, $arguments, $rightParenthesis);
                }
            }
            else if ($this->peek()->getType() === T::S_LEFT_PAREN)
            {
                // new expr() -> () always belongs to new
                if ($newable)
                {
                    break;
                }

                $leftParenthesis = $this->read();
                $arguments = $this->arguments();
                $rightParenthesis = $this->read(T::S_RIGHT_PAREN);
                $node = Nodes\Expressions\FunctionCallExpression::__instantiateUnchecked($node, $leftParenthesis, $arguments, $rightParenthesis);
            }
            else if ($this->peek()->getType() === T::S_LEFT_SQUARE_BRACKET)
            {
                $leftBracket = $this->read();
                $index = $this->peek()->getType() === T::S_RIGHT_SQUARE_BRACKET ? null : $this->expression();
                $rightBracket = $this->read(T::S_RIGHT_SQUARE_BRACKET);
                $node = Nodes\Expressions\ArrayAccessExpression::__instantiateUnchecked($node, $leftBracket, $index, $rightBracket);
            }
            else if ($this->peek()->getType() === T::T_DOUBLE_COLON)
            {
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
                        else if ($this->peek()->getType() !== T::S_LEFT_PAREN)
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
                        if ($this->peek()->getType() !== T::S_LEFT_PAREN || $newable)
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
                        if ($this->peek()->getType() !== T::S_LEFT_PAREN || $newable)
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
                        if ($this->peek(1)->getType() === T::S_LEFT_PAREN)
                        {
                            // expr::class() is a static method call of the static method named 'class'
                            goto fudgeSpecialNameToString;
                        }
                        $keyword = $this->read();
                        $node = Nodes\Expressions\ClassNameResolutionExpression::__instantiateUnchecked($node, $operator, $keyword);
                        break;

                    staticCall:
                        // we jump here when we positively decide on a static call, and have set up $memberName
                        /** @var \Phi\Nodes\Helpers\MemberName $memberName */
                        $leftParenthesis = $this->read(T::S_LEFT_PAREN);
                        $arguments = $this->arguments();
                        $rightParenthesis = $this->read(T::S_RIGHT_PAREN);
                        $node = Nodes\Expressions\StaticMethodCallExpression::__instantiateUnchecked($node, $operator, $memberName, $leftParenthesis, $arguments, $rightParenthesis);
                        break;

                    default:
                        fudgeSpecialNameToString:
                        if (\in_array($this->peek()->getType(), T::SEMI_RESERVED, true))
                        {
                            $this->peek()->_fudgeType(T::T_STRING);
                            goto doubleColonString;
                        }
                        else
                        {
                            throw ParseException::unexpected($this->peek());
                        }
                }
            }
            else
            {
                break;
            }
        }

        if ($this->peek()->getType() === T::T_DEC)
        {
            $operator = $this->read();
            $node = Nodes\Expressions\PostDecrementExpression::__instantiateUnchecked($node, $operator);
        }
        else if ($this->peek()->getType() === T::T_INC)
        {
            $operator = $this->read();
            $node = Nodes\Expressions\PostIncrementExpression::__instantiateUnchecked($node, $operator);
        }

        return $node;
    }

    private function block(int $level = self::STMT_LEVEL_OTHER): Nodes\Block
    {
        if ($this->peek()->getType() === T::S_LEFT_CURLY_BRACE)
        {
            return $this->regularBlock($level);
        }
        else
        {
            return Nodes\Blocks\ImplicitBlock::__instantiateUnchecked($this->statement());
        }
    }

    private function regularBlock(int $level = self::STMT_LEVEL_OTHER): Nodes\Blocks\RegularBlock
    {
        $leftBrace = $this->read();
        $statements = [];
        while ($this->peek()->getType() !== T::S_RIGHT_CURLY_BRACE)
        {
            $statements[] = $this->statement($level);
        }
        $rightBrace = $this->read(T::S_RIGHT_CURLY_BRACE);
        return Nodes\Blocks\RegularBlock::__instantiateUnchecked($leftBrace, $statements, $rightBrace);
    }

    /**
     * @param array<int> $implicitEndKeywords
     */
    private function altBlock(int $endKeywordType, array $implicitEndKeywords = []): Nodes\Blocks\AlternativeFormatBlock
    {
        $colon = $this->read(T::S_COLON);

        $statements = [];
        while ($this->peek()->getType() !== $endKeywordType && !in_array($this->peek()->getType(), $implicitEndKeywords, true))
        {
            $statements[] = $this->statement();
        }

        if ($this->peek()->getType() === $endKeywordType)
        {
            $endKeyword = $this->read();
            $semiColon = $this->statementDelimiter();
        }
        else
        {
            $endKeyword = $semiColon = null;
        }

        return Nodes\Blocks\AlternativeFormatBlock::__instantiateUnchecked($colon, $statements, $endKeyword, $semiColon);
    }

    /** @return array<\Phi\Nodes\Helpers\Parameter|Token|null> */
    private function parameters(): array
    {
        $nodes = [];
        if ($this->peek()->getType() !== T::S_RIGHT_PAREN)
        {
            $nodes[] = $this->parameter();
        }
        while ($this->peek()->getType() === T::S_COMMA)
        {
            $nodes[] = $this->read();
            $nodes[] = $this->parameter();
        }
        return $nodes;
    }

    private function parameter(): Nodes\Helpers\Parameter
    {
        $type = $byReference = $ellipsis = null;

        if ($this->peek()->getType() !== T::S_AMPERSAND && $this->peek()->getType() !== T::T_VARIABLE && $this->peek()->getType() !== T::T_ELLIPSIS)
        {
            $type = $this->type();
        }

        if ($this->peek()->getType() === T::S_AMPERSAND)
        {
            $byReference = $this->read();
        }

        if ($this->peek()->getType() === T::T_ELLIPSIS)
        {
            $ellipsis = $this->read();
        }

        $variable = $this->read(T::T_VARIABLE);

        if ($ellipsis && $this->peek()->getType() === T::S_EQUALS)
        {
            throw ParseException::unexpected($this->peek());
        }
        $default = $this->default_();

        return Nodes\Helpers\Parameter::__instantiateUnchecked($type, $byReference, $ellipsis, $variable, $default);
    }

    /** @return array<\Phi\Nodes\Helpers\Argument|Token|null> */
    private function arguments(): array
    {
        $arguments = [];
        while ($this->peek()->getType() !== T::S_RIGHT_PAREN)
        {
            $unpack = $this->peek()->getType() === T::T_ELLIPSIS ? $this->read() : null;
            $value = $this->expression();
            $arguments[] = Nodes\Helpers\Argument::__instantiateUnchecked($unpack, $value);

            if ($this->peek()->getType() === T::S_COMMA)
            {
                $arguments[] = $this->read();

                // trailing comma is not allowed before 7.4
                if ($this->phpVersion < PhpVersion::PHP_7_4 && $this->peek()->getType() === T::S_RIGHT_PAREN)
                {
                    throw ParseException::unexpected($this->peek());
                }
            }

            if ($unpack)
            {
                break;
            }
        }
        return $arguments;
    }

    private function returnType(): ?Nodes\Helpers\ReturnType
    {
        if ($this->peek()->getType() === T::S_COLON)
        {
            $symbol = $this->read();
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
        $nullableSymbol = $this->peek()->getType() === T::S_QUESTION_MARK ? $this->read() : null;
        $type = Nodes\Types\NamedType::__instantiateUnchecked($this->name());
        if ($nullableSymbol)
        {
            $type = Nodes\Types\NullableType::__instantiateUnchecked($nullableSymbol, $type);
        }
        return $type;
    }

    private function name(): Nodes\Helpers\Name
    {
        $parts = [];
        switch ($this->peek()->getType())
        {
            case T::T_STATIC:
            case T::T_ARRAY:
            case T::T_CALLABLE:
                $parts[] = $this->read();
                break;
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
        if ($this->peek()->getType() === T::S_EQUALS)
        {
            $symbol = $this->read();
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
        else if ($this->peek()->getType() === T::S_LEFT_CURLY_BRACE)
        {
            $leftBrace = $this->read();
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
        else if ($this->peek()->getType() === T::S_LEFT_CURLY_BRACE)
        {
            $leftBrace = $this->read();
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
            $key = $byReference = $value = null;

            if ($this->peek()->getType() !== T::S_COMMA && $this->peek()->getType() !== $delimiter)
            {
                if ($this->peek()->getType() === T::S_AMPERSAND)
                {
                    $byReference = $this->read();
                }

                $value = $this->expression();

                if ($this->peek()->getType() === T::T_DOUBLE_ARROW)
                {
                    if ($byReference)
                    {
                        throw ParseException::unexpected($this->peek());
                    }

                    $key = Nodes\Helpers\Key::__instantiateUnchecked($value, $this->read());

                    if ($this->peek()->getType() === T::S_AMPERSAND)
                    {
                        $byReference = $this->read();
                    }

                    $value = $this->expression();
                }
            }

            $items[] = Nodes\Expressions\ArrayItem::__instantiateUnchecked($key, $byReference, $value);

            if ($this->peek()->getType() === T::S_COMMA)
            {
                $items[] = $this->read();
            }
            else
            {
                break;
            }
        }
        return $items;
    }

    /** @return \Phi\Nodes\Expressions\StringInterpolation\InterpolatedStringPart[] */
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
                $var = Nodes\Expressions\StringInterpolation\NormalInterpolatedStringVariable::__instantiateUnchecked($this->read());
                if ($this->peek()->getType() === T::S_LEFT_SQUARE_BRACKET)
                {
                    $leftBracket = $this->read();
                    $minus = $this->peek()->getType() === T::S_MINUS ? $this->read() : null;
                    $value = $this->read();
                    // TODO screw this, emulate this ugliness away and validate the expression type
                    if ($value->getType() !== T::T_NUM_STRING && $value->getType() !== T::T_STRING && $value->getType() !== T::T_VARIABLE)
                    {
                        throw ParseException::unexpected($value);
                    }
                    $index = Nodes\Expressions\StringInterpolation\InterpolatedArrayAccessIndex::__instantiateUnchecked($minus, $value);
                    $rightBracket = $this->read(T::S_RIGHT_SQUARE_BRACKET);
                    $parts[] = Nodes\Expressions\StringInterpolation\ArrayAccessInterpolatedStringVariable::__instantiateUnchecked($var, $leftBracket, $index, $rightBracket);
                }
                else if ($this->peek()->getType() === T::T_OBJECT_OPERATOR)
                {
                    $operator = $this->read();
                    $name = $this->read(T::T_STRING);
                    $parts[] = Nodes\Expressions\StringInterpolation\PropertyAccessInterpolatedStringVariable::__instantiateUnchecked($var, $operator, $name);
                }
                else
                {
                    $parts[] = $var;
                }
            }
            else if ($this->peek()->getType() === T::T_DOLLAR_OPEN_CURLY_BRACES)
            {
                $leftDelimiter = $this->read();
                if ($this->peek()->getType() === T::T_STRING_VARNAME && $this->peek(1)->getType() === T::S_RIGHT_CURLY_BRACE)
                {
                    $name = $this->read();
                    $rightDelimiter = $this->read();
                    $parts[] = Nodes\Expressions\StringInterpolation\BracedInterpolatedStringVariable::__instantiateUnchecked($leftDelimiter, $name, $rightDelimiter);
                }
                else
                {
                    $name = $this->expression();
                    $rightDelimiter = $this->read(T::S_RIGHT_CURLY_BRACE);
                    $parts[] = Nodes\Expressions\StringInterpolation\VariableInterpolatedStringVariable::__instantiateUnchecked($leftDelimiter, $name, $rightDelimiter);
                }
            }
            else if ($this->peek()->getType() === T::S_LEFT_CURLY_BRACE)
            {
                $leftBrace = $this->read();
                $expression = $this->simpleExpression();
                $rightBrace = $this->read(T::S_RIGHT_CURLY_BRACE);
                $parts[] = Nodes\Expressions\StringInterpolation\InterpolatedStringExpression::__instantiateUnchecked($leftBrace, $expression, $rightBrace);
            }
            else
            {
                throw ParseException::unexpected($this->peek());
            }
        }
        return $parts;
    }
}
