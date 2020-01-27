<?php

/** @noinspection PhpFullyQualifiedNameUsageInspection */

namespace Phi;

use Phi\Exception\ParseException;
use Phi\Nodes;

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
    // TODO move these checks to Node::validate()
    /** @var int */
    private $loopDepth = 0;

    /** @var (int|string)[] */
    private $types = [];
    /** @var string */
    private $typezip = "";

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

        $ast = $this->parseRoot();

        $this->deinit();

        $ast->validate(Node::VALIDATE_EXPRESSION_CONTEXT);

        return $ast;
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
            if ($this->peek()->getType() !== Token::PH_T_EOF)
            {
                throw ParseException::unexpected($this->peek());
            }
        }
        finally
        {
            $this->deinit();
        }

        $ast->validate(Node::VALIDATE_EXPRESSION_CONTEXT);

        return $ast;
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
            if ($this->peek()->getType() !== Token::PH_T_EOF)
            {
                throw ParseException::unexpected($this->peek());
            }
        }
        finally
        {
            $this->deinit();
        }

        // since this is an expression, all we want to check is if nested expressions have the right context
        // we don't yet know what context the root expression is going to be in
        // TODO test coverage
        $ast->validateContext(0);

        return $ast;
    }

    /**
     * @throws ParseException
     */
    public function parseFragment(string $source): Node
    {
        $tokens = (new Lexer($this->phpVersion))->lexFragment($source);

        if (\count($tokens) === 2) // a single token + eof
        {
            return $tokens[0];
        }

        $this->init($tokens);

        try
        {
            $statements = [];
            while ($this->peek()->getType() !== Token::PH_T_EOF)
            {
                $statements[] = $this->statement(self::STMT_LEVEL_TOP);
            }
        }
        finally
        {
            $this->deinit();
        }

        if (count($statements) !== 1)
        {
            $node = new Nodes\RegularBlock();
            foreach ($statements as $statement)
            {
                $node->addStatement($statement);
            }
        }
        else
        {
            $node = $statements[0];
        }

        // TODO can we parse a non-read expression this way? -- we should; but this would ruin tests!
        $node->validate(Node::VALIDATE_EXPRESSION_CONTEXT);

        if ($node instanceof Nodes\ExpressionStatement && !$node->getSemiColon())
        {
            $node = $node->getExpression();
            $node->detach();
        }

        return $node;
    }

    /**
     * @param Token[] $tokens
     */
    private function init(array $tokens): void
    {
        $this->tokens = $tokens;
        $this->i = 0;
        $this->loopDepth = 0;

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

        $typezip = '';
        foreach ($this->types as $t)
        {
            $typezip .= \in_array($t, [
                Token::PH_S_RIGHT_PAREN, Token::PH_S_RIGHT_CURLY_BRACE, Token::PH_S_RIGHT_SQUARE_BRACKET,
                Token::PH_S_COMMA, Token::PH_S_SEMICOLON, Token::PH_S_COLON,
                Token::PH_T_AS, Token::PH_T_DOUBLE_ARROW,
            ], true) ? '000' : $t;
        }
        $this->typezip = $typezip;
    }

    private function deinit(): void
    {
        $this->tokens = [];
        $this->types = [];
        $this->typezip = "";
    }

    private function peek(int $ahead = 0): Token
    {
        return $this->tokens[$this->i + $ahead];
    }

    /**
     * @param int|string $expectedTokenType
     */
    private function read($expectedTokenType = null): Token
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

        if ($this->peek()->getType() === Token::PH_T_INLINE_HTML)
        {
            $content = $this->read();
        }
        else
        {
            $content = null;
        }

        if ($this->peek()->getType() === Token::PH_T_OPEN_TAG)
        {
            $statements[] = Nodes\InlineHtmlStatement::__instantiateUnchecked($this->phpVersion, null, $content, $this->read());
        }

        while ($this->peek()->getType() !== Token::PH_T_EOF)
        {
            $statements[] = $this->statement(self::STMT_LEVEL_TOP);
        }

        $eof = $this->read(Token::PH_T_EOF);

        return Nodes\RootNode::__instantiateUnchecked($this->phpVersion, $statements, $eof);
    }

    private const STMT_LEVEL_TOP = 1;
    private const STMT_LEVEL_NAMESPACE = 2;
    private const STMT_LEVEL_OTHER = 3;

    private function statement(int $level = self::STMT_LEVEL_OTHER): Nodes\Statement
    {
        if ($this->peek()->getType() === Token::PH_T_NAMESPACE)
        {
            if ($level !== self::STMT_LEVEL_TOP)
            {
                throw ParseException::unexpected($this->peek());
            }

            return $this->namespace_();
        }
        else if ($this->peek()->getType() === Token::PH_T_USE)
        {
            if ($level === self::STMT_LEVEL_OTHER)
            {
                throw ParseException::unexpected($this->peek());
            }

            return $this->use_();
        }
        else if ($this->peek()->getType() === Token::PH_T_ABSTRACT || $this->peek()->getType() === Token::PH_T_CLASS || $this->peek()->getType() === Token::PH_T_FINAL)
        {
            return $this->class_();
        }
        else if ($this->peek()->getType() === Token::PH_T_INTERFACE)
        {
            return $this->interface_();
        }
        else if ($this->peek()->getType() === Token::PH_T_TRAIT)
        {
            return $this->trait_();
        }
        else if ($this->peek()->getType() === Token::PH_T_BREAK)
        {
            $keyword = $this->read();

            if (!$this->loopDepth)
            {
                throw ParseException::unexpected($keyword);
            }

            $levels = null;
            if ($this->peek()->getType() === Token::PH_T_LNUMBER)
            {
                $levels = Nodes\IntegerLiteral::__instantiateUnchecked($this->phpVersion, $this->read());
                if ($levels->getValue() <= 0 || $levels->getValue() > $this->loopDepth)
                {
                    throw ParseException::unexpected($levels->getToken());
                }
            }

            $semiColon = $this->statementDelimiter();
            return Nodes\BreakStatement::__instantiateUnchecked($this->phpVersion, $keyword, $levels, $semiColon);
        }
        else if ($this->peek()->getType() === Token::PH_T_CONTINUE)
        {
            $keyword = $this->read();

            if (!$this->loopDepth)
            {
                throw ParseException::unexpected($keyword);
            }

            $levels = null;
            if ($this->peek()->getType() === Token::PH_T_LNUMBER)
            {
                $levels = Nodes\IntegerLiteral::__instantiateUnchecked($this->phpVersion, $this->read());
                if ($levels->getValue() <= 0 || $levels->getValue() > $this->loopDepth)
                {
                    throw ParseException::unexpected($levels->getToken());
                }
            }

            $semiColon = $this->statementDelimiter();
            return Nodes\ContinueStatement::__instantiateUnchecked($this->phpVersion, $keyword, $levels, $semiColon);
        }
        else if ($this->peek()->getType() === Token::PH_T_DECLARE)
        {
            $keyword = $this->read();
            $leftParenthesis = $this->read(Token::PH_S_LEFT_PAREN);
            $directives = [null];
            do
            {
                $directiveKey = $this->read(Token::PH_T_STRING);
                $directiveEquals = $this->read(Token::PH_S_EQUALS);
                $directiveValue = $this->expression();
                $directives[] = Nodes\DeclareDirective::__instantiateUnchecked($this->phpVersion, $directiveKey, $directiveEquals, $directiveValue);
            }
            while ($this->peek()->getType() === Token::PH_S_COMMA && $directives[] = $this->read());
            $rightParenthesis = $this->read(Token::PH_S_RIGHT_PAREN);
            if ($this->peek()->getType() !== Token::PH_S_SEMICOLON)
            {
                $block = $this->block($level); // TODO test level
                $semiColon = null;
            }
            else
            {
                $block = null;
                $semiColon = $this->read();
            }
            return Nodes\DeclareStatement::__instantiateUnchecked($this->phpVersion,
                $keyword,
                $leftParenthesis,
                $directives,
                $rightParenthesis,
                $block,
                $semiColon
            );
        }
        else if ($this->peek()->getType() === Token::PH_T_DO)
        {
            $keyword1 = $this->read();

            $this->loopDepth++;
            $block = $this->block();
            $this->loopDepth--;

            $keyword2 = $this->read(Token::PH_T_WHILE);
            $leftParenthesis = $this->read(Token::PH_S_LEFT_PAREN);
            $test = $this->expression();
            $rightParenthesis = $this->read(Token::PH_S_RIGHT_PAREN);
            $semiColon = $this->statementDelimiter();
            return Nodes\DoWhileStatement::__instantiateUnchecked($this->phpVersion,
                $keyword1,
                $block,
                $keyword2,
                $leftParenthesis,
                $test,
                $rightParenthesis,
                $semiColon
            );
        }
        else if ($this->peek()->getType() === Token::PH_T_ECHO)
        {
            $keyword = $this->read();
            $expressions = [null];
            $expressions[] = $this->expression();
            while ($this->peek()->getType() === Token::PH_S_COMMA)
            {
                $expressions[] = $this->read();
                $expressions[] = $this->expression();
            }
            $semiColon = $this->statementDelimiter();
            return Nodes\EchoStatement::__instantiateUnchecked($this->phpVersion, $keyword, $expressions, $semiColon);
        }
        else if ($this->peek()->getType() === Token::PH_T_FOR)
        {
            $keyword = $this->read();
            $leftParenthesis = $this->read(Token::PH_S_LEFT_PAREN);
            $init = $this->peek()->getType() !== Token::PH_S_SEMICOLON ? $this->expression() : null;
            $separator1 = $this->read(Token::PH_S_SEMICOLON);
            $test = $this->peek()->getType() !== Token::PH_S_SEMICOLON ? $this->expression() : null;
            $separator2 = $this->read(Token::PH_S_SEMICOLON);
            $step = $this->peek()->getType() !== Token::PH_S_RIGHT_PAREN ? $this->expression() : null;
            $rightParenthesis = $this->read(Token::PH_S_RIGHT_PAREN);

            $this->loopDepth++;
            $block = $this->peek()->getType() === Token::PH_S_COLON ? $this->altBlock(Token::PH_T_ENDFOR) : $this->block();
            $this->loopDepth--;

            return Nodes\ForStatement::__instantiateUnchecked($this->phpVersion,
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
        else if ($this->peek()->getType() === Token::PH_T_FOREACH)
        {
            $keyword = $this->read();
            $leftParenthesis = $this->read(Token::PH_S_LEFT_PAREN);
            $iterable = $this->expression();
            $as = $this->read(Token::PH_T_AS);

            $key = null;
            $byReference = $this->peek()->getType() === Token::PH_S_AMPERSAND ? $this->read() : null;
            $value = $this->simpleExpression();

            if ($this->peek()->getType() === Token::PH_T_DOUBLE_ARROW)
            {
                if ($byReference)
                {
                    throw ParseException::unexpected($this->peek());
                }

                $key = Nodes\Key::__instantiateUnchecked($this->phpVersion, $value, $this->read());

                if ($this->peek()->getType() === Token::PH_S_AMPERSAND)
                {
                    $byReference = $this->read();
                }

                $value = $this->simpleExpression();
            }

            $rightParenthesis = $this->read(Token::PH_S_RIGHT_PAREN);

            $this->loopDepth++;
            $block = $this->peek()->getType() === Token::PH_S_COLON ? $this->altBlock(Token::PH_T_ENDFOREACH) : $this->block();
            $this->loopDepth--;

            return Nodes\ForeachStatement::__instantiateUnchecked($this->phpVersion,
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
            $this->peek()->getType() === Token::PH_T_FUNCTION
            && (
                $this->peek(1)->getType() === Token::PH_T_STRING
                || $this->peek(1)->getType() === Token::PH_S_AMPERSAND && $this->peek(2)->getType() === Token::PH_T_STRING
            )
        )
        {
            $keyword = $this->read();
            $byReference = $this->peek()->getType() === Token::PH_S_AMPERSAND ? $this->read() : null;
            $name = $this->read();
            $leftParenthesis = $this->read(Token::PH_S_LEFT_PAREN);
            $parameters = $this->parameters();
            $rightParenthesis = $this->read(Token::PH_S_RIGHT_PAREN);
            $returnType = $this->returnType();
            $body = $this->functionBlock();
            return Nodes\FunctionStatement::__instantiateUnchecked($this->phpVersion,
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
        else if ($this->peek()->getType() === Token::PH_T_GOTO)
        {
            $keyword = $this->read();
            $label = $this->read(Token::PH_T_STRING);
            $semiColon = $this->statementDelimiter();
            return Nodes\GotoStatement::__instantiateUnchecked($this->phpVersion,
                $keyword,
                $label,
                $semiColon
            );
        }
        else if ($this->peek()->getType() === Token::PH_T_IF)
        {
            $keyword = $this->read();
            $leftParenthesis = $this->read(Token::PH_S_LEFT_PAREN);
            $test = $this->expression();
            $rightParenthesis = $this->read(Token::PH_S_RIGHT_PAREN);
            if ($altSyntax = ($this->peek()->getType() === Token::PH_S_COLON))
            {
                $block = $this->altBlock(Token::PH_T_ENDIF, [Token::PH_T_ELSE, Token::PH_T_ELSEIF]);
            }
            else
            {
                $block = $this->block();
            }
            $elseifs = [];
            while ($this->peek()->getType() === Token::PH_T_ELSEIF)
            {
                $elseifKeyword = $this->read();
                $elseifLeftParenthesis = $this->read(Token::PH_S_LEFT_PAREN);
                $elseifTest = $this->expression();
                $elseifRightParenthesis = $this->read(Token::PH_S_RIGHT_PAREN);
                $elseifBlock = $altSyntax ? $this->altBlock(Token::PH_T_ENDIF, [Token::PH_T_ELSE, Token::PH_T_ELSEIF]) : $this->block();
                $elseifs[] = Nodes\Elseif_::__instantiateUnchecked($this->phpVersion,
                    $elseifKeyword,
                    $elseifLeftParenthesis,
                    $elseifTest,
                    $elseifRightParenthesis,
                    $elseifBlock
                );
            }
            $else = null;
            if ($this->peek()->getType() === Token::PH_T_ELSE)
            {
                $elseKeyword = $this->read();
                $elseBlock = $altSyntax ? $this->altBlock(Token::PH_T_ENDIF) : $this->block();
                $else = Nodes\Else_::__instantiateUnchecked($this->phpVersion, $elseKeyword, $elseBlock);
            }
            return Nodes\IfStatement::__instantiateUnchecked($this->phpVersion,
                $keyword,
                $leftParenthesis,
                $test,
                $rightParenthesis,
                $block,
                $elseifs,
                $else
            );
        }
        else if ($this->peek()->getType() === Token::PH_T_RETURN)
        {
            $keyword = $this->read();
            $expression = !$this->endOfStatement() ? $this->expression() : null;
            $semiColon = $this->statementDelimiter();
            return Nodes\ReturnStatement::__instantiateUnchecked($this->phpVersion, $keyword, $expression, $semiColon);
        }
        else if ($this->peek()->getType() === Token::PH_T_STATIC && $this->peek(1)->getType() === Token::PH_T_VARIABLE)
        {
            $keyword = $this->read();
            $variables = [null];
            do
            {
                $variable = $this->read(Token::PH_T_VARIABLE);
                $default = $this->default_();
                $variables[] = Nodes\StaticVariable::__instantiateUnchecked($this->phpVersion, $variable, $default);
            }
            while ($this->peek()->getType() === Token::PH_S_COMMA && $variables[] = $this->read());
            $semiColon = $this->statementDelimiter();
            return Nodes\StaticVariableDeclaration::__instantiateUnchecked($this->phpVersion, $keyword, $variables, $semiColon);
        }
        else if ($this->peek()->getType() === Token::PH_T_SWITCH)
        {
            $keyword = $this->read();

            $leftParenthesis = $this->read(Token::PH_S_LEFT_PAREN);
            $value = $this->expression();
            $rightParenthesis = $this->read(Token::PH_S_RIGHT_PAREN);

            $this->loopDepth++;
            $leftBrace = $this->read(Token::PH_S_LEFT_CURLY_BRACE);

            $cases = [];
            while ($this->peek()->getType() !== Token::PH_T_DEFAULT && $this->peek()->getType() !== Token::PH_S_RIGHT_CURLY_BRACE)
            {
                $caseKeyword = $this->read(Token::PH_T_CASE);
                $caseValue = $this->expression();
                $caseDelimiter = $this->peek()->getType() === Token::PH_S_SEMICOLON ? $this->read() : $this->read(Token::PH_S_COLON);
                $caseStatements = [];
                while ($this->peek()->getType() !== Token::PH_T_CASE && $this->peek()->getType() !== Token::PH_T_DEFAULT && $this->peek()->getType() !== Token::PH_S_RIGHT_CURLY_BRACE)
                {
                    $caseStatements[] = $this->statement();
                }
                $cases[] = Nodes\SwitchCase::__instantiateUnchecked($this->phpVersion, $caseKeyword, $caseValue, $caseDelimiter, $caseStatements);
            }
            $default = null;
            if ($this->peek()->getType() === Token::PH_T_DEFAULT)
            {
                $defaultKeyword = $this->read();
                $defaultColon = $this->read(Token::PH_S_COLON);
                $defaultStatements = [];
                while ($this->peek()->getType() !== Token::PH_S_RIGHT_CURLY_BRACE)
                {
                    $defaultStatements[] = $this->statement();
                }
                $default = Nodes\SwitchDefault::__instantiateUnchecked($this->phpVersion, $defaultKeyword, $defaultColon, $defaultStatements);
            }

            $rightBrace = $this->read(Token::PH_S_RIGHT_CURLY_BRACE);
            $this->loopDepth--;

            return Nodes\SwitchStatement::__instantiateUnchecked($this->phpVersion,
                $keyword,
                $leftParenthesis,
                $value,
                $rightParenthesis,
                $leftBrace,
                $cases,
                $default,
                $rightBrace
            );
        }
        else if ($this->peek()->getType() === Token::PH_T_THROW)
        {
            $keyword = $this->read();
            $expression = $this->expression();
            $semiColon = $this->statementDelimiter();
            return Nodes\ThrowStatement::__instantiateUnchecked($this->phpVersion, $keyword, $expression, $semiColon);
        }
        else if ($this->peek()->getType() === Token::PH_T_TRY)
        {
            $keyword = $this->read();
            $block = $this->regularBlock();
            $catches = [];
            while ($this->peek()->getType() === Token::PH_T_CATCH)
            {
                $catchKeyword = $this->read();
                $catchLeftParenthesis = $this->read(Token::PH_S_LEFT_PAREN);
                $catchTypes = [null];
                $catchTypes[] = Nodes\NamedType::__instantiateUnchecked($this->phpVersion, $this->name());
                while ($this->peek()->getType() === Token::PH_S_VERTICAL_BAR)
                {
                    $catchTypes[] = $this->read();
                    $catchTypes[] = Nodes\NamedType::__instantiateUnchecked($this->phpVersion, $this->name());
                }
                $catchVariable = $this->read(Token::PH_T_VARIABLE);
                $catchRightParenthesis = $this->read(Token::PH_S_RIGHT_PAREN);
                $catchBlock = $this->regularBlock();
                $catches[] = Nodes\Catch_::__instantiateUnchecked($this->phpVersion,
                    $catchKeyword,
                    $catchLeftParenthesis,
                    $catchTypes,
                    $catchVariable,
                    $catchRightParenthesis,
                    $catchBlock
                );
            }
            $finally = null;
            if ($this->peek()->getType() === Token::PH_T_FINALLY)
            {
                $finallyKeyword = $this->read();
                $finallyBlock = $this->regularBlock();
                $finally = Nodes\Finally_::__instantiateUnchecked($this->phpVersion, $finallyKeyword, $finallyBlock);
            }
            return Nodes\TryStatement::__instantiateUnchecked($this->phpVersion, $keyword, $block, $catches, $finally);
        }
        else if ($this->peek()->getType() === Token::PH_T_WHILE)
        {
            $keyword = $this->read();
            $leftParenthesis = $this->read(Token::PH_S_LEFT_PAREN);
            $test = $this->expression();
            $rightParenthesis = $this->read(Token::PH_S_RIGHT_PAREN);

            $this->loopDepth++;
            $block = $this->peek()->getType() === Token::PH_S_COLON ? $this->altBlock(Token::PH_T_ENDWHILE) : $this->block();
            $this->loopDepth--;

            return Nodes\WhileStatement::__instantiateUnchecked($this->phpVersion, $keyword, $leftParenthesis, $test, $rightParenthesis, $block);
        }
        else if ($this->peek()->getType() === Token::PH_T_UNSET)
        {
            $keyword = $this->read();
            $leftParenthesis = $this->read(Token::PH_S_LEFT_PAREN);
            $expressions = [null];
            do
            {
                $expressions[] = $this->simpleExpression();
            }
            while ($this->peek()->getType() === Token::PH_S_COMMA && $expressions[] = $this->read());
            $rightParenthesis = $this->read(Token::PH_S_RIGHT_PAREN);
            $semiColon = $this->statementDelimiter();
            return Nodes\UnsetStatement::__instantiateUnchecked($this->phpVersion, $keyword, $leftParenthesis, $expressions, $rightParenthesis, $semiColon);
        }
        else if ($this->peek()->getType() === Token::PH_S_LEFT_CURLY_BRACE)
        {
            return Nodes\BlockStatement::__instantiateUnchecked($this->phpVersion, $this->regularBlock());
        }
        else if ($this->peek()->getType() === Token::PH_S_SEMICOLON)
        {
            return Nodes\NopStatement::__instantiateUnchecked($this->phpVersion, $this->read());
        }
        else if ($this->peek()->getType() === Token::PH_T_STRING && $this->peek(1)->getType() === Token::PH_S_COLON)
        {
            return Nodes\LabelStatement::__instantiateUnchecked($this->phpVersion, $this->read(), $this->read());
        }
        else
        {
            $expression = $this->expression();
            $semiColon = $this->statementDelimiter();
            return Nodes\ExpressionStatement::__instantiateUnchecked($this->phpVersion, $expression, $semiColon);
        }
    }

    private function namespace_(): Nodes\NamespaceStatement
    {
        $keyword = $this->read(Token::PH_T_NAMESPACE);
        $name = null;
        if ($this->peek()->getType() !== Token::PH_S_LEFT_CURLY_BRACE && $this->peek()->getType() !== Token::PH_S_SEMICOLON)
        {
            $name = $this->name();
        }
        if ($this->peek()->getType() === Token::PH_S_LEFT_CURLY_BRACE)
        {
            $block = $this->regularBlock(self::STMT_LEVEL_NAMESPACE);
            $semiColon = null;
        }
        else
        {
            $block = null;
            $semiColon = $this->statementDelimiter();
        }
        return Nodes\NamespaceStatement::__instantiateUnchecked($this->phpVersion, $keyword, $name, $block, $semiColon);
    }

    private function use_(): Nodes\UseStatement
    {
        $keyword = $this->read(Token::PH_T_USE);
        $type = $this->peek()->getType() === Token::PH_T_FUNCTION || $this->peek()->getType() === Token::PH_T_CONST ? $this->read() : null;
        // TODO take advantage of $uses being the same for both forms and simplify this...
        if ($this->peek()->getType() === Token::PH_S_LEFT_CURLY_BRACE)
        {
            $prefix = null;
        }
        else
        {
            $name = $this->name();
            if ($this->peek()->getType() !== Token::PH_T_NS_SEPARATOR || $this->peek(1)->getType() !== Token::PH_S_LEFT_CURLY_BRACE)
            {
                $uses = [null];
                $alias = null;
                if ($this->peek()->getType() === Token::PH_T_AS)
                {
                    $aliasKeyword = $this->read();
                    $alias = Nodes\UseAlias::__instantiateUnchecked($this->phpVersion, $aliasKeyword, $this->read(Token::PH_T_STRING));
                }
                $uses[] = Nodes\UseName::__instantiateUnchecked($this->phpVersion, $name, $alias);
                while ($this->peek()->getType() === Token::PH_S_COMMA)
                {
                    $uses[] = $this->read();
                    $name = $this->name();
                    $alias = null;
                    if ($this->peek()->getType() === Token::PH_T_AS)
                    {
                        $aliasKeyword = $this->read();
                        $alias = Nodes\UseAlias::__instantiateUnchecked($this->phpVersion, $aliasKeyword, $this->read(Token::PH_T_STRING));
                    }
                    $uses[] = Nodes\UseName::__instantiateUnchecked($this->phpVersion, $name, $alias);
                }
                $semiColon = $this->statementDelimiter();
                return Nodes\RegularUseStatement::__instantiateUnchecked($this->phpVersion,
                    $keyword,
                    $type,
                    $uses,
                    $semiColon
                );
            }
            $prefix = Nodes\GroupedUsePrefix::__instantiateUnchecked($this->phpVersion, $name, $this->read());
        }
        $leftBrace = $this->read();
        $uses = [null];
        do
        {
            $useName = $this->name();
            $useAlias = null;
            if ($this->peek()->getType() === Token::PH_T_AS)
            {
                $useAliasKeyword = $this->read();
                $useAlias = Nodes\UseAlias::__instantiateUnchecked($this->phpVersion, $useAliasKeyword, $this->read(Token::PH_T_STRING));
            }
            $uses[] = Nodes\UseName::__instantiateUnchecked($this->phpVersion, $useName, $useAlias);
            if ($this->peek()->getType() !== Token::PH_S_COMMA)
            {
                break;
            }
            $uses[] = $this->read();
        }
        while ($this->peek()->getType() !== Token::PH_S_RIGHT_CURLY_BRACE);
        $rightBrace = $this->read();
        $semiColon = $this->statementDelimiter();
        return Nodes\GroupedUseStatement::__instantiateUnchecked($this->phpVersion,
            $keyword,
            $type,
            $prefix,
            $leftBrace,
            $uses,
            $rightBrace,
            $semiColon
        );
    }

    private function class_(): Nodes\ClassStatement
    {
        $modifiers = [];
        while (in_array($this->peek()->getType(), [Token::PH_T_ABSTRACT, Token::PH_T_FINAL], true))
        {
            $modifiers[] = $this->read();
        }
        $keyword = $this->read(Token::PH_T_CLASS);
        $name = $this->read(Token::PH_T_STRING);
        if ($this->peek()->getType() === Token::PH_T_EXTENDS)
        {
            $extendsKeyword = $this->read();
            $extendsNames = [null];
            $extendsNames[] = $this->name();
            $extends = Nodes\Extends_::__instantiateUnchecked($this->phpVersion, $extendsKeyword, $extendsNames);
        }
        else
        {
            $extends = null;
        }
        if ($this->peek()->getType() === Token::PH_T_IMPLEMENTS)
        {
            $implementsKeyword = $this->read();
            $implementsNames = [null];
            $implementsNames[] = $this->name();
            while ($this->peek()->getType() === Token::PH_S_COMMA)
            {
                $implementsNames[] = $this->read();
                $implementsNames[] = $this->name();
            }
            $implements = Nodes\Implements_::__instantiateUnchecked($this->phpVersion, $implementsKeyword, $implementsNames);
        }
        else
        {
            $implements = null;
        }
        $leftBrace = $this->read(Token::PH_S_LEFT_CURLY_BRACE);
        $members = [];
        while ($this->peek()->getType() !== Token::PH_S_RIGHT_CURLY_BRACE)
        {
            $members[] = $this->classLikeMember();
        }
        $rightBrace = $this->read(Token::PH_S_RIGHT_CURLY_BRACE);
        return Nodes\ClassStatement::__instantiateUnchecked($this->phpVersion,
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

    private function interface_(): Nodes\InterfaceStatement
    {
        $keyword = $this->read(Token::PH_T_INTERFACE);
        $name = $this->read(Token::PH_T_STRING);
        if ($this->peek()->getType() === Token::PH_T_EXTENDS)
        {
            $extendsKeyword = $this->read();
            $extendsNames = [null];
            $extendsNames[] = $this->name();
            while ($this->peek()->getType() === Token::PH_S_COMMA)
            {
                $extendsNames[] = $this->read();
                $extendsNames[] = $this->name();
            }
            $extends = Nodes\Extends_::__instantiateUnchecked($this->phpVersion, $extendsKeyword, $extendsNames);
        }
        else
        {
            $extends = null;
        }
        $leftBrace = $this->read(Token::PH_S_LEFT_CURLY_BRACE);
        $members = [];
        while ($this->peek()->getType() !== Token::PH_S_RIGHT_CURLY_BRACE)
        {
            $members[] = $this->classLikeMember();
        }
        $rightBrace = $this->read(Token::PH_S_RIGHT_CURLY_BRACE);
        return Nodes\InterfaceStatement::__instantiateUnchecked($this->phpVersion,
            $keyword,
            $name,
            $extends,
            $leftBrace,
            $members,
            $rightBrace
        );
    }

    private function trait_(): Nodes\TraitStatement
    {
        $keyword = $this->read(Token::PH_T_TRAIT);
        $name = $this->read(Token::PH_T_STRING);
        $leftBrace = $this->read(Token::PH_S_LEFT_CURLY_BRACE);
        $members = [];
        while ($this->peek()->getType() !== Token::PH_S_RIGHT_CURLY_BRACE)
        {
            $members[] = $this->classLikeMember();
        }
        $rightBrace = $this->read(Token::PH_S_RIGHT_CURLY_BRACE);
        return Nodes\TraitStatement::__instantiateUnchecked($this->phpVersion,
            $keyword,
            $name,
            $leftBrace,
            $members,
            $rightBrace
        );
    }

    private function classLikeMember(): Nodes\ClassLikeMember
    {
        $modifiers = [];
        while (\in_array($this->peek()->getType(), [Token::PH_T_ABSTRACT, Token::PH_T_FINAL, Token::PH_T_PUBLIC, Token::PH_T_PROTECTED, Token::PH_T_PRIVATE, Token::PH_T_STATIC], true))
        {
            $modifiers[] = $this->read();
        }

        if ($this->peek()->getType() === Token::PH_T_FUNCTION)
        {
            $keyword = $this->read();
            $byReference = $this->peek()->getType() === Token::PH_S_AMPERSAND ? $this->read() : null;
            $name = $this->read(Token::PH_T_STRING);
            $leftParenthesis = $this->read(Token::PH_S_LEFT_PAREN);
            $parameters = $this->parameters();
            $rightParenthesis = $this->read(Token::PH_S_RIGHT_PAREN);
            $returnType = $this->returnType();
            if ($this->peek()->getType() === Token::PH_S_SEMICOLON)
            {
                $body = null;
                $semiColon = $this->read();
            }
            else
            {
                $body = $this->functionBlock();
                $semiColon = null;
            }
            return Nodes\Method::__instantiateUnchecked($this->phpVersion,
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
        else if ($this->peek()->getType() === Token::PH_T_CONST)
        {
            $keyword = $this->read();
            $name = $this->read(Token::PH_T_STRING);
            $equals = $this->read(Token::PH_S_EQUALS);
            $value = $this->expression();
            $semiColon = $this->read(Token::PH_S_SEMICOLON);
            return Nodes\ClassConstant::__instantiateUnchecked($this->phpVersion, $modifiers, $keyword, $name, $equals, $value, $semiColon);
        }
        else if ($this->peek()->getType() === Token::PH_T_USE)
        {
            $keyword = $this->read();
            $names = [null];
            do
            {
                $names[] = $this->name();
            }
            while ($this->peek()->getType() === Token::PH_S_COMMA && $names[] = $this->read(Token::PH_S_COMMA));
            $leftBrace = $rightBrace = $semiColon = null;
            $modifications = [];
            if ($this->peek()->getType() === Token::PH_S_LEFT_CURLY_BRACE)
            {
                $leftBrace = $this->read();
                assert(false); // TODO
                $rightBrace = $this->read(Token::PH_S_RIGHT_CURLY_BRACE);
            }
            else
            {
                $semiColon = $this->read(Token::PH_S_SEMICOLON);
            }
            return Nodes\TraitUse::__instantiateUnchecked($this->phpVersion,
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
            $variable = $this->read(Token::PH_T_VARIABLE);
            $default = $this->default_();
            $semiColon = $this->read(Token::PH_S_SEMICOLON);
            return Nodes\Property::__instantiateUnchecked($this->phpVersion, $modifiers, $variable, $default, $semiColon);
        }
    }

    private function endOfStatement(): bool
    {
        $t = $this->peek()->getType();
        return $t === Token::PH_S_SEMICOLON || $t === Token::PH_T_CLOSE_TAG || $t === Token::PH_T_EOF;
    }

    private function statementDelimiter(): ?Token
    {
        if ($this->peek()->getType() === Token::PH_T_CLOSE_TAG || $this->peek()->getType() === Token::PH_T_EOF)
        {
            return null;
        }
        else
        {
            return $this->read(Token::PH_S_SEMICOLON);
        }
    }

    private const PRECEDENCE_ASSIGN_LVALUE = 70;
    private const PRECEDENCE_POW = 62;
    private const PRECEDENCE_CAST = 61;
    private const PRECEDENCE_INSTANCEOF = 60;
    private const PRECEDENCE_BOOLEAN_NOT = 50;
    private const PRECEDENCE_MUL = 49;
    private const PRECEDENCE_PLUS = 48;
    private const PRECEDENCE_SHIFT = 47;
    private const PRECEDENCE_COMPARISON2 = 37;
    private const PRECEDENCE_COMPARISON1 = 36;
    private const PRECEDENCE_BITWISE_AND = 35;
    private const PRECEDENCE_BITWISE_XOR = 34;
    private const PRECEDENCE_BITWISE_OR = 33;
    private const PRECEDENCE_BOOLEAN_AND_SYMBOL = 32;
    private const PRECEDENCE_BOOLEAN_OR_SYMBOL = 31;
    private const PRECEDENCE_COALESCE = 26;
    private const PRECEDENCE_TERNARY = 25;
    private const PRECEDENCE_ASSIGN_RVALUE = 24;
    private const PRECEDENCE_BOOLEAN_AND_KEYWORD = 13;
    private const PRECEDENCE_BOOLEAN_XOR_KEYWORD = 12;
    private const PRECEDENCE_BOOLEAN_OR_KEYWORD = 11;

    private function expression(int $minPrecedence = 0): Nodes\Expression
    {
        /* EXPR SHORTCUTS */

        if ($minPrecedence <= self::PRECEDENCE_ASSIGN_LVALUE)
        {
            $left = $this->simpleExpression();

            if ($this->peek()->getType() === Token::PH_S_EQUALS)
            {
                if ($this->peek(1)->getType() === Token::PH_S_AMPERSAND)
                {
                    $operator1 = $this->read();
                    $operator2 = $this->read();
                    $right = $this->simpleExpression();
                    $left = Nodes\AliasingExpression::__instantiateUnchecked($this->phpVersion, $left, $operator1, $operator2, $right);
                }
                else
                {
                    $operator = $this->read();
                    $value = $this->expression(self::PRECEDENCE_ASSIGN_RVALUE);
                    $left = Nodes\RegularAssignmentExpression::__instantiateUnchecked($this->phpVersion, $left, $operator, $value);
                }
            }
            else if (\in_array($this->peek()->getType(), Token::COMBINED_ASSIGNMENT, true))
            {
                $operator = $this->read();
                $value = $this->expression(self::PRECEDENCE_ASSIGN_RVALUE);
                $left = Nodes\CombinedAssignmentExpression::__instantiateUnchecked($this->phpVersion, $left, $operator, $value);
            }
        }
        else
        {
            $left = $this->simpleExpression();
        }

        /* TODO test and check perf
        if (substr($this->typezip, $this->i * 3, 3) === '000')
        {
            return $left;
        }
        //*/

        while (true)
        {
            if ($minPrecedence <= self::PRECEDENCE_POW && $this->peek()->getType() === Token::PH_T_POW)
            {
                $operator = $this->read();
                $right = $this->expression(self::PRECEDENCE_POW);
                $left = Nodes\PowerExpression::__instantiateUnchecked($this->phpVersion, $left, $operator, $right);
            }
            else if ($minPrecedence <= self::PRECEDENCE_INSTANCEOF && $this->peek()->getType() === Token::PH_T_INSTANCEOF)
            {
                $operator = $this->read();
                $type = $this->simpleExpression(true);
                $left = Nodes\InstanceofExpression::__instantiateUnchecked($this->phpVersion, $left, $operator, $type);
            }
            else
            {
                break;
            }
        }

        if ($minPrecedence <= self::PRECEDENCE_MUL)
        {
            while (true)
            {
                if ($this->peek()->getType() === Token::PH_S_ASTERISK)
                {
                    $operator = $this->read();
                    $right = $this->expression(self::PRECEDENCE_MUL + 1);
                    $left = Nodes\MultiplyExpression::__instantiateUnchecked($this->phpVersion, $left, $operator, $right);
                }
                else if ($this->peek()->getType() === Token::PH_S_FORWARD_SLASH)
                {
                    $operator = $this->read();
                    $right = $this->expression(self::PRECEDENCE_MUL + 1);
                    $left = Nodes\DivideExpression::__instantiateUnchecked($this->phpVersion, $left, $operator, $right);
                }
                else if ($this->peek()->getType() === Token::PH_S_MODULO)
                {
                    $operator = $this->read();
                    $right = $this->expression(self::PRECEDENCE_MUL + 1);
                    $left = Nodes\ModuloExpression::__instantiateUnchecked($this->phpVersion, $left, $operator, $right);
                }
                else
                {
                    break;
                }
            }
        }

        if ($minPrecedence <= self::PRECEDENCE_PLUS)
        {
            while (true)
            {
                if ($this->peek()->getType() === Token::PH_S_PLUS)
                {
                    $operator = $this->read();
                    $right = $this->expression(self::PRECEDENCE_PLUS + 1);
                    $left = Nodes\AddExpression::__instantiateUnchecked($this->phpVersion, $left, $operator, $right);
                }
                else if ($this->peek()->getType() === Token::PH_S_MINUS)
                {
                    $operator = $this->read();
                    $right = $this->expression(self::PRECEDENCE_PLUS + 1);
                    $left = Nodes\SubtractExpression::__instantiateUnchecked($this->phpVersion, $left, $operator, $right);
                }
                else if ($this->peek()->getType() === Token::PH_S_DOT)
                {
                    $operator = $this->read();
                    $right = $this->expression(self::PRECEDENCE_PLUS + 1);
                    $left = Nodes\ConcatExpression::__instantiateUnchecked($this->phpVersion, $left, $operator, $right);
                }
                else
                {
                    break;
                }
            }
        }

        if ($minPrecedence <= self::PRECEDENCE_SHIFT)
        {
            while (true)
            {
                if ($this->peek()->getType() === Token::PH_T_SL)
                {
                    $operator = $this->read();
                    $right = $this->expression(self::PRECEDENCE_SHIFT + 1);
                    $left = Nodes\ShiftLeftExpression::__instantiateUnchecked($this->phpVersion, $left, $operator, $right);
                }
                if ($this->peek()->getType() === Token::PH_T_SR)
                {
                    $operator = $this->read();
                    $right = $this->expression(self::PRECEDENCE_SHIFT + 1);
                    $left = Nodes\ShiftRightExpression::__instantiateUnchecked($this->phpVersion, $left, $operator, $right);
                }
                else
                {
                    break;
                }
            }
        }

        if ($minPrecedence <= self::PRECEDENCE_COMPARISON2)
        {
            if ($this->peek()->getType() === Token::PH_S_LT)
            {
                $operator = $this->read();
                $right = $this->expression(self::PRECEDENCE_COMPARISON2 + 1);
                $left = Nodes\LessThanExpression::__instantiateUnchecked($this->phpVersion, $left, $operator, $right);
            }
            else if ($this->peek()->getType() === Token::PH_T_IS_SMALLER_OR_EQUAL)
            {
                $operator = $this->read();
                $right = $this->expression(self::PRECEDENCE_COMPARISON2 + 1);
                $left = Nodes\LessThanOrEqualsExpression::__instantiateUnchecked($this->phpVersion, $left, $operator, $right);
            }
            else if ($this->peek()->getType() === Token::PH_S_GT)
            {
                $operator = $this->read();
                $right = $this->expression(self::PRECEDENCE_COMPARISON2 + 1);
                $left = Nodes\GreaterThanExpression::__instantiateUnchecked($this->phpVersion, $left, $operator, $right);
            }
            else if ($this->peek()->getType() === Token::PH_T_IS_GREATER_OR_EQUAL)
            {
                $operator = $this->read();
                $right = $this->expression(self::PRECEDENCE_COMPARISON2 + 1);
                $left = Nodes\GreaterThanOrEqualsExpression::__instantiateUnchecked($this->phpVersion, $left, $operator, $right);
            }
        }

        if ($minPrecedence <= self::PRECEDENCE_COMPARISON1)
        {
            if ($this->peek()->getType() === Token::PH_T_IS_IDENTICAL)
            {
                $operator = $this->read();
                $right = $this->expression(self::PRECEDENCE_COMPARISON1 + 1);
                $left = Nodes\IsIdenticalExpression::__instantiateUnchecked($this->phpVersion, $left, $operator, $right);
            }
            else if ($this->peek()->getType() === Token::PH_T_IS_NOT_IDENTICAL)
            {
                $operator = $this->read();
                $right = $this->expression(self::PRECEDENCE_COMPARISON1 + 1);
                $left = Nodes\IsNotIdenticalExpression::__instantiateUnchecked($this->phpVersion, $left, $operator, $right);
            }
            else if ($this->peek()->getType() === Token::PH_T_IS_EQUAL)
            {
                $operator = $this->read();
                $right = $this->expression(self::PRECEDENCE_COMPARISON1 + 1);
                $left = Nodes\IsEqualExpression::__instantiateUnchecked($this->phpVersion, $left, $operator, $right);
            }
            else if ($this->peek()->getType() === Token::PH_T_IS_NOT_EQUAL)
            {
                $operator = $this->read();
                $right = $this->expression(self::PRECEDENCE_COMPARISON1 + 1);
                $left = Nodes\IsNotEqualExpression::__instantiateUnchecked($this->phpVersion, $left, $operator, $right);
            }
            else if ($this->peek()->getType() === Token::PH_T_SPACESHIP)
            {
                $operator = $this->read();
                $right = $this->expression(self::PRECEDENCE_COMPARISON1 + 1);
                $left = Nodes\SpaceshipExpression::__instantiateUnchecked($this->phpVersion, $left, $operator, $right);
            }
        }

        if ($minPrecedence <= self::PRECEDENCE_BITWISE_AND)
        {
            while ($this->peek()->getType() === Token::PH_S_AMPERSAND)
            {
                $operator = $this->read();
                $right = $this->expression(self::PRECEDENCE_BITWISE_AND + 1);
                $left = Nodes\BitwiseAndExpression::__instantiateUnchecked($this->phpVersion, $left, $operator, $right);
            }
        }

        if ($minPrecedence <= self::PRECEDENCE_BITWISE_XOR)
        {
            while ($this->peek()->getType() === Token::PH_S_CARAT)
            {
                $operator = $this->read();
                $right = $this->expression(self::PRECEDENCE_BITWISE_XOR + 1);
                $left = Nodes\BitwiseXorExpression::__instantiateUnchecked($this->phpVersion, $left, $operator, $right);
            }
        }

        if ($minPrecedence <= self::PRECEDENCE_BITWISE_OR)
        {
            while ($this->peek()->getType() === Token::PH_S_VERTICAL_BAR)
            {
                $operator = $this->read();
                $right = $this->expression(self::PRECEDENCE_BITWISE_OR + 1);
                $left = Nodes\BitwiseOrExpression::__instantiateUnchecked($this->phpVersion, $left, $operator, $right);
            }
        }

        if ($minPrecedence <= self::PRECEDENCE_BOOLEAN_AND_SYMBOL)
        {
            while ($this->peek()->getType() === Token::PH_T_BOOLEAN_AND)
            {
                $operator = $this->read();
                $right = $this->expression(self::PRECEDENCE_BOOLEAN_AND_SYMBOL + 1);
                $left = Nodes\SymbolBooleanAndExpression::__instantiateUnchecked($this->phpVersion, $left, $operator, $right);
            }
        }

        if ($minPrecedence <= self::PRECEDENCE_BOOLEAN_OR_SYMBOL)
        {
            while ($this->peek()->getType() === Token::PH_T_BOOLEAN_OR)
            {
                $operator = $this->read();
                $right = $this->expression(self::PRECEDENCE_BOOLEAN_OR_SYMBOL + 1);
                $left = Nodes\SymbolBooleanOrExpression::__instantiateUnchecked($this->phpVersion, $left, $operator, $right);
            }
        }

        if ($minPrecedence <= self::PRECEDENCE_COALESCE)
        {
            while ($this->peek()->getType() === Token::PH_T_COALESCE)
            {
                $operator = $this->read();
                $right = $this->expression(self::PRECEDENCE_COALESCE + 1);
                $left = Nodes\CoalesceExpression::__instantiateUnchecked($this->phpVersion, $left, $operator, $right);
            }
        }

        if ($minPrecedence <= self::PRECEDENCE_TERNARY)
        {
            while ($this->peek()->getType() === Token::PH_S_QUESTION_MARK)
            {
                $questionMark = $this->read();
                // note: no precedence is passed here, delimiters on both sides allow for e.g. 1 ? 2 and 3 : 4
                $then = $this->peek()->getType() !== Token::PH_S_COLON ? $this->expression() : null;
                $colon = $this->read(Token::PH_S_COLON);
                $else = $this->expression(self::PRECEDENCE_TERNARY + 1);
                $left = Nodes\TernaryExpression::__instantiateUnchecked($this->phpVersion, $left, $questionMark, $then, $colon, $else);
            }
        }

        if ($minPrecedence <= self::PRECEDENCE_BOOLEAN_AND_KEYWORD)
        {
            while ($this->peek()->getType() === Token::PH_T_LOGICAL_AND)
            {
                $operator = $this->read();
                $right = $this->expression(self::PRECEDENCE_BOOLEAN_AND_KEYWORD + 1);
                $left = Nodes\KeywordBooleanAndExpression::__instantiateUnchecked($this->phpVersion, $left, $operator, $right);
            }
        }

        if ($minPrecedence <= self::PRECEDENCE_BOOLEAN_XOR_KEYWORD)
        {
            while ($this->peek()->getType() === Token::PH_T_LOGICAL_XOR)
            {
                $operator = $this->read();
                $right = $this->expression(self::PRECEDENCE_BOOLEAN_XOR_KEYWORD + 1);
                $left = Nodes\KeywordBooleanXorExpression::__instantiateUnchecked($this->phpVersion, $left, $operator, $right);
            }
        }

        if ($minPrecedence <= self::PRECEDENCE_BOOLEAN_OR_KEYWORD)
        {
            while ($this->peek()->getType() === Token::PH_T_LOGICAL_OR)
            {
                $operator = $this->read();
                $right = $this->expression(self::PRECEDENCE_BOOLEAN_OR_KEYWORD + 1);
                $left = Nodes\KeywordBooleanOrExpression::__instantiateUnchecked($this->phpVersion, $left, $operator, $right);
            }
        }

        return $left;
    }

    private function simpleExpression(bool $newable = false): Nodes\Expression
    {
        if ($this->peek()->getType() === Token::PH_T_VARIABLE)
        {
            $node = Nodes\RegularVariableExpression::__instantiateUnchecked($this->phpVersion, $this->read());
        }
        else if ($this->peek()->getType() === Token::PH_S_DOLLAR)
        {
            $node = $this->variableVariable();
        }
        else if ($this->peek()->getType() === Token::PH_T_STRING || $this->peek()->getType() === Token::PH_T_NS_SEPARATOR)
        {
            $node = Nodes\NameExpression::__instantiateUnchecked($this->phpVersion, $this->name());
        }
        else if ($this->peek()->getType() === Token::PH_T_STATIC && $this->peek(1)->getType() !== Token::PH_T_FUNCTION)
        {
            $node = Nodes\NameExpression::__instantiateUnchecked($this->phpVersion, Nodes\SpecialName::__instantiateUnchecked($this->phpVersion, $this->read()));
        }
        else if ($newable)
        {
            throw ParseException::unexpected($this->peek());
        }
        else if ($this->peek()->getType() === Token::PH_T_CONSTANT_ENCAPSED_STRING)
        {
            $node = Nodes\ConstantStringLiteral::__instantiateUnchecked($this->phpVersion, $this->read());
        }
        else if ($this->peek()->getType() === Token::PH_S_DOUBLE_QUOTE || $this->peek()->getType() === Token::PH_T_START_HEREDOC)
        {
            $leftDelimiter = $this->read();
            $parts = [];
            while ($this->peek()->getType() !== Token::PH_S_DOUBLE_QUOTE && $this->peek()->getType() !== Token::PH_T_END_HEREDOC)
            {
                if ($this->peek()->getType() === Token::PH_T_ENCAPSED_AND_WHITESPACE)
                {
                    $parts[] = Nodes\ConstantInterpolatedStringPart::__instantiateUnchecked($this->phpVersion, $this->read());
                }
                else if ($this->peek()->getType() === Token::PH_T_CURLY_OPEN)
                {
                    $leftBrace = $this->read();
                    $expression = $this->expression();
                    $rightBrace = $this->read(Token::PH_S_RIGHT_CURLY_BRACE);
                    $parts[] = Nodes\ComplexInterpolatedStringExpression::__instantiateUnchecked($this->phpVersion, $leftBrace, $expression, $rightBrace);
                }
                else
                {
                    $parts[] = Nodes\SimpleInterpolatedStringExpression::__instantiateUnchecked($this->phpVersion, $this->expression());
                }
            }
            $rightDelimiter = $this->read();
            $node = Nodes\InterpolatedString::__instantiateUnchecked($this->phpVersion, $leftDelimiter, $parts, $rightDelimiter);
        }
        else if ($this->peek()->getType() === Token::PH_T_START_HEREDOC)
        {
            $this->read()->debugDump();
            var_dump(__FILE__, __LINE__);
            die(); // TODO
        }
        else if ($this->peek()->getType() === Token::PH_T_LNUMBER)
        {
            $node = Nodes\IntegerLiteral::__instantiateUnchecked($this->phpVersion, $this->read());
        }
        else if ($this->peek()->getType() === Token::PH_T_DNUMBER)
        {
            $node = Nodes\FloatLiteral::__instantiateUnchecked($this->phpVersion, $this->read());
        }
        else if ($this->peek()->getType() === Token::PH_T_NEW)
        {
            $keyword = $this->read();
            $class = $this->simpleExpression(true);
            $leftParenthesis = $rightParenthesis = null;
            $arguments = [];
            if ($this->peek()->getType() === Token::PH_S_LEFT_PAREN)
            {
                $leftParenthesis = $this->read();
                $arguments = $this->arguments();
                $rightParenthesis = $this->read(Token::PH_S_RIGHT_PAREN);
            }
            $node = Nodes\NewExpression::__instantiateUnchecked($this->phpVersion, $keyword, $class, $leftParenthesis, $arguments, $rightParenthesis);
        }
        else if ($this->peek()->getType() === Token::PH_S_LEFT_SQUARE_BRACKET)
        {
            $leftBracket = $this->read();
            $items = $this->arrayItems(Token::PH_S_RIGHT_SQUARE_BRACKET);
            $rightBracket = $this->read(Token::PH_S_RIGHT_SQUARE_BRACKET);
            $node = Nodes\ShortArrayExpression::__instantiateUnchecked($this->phpVersion, $leftBracket, $items, $rightBracket);
        }
        else if ($this->peek()->getType() === Token::PH_T_ARRAY)
        {
            $keyword = $this->read();
            $leftParenthesis = $this->read(Token::PH_S_LEFT_PAREN);
            $items = $this->arrayItems(Token::PH_S_RIGHT_PAREN);
            $rightParenthesis = $this->read(Token::PH_S_RIGHT_PAREN);
            $node = Nodes\LongArrayExpression::__instantiateUnchecked($this->phpVersion, $keyword, $leftParenthesis, $items, $rightParenthesis);
        }
        else if ($this->peek()->getType() === Token::PH_S_LEFT_PAREN)
        {
            $leftParenthesis = $this->read();
            $expression = $this->expression();
            $rightParenthesis = $this->read(Token::PH_S_RIGHT_PAREN);
            $node = Nodes\ParenthesizedExpression::__instantiateUnchecked($this->phpVersion, $leftParenthesis, $expression, $rightParenthesis);
        }
        else if ($this->peek()->getType() === Token::PH_T_ISSET)
        {
            $keyword = $this->read();
            $leftParenthesis = $this->read(Token::PH_S_LEFT_PAREN);
            $expressions = [null];
            do
            {
                $expressions[] = $this->expression();
            }
            while ($this->peek()->getType() === Token::PH_S_COMMA && $expressions[] = $this->read());
            $rightParenthesis = $this->read(Token::PH_S_RIGHT_PAREN);
            $node = Nodes\IssetExpression::__instantiateUnchecked($this->phpVersion, $keyword, $leftParenthesis, $expressions, $rightParenthesis);
        }
        else if ($this->peek()->getType() === Token::PH_T_EMPTY)
        {
            $keyword = $this->read();
            $leftParenthesis = $this->read(Token::PH_S_LEFT_PAREN);
            $expression = $this->expression();
            $rightParenthesis = $this->read(Token::PH_S_RIGHT_PAREN);
            $node = Nodes\EmptyExpression::__instantiateUnchecked($this->phpVersion, $keyword, $leftParenthesis, $expression, $rightParenthesis);
        }
        else if ($this->peek()->getType() === Token::PH_T_DEC)
        {
            $operator = $this->read();
            $expression = $this->simpleExpression();
            $node = Nodes\PreDecrementExpression::__instantiateUnchecked($this->phpVersion, $operator, $expression);
        }
        else if ($this->peek()->getType() === Token::PH_T_INC)
        {
            $operator = $this->read();
            $expression = $this->simpleExpression();
            $node = Nodes\PreIncrementExpression::__instantiateUnchecked($this->phpVersion, $operator, $expression);
        }
        else if (\in_array($this->peek()->getType(), Token::MAGIC_CONSTANTS, true))
        {
            $node = Nodes\MagicConstant::__instantiateUnchecked($this->phpVersion, $this->read());
        }
        else if ($this->peek()->getType() === Token::PH_T_CLONE)
        {
            $keyword = $this->read();
            $expression = $this->expression(self::PRECEDENCE_ASSIGN_LVALUE);
            $node = Nodes\CloneExpression::__instantiateUnchecked($this->phpVersion, $keyword, $expression);
        }
        else if ($this->peek()->getType() === Token::PH_S_EXCLAMATION_MARK)
        {
            $operator = $this->read();
            $expression = $this->expression(self::PRECEDENCE_BOOLEAN_NOT);
            $node = Nodes\BooleanNotExpression::__instantiateUnchecked($this->phpVersion, $operator, $expression);
        }
        else if ($this->peek()->getType() === Token::PH_T_YIELD)
        {
            $keyword = $this->read();
            $key = null;
            $expression = $this->expression(self::PRECEDENCE_TERNARY);
            if ($this->peek()->getType() === Token::PH_T_DOUBLE_ARROW)
            {
                $key = Nodes\Key::__instantiateUnchecked($this->phpVersion, $expression, $this->read());
                $expression = $this->expression(self::PRECEDENCE_TERNARY);
            }
            $node = Nodes\YieldExpression::__instantiateUnchecked($this->phpVersion, $keyword, $key, $expression);
        }
        else if ($this->peek()->getType() === Token::PH_T_YIELD_FROM)
        {
            $keyword = $this->read();
            $expression = $this->expression(self::PRECEDENCE_TERNARY);
            $node = Nodes\YieldFromExpression::__instantiateUnchecked($this->phpVersion, $keyword, $expression);
        }
        else if (in_array($this->peek()->getType(), [Token::PH_T_INCLUDE, Token::PH_T_INCLUDE_ONCE, Token::PH_T_REQUIRE, Token::PH_T_REQUIRE_ONCE], true))
        {
            $keyword = $this->read();
            $expression = $this->expression();
            $node = Nodes\IncludeLikeExpression::__instantiateUnchecked($this->phpVersion, $keyword, $expression);
        }
        else if ($this->peek()->getType() === Token::PH_S_TILDE)
        {
            $symbol = $this->read();
            $expression = $this->expression(self::PRECEDENCE_ASSIGN_LVALUE);
            $node = Nodes\BitwiseNotExpression::__instantiateUnchecked($this->phpVersion, $symbol, $expression);
        }
        else if ($this->peek()->getType() === Token::PH_S_MINUS)
        {
            $symbol = $this->read();
            $expression = $this->expression(self::PRECEDENCE_ASSIGN_LVALUE);
            $node = Nodes\UnaryMinusExpression::__instantiateUnchecked($this->phpVersion, $symbol, $expression);
        }
        else if ($this->peek()->getType() === Token::PH_S_PLUS)
        {
            $symbol = $this->read();
            $expression = $this->expression(self::PRECEDENCE_ASSIGN_LVALUE);
            $node = Nodes\UnaryPlusExpression::__instantiateUnchecked($this->phpVersion, $symbol, $expression);
        }
        else if (\in_array($this->peek()->getType(), Token::CASTS, true))
        {
            $cast = $this->read();
            $expression = $this->expression(self::PRECEDENCE_CAST);
            $node = Nodes\CastExpression::__instantiateUnchecked($this->phpVersion, $cast, $expression);
        }
        else if ($this->peek()->getType() === Token::PH_S_AT)
        {
            $operator = $this->read();
            $expression = $this->expression(self::PRECEDENCE_POW); // TODO test precedence...
            $node = Nodes\SuppressErrorsExpression::__instantiateUnchecked($this->phpVersion, $operator, $expression);
        }
        else if ($this->peek()->getType() === Token::PH_T_LIST)
        {
            $keyword = $this->read();
            $leftParenthesis = $this->read(Token::PH_S_LEFT_PAREN);
            $expressions = [null];
            do
            {
                $expressions[] = $this->simpleExpression();
            }
            while ($this->peek()->getType() === Token::PH_S_COMMA && $expressions[] = $this->read());
            $rightParenthesis = $this->read(Token::PH_S_RIGHT_PAREN);
            $node = Nodes\ListExpression::__instantiateUnchecked($this->phpVersion, $keyword, $leftParenthesis, $expressions, $rightParenthesis);
        }
        else if ($this->peek()->getType() === Token::PH_T_EXIT)
        {
            $keyword = $this->read();
            $leftParenthesis = $this->read(Token::PH_S_LEFT_PAREN);
            $expression = $this->peek()->getType() !== Token::PH_S_RIGHT_PAREN ? $this->expression() : null;
            $rightParenthesis = $this->read(Token::PH_S_RIGHT_PAREN);
            $node = Nodes\ExitExpression::__instantiateUnchecked($this->phpVersion, $keyword, $leftParenthesis, $expression, $rightParenthesis);
        }
        else if ($this->peek()->getType() === Token::PH_T_PRINT)
        {
            $keyword = $this->read();
            $expression = $this->expression(self::PRECEDENCE_BOOLEAN_NOT);
            $node = Nodes\PrintExpression::__instantiateUnchecked($this->phpVersion, $keyword, $expression);
        }
        else if ($this->peek()->getType() === Token::PH_T_EVAL)
        {
            $keyword = $this->read();
            $leftParenthesis = $this->read(Token::PH_S_LEFT_PAREN);
            $expression = $this->expression();
            $rightParenthesis = $this->read(Token::PH_S_RIGHT_PAREN);
            $node = Nodes\EvalExpression::__instantiateUnchecked($this->phpVersion, $keyword, $leftParenthesis, $expression, $rightParenthesis);
        }
        else if (
            $this->peek()->getType() === Token::PH_T_FUNCTION ||
            ($this->peek()->getType() === Token::PH_T_STATIC && $this->peek(1)->getType() === Token::PH_T_FUNCTION)
        )
        {
            $static = $this->peek()->getType() === Token::PH_T_STATIC ? $this->read() : null;
            $keyword = $this->read();
            $byReference = $this->peek()->getType() === Token::PH_S_AMPERSAND ? $this->read() : null;
            $leftParenthesis = $this->read(Token::PH_S_LEFT_PAREN);
            $parameters = $this->parameters();
            $rightParenthesis = $this->read(Token::PH_S_RIGHT_PAREN);
            $use = null;
            if ($this->peek()->getType() === Token::PH_T_USE)
            {
                $useKeyword = $this->read();
                $useLeftParenthesis = $this->read(Token::PH_S_LEFT_PAREN);
                $useBindings = [null];
                while (true)
                {
                    $useBindingByReference = null;
                    if ($this->peek()->getType() === Token::PH_S_AMPERSAND)
                    {
                        $useBindingByReference = $this->read();
                    }
                    $useBindingVariable = $this->read(Token::PH_T_VARIABLE);
                    $useBindings[] = Nodes\AnonymousFunctionUseBinding::__instantiateUnchecked($this->phpVersion, $useBindingByReference, $useBindingVariable);

                    if ($this->peek()->getType() === Token::PH_S_COMMA)
                    {
                        $useBindings[] = $this->read();
                    }
                    else
                    {
                        break;
                    }
                }
                $useRightParenthesis = $this->read(Token::PH_S_RIGHT_PAREN);
                $use = Nodes\AnonymousFunctionUse::__instantiateUnchecked($this->phpVersion, $useKeyword, $useLeftParenthesis, $useBindings, $useRightParenthesis);
            }
            $returnType = $this->returnType();

            $body = $this->functionBlock();

            $node = Nodes\AnonymousFunctionExpression::__instantiateUnchecked($this->phpVersion,
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
            if ($this->peek()->getType() === Token::PH_T_OBJECT_OPERATOR)
            {
                $operator = $this->read();

                $name = $this->memberName();
                // expr->v -> access the property named 'v'
                // new expr->v -> instantiate the class named by expr->v
                // new expr->v() -> same, () is part of the NewExpression
                if ($this->peek()->getType() !== Token::PH_S_LEFT_PAREN || $newable)
                {
                    $node = Nodes\PropertyAccessExpression::__instantiateUnchecked($this->phpVersion, $node, $operator, $name);
                }
                // expr->v() -> call the method named v
                else
                {
                    $leftParenthesis = $this->read();
                    $arguments = $this->arguments();
                    $rightParenthesis = $this->read(Token::PH_S_RIGHT_PAREN);
                    $node = Nodes\MethodCallExpression::__instantiateUnchecked($this->phpVersion, $node, $operator, $name, $leftParenthesis, $arguments, $rightParenthesis);
                }
            }
            else if ($this->peek()->getType() === Token::PH_S_LEFT_PAREN)
            {
                // new expr() -> () always belongs to new
                if ($newable)
                {
                    break;
                }

                $leftParenthesis = $this->read();
                $arguments = $this->arguments();
                $rightParenthesis = $this->read(Token::PH_S_RIGHT_PAREN);
                $node = Nodes\FunctionCallExpression::__instantiateUnchecked($this->phpVersion, $node, $leftParenthesis, $arguments, $rightParenthesis);
            }
            else if ($this->peek()->getType() === Token::PH_S_LEFT_SQUARE_BRACKET)
            {
                $leftBracket = $this->read();
                $index = $this->peek()->getType() === Token::PH_S_RIGHT_SQUARE_BRACKET ? null : $this->expression();
                $rightBracket = $this->read(Token::PH_S_RIGHT_SQUARE_BRACKET);
                $node = Nodes\ArrayAccessExpression::__instantiateUnchecked($this->phpVersion, $node, $leftBracket, $index, $rightBracket);
            }
            else if ($this->peek()->getType() === Token::PH_T_DOUBLE_COLON)
            {
                // TODO manual test coverage for error messages, esp. in combination with new

                $operator = $this->read();

                switch ($this->peek()->getType())
                {
                    /** @noinspection PhpMissingBreakStatementInspection */
                    case Token::PH_T_STRING:
                        $name = $this->read();
                        // new expr::a -> parse error
                        // new expr::a() -> parse error
                        if ($newable)
                        {
                            throw ParseException::unexpected($this->peek());
                        }
                        // expr::a -> access constant 'a'
                        else if ($this->peek()->getType() !== Token::PH_S_LEFT_PAREN)
                        {
                            $node = Nodes\ConstantAccessExpression::__instantiateUnchecked($this->phpVersion, $node, $operator, $name);
                            break;
                        }
                        // expr::a() -> call static method 'a'
                        else
                        {
                            $memberName = Nodes\RegularMemberName::__instantiateUnchecked($this->phpVersion, $name);
                            goto staticCall;
                        }

                    /** @noinspection PhpMissingBreakStatementInspection */
                    case Token::PH_T_VARIABLE:
                        $variable = Nodes\RegularVariableExpression::__instantiateUnchecked($this->phpVersion, $this->read());
                        foundVariable:
                        // expr::$v -> access the static property named 'v'
                        // new expr::$v -> instantiate the class named by the value of expr::$v
                        // new expr::$v() -> same, () is part of the NewExpression
                        if ($this->peek()->getType() !== Token::PH_S_LEFT_PAREN || $newable)
                        {
                            $node = Nodes\StaticPropertyAccessExpression::__instantiateUnchecked($this->phpVersion, $node, $operator, $variable);
                            break;
                        }
                        // expr::$v() -> $v refers to method named by the value of the variable $v
                        else
                        {
                            $memberName = Nodes\VariableMemberName::__instantiateUnchecked($this->phpVersion, null, $variable, null);
                            goto staticCall;
                        }

                    /** @noinspection PhpMissingBreakStatementInspection */
                    case Token::PH_S_DOLLAR:
                        $variable = $this->variableVariable();
                        // all variations are the same as `expr::$v`, except the variable is variable
                        goto foundVariable;

                    case Token::PH_S_LEFT_CURLY_BRACE:
                        $memberName = $this->memberName();
                        // expr::{expr} -> parse error
                        // new expr::{expr} -> parse error
                        // new expr::{expr}() -> parse error
                        if ($this->peek()->getType() !== Token::PH_S_LEFT_PAREN || $newable)
                        {
                            throw ParseException::unexpected($this->peek());
                        }
                        // expr::{expr2}() -> call static method named by the value of expr2
                        else
                        {
                            goto staticCall;
                        }

                    staticCall:
                        // we jump here when we positively decide on a static call, and have set up $memberName
                        /** @var Nodes\MemberName $memberName */
                        $leftParenthesis = $this->read(Token::PH_S_LEFT_PAREN);
                        $arguments = $this->arguments();
                        $rightParenthesis = $this->read(Token::PH_S_RIGHT_PAREN);
                        $node = Nodes\StaticMethodCallExpression::__instantiateUnchecked($this->phpVersion, $node, $operator, $memberName, $leftParenthesis, $arguments, $rightParenthesis);
                        break;

                    // TODO case T_STATIC etc
                    // which is either an access for the constant static or a call for the method static
                    // we'll probaby want to goto into to the T_STRING case here...
                    default:
                        if ($this->peek()->getSource() === 'class')
                        {
                            // TODO what is this!? undoing of Lexer/$forceIdentifier? maybe we should force the identifier in the parser
                            // TODO property handle reserved, semi reserved
                            $keyword = $this->read()->_withType(Token::PH_T_CLASS);
                            $node = Nodes\ClassNameResolutionExpression::__instantiateUnchecked($this->phpVersion, $node, $operator, $keyword);
                            break;
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

        if ($this->peek()->getType() === Token::PH_T_DEC)
        {
            $operator = $this->read();
            $node = Nodes\PostDecrementExpression::__instantiateUnchecked($this->phpVersion, $node, $operator);
        }
        else if ($this->peek()->getType() === Token::PH_T_INC)
        {
            $operator = $this->read();
            $node = Nodes\PostIncrementExpression::__instantiateUnchecked($this->phpVersion, $node, $operator);
        }

        return $node;
    }

    private function block(int $level = self::STMT_LEVEL_OTHER): Nodes\Block
    {
        if ($this->peek()->getType() === Token::PH_S_LEFT_CURLY_BRACE)
        {
            return $this->regularBlock($level);
        }
        else
        {
            return Nodes\ImplicitBlock::__instantiateUnchecked($this->phpVersion, $this->statement());
        }
    }

    private function functionBlock(): Nodes\RegularBlock
    {
        $loopDepth = $this->loopDepth;
        $this->loopDepth = 0;
        $block = $this->regularBlock();
        $this->loopDepth = $loopDepth;
        return $block;
    }

    private function regularBlock(int $level = self::STMT_LEVEL_OTHER): Nodes\RegularBlock
    {
        $leftBrace = $this->read();
        $statements = [];
        while ($this->peek()->getType() !== Token::PH_S_RIGHT_CURLY_BRACE)
        {
            $statements[] = $this->statement($level);
        }
        $rightBrace = $this->read(Token::PH_S_RIGHT_CURLY_BRACE);
        return Nodes\RegularBlock::__instantiateUnchecked($this->phpVersion, $leftBrace, $statements, $rightBrace);
    }

    private function altBlock(int $endKeywordType, array $implicitEndKeywords = []): Nodes\AlternativeFormatBlock
    {
        $colon = $this->read(Token::PH_S_COLON);

        $statements = [];
        while ($this->peek()->getType() !== $endKeywordType && !in_array($this->peek()->getType(), $implicitEndKeywords))
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

        return Nodes\AlternativeFormatBlock::__instantiateUnchecked($this->phpVersion, $colon, $statements, $endKeyword, $semiColon);
    }

    /** @return array<Node|null>[] */
    private function parameters(): array
    {
        $nodes = [null];
        if ($this->peek()->getType() !== Token::PH_S_RIGHT_PAREN)
        {
            $nodes[] = $this->parameter();
        }
        while ($this->peek()->getType() === Token::PH_S_COMMA)
        {
            $nodes[] = $this->read();
            $nodes[] = $this->parameter();
        }
        return $nodes;
    }

    private function parameter(): Nodes\Parameter
    {
        $type = $byReference = $ellipsis = null;

        if ($this->peek()->getType() !== Token::PH_S_AMPERSAND && $this->peek()->getType() !== Token::PH_T_VARIABLE && $this->peek()->getType() !== Token::PH_T_ELLIPSIS)
        {
            $type = $this->type();
        }

        if ($this->peek()->getType() === Token::PH_S_AMPERSAND)
        {
            $byReference = $this->read();
        }

        if ($this->peek()->getType() === Token::PH_T_ELLIPSIS)
        {
            $ellipsis = $this->read();
        }

        $variable = $this->read(Token::PH_T_VARIABLE);

        if ($ellipsis && $this->peek()->getType() === Token::PH_S_EQUALS)
        {
            throw ParseException::unexpected($this->peek());
        }
        $default = $this->default_();

        return Nodes\Parameter::__instantiateUnchecked($this->phpVersion, $type, $byReference, $ellipsis, $variable, $default);
    }

    /** @return array<Node|null>[] */
    private function arguments(): array
    {
        $arguments = [null];
        while ($this->peek()->getType() !== Token::PH_S_RIGHT_PAREN)
        {
            $unpack = $this->peek()->getType() === Token::PH_T_ELLIPSIS ? $this->read() : null;
            $value = $this->expression();
            $arguments[] = Nodes\Argument::__instantiateUnchecked($this->phpVersion, $unpack, $value);

            if ($this->peek()->getType() === Token::PH_S_COMMA)
            {
                $arguments[] = $this->read();

                // trailing comma is not allowed before 7.4
                if ($this->phpVersion < PhpVersion::PHP_7_4 && $this->peek()->getType() === Token::PH_S_RIGHT_PAREN)
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

    private function returnType(): ?Nodes\ReturnType
    {
        if ($this->peek()->getType() === Token::PH_S_COLON)
        {
            $symbol = $this->read();
            $type = $this->type();
            return Nodes\ReturnType::__instantiateUnchecked($this->phpVersion, $symbol, $type);
        }
        else
        {
            return null;
        }
    }

    private function type(): Nodes\Type
    {
        $nullableSymbol = $this->peek()->getType() === Token::PH_S_QUESTION_MARK ? $this->read() : null;

        if (in_array($this->peek()->getType(), [Token::PH_T_ARRAY, Token::PH_T_CALLABLE], true))
        {
            $type = Nodes\SpecialType::__instantiateUnchecked($this->phpVersion, $this->read());
        }
        else
        {
            $type = Nodes\NamedType::__instantiateUnchecked($this->phpVersion, $this->name());
        }

        if ($nullableSymbol)
        {
            $type = Nodes\NullableType::__instantiateUnchecked($this->phpVersion, $nullableSymbol, $type);
        }

        return $type;
    }

    private function name(): Nodes\Name
    {
        if ($this->peek()->getType() === Token::PH_T_STATIC)
        {
            return Nodes\SpecialName::__instantiateUnchecked($this->phpVersion, $this->read());
        }
        else
        {
            $parts = [];
            $parts[] = $this->peek()->getType() === Token::PH_T_NS_SEPARATOR ? $this->read() : null;
            $parts[] = $this->read(Token::PH_T_STRING);
            while ($this->peek()->getType() === Token::PH_T_NS_SEPARATOR && $this->peek(1)->getType() === Token::PH_T_STRING)
            {
                $parts[] = $this->read();
                $parts[] = $this->read();
            }
            return Nodes\RegularName::__instantiateUnchecked($this->phpVersion, $parts);
        }
    }

    private function default_(): ?Nodes\Default_
    {
        if ($this->peek()->getType() === Token::PH_S_EQUALS)
        {
            $symbol = $this->read();
            $value = $this->expression();
            return Nodes\Default_::__instantiateUnchecked($this->phpVersion, $symbol, $value);
        }
        else
        {
            return null;
        }
    }

    private function memberName(): Nodes\MemberName
    {
        if ($this->peek()->getType() === Token::PH_T_STRING)
        {
            return Nodes\RegularMemberName::__instantiateUnchecked($this->phpVersion, $this->read());
        }
        else if ($this->peek()->getType() === Token::PH_T_VARIABLE)
        {
            $expression = Nodes\RegularVariableExpression::__instantiateUnchecked($this->phpVersion, $this->read());
            return Nodes\VariableMemberName::__instantiateUnchecked($this->phpVersion, null, $expression, null);
        }
        else if ($this->peek()->getType() === Token::PH_S_DOLLAR)
        {
            $expression = $this->variableVariable();
            return Nodes\VariableMemberName::__instantiateUnchecked($this->phpVersion, null, $expression, null);
        }
        else if ($this->peek()->getType() === Token::PH_S_LEFT_CURLY_BRACE)
        {
            $leftBrace = $this->read();
            $expr = $this->expression();
            $rightBrace = $this->read(Token::PH_S_RIGHT_CURLY_BRACE);
            return Nodes\VariableMemberName::__instantiateUnchecked($this->phpVersion, $leftBrace, $expr, $rightBrace);
        }
        else
        {
            throw ParseException::unexpected($this->peek());
        }
    }

    private function variableVariable(): Nodes\VariableVariableExpression
    {
        $dollar = $this->read(Token::PH_S_DOLLAR);
        $leftBrace = $rightBrace = null;
        if ($this->peek()->getType() === Token::PH_T_VARIABLE)
        {
            $expression = Nodes\RegularVariableExpression::__instantiateUnchecked($this->phpVersion, $this->read());
        }
        else if ($this->peek()->getType() === Token::PH_S_DOLLAR)
        {
            $expression = $this->variableVariable();
        }
        else if ($this->peek()->getType() === Token::PH_S_LEFT_CURLY_BRACE)
        {
            $leftBrace = $this->read();
            $expression = $this->expression();
            $rightBrace = $this->read(Token::PH_S_RIGHT_CURLY_BRACE);
        }
        else
        {
            throw ParseException::unexpected($this->peek());
        }

        return Nodes\VariableVariableExpression::__instantiateUnchecked($this->phpVersion, $dollar, $leftBrace, $expression, $rightBrace);
    }

    /** @return array<Node|null>[] */
    private function arrayItems(int $delimiter): array
    {
        $items = [null];
        while ($this->peek()->getType() !== $delimiter)
        {
            $key = $byReference = $value = null;

            if ($this->peek()->getType() === Token::PH_S_AMPERSAND)
            {
                $byReference = $this->read();
            }

            if ($this->peek()->getType() !== Token::PH_S_COMMA && $this->peek()->getType() !== $delimiter)
            {
                $value = $this->expression();
            }

            if ($this->peek()->getType() === Token::PH_T_DOUBLE_ARROW)
            {
                if ($byReference)
                {
                    throw ParseException::unexpected($this->peek());
                }

                $key = Nodes\Key::__instantiateUnchecked($this->phpVersion, $value, $this->read());
                $value = null;
            }

            if ($this->peek()->getType() === Token::PH_S_AMPERSAND)
            {
                $byReference = $this->read();
            }

            if ($this->peek()->getType() !== Token::PH_S_COMMA && $this->peek()->getType() !== $delimiter)
            {
                $value = $this->expression();
            }

            $items[] = Nodes\ArrayItem::__instantiateUnchecked($this->phpVersion, $key, $byReference, $value);

            if ($this->peek()->getType() === Token::PH_S_COMMA)
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
}
