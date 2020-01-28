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
            if ($this->types[$this->i] !== TokenType::T_EOF)
            {
                throw ParseException::unexpected($this->tokens[$this->i]);
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
            if ($this->types[$this->i] !== TokenType::T_EOF)
            {
                throw ParseException::unexpected($this->tokens[$this->i]);
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
            while ($this->types[$this->i] !== TokenType::T_EOF)
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
            $node->setLeftBrace(new Token(TokenType::S_LEFT_CURLY_BRACE, '')); // TODO fix
            foreach ($statements as $statement)
            {
                $node->addStatement($statement);
            }
            $node->setRightBrace(new Token(TokenType::S_RIGHT_CURLY_BRACE, '')); // TODO fix
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

        $typezip = "";
        foreach ($this->types as $t)
        {
            $typezip .= \in_array($t, [
                TokenType::S_RIGHT_PAREN, TokenType::S_RIGHT_CURLY_BRACE, TokenType::S_RIGHT_SQUARE_BRACKET,
                TokenType::S_COMMA, TokenType::S_SEMICOLON, TokenType::S_COLON,
                TokenType::T_AS, TokenType::T_DOUBLE_ARROW,
            ], true) ? "000" : $t;
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

    private function read(int $expectedTokenType = null): Token
    {
        if ($expectedTokenType !== null && $this->types[$this->i] !== $expectedTokenType)
        {
            throw ParseException::unexpected($this->tokens[$this->i]);
        }

        return $this->tokens[$this->i++];
    }

    private function parseRoot(): Nodes\RootNode
    {
        $statements = [];

        while ($this->types[$this->i] !== TokenType::T_EOF)
        {
            $statements[] = $this->statement(self::STMT_LEVEL_TOP);
        }

        $eof = $this->read(TokenType::T_EOF);

        return Nodes\RootNode::__instantiateUnchecked($this->phpVersion, $statements, $eof);
    }

    private const STMT_LEVEL_TOP = 1;
    private const STMT_LEVEL_NAMESPACE = 2;
    private const STMT_LEVEL_OTHER = 3;

    private function statement(int $level = self::STMT_LEVEL_OTHER): Nodes\Statement
    {
        if ($this->types[$this->i] === TokenType::T_NAMESPACE)
        {
            if ($level !== self::STMT_LEVEL_TOP)
            {
                throw ParseException::unexpected($this->tokens[$this->i]);
            }

            return $this->namespace_();
        }
        else if ($this->types[$this->i] === TokenType::T_USE)
        {
            if ($level === self::STMT_LEVEL_OTHER)
            {
                throw ParseException::unexpected($this->tokens[$this->i]);
            }

            return $this->use_();
        }
        else if ($this->types[$this->i] === TokenType::T_ABSTRACT || $this->types[$this->i] === TokenType::T_CLASS || $this->types[$this->i] === TokenType::T_FINAL)
        {
            return $this->class_();
        }
        else if ($this->types[$this->i] === TokenType::T_INTERFACE)
        {
            return $this->interface_();
        }
        else if ($this->types[$this->i] === TokenType::T_TRAIT)
        {
            return $this->trait_();
        }
        else if ($this->types[$this->i] === TokenType::T_BREAK)
        {
            $keyword = $this->tokens[$this->i++];

            if (!$this->loopDepth)
            {
                throw ParseException::unexpected($keyword);
            }

            $levels = null;
            if ($this->types[$this->i] === TokenType::T_LNUMBER)
            {
                $levels = Nodes\IntegerLiteral::__instantiateUnchecked($this->phpVersion, $this->tokens[$this->i++]);
                if ($levels->getValue() <= 0 || $levels->getValue() > $this->loopDepth)
                {
                    throw ParseException::unexpected($levels->getToken());
                }
            }

            $semiColon = $this->statementDelimiter();
            return Nodes\BreakStatement::__instantiateUnchecked($this->phpVersion, $keyword, $levels, $semiColon);
        }
        else if ($this->types[$this->i] === TokenType::T_CONTINUE)
        {
            $keyword = $this->tokens[$this->i++];

            if (!$this->loopDepth)
            {
                throw ParseException::unexpected($keyword);
            }

            $levels = null;
            if ($this->types[$this->i] === TokenType::T_LNUMBER)
            {
                $levels = Nodes\IntegerLiteral::__instantiateUnchecked($this->phpVersion, $this->tokens[$this->i++]);
                if ($levels->getValue() <= 0 || $levels->getValue() > $this->loopDepth)
                {
                    throw ParseException::unexpected($levels->getToken());
                }
            }

            $semiColon = $this->statementDelimiter();
            return Nodes\ContinueStatement::__instantiateUnchecked($this->phpVersion, $keyword, $levels, $semiColon);
        }
        else if ($this->types[$this->i] === TokenType::T_DECLARE)
        {
            $keyword = $this->tokens[$this->i++];
            $leftParenthesis = $this->read(TokenType::S_LEFT_PAREN);
            $directives = [null];
            do
            {
                $directiveKey = $this->read(TokenType::T_STRING);
                $directiveEquals = $this->read(TokenType::S_EQUALS);
                $directiveValue = $this->expression();
                $directives[] = Nodes\DeclareDirective::__instantiateUnchecked($this->phpVersion, $directiveKey, $directiveEquals, $directiveValue);
            }
            while ($this->types[$this->i] === TokenType::S_COMMA && $directives[] = $this->tokens[$this->i++]);
            $rightParenthesis = $this->read(TokenType::S_RIGHT_PAREN);
            if (!$this->endOfStatement())
            {
                $block = $this->block($level); // TODO test level
                $semiColon = null;
            }
            else
            {
                $block = null;
                $semiColon = $this->statementDelimiter();
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
        else if ($this->types[$this->i] === TokenType::T_DO)
        {
            $keyword1 = $this->tokens[$this->i++];

            $this->loopDepth++;
            $block = $this->block();
            $this->loopDepth--;

            $keyword2 = $this->read(TokenType::T_WHILE);
            $leftParenthesis = $this->read(TokenType::S_LEFT_PAREN);
            $test = $this->expression();
            $rightParenthesis = $this->read(TokenType::S_RIGHT_PAREN);
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
        else if ($this->types[$this->i] === TokenType::T_ECHO || $this->types[$this->i] === TokenType::T_OPEN_TAG_WITH_ECHO)
        {
            $keyword = $this->tokens[$this->i++];
            $expressions = [null];
            $expressions[] = $this->expression();
            while ($this->types[$this->i] === TokenType::S_COMMA)
            {
                $expressions[] = $this->tokens[$this->i++];
                $expressions[] = $this->expression();
            }
            $semiColon = $this->statementDelimiter();
            return Nodes\EchoStatement::__instantiateUnchecked($this->phpVersion, $keyword, $expressions, $semiColon);
        }
        else if ($this->types[$this->i] === TokenType::T_FOR)
        {
            $keyword = $this->tokens[$this->i++];
            $leftParenthesis = $this->read(TokenType::S_LEFT_PAREN);

            $init = [null];
            if ($this->types[$this->i] !== TokenType::S_SEMICOLON)
            {
                $init[] = $this->expression();
                while ($this->types[$this->i] === TokenType::S_COMMA)
                {
                    $init[] = $this->tokens[$this->i++];
                    $init[] = $this->expression();
                }
            }

            $separator1 = $this->types[$this->i] === TokenType::S_COMMA ? $this->tokens[$this->i++] : $this->read(TokenType::S_SEMICOLON);

            $test = [null];
            if ($this->types[$this->i] !== TokenType::S_SEMICOLON)
            {
                $test[] = $this->expression();
                while ($this->types[$this->i] === TokenType::S_COMMA)
                {
                    $test[] = $this->tokens[$this->i++];
                    $test[] = $this->expression();
                }
            }

            $separator2 = $this->types[$this->i] === TokenType::S_COMMA ? $this->tokens[$this->i++] : $this->read(TokenType::S_SEMICOLON);

            $step = [null];
            if ($this->types[$this->i] !== TokenType::S_RIGHT_PAREN)
            {
                $step[] = $this->expression();
                while ($this->types[$this->i] === TokenType::S_COMMA)
                {
                    $step[] = $this->tokens[$this->i++];
                    $step[] = $this->expression();
                }
            }

            $rightParenthesis = $this->read(TokenType::S_RIGHT_PAREN);

            $this->loopDepth++;
            $block = $this->types[$this->i] === TokenType::S_COLON ? $this->altBlock(TokenType::T_ENDFOR) : $this->block();
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
        else if ($this->types[$this->i] === TokenType::T_FOREACH)
        {
            $keyword = $this->tokens[$this->i++];
            $leftParenthesis = $this->read(TokenType::S_LEFT_PAREN);
            $iterable = $this->expression();
            $as = $this->read(TokenType::T_AS);

            $key = $byReference = null;
            $byReference = $this->types[$this->i] === TokenType::S_AMPERSAND ? $this->tokens[$this->i++] : null;
            $value = $this->simpleExpression();

            if ($this->types[$this->i] === TokenType::T_DOUBLE_ARROW)
            {
                if ($byReference)
                {
                    throw ParseException::unexpected($this->tokens[$this->i]);
                }

                $key = Nodes\Key::__instantiateUnchecked($this->phpVersion, $value, $this->tokens[$this->i++]);

                if ($this->types[$this->i] === TokenType::S_AMPERSAND)
                {
                    $byReference = $this->tokens[$this->i++];
                }

                $value = $this->simpleExpression();
            }

            $rightParenthesis = $this->read(TokenType::S_RIGHT_PAREN);

            $this->loopDepth++;
            $block = $this->types[$this->i] === TokenType::S_COLON ? $this->altBlock(TokenType::T_ENDFOREACH) : $this->block();
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
            $this->types[$this->i] === TokenType::T_FUNCTION
            && (
                $this->types[$this->i + 1] === TokenType::T_STRING
                || $this->types[$this->i + 1] === TokenType::S_AMPERSAND && $this->types[$this->i + 2] === TokenType::T_STRING
            )
        )
        {
            $keyword = $this->tokens[$this->i++];
            $byReference = $this->types[$this->i] === TokenType::S_AMPERSAND ? $this->tokens[$this->i++] : null;
            $name = $this->tokens[$this->i++];
            $leftParenthesis = $this->read(TokenType::S_LEFT_PAREN);
            $parameters = $this->parameters();
            $rightParenthesis = $this->read(TokenType::S_RIGHT_PAREN);
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
        else if ($this->types[$this->i] === TokenType::T_GOTO)
        {
            $keyword = $this->tokens[$this->i++];
            $label = $this->read(TokenType::T_STRING);
            $semiColon = $this->statementDelimiter();
            return Nodes\GotoStatement::__instantiateUnchecked($this->phpVersion,
                $keyword,
                $label,
                $semiColon
            );
        }
        else if ($this->types[$this->i] === TokenType::T_IF)
        {
            $keyword = $this->tokens[$this->i++];
            $leftParenthesis = $this->read(TokenType::S_LEFT_PAREN);
            $test = $this->expression();
            $rightParenthesis = $this->read(TokenType::S_RIGHT_PAREN);
            if ($altSyntax = ($this->types[$this->i] === TokenType::S_COLON))
            {
                $block = $this->altBlock(TokenType::T_ENDIF, [TokenType::T_ELSE, TokenType::T_ELSEIF]);
            }
            else
            {
                $block = $this->block();
            }
            $elseifs = [];
            while ($this->types[$this->i] === TokenType::T_ELSEIF)
            {
                $elseifKeyword = $this->tokens[$this->i++];
                $elseifLeftParenthesis = $this->read(TokenType::S_LEFT_PAREN);
                $elseifTest = $this->expression();
                $elseifRightParenthesis = $this->read(TokenType::S_RIGHT_PAREN);
                $elseifBlock = $altSyntax ? $this->altBlock(TokenType::T_ENDIF, [TokenType::T_ELSE, TokenType::T_ELSEIF]) : $this->block();
                $elseifs[] = Nodes\Elseif_::__instantiateUnchecked($this->phpVersion,
                    $elseifKeyword,
                    $elseifLeftParenthesis,
                    $elseifTest,
                    $elseifRightParenthesis,
                    $elseifBlock
                );
            }
            $else = null;
            if ($this->types[$this->i] === TokenType::T_ELSE)
            {
                $elseKeyword = $this->tokens[$this->i++];
                $elseBlock = $altSyntax ? $this->altBlock(TokenType::T_ENDIF) : $this->block();
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
        else if ($this->types[$this->i] === TokenType::T_RETURN)
        {
            $keyword = $this->tokens[$this->i++];
            $expression = !$this->endOfStatement() ? $this->expression() : null;
            $semiColon = $this->statementDelimiter();
            return Nodes\ReturnStatement::__instantiateUnchecked($this->phpVersion, $keyword, $expression, $semiColon);
        }
        else if ($this->types[$this->i] === TokenType::T_STATIC && $this->types[$this->i + 1] === TokenType::T_VARIABLE)
        {
            $keyword = $this->tokens[$this->i++];
            $variables = [null];
            do
            {
                $variable = $this->read(TokenType::T_VARIABLE);
                $default = $this->default_();
                $variables[] = Nodes\StaticVariable::__instantiateUnchecked($this->phpVersion, $variable, $default);
            }
            while ($this->types[$this->i] === TokenType::S_COMMA && $variables[] = $this->tokens[$this->i++]);
            $semiColon = $this->statementDelimiter();
            return Nodes\StaticVariableDeclaration::__instantiateUnchecked($this->phpVersion, $keyword, $variables, $semiColon);
        }
        else if ($this->types[$this->i] === TokenType::T_SWITCH)
        {
            $keyword = $this->tokens[$this->i++];

            $leftParenthesis = $this->read(TokenType::S_LEFT_PAREN);
            $value = $this->expression();
            $rightParenthesis = $this->read(TokenType::S_RIGHT_PAREN);

            $this->loopDepth++;
            $leftBrace = $this->read(TokenType::S_LEFT_CURLY_BRACE);

            $cases = [];
            while ($this->types[$this->i] !== TokenType::T_DEFAULT && $this->types[$this->i] !== TokenType::S_RIGHT_CURLY_BRACE)
            {
                $caseKeyword = $this->read(TokenType::T_CASE);
                $caseValue = $this->expression();
                $caseDelimiter = $this->types[$this->i] === TokenType::S_SEMICOLON ? $this->tokens[$this->i++] : $this->read(TokenType::S_COLON);
                $caseStatements = [];
                while ($this->types[$this->i] !== TokenType::T_CASE && $this->types[$this->i] !== TokenType::T_DEFAULT && $this->types[$this->i] !== TokenType::S_RIGHT_CURLY_BRACE)
                {
                    $caseStatements[] = $this->statement();
                }
                $cases[] = Nodes\SwitchCase::__instantiateUnchecked($this->phpVersion, $caseKeyword, $caseValue, $caseDelimiter, $caseStatements);
            }
            $default = null;
            if ($this->types[$this->i] === TokenType::T_DEFAULT)
            {
                $defaultKeyword = $this->tokens[$this->i++];
                $defaultColon = $this->read(TokenType::S_COLON);
                $defaultStatements = [];
                while ($this->types[$this->i] !== TokenType::S_RIGHT_CURLY_BRACE)
                {
                    $defaultStatements[] = $this->statement();
                }
                $default = Nodes\SwitchDefault::__instantiateUnchecked($this->phpVersion, $defaultKeyword, $defaultColon, $defaultStatements);
            }

            $rightBrace = $this->read(TokenType::S_RIGHT_CURLY_BRACE);
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
        else if ($this->types[$this->i] === TokenType::T_THROW)
        {
            $keyword = $this->tokens[$this->i++];
            $expression = $this->expression();
            $semiColon = $this->statementDelimiter();
            return Nodes\ThrowStatement::__instantiateUnchecked($this->phpVersion, $keyword, $expression, $semiColon);
        }
        else if ($this->types[$this->i] === TokenType::T_TRY)
        {
            $keyword = $this->tokens[$this->i++];
            $block = $this->regularBlock();
            $catches = [];
            while ($this->types[$this->i] === TokenType::T_CATCH)
            {
                $catchKeyword = $this->tokens[$this->i++];
                $catchLeftParenthesis = $this->read(TokenType::S_LEFT_PAREN);
                $catchTypes = [null];
                $catchTypes[] = Nodes\NamedType::__instantiateUnchecked($this->phpVersion, $this->name());
                while ($this->types[$this->i] === TokenType::S_VERTICAL_BAR)
                {
                    $catchTypes[] = $this->tokens[$this->i++];
                    $catchTypes[] = Nodes\NamedType::__instantiateUnchecked($this->phpVersion, $this->name());
                }
                $catchVariable = $this->read(TokenType::T_VARIABLE);
                $catchRightParenthesis = $this->read(TokenType::S_RIGHT_PAREN);
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
            if ($this->types[$this->i] === TokenType::T_FINALLY)
            {
                $finallyKeyword = $this->tokens[$this->i++];
                $finallyBlock = $this->regularBlock();
                $finally = Nodes\Finally_::__instantiateUnchecked($this->phpVersion, $finallyKeyword, $finallyBlock);
            }
            return Nodes\TryStatement::__instantiateUnchecked($this->phpVersion, $keyword, $block, $catches, $finally);
        }
        else if ($this->types[$this->i] === TokenType::T_WHILE)
        {
            $keyword = $this->tokens[$this->i++];
            $leftParenthesis = $this->read(TokenType::S_LEFT_PAREN);
            $test = $this->expression();
            $rightParenthesis = $this->read(TokenType::S_RIGHT_PAREN);

            $this->loopDepth++;
            $block = $this->types[$this->i] === TokenType::S_COLON ? $this->altBlock(TokenType::T_ENDWHILE) : $this->block();
            $this->loopDepth--;

            return Nodes\WhileStatement::__instantiateUnchecked($this->phpVersion, $keyword, $leftParenthesis, $test, $rightParenthesis, $block);
        }
        else if ($this->types[$this->i] === TokenType::T_UNSET)
        {
            $keyword = $this->tokens[$this->i++];
            $leftParenthesis = $this->read(TokenType::S_LEFT_PAREN);
            $expressions = [null];
            do
            {
                $expressions[] = $this->simpleExpression();
            }
            while ($this->types[$this->i] === TokenType::S_COMMA && $expressions[] = $this->tokens[$this->i++]);
            $rightParenthesis = $this->read(TokenType::S_RIGHT_PAREN);
            $semiColon = $this->statementDelimiter();
            return Nodes\UnsetStatement::__instantiateUnchecked($this->phpVersion, $keyword, $leftParenthesis, $expressions, $rightParenthesis, $semiColon);
        }
        else if ($this->types[$this->i] === TokenType::S_LEFT_CURLY_BRACE)
        {
            return Nodes\BlockStatement::__instantiateUnchecked($this->phpVersion, $this->regularBlock());
        }
        else if ($this->types[$this->i] === TokenType::T_STRING && $this->types[$this->i + 1] === TokenType::S_COLON)
        {
            return Nodes\LabelStatement::__instantiateUnchecked($this->phpVersion, $this->tokens[$this->i++], $this->tokens[$this->i++]);
        }
        else if ($this->types[$this->i] === TokenType::T_INLINE_HTML || $this->types[$this->i] === TokenType::T_OPEN_TAG)
        {
            $content = $this->types[$this->i] === TokenType::T_INLINE_HTML ? $this->tokens[$this->i++] : null;
            $open = $this->types[$this->i] === TokenType::T_OPEN_TAG ? $this->tokens[$this->i++] :     null;
            return Nodes\InlineHtmlStatement::__instantiateUnchecked($this->phpVersion, $content, $open);
        }
        else if ($this->types[$this->i] === TokenType::S_SEMICOLON || $this->types[$this->i] === TokenType::T_CLOSE_TAG)
        {
            return Nodes\NopStatement::__instantiateUnchecked($this->phpVersion, $this->tokens[$this->i++]);
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
        $keyword = $this->read(TokenType::T_NAMESPACE);
        $name = null;
        if ($this->types[$this->i] !== TokenType::S_LEFT_CURLY_BRACE && $this->types[$this->i] !== TokenType::S_SEMICOLON)
        {
            $name = $this->name();
        }
        if ($this->types[$this->i] === TokenType::S_LEFT_CURLY_BRACE)
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
        $keyword = $this->read(TokenType::T_USE);
        $type = $this->types[$this->i] === TokenType::T_FUNCTION || $this->types[$this->i] === TokenType::T_CONST ? $this->tokens[$this->i++] : null;
        // TODO take advantage of $uses being the same for both forms and simplify this...
        if ($this->types[$this->i] === TokenType::S_LEFT_CURLY_BRACE)
        {
            $prefix = null;
        }
        else
        {
            $name = $this->name();
            if ($this->types[$this->i] !== TokenType::T_NS_SEPARATOR || $this->types[$this->i + 1] !== TokenType::S_LEFT_CURLY_BRACE)
            {
                $uses = [null];
                $alias = null;
                if ($this->types[$this->i] === TokenType::T_AS)
                {
                    $aliasKeyword = $this->tokens[$this->i++];
                    $alias = Nodes\UseAlias::__instantiateUnchecked($this->phpVersion, $aliasKeyword, $this->read(TokenType::T_STRING));
                }
                $uses[] = Nodes\UseName::__instantiateUnchecked($this->phpVersion, $name, $alias);
                while ($this->types[$this->i] === TokenType::S_COMMA)
                {
                    $uses[] = $this->tokens[$this->i++];
                    $name = $this->name();
                    $alias = null;
                    if ($this->types[$this->i] === TokenType::T_AS)
                    {
                        $aliasKeyword = $this->tokens[$this->i++];
                        $alias = Nodes\UseAlias::__instantiateUnchecked($this->phpVersion, $aliasKeyword, $this->read(TokenType::T_STRING));
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
            $prefix = Nodes\GroupedUsePrefix::__instantiateUnchecked($this->phpVersion, $name, $this->tokens[$this->i++]);
        }
        $leftBrace = $this->tokens[$this->i++];
        $uses = [null];
        do
        {
            $useName = $this->name();
            $useAlias = null;
            if ($this->types[$this->i] === TokenType::T_AS)
            {
                $useAliasKeyword = $this->tokens[$this->i++];
                $useAlias = Nodes\UseAlias::__instantiateUnchecked($this->phpVersion, $useAliasKeyword, $this->read(TokenType::T_STRING));
            }
            $uses[] = Nodes\UseName::__instantiateUnchecked($this->phpVersion, $useName, $useAlias);
            if ($this->types[$this->i] !== TokenType::S_COMMA)
            {
                break;
            }
            $uses[] = $this->tokens[$this->i++];
        }
        while ($this->types[$this->i] !== TokenType::S_RIGHT_CURLY_BRACE);
        $rightBrace = $this->tokens[$this->i++];
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
        while (in_array($this->types[$this->i], [TokenType::T_ABSTRACT, TokenType::T_FINAL], true))
        {
            $modifiers[] = $this->tokens[$this->i++];
        }
        $keyword = $this->read(TokenType::T_CLASS);
        $name = $this->read(TokenType::T_STRING);
        if ($this->types[$this->i] === TokenType::T_EXTENDS)
        {
            $extendsKeyword = $this->tokens[$this->i++];
            $extendsNames = [null];
            $extendsNames[] = $this->name();
            $extends = Nodes\Extends_::__instantiateUnchecked($this->phpVersion, $extendsKeyword, $extendsNames);
        }
        else
        {
            $extends = null;
        }
        if ($this->types[$this->i] === TokenType::T_IMPLEMENTS)
        {
            $implementsKeyword = $this->tokens[$this->i++];
            $implementsNames = [null];
            $implementsNames[] = $this->name();
            while ($this->types[$this->i] === TokenType::S_COMMA)
            {
                $implementsNames[] = $this->tokens[$this->i++];
                $implementsNames[] = $this->name();
            }
            $implements = Nodes\Implements_::__instantiateUnchecked($this->phpVersion, $implementsKeyword, $implementsNames);
        }
        else
        {
            $implements = null;
        }
        $leftBrace = $this->read(TokenType::S_LEFT_CURLY_BRACE);
        $members = [];
        while ($this->types[$this->i] !== TokenType::S_RIGHT_CURLY_BRACE)
        {
            $members[] = $this->classLikeMember();
        }
        $rightBrace = $this->read(TokenType::S_RIGHT_CURLY_BRACE);
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
        $keyword = $this->read(TokenType::T_INTERFACE);
        $name = $this->read(TokenType::T_STRING);
        if ($this->types[$this->i] === TokenType::T_EXTENDS)
        {
            $extendsKeyword = $this->tokens[$this->i++];
            $extendsNames = [null];
            $extendsNames[] = $this->name();
            while ($this->types[$this->i] === TokenType::S_COMMA)
            {
                $extendsNames[] = $this->tokens[$this->i++];
                $extendsNames[] = $this->name();
            }
            $extends = Nodes\Extends_::__instantiateUnchecked($this->phpVersion, $extendsKeyword, $extendsNames);
        }
        else
        {
            $extends = null;
        }
        $leftBrace = $this->read(TokenType::S_LEFT_CURLY_BRACE);
        $members = [];
        while ($this->types[$this->i] !== TokenType::S_RIGHT_CURLY_BRACE)
        {
            $members[] = $this->classLikeMember();
        }
        $rightBrace = $this->read(TokenType::S_RIGHT_CURLY_BRACE);
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
        $keyword = $this->read(TokenType::T_TRAIT);
        $name = $this->read(TokenType::T_STRING);
        $leftBrace = $this->read(TokenType::S_LEFT_CURLY_BRACE);
        $members = [];
        while ($this->types[$this->i] !== TokenType::S_RIGHT_CURLY_BRACE)
        {
            $members[] = $this->classLikeMember();
        }
        $rightBrace = $this->read(TokenType::S_RIGHT_CURLY_BRACE);
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
        while (\in_array($this->types[$this->i], [TokenType::T_ABSTRACT, TokenType::T_FINAL, TokenType::T_PUBLIC, TokenType::T_PROTECTED, TokenType::T_PRIVATE, TokenType::T_STATIC], true))
        {
            $modifiers[] = $this->tokens[$this->i++];
        }

        if ($this->types[$this->i] === TokenType::T_FUNCTION)
        {
            $keyword = $this->tokens[$this->i++];
            $byReference = $this->types[$this->i] === TokenType::S_AMPERSAND ? $this->tokens[$this->i++] : null;
            $name = $this->read(TokenType::T_STRING);
            $leftParenthesis = $this->read(TokenType::S_LEFT_PAREN);
            $parameters = $this->parameters();
            $rightParenthesis = $this->read(TokenType::S_RIGHT_PAREN);
            $returnType = $this->returnType();
            if ($this->types[$this->i] === TokenType::S_SEMICOLON)
            {
                $body = null;
                $semiColon = $this->tokens[$this->i++];
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
        else if ($this->types[$this->i] === TokenType::T_CONST)
        {
            $keyword = $this->tokens[$this->i++];
            $name = $this->read(TokenType::T_STRING);
            $equals = $this->read(TokenType::S_EQUALS);
            $value = $this->expression();
            $semiColon = $this->read(TokenType::S_SEMICOLON);
            return Nodes\ClassConstant::__instantiateUnchecked($this->phpVersion, $modifiers, $keyword, $name, $equals, $value, $semiColon);
        }
        else if ($this->types[$this->i] === TokenType::T_USE)
        {
            $keyword = $this->tokens[$this->i++];
            $names = [null];
            do
            {
                $names[] = $this->name();
            }
            while ($this->types[$this->i] === TokenType::S_COMMA && $names[] = $this->read(TokenType::S_COMMA));
            $leftBrace = $rightBrace = $semiColon = null;
            $modifications = [];
            if ($this->types[$this->i] === TokenType::S_LEFT_CURLY_BRACE)
            {
                $leftBrace = $this->tokens[$this->i++];
                die(); // TODO
                $rightBrace = $this->read(TokenType::S_RIGHT_CURLY_BRACE);
            }
            else
            {
                $semiColon = $this->read(TokenType::S_SEMICOLON);
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
            $variable = $this->read(TokenType::T_VARIABLE);
            $default = $this->default_();
            $semiColon = $this->read(TokenType::S_SEMICOLON);
            return Nodes\Property::__instantiateUnchecked($this->phpVersion, $modifiers, $variable, $default, $semiColon);
        }
    }

    private function endOfStatement(): bool
    {
        $t = $this->types[$this->i];
        return $t === TokenType::S_SEMICOLON || $t === TokenType::T_CLOSE_TAG || $t === TokenType::T_EOF;
    }

    private function statementDelimiter(): Token
    {
        if ($this->types[$this->i] === TokenType::T_CLOSE_TAG)
        {
            return $this->tokens[$this->i++];
        }
        else
        {
            return $this->read(TokenType::S_SEMICOLON);
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
        if ($minPrecedence <= self::PRECEDENCE_ASSIGN_LVALUE)
        {
            $left = $this->simpleExpression();

            if ($this->types[$this->i] === TokenType::S_EQUALS)
            {
                if ($this->types[$this->i + 1] === TokenType::S_AMPERSAND)
                {
                    $operator1 = $this->tokens[$this->i++];
                    $operator2 = $this->tokens[$this->i++];
                    $right = $this->simpleExpression();
                    $left = Nodes\AliasingExpression::__instantiateUnchecked($this->phpVersion, $left, $operator1, $operator2, $right);
                }
                else
                {
                    $operator = $this->tokens[$this->i++];
                    $value = $this->expression(self::PRECEDENCE_ASSIGN_RVALUE);
                    $left = Nodes\RegularAssignmentExpression::__instantiateUnchecked($this->phpVersion, $left, $operator, $value);
                }
            }
            else if (\in_array($this->types[$this->i], TokenType::COMBINED_ASSIGNMENTS, true))
            {
                $operator = $this->tokens[$this->i++];
                $value = $this->expression(self::PRECEDENCE_ASSIGN_RVALUE);
                $left = Nodes\CombinedAssignmentExpression::__instantiateUnchecked($this->phpVersion, $left, $operator, $value);
            }
        }
        else
        {
            $left = $this->simpleExpression();
        }

        while (true)
        {
            if ($minPrecedence <= self::PRECEDENCE_POW && $this->types[$this->i] === TokenType::T_POW)
            {
                $operator = $this->tokens[$this->i++];
                $right = $this->expression(self::PRECEDENCE_POW);
                $left = Nodes\PowerExpression::__instantiateUnchecked($this->phpVersion, $left, $operator, $right);
            }
            else if ($minPrecedence <= self::PRECEDENCE_INSTANCEOF && $this->types[$this->i] === TokenType::T_INSTANCEOF)
            {
                $operator = $this->tokens[$this->i++];
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
                if ($this->types[$this->i] === TokenType::S_ASTERISK)
                {
                    $operator = $this->tokens[$this->i++];
                    $right = $this->expression(self::PRECEDENCE_MUL + 1);
                    $left = Nodes\MultiplyExpression::__instantiateUnchecked($this->phpVersion, $left, $operator, $right);
                }
                else if ($this->types[$this->i] === TokenType::S_FORWARD_SLASH)
                {
                    $operator = $this->tokens[$this->i++];
                    $right = $this->expression(self::PRECEDENCE_MUL + 1);
                    $left = Nodes\DivideExpression::__instantiateUnchecked($this->phpVersion, $left, $operator, $right);
                }
                else if ($this->types[$this->i] === TokenType::S_MODULO)
                {
                    $operator = $this->tokens[$this->i++];
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
                if ($this->types[$this->i] === TokenType::S_PLUS)
                {
                    $operator = $this->tokens[$this->i++];
                    $right = $this->expression(self::PRECEDENCE_PLUS + 1);
                    $left = Nodes\AddExpression::__instantiateUnchecked($this->phpVersion, $left, $operator, $right);
                }
                else if ($this->types[$this->i] === TokenType::S_MINUS)
                {
                    $operator = $this->tokens[$this->i++];
                    $right = $this->expression(self::PRECEDENCE_PLUS + 1);
                    $left = Nodes\SubtractExpression::__instantiateUnchecked($this->phpVersion, $left, $operator, $right);
                }
                else if ($this->types[$this->i] === TokenType::S_DOT)
                {
                    $operator = $this->tokens[$this->i++];
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
                if ($this->types[$this->i] === TokenType::T_SL)
                {
                    $operator = $this->tokens[$this->i++];
                    $right = $this->expression(self::PRECEDENCE_SHIFT + 1);
                    $left = Nodes\ShiftLeftExpression::__instantiateUnchecked($this->phpVersion, $left, $operator, $right);
                }
                if ($this->types[$this->i] === TokenType::T_SR)
                {
                    $operator = $this->tokens[$this->i++];
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
            if ($this->types[$this->i] === TokenType::S_LT)
            {
                $operator = $this->tokens[$this->i++];
                $right = $this->expression(self::PRECEDENCE_COMPARISON2 + 1);
                $left = Nodes\LessThanExpression::__instantiateUnchecked($this->phpVersion, $left, $operator, $right);
            }
            else if ($this->types[$this->i] === TokenType::T_IS_SMALLER_OR_EQUAL)
            {
                $operator = $this->tokens[$this->i++];
                $right = $this->expression(self::PRECEDENCE_COMPARISON2 + 1);
                $left = Nodes\LessThanOrEqualsExpression::__instantiateUnchecked($this->phpVersion, $left, $operator, $right);
            }
            else if ($this->types[$this->i] === TokenType::S_GT)
            {
                $operator = $this->tokens[$this->i++];
                $right = $this->expression(self::PRECEDENCE_COMPARISON2 + 1);
                $left = Nodes\GreaterThanExpression::__instantiateUnchecked($this->phpVersion, $left, $operator, $right);
            }
            else if ($this->types[$this->i] === TokenType::T_IS_GREATER_OR_EQUAL)
            {
                $operator = $this->tokens[$this->i++];
                $right = $this->expression(self::PRECEDENCE_COMPARISON2 + 1);
                $left = Nodes\GreaterThanOrEqualsExpression::__instantiateUnchecked($this->phpVersion, $left, $operator, $right);
            }
        }

        if ($minPrecedence <= self::PRECEDENCE_COMPARISON1)
        {
            if ($this->types[$this->i] === TokenType::T_IS_IDENTICAL)
            {
                $operator = $this->tokens[$this->i++];
                $right = $this->expression(self::PRECEDENCE_COMPARISON1 + 1);
                $left = Nodes\IsIdenticalExpression::__instantiateUnchecked($this->phpVersion, $left, $operator, $right);
            }
            else if ($this->types[$this->i] === TokenType::T_IS_NOT_IDENTICAL)
            {
                $operator = $this->tokens[$this->i++];
                $right = $this->expression(self::PRECEDENCE_COMPARISON1 + 1);
                $left = Nodes\IsNotIdenticalExpression::__instantiateUnchecked($this->phpVersion, $left, $operator, $right);
            }
            else if ($this->types[$this->i] === TokenType::T_IS_EQUAL)
            {
                $operator = $this->tokens[$this->i++];
                $right = $this->expression(self::PRECEDENCE_COMPARISON1 + 1);
                $left = Nodes\IsEqualExpression::__instantiateUnchecked($this->phpVersion, $left, $operator, $right);
            }
            else if ($this->types[$this->i] === TokenType::T_IS_NOT_EQUAL)
            {
                $operator = $this->tokens[$this->i++];
                $right = $this->expression(self::PRECEDENCE_COMPARISON1 + 1);
                $left = Nodes\IsNotEqualExpression::__instantiateUnchecked($this->phpVersion, $left, $operator, $right);
            }
            else if ($this->types[$this->i] === TokenType::T_SPACESHIP)
            {
                $operator = $this->tokens[$this->i++];
                $right = $this->expression(self::PRECEDENCE_COMPARISON1 + 1);
                $left = Nodes\SpaceshipExpression::__instantiateUnchecked($this->phpVersion, $left, $operator, $right);
            }
        }

        if ($minPrecedence <= self::PRECEDENCE_BITWISE_AND)
        {
            while ($this->types[$this->i] === TokenType::S_AMPERSAND)
            {
                $operator = $this->tokens[$this->i++];
                $right = $this->expression(self::PRECEDENCE_BITWISE_AND + 1);
                $left = Nodes\BitwiseAndExpression::__instantiateUnchecked($this->phpVersion, $left, $operator, $right);
            }
        }

        if ($minPrecedence <= self::PRECEDENCE_BITWISE_XOR)
        {
            while ($this->types[$this->i] === TokenType::S_CARAT)
            {
                $operator = $this->tokens[$this->i++];
                $right = $this->expression(self::PRECEDENCE_BITWISE_XOR + 1);
                $left = Nodes\BitwiseXorExpression::__instantiateUnchecked($this->phpVersion, $left, $operator, $right);
            }
        }

        if ($minPrecedence <= self::PRECEDENCE_BITWISE_OR)
        {
            while ($this->types[$this->i] === TokenType::S_VERTICAL_BAR)
            {
                $operator = $this->tokens[$this->i++];
                $right = $this->expression(self::PRECEDENCE_BITWISE_OR + 1);
                $left = Nodes\BitwiseOrExpression::__instantiateUnchecked($this->phpVersion, $left, $operator, $right);
            }
        }

        if ($minPrecedence <= self::PRECEDENCE_BOOLEAN_AND_SYMBOL)
        {
            while ($this->types[$this->i] === TokenType::T_BOOLEAN_AND)
            {
                $operator = $this->tokens[$this->i++];
                $right = $this->expression(self::PRECEDENCE_BOOLEAN_AND_SYMBOL + 1);
                $left = Nodes\SymbolBooleanAndExpression::__instantiateUnchecked($this->phpVersion, $left, $operator, $right);
            }
        }

        if ($minPrecedence <= self::PRECEDENCE_BOOLEAN_OR_SYMBOL)
        {
            while ($this->types[$this->i] === TokenType::T_BOOLEAN_OR)
            {
                $operator = $this->tokens[$this->i++];
                $right = $this->expression(self::PRECEDENCE_BOOLEAN_OR_SYMBOL + 1);
                $left = Nodes\SymbolBooleanOrExpression::__instantiateUnchecked($this->phpVersion, $left, $operator, $right);
            }
        }

        if ($minPrecedence <= self::PRECEDENCE_COALESCE)
        {
            while ($this->types[$this->i] === TokenType::T_COALESCE)
            {
                $operator = $this->tokens[$this->i++];
                $right = $this->expression(self::PRECEDENCE_COALESCE + 1);
                $left = Nodes\CoalesceExpression::__instantiateUnchecked($this->phpVersion, $left, $operator, $right);
            }
        }

        if ($minPrecedence <= self::PRECEDENCE_TERNARY)
        {
            while ($this->types[$this->i] === TokenType::S_QUESTION_MARK)
            {
                $questionMark = $this->tokens[$this->i++];
                // note: no precedence is passed here, delimiters on both sides allow for e.g. 1 ? 2 and 3 : 4
                $then = $this->types[$this->i] !== TokenType::S_COLON ? $this->expression() : null;
                $colon = $this->read(TokenType::S_COLON);
                $else = $this->expression(self::PRECEDENCE_TERNARY + 1);
                $left = Nodes\TernaryExpression::__instantiateUnchecked($this->phpVersion, $left, $questionMark, $then, $colon, $else);
            }
        }

        if ($minPrecedence <= self::PRECEDENCE_BOOLEAN_AND_KEYWORD)
        {
            while ($this->types[$this->i] === TokenType::T_LOGICAL_AND)
            {
                $operator = $this->tokens[$this->i++];
                $right = $this->expression(self::PRECEDENCE_BOOLEAN_AND_KEYWORD + 1);
                $left = Nodes\KeywordBooleanAndExpression::__instantiateUnchecked($this->phpVersion, $left, $operator, $right);
            }
        }

        if ($minPrecedence <= self::PRECEDENCE_BOOLEAN_XOR_KEYWORD)
        {
            while ($this->types[$this->i] === TokenType::T_LOGICAL_XOR)
            {
                $operator = $this->tokens[$this->i++];
                $right = $this->expression(self::PRECEDENCE_BOOLEAN_XOR_KEYWORD + 1);
                $left = Nodes\KeywordBooleanXorExpression::__instantiateUnchecked($this->phpVersion, $left, $operator, $right);
            }
        }

        if ($minPrecedence <= self::PRECEDENCE_BOOLEAN_OR_KEYWORD)
        {
            while ($this->types[$this->i] === TokenType::T_LOGICAL_OR)
            {
                $operator = $this->tokens[$this->i++];
                $right = $this->expression(self::PRECEDENCE_BOOLEAN_OR_KEYWORD + 1);
                $left = Nodes\KeywordBooleanOrExpression::__instantiateUnchecked($this->phpVersion, $left, $operator, $right);
            }
        }

        return $left;
    }

    private function simpleExpression(bool $newable = false): Nodes\Expression
    {
        if ($this->types[$this->i] === TokenType::T_VARIABLE)
        {
            $node = Nodes\RegularVariableExpression::__instantiateUnchecked($this->phpVersion, $this->tokens[$this->i++]);
        }
        else if ($this->types[$this->i] === TokenType::S_DOLLAR)
        {
            $node = $this->variableVariable();
        }
        else if ($this->types[$this->i] === TokenType::T_STRING || $this->types[$this->i] === TokenType::T_NS_SEPARATOR)
        {
            $node = Nodes\NameExpression::__instantiateUnchecked($this->phpVersion, $this->name());
        }
        else if ($this->types[$this->i] === TokenType::T_STATIC && $this->types[$this->i + 1] !== TokenType::T_FUNCTION)
        {
            $node = Nodes\NameExpression::__instantiateUnchecked($this->phpVersion, Nodes\SpecialName::__instantiateUnchecked($this->phpVersion, $this->tokens[$this->i++]));
        }
        else if ($newable)
        {
            throw ParseException::unexpected($this->tokens[$this->i]);
        }
        else if ($this->types[$this->i] === TokenType::T_CONSTANT_ENCAPSED_STRING)
        {
            $node = Nodes\ConstantStringLiteral::__instantiateUnchecked($this->phpVersion, $this->tokens[$this->i++]);
        }
        else if ($this->types[$this->i] === TokenType::S_DOUBLE_QUOTE || $this->types[$this->i] === TokenType::T_START_HEREDOC)
        {
            $leftDelimiter = $this->tokens[$this->i++];
            $parts = [];
            while ($this->types[$this->i] !== TokenType::S_DOUBLE_QUOTE && $this->types[$this->i] !== TokenType::T_END_HEREDOC)
            {
                if ($this->types[$this->i] === TokenType::T_ENCAPSED_AND_WHITESPACE)
                {
                    $parts[] = Nodes\ConstantInterpolatedStringPart::__instantiateUnchecked($this->phpVersion, $this->tokens[$this->i++]);
                }
                else if ($this->types[$this->i] === TokenType::T_CURLY_OPEN)
                {
                    $leftBrace = $this->tokens[$this->i++];
                    $expression = $this->expression();
                    $rightBrace = $this->read(TokenType::S_RIGHT_CURLY_BRACE);
                    $parts[] = Nodes\ComplexInterpolatedStringExpression::__instantiateUnchecked($this->phpVersion, $leftBrace, $expression, $rightBrace);
                }
                else
                {
                    $parts[] = Nodes\SimpleInterpolatedStringExpression::__instantiateUnchecked($this->phpVersion, $this->expression());
                }
            }
            $rightDelimiter = $this->tokens[$this->i++];
            $node = Nodes\InterpolatedString::__instantiateUnchecked($this->phpVersion, $leftDelimiter, $parts, $rightDelimiter);
        }
        else if ($this->types[$this->i] === TokenType::T_START_HEREDOC)
        {
            $this->tokens[$this->i++]->debugDump();
            var_dump(__FILE__, __LINE__);
            die(); // TODO
        }
        else if ($this->types[$this->i] === TokenType::T_LNUMBER)
        {
            $node = Nodes\IntegerLiteral::__instantiateUnchecked($this->phpVersion, $this->tokens[$this->i++]);
        }
        else if ($this->types[$this->i] === TokenType::T_DNUMBER)
        {
            $node = Nodes\FloatLiteral::__instantiateUnchecked($this->phpVersion, $this->tokens[$this->i++]);
        }
        else if ($this->types[$this->i] === TokenType::T_NEW)
        {
            $keyword = $this->tokens[$this->i++];
            $class = $this->simpleExpression(true);
            $leftParenthesis = $rightParenthesis = null;
            $arguments = [];
            if ($this->types[$this->i] === TokenType::S_LEFT_PAREN)
            {
                $leftParenthesis = $this->tokens[$this->i++];
                $arguments = $this->arguments();
                $rightParenthesis = $this->read(TokenType::S_RIGHT_PAREN);
            }
            $node = Nodes\NewExpression::__instantiateUnchecked($this->phpVersion, $keyword, $class, $leftParenthesis, $arguments, $rightParenthesis);
        }
        else if ($this->types[$this->i] === TokenType::S_LEFT_SQUARE_BRACKET)
        {
            $leftBracket = $this->tokens[$this->i++];
            $items = $this->arrayItems(TokenType::S_RIGHT_SQUARE_BRACKET);
            $rightBracket = $this->read(TokenType::S_RIGHT_SQUARE_BRACKET);
            $node = Nodes\ShortArrayExpression::__instantiateUnchecked($this->phpVersion, $leftBracket, $items, $rightBracket);
        }
        else if ($this->types[$this->i] === TokenType::T_ARRAY)
        {
            $keyword = $this->tokens[$this->i++];
            $leftParenthesis = $this->read(TokenType::S_LEFT_PAREN);
            $items = $this->arrayItems(TokenType::S_RIGHT_PAREN);
            $rightParenthesis = $this->read(TokenType::S_RIGHT_PAREN);
            $node = Nodes\LongArrayExpression::__instantiateUnchecked($this->phpVersion, $keyword, $leftParenthesis, $items, $rightParenthesis);
        }
        else if ($this->types[$this->i] === TokenType::S_LEFT_PAREN)
        {
            $leftParenthesis = $this->tokens[$this->i++];
            $expression = $this->expression();
            $rightParenthesis = $this->read(TokenType::S_RIGHT_PAREN);
            $node = Nodes\ParenthesizedExpression::__instantiateUnchecked($this->phpVersion, $leftParenthesis, $expression, $rightParenthesis);
        }
        else if ($this->types[$this->i] === TokenType::T_ISSET)
        {
            $keyword = $this->tokens[$this->i++];
            $leftParenthesis = $this->read(TokenType::S_LEFT_PAREN);
            $expressions = [null];
            do
            {
                $expressions[] = $this->expression();
            }
            while ($this->types[$this->i] === TokenType::S_COMMA && $expressions[] = $this->tokens[$this->i++]);
            $rightParenthesis = $this->read(TokenType::S_RIGHT_PAREN);
            $node = Nodes\IssetExpression::__instantiateUnchecked($this->phpVersion, $keyword, $leftParenthesis, $expressions, $rightParenthesis);
        }
        else if ($this->types[$this->i] === TokenType::T_EMPTY)
        {
            $keyword = $this->tokens[$this->i++];
            $leftParenthesis = $this->read(TokenType::S_LEFT_PAREN);
            $expression = $this->expression();
            $rightParenthesis = $this->read(TokenType::S_RIGHT_PAREN);
            $node = Nodes\EmptyExpression::__instantiateUnchecked($this->phpVersion, $keyword, $leftParenthesis, $expression, $rightParenthesis);
        }
        else if ($this->types[$this->i] === TokenType::T_DEC)
        {
            $operator = $this->tokens[$this->i++];
            $expression = $this->simpleExpression();
            $node = Nodes\PreDecrementExpression::__instantiateUnchecked($this->phpVersion, $operator, $expression);
        }
        else if ($this->types[$this->i] === TokenType::T_INC)
        {
            $operator = $this->tokens[$this->i++];
            $expression = $this->simpleExpression();
            $node = Nodes\PreIncrementExpression::__instantiateUnchecked($this->phpVersion, $operator, $expression);
        }
        else if (\in_array($this->types[$this->i], TokenType::MAGIC_CONSTANTS, true))
        {
            $node = Nodes\MagicConstant::__instantiateUnchecked($this->phpVersion, $this->tokens[$this->i++]);
        }
        else if ($this->types[$this->i] === TokenType::T_CLONE)
        {
            $keyword = $this->tokens[$this->i++];
            $expression = $this->expression(self::PRECEDENCE_ASSIGN_LVALUE);
            $node = Nodes\CloneExpression::__instantiateUnchecked($this->phpVersion, $keyword, $expression);
        }
        else if ($this->types[$this->i] === TokenType::S_EXCLAMATION_MARK)
        {
            $operator = $this->tokens[$this->i++];
            $expression = $this->expression(self::PRECEDENCE_BOOLEAN_NOT);
            $node = Nodes\BooleanNotExpression::__instantiateUnchecked($this->phpVersion, $operator, $expression);
        }
        else if ($this->types[$this->i] === TokenType::T_YIELD)
        {
            $keyword = $this->tokens[$this->i++];
            $key = null;
            $expression = $this->expression(self::PRECEDENCE_TERNARY);
            if ($this->types[$this->i] === TokenType::T_DOUBLE_ARROW)
            {
                $key = Nodes\Key::__instantiateUnchecked($this->phpVersion, $expression, $this->tokens[$this->i++]);
                $expression = $this->expression(self::PRECEDENCE_TERNARY);
            }
            $node = Nodes\YieldExpression::__instantiateUnchecked($this->phpVersion, $keyword, $key, $expression);
        }
        else if ($this->types[$this->i] === TokenType::T_YIELD_FROM)
        {
            $keyword = $this->tokens[$this->i++];
            $expression = $this->expression(self::PRECEDENCE_TERNARY);
            $node = Nodes\YieldFromExpression::__instantiateUnchecked($this->phpVersion, $keyword, $expression);
        }
        else if (in_array($this->types[$this->i], [TokenType::T_INCLUDE, TokenType::T_INCLUDE_ONCE, TokenType::T_REQUIRE, TokenType::T_REQUIRE_ONCE], true))
        {
            $keyword = $this->tokens[$this->i++];
            $expression = $this->expression();
            $node = Nodes\IncludeLikeExpression::__instantiateUnchecked($this->phpVersion, $keyword, $expression);
        }
        else if ($this->types[$this->i] === TokenType::S_TILDE)
        {
            $symbol = $this->tokens[$this->i++];
            $expression = $this->expression(self::PRECEDENCE_ASSIGN_LVALUE);
            $node = Nodes\BitwiseNotExpression::__instantiateUnchecked($this->phpVersion, $symbol, $expression);
        }
        else if ($this->types[$this->i] === TokenType::S_MINUS)
        {
            $symbol = $this->tokens[$this->i++];
            $expression = $this->expression(self::PRECEDENCE_ASSIGN_LVALUE);
            $node = Nodes\UnaryMinusExpression::__instantiateUnchecked($this->phpVersion, $symbol, $expression);
        }
        else if ($this->types[$this->i] === TokenType::S_PLUS)
        {
            $symbol = $this->tokens[$this->i++];
            $expression = $this->expression(self::PRECEDENCE_ASSIGN_LVALUE);
            $node = Nodes\UnaryPlusExpression::__instantiateUnchecked($this->phpVersion, $symbol, $expression);
        }
        else if (\in_array($this->types[$this->i], TokenType::CASTS, true))
        {
            $cast = $this->tokens[$this->i++];
            $expression = $this->expression(self::PRECEDENCE_CAST);
            $node = Nodes\CastExpression::__instantiateUnchecked($this->phpVersion, $cast, $expression);
        }
        else if ($this->types[$this->i] === TokenType::S_AT)
        {
            $operator = $this->tokens[$this->i++];
            $expression = $this->expression(self::PRECEDENCE_POW); // TODO test precedence...
            $node = Nodes\SuppressErrorsExpression::__instantiateUnchecked($this->phpVersion, $operator, $expression);
        }
        else if ($this->types[$this->i] === TokenType::T_LIST)
        {
            $keyword = $this->tokens[$this->i++];
            $leftParenthesis = $this->read(TokenType::S_LEFT_PAREN);
            $expressions = [null];
            do
            {
                $expressions[] = $this->simpleExpression();
            }
            while ($this->types[$this->i] === TokenType::S_COMMA && $expressions[] = $this->tokens[$this->i++]);
            $rightParenthesis = $this->read(TokenType::S_RIGHT_PAREN);
            $node = Nodes\ListExpression::__instantiateUnchecked($this->phpVersion, $keyword, $leftParenthesis, $expressions, $rightParenthesis);
        }
        else if ($this->types[$this->i] === TokenType::T_EXIT)
        {
            $keyword = $this->tokens[$this->i++];
            $leftParenthesis = $this->read(TokenType::S_LEFT_PAREN);
            $expression = $this->types[$this->i] !== TokenType::S_RIGHT_PAREN ? $this->expression() : null;
            $rightParenthesis = $this->read(TokenType::S_RIGHT_PAREN);
            $node = Nodes\ExitExpression::__instantiateUnchecked($this->phpVersion, $keyword, $leftParenthesis, $expression, $rightParenthesis);
        }
        else if ($this->types[$this->i] === TokenType::T_PRINT)
        {
            $keyword = $this->tokens[$this->i++];
            $expression = $this->expression(self::PRECEDENCE_BOOLEAN_NOT);
            $node = Nodes\PrintExpression::__instantiateUnchecked($this->phpVersion, $keyword, $expression);
        }
        else if ($this->types[$this->i] === TokenType::T_EVAL)
        {
            $keyword = $this->tokens[$this->i++];
            $leftParenthesis = $this->read(TokenType::S_LEFT_PAREN);
            $expression = $this->expression();
            $rightParenthesis = $this->read(TokenType::S_RIGHT_PAREN);
            $node = Nodes\EvalExpression::__instantiateUnchecked($this->phpVersion, $keyword, $leftParenthesis, $expression, $rightParenthesis);
        }
        else if (
            $this->types[$this->i] === TokenType::T_FUNCTION ||
            ($this->types[$this->i] === TokenType::T_STATIC && $this->types[$this->i + 1] === TokenType::T_FUNCTION)
        )
        {
            $static = $this->types[$this->i] === TokenType::T_STATIC ? $this->tokens[$this->i++] : null;
            $keyword = $this->tokens[$this->i++];
            $byReference = $this->types[$this->i] === TokenType::S_AMPERSAND ? $this->tokens[$this->i++] : null;
            $leftParenthesis = $this->read(TokenType::S_LEFT_PAREN);
            $parameters = $this->parameters();
            $rightParenthesis = $this->read(TokenType::S_RIGHT_PAREN);
            $use = null;
            if ($this->types[$this->i] === TokenType::T_USE)
            {
                $useKeyword = $this->tokens[$this->i++];
                $useLeftParenthesis = $this->read(TokenType::S_LEFT_PAREN);
                $useBindings = [null];
                while (true)
                {
                    $useBindingByReference = null;
                    if ($this->types[$this->i] === TokenType::S_AMPERSAND)
                    {
                        $useBindingByReference = $this->tokens[$this->i++];
                    }
                    $useBindingVariable = $this->read(TokenType::T_VARIABLE);
                    $useBindings[] = Nodes\AnonymousFunctionUseBinding::__instantiateUnchecked($this->phpVersion, $useBindingByReference, $useBindingVariable);

                    if ($this->types[$this->i] === TokenType::S_COMMA)
                    {
                        $useBindings[] = $this->tokens[$this->i++];
                    }
                    else
                    {
                        break;
                    }
                }
                $useRightParenthesis = $this->read(TokenType::S_RIGHT_PAREN);
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
            throw ParseException::unexpected($this->tokens[$this->i]);
        }

        while (true)
        {
            if ($this->types[$this->i] === TokenType::T_OBJECT_OPERATOR)
            {
                $operator = $this->tokens[$this->i++];

                $name = $this->memberName();
                // expr->v -> access the property named 'v'
                // new expr->v -> instantiate the class named by expr->v
                // new expr->v() -> same, () is part of the NewExpression
                if ($this->types[$this->i] !== TokenType::S_LEFT_PAREN || $newable)
                {
                    $node = Nodes\PropertyAccessExpression::__instantiateUnchecked($this->phpVersion, $node, $operator, $name);
                }
                // expr->v() -> call the method named v
                else
                {
                    $leftParenthesis = $this->tokens[$this->i++];
                    $arguments = $this->arguments();
                    $rightParenthesis = $this->read(TokenType::S_RIGHT_PAREN);
                    $node = Nodes\MethodCallExpression::__instantiateUnchecked($this->phpVersion, $node, $operator, $name, $leftParenthesis, $arguments, $rightParenthesis);
                }
            }
            else if ($this->types[$this->i] === TokenType::S_LEFT_PAREN)
            {
                // new expr() -> () always belongs to new
                if ($newable)
                {
                    break;
                }

                $leftParenthesis = $this->tokens[$this->i++];
                $arguments = $this->arguments();
                $rightParenthesis = $this->read(TokenType::S_RIGHT_PAREN);
                $node = Nodes\FunctionCallExpression::__instantiateUnchecked($this->phpVersion, $node, $leftParenthesis, $arguments, $rightParenthesis);
            }
            else if ($this->types[$this->i] === TokenType::S_LEFT_SQUARE_BRACKET)
            {
                $leftBracket = $this->tokens[$this->i++];
                $index = $this->types[$this->i] === TokenType::S_RIGHT_SQUARE_BRACKET ? null : $this->expression();
                $rightBracket = $this->read(TokenType::S_RIGHT_SQUARE_BRACKET);
                $node = Nodes\ArrayAccessExpression::__instantiateUnchecked($this->phpVersion, $node, $leftBracket, $index, $rightBracket);
            }
            else if ($this->types[$this->i] === TokenType::T_DOUBLE_COLON)
            {
                // TODO manual test coverage for error messages, esp. in combination with new

                $operator = $this->tokens[$this->i++];

                switch ($this->types[$this->i])
                {
                    /** @noinspection PhpMissingBreakStatementInspection */
                    case TokenType::T_STRING:
                        $name = $this->tokens[$this->i++];
                        // new expr::a -> parse error
                        // new expr::a() -> parse error
                        if ($newable)
                        {
                            throw ParseException::unexpected($this->tokens[$this->i]);
                        }
                        // expr::a -> access constant 'a'
                        else if ($this->types[$this->i] !== TokenType::S_LEFT_PAREN)
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
                    case TokenType::T_VARIABLE:
                        $variable = Nodes\RegularVariableExpression::__instantiateUnchecked($this->phpVersion, $this->tokens[$this->i++]);
                        foundVariable:
                        // expr::$v -> access the static property named 'v'
                        // new expr::$v -> instantiate the class named by the value of expr::$v
                        // new expr::$v() -> same, () is part of the NewExpression
                        if ($this->types[$this->i] !== TokenType::S_LEFT_PAREN || $newable)
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
                    case TokenType::S_DOLLAR:
                        $variable = $this->variableVariable();
                        // all variations are the same as `expr::$v`, except the variable is variable
                        goto foundVariable;

                    case TokenType::S_LEFT_CURLY_BRACE:
                        $memberName = $this->memberName();
                        // expr::{expr} -> parse error
                        // new expr::{expr} -> parse error
                        // new expr::{expr}() -> parse error
                        if ($this->types[$this->i] !== TokenType::S_LEFT_PAREN || $newable)
                        {
                            throw ParseException::unexpected($this->tokens[$this->i]);
                        }
                        // expr::{expr2}() -> call static method named by the value of expr2
                        else
                        {
                            goto staticCall;
                        }

                    staticCall:
                        // we jump here when we positively decide on a static call, and have set up $memberName
                        /** @var Nodes\MemberName $memberName */
                        $leftParenthesis = $this->read(TokenType::S_LEFT_PAREN);
                        $arguments = $this->arguments();
                        $rightParenthesis = $this->read(TokenType::S_RIGHT_PAREN);
                        $node = Nodes\StaticMethodCallExpression::__instantiateUnchecked($this->phpVersion, $node, $operator, $memberName, $leftParenthesis, $arguments, $rightParenthesis);
                        break;

                    // TODO case T_STATIC etc
                    // which is either an access for the constant static or a call for the method static
                    // we'll probaby want to goto into to the T_STRING case here...
                    default:
                        if ($this->tokens[$this->i]->getSource() === "class")
                        {
                            // TODO what is this!? undoing of Lexer/$forceIdentifier? maybe we should force the identifier in the parser
                            // TODO property handle reserved, semi reserved
                            $keyword = $this->tokens[$this->i++]->_withType(TokenType::T_CLASS);
                            $node = Nodes\ClassNameResolutionExpression::__instantiateUnchecked($this->phpVersion, $node, $operator, $keyword);
                            break;
                        }
                        else
                        {
                            throw ParseException::unexpected($this->tokens[$this->i]);
                        }
                }
            }
            else
            {
                break;
            }
        }

        if ($this->types[$this->i] === TokenType::T_DEC)
        {
            $operator = $this->tokens[$this->i++];
            $node = Nodes\PostDecrementExpression::__instantiateUnchecked($this->phpVersion, $node, $operator);
        }
        else if ($this->types[$this->i] === TokenType::T_INC)
        {
            $operator = $this->tokens[$this->i++];
            $node = Nodes\PostIncrementExpression::__instantiateUnchecked($this->phpVersion, $node, $operator);
        }

        return $node;
    }

    private function block(int $level = self::STMT_LEVEL_OTHER): Nodes\Block
    {
        if ($this->types[$this->i] === TokenType::S_LEFT_CURLY_BRACE)
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
        $leftBrace = $this->tokens[$this->i++];
        $statements = [];
        while ($this->types[$this->i] !== TokenType::S_RIGHT_CURLY_BRACE)
        {
            $statements[] = $this->statement($level);
        }
        $rightBrace = $this->read(TokenType::S_RIGHT_CURLY_BRACE);
        return Nodes\RegularBlock::__instantiateUnchecked($this->phpVersion, $leftBrace, $statements, $rightBrace);
    }

    /**
     * @param array<int> $implicitEndKeywords
     */
    private function altBlock(int $endKeywordType, array $implicitEndKeywords = []): Nodes\AlternativeFormatBlock
    {
        $colon = $this->read(TokenType::S_COLON);

        $statements = [];
        while ($this->types[$this->i] !== $endKeywordType && !in_array($this->types[$this->i], $implicitEndKeywords, true))
        {
            $statements[] = $this->statement();
        }

        if ($this->types[$this->i] === $endKeywordType)
        {
            $endKeyword = $this->tokens[$this->i++];
            $semiColon = $this->statementDelimiter();
        }
        else
        {
            $endKeyword = $semiColon = null;
        }

        return Nodes\AlternativeFormatBlock::__instantiateUnchecked($this->phpVersion, $colon, $statements, $endKeyword, $semiColon);
    }

    /** @return array<Nodes\Parameter|Token|null> */
    private function parameters(): array
    {
        $nodes = [null];
        if ($this->types[$this->i] !== TokenType::S_RIGHT_PAREN)
        {
            $nodes[] = $this->parameter();
        }
        while ($this->types[$this->i] === TokenType::S_COMMA)
        {
            $nodes[] = $this->tokens[$this->i++];
            $nodes[] = $this->parameter();
        }
        return $nodes;
    }

    private function parameter(): Nodes\Parameter
    {
        $type = $byReference = $ellipsis = null;

        if ($this->types[$this->i] !== TokenType::S_AMPERSAND && $this->types[$this->i] !== TokenType::T_VARIABLE && $this->types[$this->i] !== TokenType::T_ELLIPSIS)
        {
            $type = $this->type();
        }

        if ($this->types[$this->i] === TokenType::S_AMPERSAND)
        {
            $byReference = $this->tokens[$this->i++];
        }

        if ($this->types[$this->i] === TokenType::T_ELLIPSIS)
        {
            $ellipsis = $this->tokens[$this->i++];
        }

        $variable = $this->read(TokenType::T_VARIABLE);

        if ($ellipsis && $this->types[$this->i] === TokenType::S_EQUALS)
        {
            throw ParseException::unexpected($this->tokens[$this->i]);
        }
        $default = $this->default_();

        return Nodes\Parameter::__instantiateUnchecked($this->phpVersion, $type, $byReference, $ellipsis, $variable, $default);
    }

    /** @return array<Nodes\Argument|Token|null> */
    private function arguments(): array
    {
        $arguments = [null];
        while ($this->types[$this->i] !== TokenType::S_RIGHT_PAREN)
        {
            $unpack = $this->types[$this->i] === TokenType::T_ELLIPSIS ? $this->tokens[$this->i++] : null;
            $value = $this->expression();
            $arguments[] = Nodes\Argument::__instantiateUnchecked($this->phpVersion, $unpack, $value);

            if ($this->types[$this->i] === TokenType::S_COMMA)
            {
                $arguments[] = $this->tokens[$this->i++];

                // trailing comma is not allowed before 7.4
                if ($this->phpVersion < PhpVersion::PHP_7_4 && $this->types[$this->i] === TokenType::S_RIGHT_PAREN)
                {
                    throw ParseException::unexpected($this->tokens[$this->i]);
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
        if ($this->types[$this->i] === TokenType::S_COLON)
        {
            $symbol = $this->tokens[$this->i++];
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
        $nullableSymbol = $this->types[$this->i] === TokenType::S_QUESTION_MARK ? $this->tokens[$this->i++] : null;

        if (in_array($this->types[$this->i], [TokenType::T_ARRAY, TokenType::T_CALLABLE], true))
        {
            $type = Nodes\SpecialType::__instantiateUnchecked($this->phpVersion, $this->tokens[$this->i++]);
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
        if ($this->types[$this->i] === TokenType::T_STATIC)
        {
            return Nodes\SpecialName::__instantiateUnchecked($this->phpVersion, $this->tokens[$this->i++]);
        }
        else
        {
            $parts = [];
            $parts[] = $this->types[$this->i] === TokenType::T_NS_SEPARATOR ? $this->tokens[$this->i++] : null;
            $parts[] = $this->read(TokenType::T_STRING);
            while ($this->types[$this->i] === TokenType::T_NS_SEPARATOR && $this->types[$this->i + 1] === TokenType::T_STRING)
            {
                $parts[] = $this->tokens[$this->i++];
                $parts[] = $this->tokens[$this->i++];
            }
            return Nodes\RegularName::__instantiateUnchecked($this->phpVersion, $parts);
        }
    }

    private function default_(): ?Nodes\Default_
    {
        if ($this->types[$this->i] === TokenType::S_EQUALS)
        {
            $symbol = $this->tokens[$this->i++];
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
        if ($this->types[$this->i] === TokenType::T_STRING)
        {
            return Nodes\RegularMemberName::__instantiateUnchecked($this->phpVersion, $this->tokens[$this->i++]);
        }
        else if ($this->types[$this->i] === TokenType::T_VARIABLE)
        {
            $expression = Nodes\RegularVariableExpression::__instantiateUnchecked($this->phpVersion, $this->tokens[$this->i++]);
            return Nodes\VariableMemberName::__instantiateUnchecked($this->phpVersion, null, $expression, null);
        }
        else if ($this->types[$this->i] === TokenType::S_DOLLAR)
        {
            $expression = $this->variableVariable();
            return Nodes\VariableMemberName::__instantiateUnchecked($this->phpVersion, null, $expression, null);
        }
        else if ($this->types[$this->i] === TokenType::S_LEFT_CURLY_BRACE)
        {
            $leftBrace = $this->tokens[$this->i++];
            $expr = $this->expression();
            $rightBrace = $this->read(TokenType::S_RIGHT_CURLY_BRACE);
            return Nodes\VariableMemberName::__instantiateUnchecked($this->phpVersion, $leftBrace, $expr, $rightBrace);
        }
        else
        {
            throw ParseException::unexpected($this->tokens[$this->i]);
        }
    }

    private function variableVariable(): Nodes\VariableVariableExpression
    {
        $dollar = $this->read(TokenType::S_DOLLAR);
        $leftBrace = $rightBrace = null;
        if ($this->types[$this->i] === TokenType::T_VARIABLE)
        {
            $expression = Nodes\RegularVariableExpression::__instantiateUnchecked($this->phpVersion, $this->tokens[$this->i++]);
        }
        else if ($this->types[$this->i] === TokenType::S_DOLLAR)
        {
            $expression = $this->variableVariable();
        }
        else if ($this->types[$this->i] === TokenType::S_LEFT_CURLY_BRACE)
        {
            $leftBrace = $this->tokens[$this->i++];
            $expression = $this->expression();
            $rightBrace = $this->read(TokenType::S_RIGHT_CURLY_BRACE);
        }
        else
        {
            throw ParseException::unexpected($this->tokens[$this->i]);
        }

        return Nodes\VariableVariableExpression::__instantiateUnchecked($this->phpVersion, $dollar, $leftBrace, $expression, $rightBrace);
    }

    /** @return array<Node|null> */
    private function arrayItems(int $delimiter): array
    {
        $items = [null];
        while ($this->types[$this->i] !== $delimiter)
        {
            $key = $byReference = $value = null;

            if ($this->types[$this->i] !== TokenType::S_COMMA && $this->types[$this->i] !== $delimiter)
            {
                if ($this->types[$this->i] === TokenType::S_AMPERSAND)
                {
                    $byReference = $this->tokens[$this->i++];
                }

                $value = $this->expression();

                if ($this->types[$this->i] === TokenType::T_DOUBLE_ARROW)
                {
                    if ($byReference)
                    {
                        throw ParseException::unexpected($this->tokens[$this->i]);
                    }

                    $key = Nodes\Key::__instantiateUnchecked($this->phpVersion, $value, $this->tokens[$this->i++]);

                    if ($this->types[$this->i] === TokenType::S_AMPERSAND)
                    {
                        $byReference = $this->tokens[$this->i++];
                    }

                    $value = $this->expression();
                }
            }

            $items[] = Nodes\ArrayItem::__instantiateUnchecked($this->phpVersion, $key, $byReference, $value);

            if ($this->types[$this->i] === TokenType::S_COMMA)
            {
                $items[] = $this->tokens[$this->i++];
            }
            else
            {
                break;
            }
        }
        return $items;
    }
}
