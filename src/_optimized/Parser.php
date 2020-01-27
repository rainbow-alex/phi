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
            if ($this->types[$this->i] !== 999)
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
            if ($this->types[$this->i] !== 999)
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
            while ($this->types[$this->i] !== 999)
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
                106, 126, 121,
                109, 114, 113,
                132, 160,
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
        if ($expectedTokenType !== null && $this->types[$this->i] !== $expectedTokenType)
        {
            throw ParseException::unexpected($this->tokens[$this->i]);
        }

        return $this->tokens[$this->i++];
    }

    private function parseRoot(): Nodes\RootNode
    {
        $statements = [];

        if ($this->types[$this->i] === 194)
        {
            $content = $this->tokens[$this->i++];
        }
        else
        {
            $content = null;
        }

        if ($this->types[$this->i] === 223)
        {
            $statements[] = Nodes\InlineHtmlStatement::__instantiateUnchecked($this->phpVersion, null, $content, $this->tokens[$this->i++]);
        }

        while ($this->types[$this->i] !== 999)
        {
            $statements[] = $this->statement(self::STMT_LEVEL_TOP);
        }

        $eof = $this->read(999);

        return Nodes\RootNode::__instantiateUnchecked($this->phpVersion, $statements, $eof);
    }

    private const STMT_LEVEL_TOP = 1;
    private const STMT_LEVEL_NAMESPACE = 2;
    private const STMT_LEVEL_OTHER = 3;

    private function statement(int $level = self::STMT_LEVEL_OTHER): Nodes\Statement
    {
        if ($this->types[$this->i] === 216)
        {
            if ($level !== self::STMT_LEVEL_TOP)
            {
                throw ParseException::unexpected($this->tokens[$this->i]);
            }

            return $this->namespace_();
        }
        else if ($this->types[$this->i] === 253)
        {
            if ($level === self::STMT_LEVEL_OTHER)
            {
                throw ParseException::unexpected($this->tokens[$this->i]);
            }

            return $this->use_();
        }
        else if ($this->types[$this->i] === 128 || $this->types[$this->i] === 140 || $this->types[$this->i] === 180)
        {
            return $this->class_();
        }
        else if ($this->types[$this->i] === 197)
        {
            return $this->interface_();
        }
        else if ($this->types[$this->i] === 248)
        {
            return $this->trait_();
        }
        else if ($this->types[$this->i] === 136)
        {
            $keyword = $this->tokens[$this->i++];

            if (!$this->loopDepth)
            {
                throw ParseException::unexpected($keyword);
            }

            $levels = null;
            if ($this->types[$this->i] === 208)
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
        else if ($this->types[$this->i] === 149)
        {
            $keyword = $this->tokens[$this->i++];

            if (!$this->loopDepth)
            {
                throw ParseException::unexpected($keyword);
            }

            $levels = null;
            if ($this->types[$this->i] === 208)
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
        else if ($this->types[$this->i] === 152)
        {
            $keyword = $this->tokens[$this->i++];
            $leftParenthesis = $this->read(105);
            $directives = [null];
            do
            {
                $directiveKey = $this->read(243);
                $directiveEquals = $this->read(116);
                $directiveValue = $this->expression();
                $directives[] = Nodes\DeclareDirective::__instantiateUnchecked($this->phpVersion, $directiveKey, $directiveEquals, $directiveValue);
            }
            while ($this->types[$this->i] === 109 && $directives[] = $this->tokens[$this->i++]);
            $rightParenthesis = $this->read(106);
            if ($this->types[$this->i] !== 114)
            {
                $block = $this->block($level); // TODO test level
                $semiColon = null;
            }
            else
            {
                $block = null;
                $semiColon = $this->tokens[$this->i++];
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
        else if ($this->types[$this->i] === 157)
        {
            $keyword1 = $this->tokens[$this->i++];

            $this->loopDepth++;
            $block = $this->block();
            $this->loopDepth--;

            $keyword2 = $this->read(256);
            $leftParenthesis = $this->read(105);
            $test = $this->expression();
            $rightParenthesis = $this->read(106);
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
        else if ($this->types[$this->i] === 163)
        {
            $keyword = $this->tokens[$this->i++];
            $expressions = [null];
            $expressions[] = $this->expression();
            while ($this->types[$this->i] === 109)
            {
                $expressions[] = $this->tokens[$this->i++];
                $expressions[] = $this->expression();
            }
            $semiColon = $this->statementDelimiter();
            return Nodes\EchoStatement::__instantiateUnchecked($this->phpVersion, $keyword, $expressions, $semiColon);
        }
        else if ($this->types[$this->i] === 182)
        {
            $keyword = $this->tokens[$this->i++];
            $leftParenthesis = $this->read(105);
            $init = $this->types[$this->i] !== 114 ? $this->expression() : null;
            $separator1 = $this->read(114);
            $test = $this->types[$this->i] !== 114 ? $this->expression() : null;
            $separator2 = $this->read(114);
            $step = $this->types[$this->i] !== 106 ? $this->expression() : null;
            $rightParenthesis = $this->read(106);

            $this->loopDepth++;
            $block = $this->types[$this->i] === 113 ? $this->altBlock(170) : $this->block();
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
        else if ($this->types[$this->i] === 183)
        {
            $keyword = $this->tokens[$this->i++];
            $leftParenthesis = $this->read(105);
            $iterable = $this->expression();
            $as = $this->read(132);

            $key = null;
            $byReference = $this->types[$this->i] === 104 ? $this->tokens[$this->i++] : null;
            $value = $this->simpleExpression();

            if ($this->types[$this->i] === 160)
            {
                if ($byReference)
                {
                    throw ParseException::unexpected($this->tokens[$this->i]);
                }

                $key = Nodes\Key::__instantiateUnchecked($this->phpVersion, $value, $this->tokens[$this->i++]);

                if ($this->types[$this->i] === 104)
                {
                    $byReference = $this->tokens[$this->i++];
                }

                $value = $this->simpleExpression();
            }

            $rightParenthesis = $this->read(106);

            $this->loopDepth++;
            $block = $this->types[$this->i] === 113 ? $this->altBlock(171) : $this->block();
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
            $this->types[$this->i] === 184
            && (
                $this->types[$this->i + 1] === 243
                || $this->types[$this->i + 1] === 104 && $this->types[$this->i + 2] === 243
            )
        )
        {
            $keyword = $this->tokens[$this->i++];
            $byReference = $this->types[$this->i] === 104 ? $this->tokens[$this->i++] : null;
            $name = $this->tokens[$this->i++];
            $leftParenthesis = $this->read(105);
            $parameters = $this->parameters();
            $rightParenthesis = $this->read(106);
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
        else if ($this->types[$this->i] === 187)
        {
            $keyword = $this->tokens[$this->i++];
            $label = $this->read(243);
            $semiColon = $this->statementDelimiter();
            return Nodes\GotoStatement::__instantiateUnchecked($this->phpVersion,
                $keyword,
                $label,
                $semiColon
            );
        }
        else if ($this->types[$this->i] === 189)
        {
            $keyword = $this->tokens[$this->i++];
            $leftParenthesis = $this->read(105);
            $test = $this->expression();
            $rightParenthesis = $this->read(106);
            if ($altSyntax = ($this->types[$this->i] === 113))
            {
                $block = $this->altBlock(172, [165, 166]);
            }
            else
            {
                $block = $this->block();
            }
            $elseifs = [];
            while ($this->types[$this->i] === 166)
            {
                $elseifKeyword = $this->tokens[$this->i++];
                $elseifLeftParenthesis = $this->read(105);
                $elseifTest = $this->expression();
                $elseifRightParenthesis = $this->read(106);
                $elseifBlock = $altSyntax ? $this->altBlock(172, [165, 166]) : $this->block();
                $elseifs[] = Nodes\Elseif_::__instantiateUnchecked($this->phpVersion,
                    $elseifKeyword,
                    $elseifLeftParenthesis,
                    $elseifTest,
                    $elseifRightParenthesis,
                    $elseifBlock
                );
            }
            $else = null;
            if ($this->types[$this->i] === 165)
            {
                $elseKeyword = $this->tokens[$this->i++];
                $elseBlock = $altSyntax ? $this->altBlock(172) : $this->block();
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
        else if ($this->types[$this->i] === 235)
        {
            $keyword = $this->tokens[$this->i++];
            $expression = !$this->endOfStatement() ? $this->expression() : null;
            $semiColon = $this->statementDelimiter();
            return Nodes\ReturnStatement::__instantiateUnchecked($this->phpVersion, $keyword, $expression, $semiColon);
        }
        else if ($this->types[$this->i] === 242 && $this->types[$this->i + 1] === 255)
        {
            $keyword = $this->tokens[$this->i++];
            $variables = [null];
            do
            {
                $variable = $this->read(255);
                $default = $this->default_();
                $variables[] = Nodes\StaticVariable::__instantiateUnchecked($this->phpVersion, $variable, $default);
            }
            while ($this->types[$this->i] === 109 && $variables[] = $this->tokens[$this->i++]);
            $semiColon = $this->statementDelimiter();
            return Nodes\StaticVariableDeclaration::__instantiateUnchecked($this->phpVersion, $keyword, $variables, $semiColon);
        }
        else if ($this->types[$this->i] === 246)
        {
            $keyword = $this->tokens[$this->i++];

            $leftParenthesis = $this->read(105);
            $value = $this->expression();
            $rightParenthesis = $this->read(106);

            $this->loopDepth++;
            $leftBrace = $this->read(124);

            $cases = [];
            while ($this->types[$this->i] !== 153 && $this->types[$this->i] !== 126)
            {
                $caseKeyword = $this->read(138);
                $caseValue = $this->expression();
                $caseDelimiter = $this->types[$this->i] === 114 ? $this->tokens[$this->i++] : $this->read(113);
                $caseStatements = [];
                while ($this->types[$this->i] !== 138 && $this->types[$this->i] !== 153 && $this->types[$this->i] !== 126)
                {
                    $caseStatements[] = $this->statement();
                }
                $cases[] = Nodes\SwitchCase::__instantiateUnchecked($this->phpVersion, $caseKeyword, $caseValue, $caseDelimiter, $caseStatements);
            }
            $default = null;
            if ($this->types[$this->i] === 153)
            {
                $defaultKeyword = $this->tokens[$this->i++];
                $defaultColon = $this->read(113);
                $defaultStatements = [];
                while ($this->types[$this->i] !== 126)
                {
                    $defaultStatements[] = $this->statement();
                }
                $default = Nodes\SwitchDefault::__instantiateUnchecked($this->phpVersion, $defaultKeyword, $defaultColon, $defaultStatements);
            }

            $rightBrace = $this->read(126);
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
        else if ($this->types[$this->i] === 247)
        {
            $keyword = $this->tokens[$this->i++];
            $expression = $this->expression();
            $semiColon = $this->statementDelimiter();
            return Nodes\ThrowStatement::__instantiateUnchecked($this->phpVersion, $keyword, $expression, $semiColon);
        }
        else if ($this->types[$this->i] === 250)
        {
            $keyword = $this->tokens[$this->i++];
            $block = $this->regularBlock();
            $catches = [];
            while ($this->types[$this->i] === 139)
            {
                $catchKeyword = $this->tokens[$this->i++];
                $catchLeftParenthesis = $this->read(105);
                $catchTypes = [null];
                $catchTypes[] = Nodes\NamedType::__instantiateUnchecked($this->phpVersion, $this->name());
                while ($this->types[$this->i] === 125)
                {
                    $catchTypes[] = $this->tokens[$this->i++];
                    $catchTypes[] = Nodes\NamedType::__instantiateUnchecked($this->phpVersion, $this->name());
                }
                $catchVariable = $this->read(255);
                $catchRightParenthesis = $this->read(106);
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
            if ($this->types[$this->i] === 181)
            {
                $finallyKeyword = $this->tokens[$this->i++];
                $finallyBlock = $this->regularBlock();
                $finally = Nodes\Finally_::__instantiateUnchecked($this->phpVersion, $finallyKeyword, $finallyBlock);
            }
            return Nodes\TryStatement::__instantiateUnchecked($this->phpVersion, $keyword, $block, $catches, $finally);
        }
        else if ($this->types[$this->i] === 256)
        {
            $keyword = $this->tokens[$this->i++];
            $leftParenthesis = $this->read(105);
            $test = $this->expression();
            $rightParenthesis = $this->read(106);

            $this->loopDepth++;
            $block = $this->types[$this->i] === 113 ? $this->altBlock(174) : $this->block();
            $this->loopDepth--;

            return Nodes\WhileStatement::__instantiateUnchecked($this->phpVersion, $keyword, $leftParenthesis, $test, $rightParenthesis, $block);
        }
        else if ($this->types[$this->i] === 251)
        {
            $keyword = $this->tokens[$this->i++];
            $leftParenthesis = $this->read(105);
            $expressions = [null];
            do
            {
                $expressions[] = $this->simpleExpression();
            }
            while ($this->types[$this->i] === 109 && $expressions[] = $this->tokens[$this->i++]);
            $rightParenthesis = $this->read(106);
            $semiColon = $this->statementDelimiter();
            return Nodes\UnsetStatement::__instantiateUnchecked($this->phpVersion, $keyword, $leftParenthesis, $expressions, $rightParenthesis, $semiColon);
        }
        else if ($this->types[$this->i] === 124)
        {
            return Nodes\BlockStatement::__instantiateUnchecked($this->phpVersion, $this->regularBlock());
        }
        else if ($this->types[$this->i] === 114)
        {
            return Nodes\NopStatement::__instantiateUnchecked($this->phpVersion, $this->tokens[$this->i++]);
        }
        else if ($this->types[$this->i] === 243 && $this->types[$this->i + 1] === 113)
        {
            return Nodes\LabelStatement::__instantiateUnchecked($this->phpVersion, $this->tokens[$this->i++], $this->tokens[$this->i++]);
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
        $keyword = $this->read(216);
        $name = null;
        if ($this->types[$this->i] !== 124 && $this->types[$this->i] !== 114)
        {
            $name = $this->name();
        }
        if ($this->types[$this->i] === 124)
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
        $keyword = $this->read(253);
        $type = $this->types[$this->i] === 184 || $this->types[$this->i] === 147 ? $this->tokens[$this->i++] : null;
        // TODO take advantage of $uses being the same for both forms and simplify this...
        if ($this->types[$this->i] === 124)
        {
            $prefix = null;
        }
        else
        {
            $name = $this->name();
            if ($this->types[$this->i] !== 219 || $this->types[$this->i + 1] !== 124)
            {
                $uses = [null];
                $alias = null;
                if ($this->types[$this->i] === 132)
                {
                    $aliasKeyword = $this->tokens[$this->i++];
                    $alias = Nodes\UseAlias::__instantiateUnchecked($this->phpVersion, $aliasKeyword, $this->read(243));
                }
                $uses[] = Nodes\UseName::__instantiateUnchecked($this->phpVersion, $name, $alias);
                while ($this->types[$this->i] === 109)
                {
                    $uses[] = $this->tokens[$this->i++];
                    $name = $this->name();
                    $alias = null;
                    if ($this->types[$this->i] === 132)
                    {
                        $aliasKeyword = $this->tokens[$this->i++];
                        $alias = Nodes\UseAlias::__instantiateUnchecked($this->phpVersion, $aliasKeyword, $this->read(243));
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
            if ($this->types[$this->i] === 132)
            {
                $useAliasKeyword = $this->tokens[$this->i++];
                $useAlias = Nodes\UseAlias::__instantiateUnchecked($this->phpVersion, $useAliasKeyword, $this->read(243));
            }
            $uses[] = Nodes\UseName::__instantiateUnchecked($this->phpVersion, $useName, $useAlias);
            if ($this->types[$this->i] !== 109)
            {
                break;
            }
            $uses[] = $this->tokens[$this->i++];
        }
        while ($this->types[$this->i] !== 126);
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
        while (in_array($this->types[$this->i], [128, 180], true))
        {
            $modifiers[] = $this->tokens[$this->i++];
        }
        $keyword = $this->read(140);
        $name = $this->read(243);
        if ($this->types[$this->i] === 178)
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
        if ($this->types[$this->i] === 190)
        {
            $implementsKeyword = $this->tokens[$this->i++];
            $implementsNames = [null];
            $implementsNames[] = $this->name();
            while ($this->types[$this->i] === 109)
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
        $leftBrace = $this->read(124);
        $members = [];
        while ($this->types[$this->i] !== 126)
        {
            $members[] = $this->classLikeMember();
        }
        $rightBrace = $this->read(126);
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
        $keyword = $this->read(197);
        $name = $this->read(243);
        if ($this->types[$this->i] === 178)
        {
            $extendsKeyword = $this->tokens[$this->i++];
            $extendsNames = [null];
            $extendsNames[] = $this->name();
            while ($this->types[$this->i] === 109)
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
        $leftBrace = $this->read(124);
        $members = [];
        while ($this->types[$this->i] !== 126)
        {
            $members[] = $this->classLikeMember();
        }
        $rightBrace = $this->read(126);
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
        $keyword = $this->read(248);
        $name = $this->read(243);
        $leftBrace = $this->read(124);
        $members = [];
        while ($this->types[$this->i] !== 126)
        {
            $members[] = $this->classLikeMember();
        }
        $rightBrace = $this->read(126);
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
        while (\in_array($this->types[$this->i], [128, 180, 232, 231, 230, 242], true))
        {
            $modifiers[] = $this->tokens[$this->i++];
        }

        if ($this->types[$this->i] === 184)
        {
            $keyword = $this->tokens[$this->i++];
            $byReference = $this->types[$this->i] === 104 ? $this->tokens[$this->i++] : null;
            $name = $this->read(243);
            $leftParenthesis = $this->read(105);
            $parameters = $this->parameters();
            $rightParenthesis = $this->read(106);
            $returnType = $this->returnType();
            if ($this->types[$this->i] === 114)
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
        else if ($this->types[$this->i] === 147)
        {
            $keyword = $this->tokens[$this->i++];
            $name = $this->read(243);
            $equals = $this->read(116);
            $value = $this->expression();
            $semiColon = $this->read(114);
            return Nodes\ClassConstant::__instantiateUnchecked($this->phpVersion, $modifiers, $keyword, $name, $equals, $value, $semiColon);
        }
        else if ($this->types[$this->i] === 253)
        {
            $keyword = $this->tokens[$this->i++];
            $names = [null];
            do
            {
                $names[] = $this->name();
            }
            while ($this->types[$this->i] === 109 && $names[] = $this->read(109));
            $leftBrace = $rightBrace = $semiColon = null;
            $modifications = [];
            if ($this->types[$this->i] === 124)
            {
                $leftBrace = $this->tokens[$this->i++];
                assert(false); // TODO
                $rightBrace = $this->read(126);
            }
            else
            {
                $semiColon = $this->read(114);
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
            $variable = $this->read(255);
            $default = $this->default_();
            $semiColon = $this->read(114);
            return Nodes\Property::__instantiateUnchecked($this->phpVersion, $modifiers, $variable, $default, $semiColon);
        }
    }

    private function endOfStatement(): bool
    {
        $t = $this->types[$this->i];
        return $t === 114 || $t === 143 || $t === 999;
    }

    private function statementDelimiter(): ?Token
    {
        if ($this->types[$this->i] === 143 || $this->types[$this->i] === 999)
        {
            return null;
        }
        else
        {
            return $this->read(114);
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
        switch (substr($this->typezip, $this->i * 3, 6)){    case '208000':    case '156000':        return Nodes\IntegerLiteral::__instantiateUnchecked($this->phpVersion, $this->tokens[$this->i++]);    case '243000':        return Nodes\NameExpression::__instantiateUnchecked($this->phpVersion, $this->name()); /* TODO further optim possible here (?) */    case '255000':        return Nodes\RegularVariableExpression::__instantiateUnchecked($this->phpVersion, $this->tokens[$this->i++]);    case '148000':        return Nodes\ConstantStringLiteral::__instantiateUnchecked($this->phpVersion, $this->tokens[$this->i++]);    case '206000':    case '179000':    case '154000':    case '141000':    case '249000':    case '212000':    case '185000':        return Nodes\MagicConstant::__instantiateUnchecked($this->phpVersion, $this->tokens[$this->i++]);}switch (substr($this->typezip, $this->i * 3, 9)){    case '219243000':        return Nodes\NameExpression::__instantiateUnchecked($this->phpVersion, Nodes\RegularName::__instantiateUnchecked($this->phpVersion, [$this->tokens[$this->i++], $this->tokens[$this->i++]]));    case '151255000':        return Nodes\PreDecrementExpression::__instantiateUnchecked($this->phpVersion, $this->tokens[$this->i++], Nodes\RegularVariableExpression::__instantiateUnchecked($this->phpVersion, $this->tokens[$this->i++]));    case '191255000':        return Nodes\PreIncrementExpression::__instantiateUnchecked($this->phpVersion, $this->tokens[$this->i++], Nodes\RegularVariableExpression::__instantiateUnchecked($this->phpVersion, $this->tokens[$this->i++]));    case '255151000':        return Nodes\PostDecrementExpression::__instantiateUnchecked($this->phpVersion, Nodes\RegularVariableExpression::__instantiateUnchecked($this->phpVersion, $this->tokens[$this->i++]), $this->tokens[$this->i++]);    case '255191000':        return Nodes\PostIncrementExpression::__instantiateUnchecked($this->phpVersion, Nodes\RegularVariableExpression::__instantiateUnchecked($this->phpVersion, $this->tokens[$this->i++]), $this->tokens[$this->i++]);    case '217243000':        return Nodes\NewExpression::__instantiateUnchecked($this->phpVersion, $this->tokens[$this->i++], Nodes\NameExpression::__instantiateUnchecked($this->phpVersion, $this->name()), null, [], null);}

        if ($minPrecedence <= self::PRECEDENCE_ASSIGN_LVALUE)
        {
            $left = $this->simpleExpression();

            if ($this->types[$this->i] === 116)
            {
                if ($this->types[$this->i + 1] === 104)
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
            else if (\in_array($this->types[$this->i], Token::COMBINED_ASSIGNMENT, true))
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

        /* TODO test and check perf
        if (substr($this->typezip, $this->i * 3, 3) === '000')
        {
            return $left;
        }
        //*/

        while (true)
        {
            if ($minPrecedence <= self::PRECEDENCE_POW && $this->types[$this->i] === 227)
            {
                $operator = $this->tokens[$this->i++];
                $right = $this->expression(self::PRECEDENCE_POW);
                $left = Nodes\PowerExpression::__instantiateUnchecked($this->phpVersion, $left, $operator, $right);
            }
            else if ($minPrecedence <= self::PRECEDENCE_INSTANCEOF && $this->types[$this->i] === 195)
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
                if ($this->types[$this->i] === 107)
                {
                    $operator = $this->tokens[$this->i++];
                    $right = $this->expression(self::PRECEDENCE_MUL + 1);
                    $left = Nodes\MultiplyExpression::__instantiateUnchecked($this->phpVersion, $left, $operator, $right);
                }
                else if ($this->types[$this->i] === 112)
                {
                    $operator = $this->tokens[$this->i++];
                    $right = $this->expression(self::PRECEDENCE_MUL + 1);
                    $left = Nodes\DivideExpression::__instantiateUnchecked($this->phpVersion, $left, $operator, $right);
                }
                else if ($this->types[$this->i] === 103)
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
                if ($this->types[$this->i] === 108)
                {
                    $operator = $this->tokens[$this->i++];
                    $right = $this->expression(self::PRECEDENCE_PLUS + 1);
                    $left = Nodes\AddExpression::__instantiateUnchecked($this->phpVersion, $left, $operator, $right);
                }
                else if ($this->types[$this->i] === 110)
                {
                    $operator = $this->tokens[$this->i++];
                    $right = $this->expression(self::PRECEDENCE_PLUS + 1);
                    $left = Nodes\SubtractExpression::__instantiateUnchecked($this->phpVersion, $left, $operator, $right);
                }
                else if ($this->types[$this->i] === 111)
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
                if ($this->types[$this->i] === 236)
                {
                    $operator = $this->tokens[$this->i++];
                    $right = $this->expression(self::PRECEDENCE_SHIFT + 1);
                    $left = Nodes\ShiftLeftExpression::__instantiateUnchecked($this->phpVersion, $left, $operator, $right);
                }
                if ($this->types[$this->i] === 239)
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
            if ($this->types[$this->i] === 115)
            {
                $operator = $this->tokens[$this->i++];
                $right = $this->expression(self::PRECEDENCE_COMPARISON2 + 1);
                $left = Nodes\LessThanExpression::__instantiateUnchecked($this->phpVersion, $left, $operator, $right);
            }
            else if ($this->types[$this->i] === 205)
            {
                $operator = $this->tokens[$this->i++];
                $right = $this->expression(self::PRECEDENCE_COMPARISON2 + 1);
                $left = Nodes\LessThanOrEqualsExpression::__instantiateUnchecked($this->phpVersion, $left, $operator, $right);
            }
            else if ($this->types[$this->i] === 117)
            {
                $operator = $this->tokens[$this->i++];
                $right = $this->expression(self::PRECEDENCE_COMPARISON2 + 1);
                $left = Nodes\GreaterThanExpression::__instantiateUnchecked($this->phpVersion, $left, $operator, $right);
            }
            else if ($this->types[$this->i] === 201)
            {
                $operator = $this->tokens[$this->i++];
                $right = $this->expression(self::PRECEDENCE_COMPARISON2 + 1);
                $left = Nodes\GreaterThanOrEqualsExpression::__instantiateUnchecked($this->phpVersion, $left, $operator, $right);
            }
        }

        if ($minPrecedence <= self::PRECEDENCE_COMPARISON1)
        {
            if ($this->types[$this->i] === 202)
            {
                $operator = $this->tokens[$this->i++];
                $right = $this->expression(self::PRECEDENCE_COMPARISON1 + 1);
                $left = Nodes\IsIdenticalExpression::__instantiateUnchecked($this->phpVersion, $left, $operator, $right);
            }
            else if ($this->types[$this->i] === 204)
            {
                $operator = $this->tokens[$this->i++];
                $right = $this->expression(self::PRECEDENCE_COMPARISON1 + 1);
                $left = Nodes\IsNotIdenticalExpression::__instantiateUnchecked($this->phpVersion, $left, $operator, $right);
            }
            else if ($this->types[$this->i] === 200)
            {
                $operator = $this->tokens[$this->i++];
                $right = $this->expression(self::PRECEDENCE_COMPARISON1 + 1);
                $left = Nodes\IsEqualExpression::__instantiateUnchecked($this->phpVersion, $left, $operator, $right);
            }
            else if ($this->types[$this->i] === 203)
            {
                $operator = $this->tokens[$this->i++];
                $right = $this->expression(self::PRECEDENCE_COMPARISON1 + 1);
                $left = Nodes\IsNotEqualExpression::__instantiateUnchecked($this->phpVersion, $left, $operator, $right);
            }
            else if ($this->types[$this->i] === 238)
            {
                $operator = $this->tokens[$this->i++];
                $right = $this->expression(self::PRECEDENCE_COMPARISON1 + 1);
                $left = Nodes\SpaceshipExpression::__instantiateUnchecked($this->phpVersion, $left, $operator, $right);
            }
        }

        if ($minPrecedence <= self::PRECEDENCE_BITWISE_AND)
        {
            while ($this->types[$this->i] === 104)
            {
                $operator = $this->tokens[$this->i++];
                $right = $this->expression(self::PRECEDENCE_BITWISE_AND + 1);
                $left = Nodes\BitwiseAndExpression::__instantiateUnchecked($this->phpVersion, $left, $operator, $right);
            }
        }

        if ($minPrecedence <= self::PRECEDENCE_BITWISE_XOR)
        {
            while ($this->types[$this->i] === 122)
            {
                $operator = $this->tokens[$this->i++];
                $right = $this->expression(self::PRECEDENCE_BITWISE_XOR + 1);
                $left = Nodes\BitwiseXorExpression::__instantiateUnchecked($this->phpVersion, $left, $operator, $right);
            }
        }

        if ($minPrecedence <= self::PRECEDENCE_BITWISE_OR)
        {
            while ($this->types[$this->i] === 125)
            {
                $operator = $this->tokens[$this->i++];
                $right = $this->expression(self::PRECEDENCE_BITWISE_OR + 1);
                $left = Nodes\BitwiseOrExpression::__instantiateUnchecked($this->phpVersion, $left, $operator, $right);
            }
        }

        if ($minPrecedence <= self::PRECEDENCE_BOOLEAN_AND_SYMBOL)
        {
            while ($this->types[$this->i] === 133)
            {
                $operator = $this->tokens[$this->i++];
                $right = $this->expression(self::PRECEDENCE_BOOLEAN_AND_SYMBOL + 1);
                $left = Nodes\SymbolBooleanAndExpression::__instantiateUnchecked($this->phpVersion, $left, $operator, $right);
            }
        }

        if ($minPrecedence <= self::PRECEDENCE_BOOLEAN_OR_SYMBOL)
        {
            while ($this->types[$this->i] === 134)
            {
                $operator = $this->tokens[$this->i++];
                $right = $this->expression(self::PRECEDENCE_BOOLEAN_OR_SYMBOL + 1);
                $left = Nodes\SymbolBooleanOrExpression::__instantiateUnchecked($this->phpVersion, $left, $operator, $right);
            }
        }

        if ($minPrecedence <= self::PRECEDENCE_COALESCE)
        {
            while ($this->types[$this->i] === 144)
            {
                $operator = $this->tokens[$this->i++];
                $right = $this->expression(self::PRECEDENCE_COALESCE + 1);
                $left = Nodes\CoalesceExpression::__instantiateUnchecked($this->phpVersion, $left, $operator, $right);
            }
        }

        if ($minPrecedence <= self::PRECEDENCE_TERNARY)
        {
            while ($this->types[$this->i] === 118)
            {
                $questionMark = $this->tokens[$this->i++];
                // note: no precedence is passed here, delimiters on both sides allow for e.g. 1 ? 2 and 3 : 4
                $then = $this->types[$this->i] !== 113 ? $this->expression() : null;
                $colon = $this->read(113);
                $else = $this->expression(self::PRECEDENCE_TERNARY + 1);
                $left = Nodes\TernaryExpression::__instantiateUnchecked($this->phpVersion, $left, $questionMark, $then, $colon, $else);
            }
        }

        if ($minPrecedence <= self::PRECEDENCE_BOOLEAN_AND_KEYWORD)
        {
            while ($this->types[$this->i] === 209)
            {
                $operator = $this->tokens[$this->i++];
                $right = $this->expression(self::PRECEDENCE_BOOLEAN_AND_KEYWORD + 1);
                $left = Nodes\KeywordBooleanAndExpression::__instantiateUnchecked($this->phpVersion, $left, $operator, $right);
            }
        }

        if ($minPrecedence <= self::PRECEDENCE_BOOLEAN_XOR_KEYWORD)
        {
            while ($this->types[$this->i] === 211)
            {
                $operator = $this->tokens[$this->i++];
                $right = $this->expression(self::PRECEDENCE_BOOLEAN_XOR_KEYWORD + 1);
                $left = Nodes\KeywordBooleanXorExpression::__instantiateUnchecked($this->phpVersion, $left, $operator, $right);
            }
        }

        if ($minPrecedence <= self::PRECEDENCE_BOOLEAN_OR_KEYWORD)
        {
            while ($this->types[$this->i] === 210)
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
        if ($this->types[$this->i] === 255)
        {
            $node = Nodes\RegularVariableExpression::__instantiateUnchecked($this->phpVersion, $this->tokens[$this->i++]);
        }
        else if ($this->types[$this->i] === 102)
        {
            $node = $this->variableVariable();
        }
        else if ($this->types[$this->i] === 243 || $this->types[$this->i] === 219)
        {
            $node = Nodes\NameExpression::__instantiateUnchecked($this->phpVersion, $this->name());
        }
        else if ($this->types[$this->i] === 242 && $this->types[$this->i + 1] !== 184)
        {
            $node = Nodes\NameExpression::__instantiateUnchecked($this->phpVersion, Nodes\SpecialName::__instantiateUnchecked($this->phpVersion, $this->tokens[$this->i++]));
        }
        else if ($newable)
        {
            throw ParseException::unexpected($this->tokens[$this->i]);
        }
        else if ($this->types[$this->i] === 148)
        {
            $node = Nodes\ConstantStringLiteral::__instantiateUnchecked($this->phpVersion, $this->tokens[$this->i++]);
        }
        else if ($this->types[$this->i] === 101 || $this->types[$this->i] === 241)
        {
            $leftDelimiter = $this->tokens[$this->i++];
            $parts = [];
            while ($this->types[$this->i] !== 101 && $this->types[$this->i] !== 175)
            {
                if ($this->types[$this->i] === 168)
                {
                    $parts[] = Nodes\ConstantInterpolatedStringPart::__instantiateUnchecked($this->phpVersion, $this->tokens[$this->i++]);
                }
                else if ($this->types[$this->i] === 150)
                {
                    $leftBrace = $this->tokens[$this->i++];
                    $expression = $this->expression();
                    $rightBrace = $this->read(126);
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
        else if ($this->types[$this->i] === 241)
        {
            $this->tokens[$this->i++]->debugDump();
            var_dump(__FILE__, __LINE__);
            die(); // TODO
        }
        else if ($this->types[$this->i] === 208)
        {
            $node = Nodes\IntegerLiteral::__instantiateUnchecked($this->phpVersion, $this->tokens[$this->i++]);
        }
        else if ($this->types[$this->i] === 156)
        {
            $node = Nodes\FloatLiteral::__instantiateUnchecked($this->phpVersion, $this->tokens[$this->i++]);
        }
        else if ($this->types[$this->i] === 217)
        {
            $keyword = $this->tokens[$this->i++];
            $class = $this->simpleExpression(true);
            $leftParenthesis = $rightParenthesis = null;
            $arguments = [];
            if ($this->types[$this->i] === 105)
            {
                $leftParenthesis = $this->tokens[$this->i++];
                $arguments = $this->arguments();
                $rightParenthesis = $this->read(106);
            }
            $node = Nodes\NewExpression::__instantiateUnchecked($this->phpVersion, $keyword, $class, $leftParenthesis, $arguments, $rightParenthesis);
        }
        else if ($this->types[$this->i] === 120)
        {
            $leftBracket = $this->tokens[$this->i++];
            $items = $this->arrayItems(121);
            $rightBracket = $this->read(121);
            $node = Nodes\ShortArrayExpression::__instantiateUnchecked($this->phpVersion, $leftBracket, $items, $rightBracket);
        }
        else if ($this->types[$this->i] === 130)
        {
            $keyword = $this->tokens[$this->i++];
            $leftParenthesis = $this->read(105);
            $items = $this->arrayItems(106);
            $rightParenthesis = $this->read(106);
            $node = Nodes\LongArrayExpression::__instantiateUnchecked($this->phpVersion, $keyword, $leftParenthesis, $items, $rightParenthesis);
        }
        else if ($this->types[$this->i] === 105)
        {
            $leftParenthesis = $this->tokens[$this->i++];
            $expression = $this->expression();
            $rightParenthesis = $this->read(106);
            $node = Nodes\ParenthesizedExpression::__instantiateUnchecked($this->phpVersion, $leftParenthesis, $expression, $rightParenthesis);
        }
        else if ($this->types[$this->i] === 199)
        {
            $keyword = $this->tokens[$this->i++];
            $leftParenthesis = $this->read(105);
            $expressions = [null];
            do
            {
                $expressions[] = $this->expression();
            }
            while ($this->types[$this->i] === 109 && $expressions[] = $this->tokens[$this->i++]);
            $rightParenthesis = $this->read(106);
            $node = Nodes\IssetExpression::__instantiateUnchecked($this->phpVersion, $keyword, $leftParenthesis, $expressions, $rightParenthesis);
        }
        else if ($this->types[$this->i] === 167)
        {
            $keyword = $this->tokens[$this->i++];
            $leftParenthesis = $this->read(105);
            $expression = $this->expression();
            $rightParenthesis = $this->read(106);
            $node = Nodes\EmptyExpression::__instantiateUnchecked($this->phpVersion, $keyword, $leftParenthesis, $expression, $rightParenthesis);
        }
        else if ($this->types[$this->i] === 151)
        {
            $operator = $this->tokens[$this->i++];
            $expression = $this->simpleExpression();
            $node = Nodes\PreDecrementExpression::__instantiateUnchecked($this->phpVersion, $operator, $expression);
        }
        else if ($this->types[$this->i] === 191)
        {
            $operator = $this->tokens[$this->i++];
            $expression = $this->simpleExpression();
            $node = Nodes\PreIncrementExpression::__instantiateUnchecked($this->phpVersion, $operator, $expression);
        }
        else if (\in_array($this->types[$this->i], Token::MAGIC_CONSTANTS, true))
        {
            $node = Nodes\MagicConstant::__instantiateUnchecked($this->phpVersion, $this->tokens[$this->i++]);
        }
        else if ($this->types[$this->i] === 142)
        {
            $keyword = $this->tokens[$this->i++];
            $expression = $this->expression(self::PRECEDENCE_ASSIGN_LVALUE);
            $node = Nodes\CloneExpression::__instantiateUnchecked($this->phpVersion, $keyword, $expression);
        }
        else if ($this->types[$this->i] === 100)
        {
            $operator = $this->tokens[$this->i++];
            $expression = $this->expression(self::PRECEDENCE_BOOLEAN_NOT);
            $node = Nodes\BooleanNotExpression::__instantiateUnchecked($this->phpVersion, $operator, $expression);
        }
        else if ($this->types[$this->i] === 259)
        {
            $keyword = $this->tokens[$this->i++];
            $key = null;
            $expression = $this->expression(self::PRECEDENCE_TERNARY);
            if ($this->types[$this->i] === 160)
            {
                $key = Nodes\Key::__instantiateUnchecked($this->phpVersion, $expression, $this->tokens[$this->i++]);
                $expression = $this->expression(self::PRECEDENCE_TERNARY);
            }
            $node = Nodes\YieldExpression::__instantiateUnchecked($this->phpVersion, $keyword, $key, $expression);
        }
        else if ($this->types[$this->i] === 260)
        {
            $keyword = $this->tokens[$this->i++];
            $expression = $this->expression(self::PRECEDENCE_TERNARY);
            $node = Nodes\YieldFromExpression::__instantiateUnchecked($this->phpVersion, $keyword, $expression);
        }
        else if (in_array($this->types[$this->i], [192, 193, 233, 234], true))
        {
            $keyword = $this->tokens[$this->i++];
            $expression = $this->expression();
            $node = Nodes\IncludeLikeExpression::__instantiateUnchecked($this->phpVersion, $keyword, $expression);
        }
        else if ($this->types[$this->i] === 127)
        {
            $symbol = $this->tokens[$this->i++];
            $expression = $this->expression(self::PRECEDENCE_ASSIGN_LVALUE);
            $node = Nodes\BitwiseNotExpression::__instantiateUnchecked($this->phpVersion, $symbol, $expression);
        }
        else if ($this->types[$this->i] === 110)
        {
            $symbol = $this->tokens[$this->i++];
            $expression = $this->expression(self::PRECEDENCE_ASSIGN_LVALUE);
            $node = Nodes\UnaryMinusExpression::__instantiateUnchecked($this->phpVersion, $symbol, $expression);
        }
        else if ($this->types[$this->i] === 108)
        {
            $symbol = $this->tokens[$this->i++];
            $expression = $this->expression(self::PRECEDENCE_ASSIGN_LVALUE);
            $node = Nodes\UnaryPlusExpression::__instantiateUnchecked($this->phpVersion, $symbol, $expression);
        }
        else if (\in_array($this->types[$this->i], Token::CASTS, true))
        {
            $cast = $this->tokens[$this->i++];
            $expression = $this->expression(self::PRECEDENCE_CAST);
            $node = Nodes\CastExpression::__instantiateUnchecked($this->phpVersion, $cast, $expression);
        }
        else if ($this->types[$this->i] === 119)
        {
            $operator = $this->tokens[$this->i++];
            $expression = $this->expression(self::PRECEDENCE_POW); // TODO test precedence...
            $node = Nodes\SuppressErrorsExpression::__instantiateUnchecked($this->phpVersion, $operator, $expression);
        }
        else if ($this->types[$this->i] === 207)
        {
            $keyword = $this->tokens[$this->i++];
            $leftParenthesis = $this->read(105);
            $expressions = [null];
            do
            {
                $expressions[] = $this->simpleExpression();
            }
            while ($this->types[$this->i] === 109 && $expressions[] = $this->tokens[$this->i++]);
            $rightParenthesis = $this->read(106);
            $node = Nodes\ListExpression::__instantiateUnchecked($this->phpVersion, $keyword, $leftParenthesis, $expressions, $rightParenthesis);
        }
        else if ($this->types[$this->i] === 177)
        {
            $keyword = $this->tokens[$this->i++];
            $leftParenthesis = $this->read(105);
            $expression = $this->types[$this->i] !== 106 ? $this->expression() : null;
            $rightParenthesis = $this->read(106);
            $node = Nodes\ExitExpression::__instantiateUnchecked($this->phpVersion, $keyword, $leftParenthesis, $expression, $rightParenthesis);
        }
        else if ($this->types[$this->i] === 229)
        {
            $keyword = $this->tokens[$this->i++];
            $expression = $this->expression(self::PRECEDENCE_BOOLEAN_NOT);
            $node = Nodes\PrintExpression::__instantiateUnchecked($this->phpVersion, $keyword, $expression);
        }
        else if ($this->types[$this->i] === 176)
        {
            $keyword = $this->tokens[$this->i++];
            $leftParenthesis = $this->read(105);
            $expression = $this->expression();
            $rightParenthesis = $this->read(106);
            $node = Nodes\EvalExpression::__instantiateUnchecked($this->phpVersion, $keyword, $leftParenthesis, $expression, $rightParenthesis);
        }
        else if (
            $this->types[$this->i] === 184 ||
            ($this->types[$this->i] === 242 && $this->types[$this->i + 1] === 184)
        )
        {
            $static = $this->types[$this->i] === 242 ? $this->tokens[$this->i++] : null;
            $keyword = $this->tokens[$this->i++];
            $byReference = $this->types[$this->i] === 104 ? $this->tokens[$this->i++] : null;
            $leftParenthesis = $this->read(105);
            $parameters = $this->parameters();
            $rightParenthesis = $this->read(106);
            $use = null;
            if ($this->types[$this->i] === 253)
            {
                $useKeyword = $this->tokens[$this->i++];
                $useLeftParenthesis = $this->read(105);
                $useBindings = [null];
                while (true)
                {
                    $useBindingByReference = null;
                    if ($this->types[$this->i] === 104)
                    {
                        $useBindingByReference = $this->tokens[$this->i++];
                    }
                    $useBindingVariable = $this->read(255);
                    $useBindings[] = Nodes\AnonymousFunctionUseBinding::__instantiateUnchecked($this->phpVersion, $useBindingByReference, $useBindingVariable);

                    if ($this->types[$this->i] === 109)
                    {
                        $useBindings[] = $this->tokens[$this->i++];
                    }
                    else
                    {
                        break;
                    }
                }
                $useRightParenthesis = $this->read(106);
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
            if ($this->types[$this->i] === 222)
            {
                $operator = $this->tokens[$this->i++];

                $name = $this->memberName();
                // expr->v -> access the property named 'v'
                // new expr->v -> instantiate the class named by expr->v
                // new expr->v() -> same, () is part of the NewExpression
                if ($this->types[$this->i] !== 105 || $newable)
                {
                    $node = Nodes\PropertyAccessExpression::__instantiateUnchecked($this->phpVersion, $node, $operator, $name);
                }
                // expr->v() -> call the method named v
                else
                {
                    $leftParenthesis = $this->tokens[$this->i++];
                    $arguments = $this->arguments();
                    $rightParenthesis = $this->read(106);
                    $node = Nodes\MethodCallExpression::__instantiateUnchecked($this->phpVersion, $node, $operator, $name, $leftParenthesis, $arguments, $rightParenthesis);
                }
            }
            else if ($this->types[$this->i] === 105)
            {
                // new expr() -> () always belongs to new
                if ($newable)
                {
                    break;
                }

                $leftParenthesis = $this->tokens[$this->i++];
                $arguments = $this->arguments();
                $rightParenthesis = $this->read(106);
                $node = Nodes\FunctionCallExpression::__instantiateUnchecked($this->phpVersion, $node, $leftParenthesis, $arguments, $rightParenthesis);
            }
            else if ($this->types[$this->i] === 120)
            {
                $leftBracket = $this->tokens[$this->i++];
                $index = $this->types[$this->i] === 121 ? null : $this->expression();
                $rightBracket = $this->read(121);
                $node = Nodes\ArrayAccessExpression::__instantiateUnchecked($this->phpVersion, $node, $leftBracket, $index, $rightBracket);
            }
            else if ($this->types[$this->i] === 162)
            {
                // TODO manual test coverage for error messages, esp. in combination with new

                $operator = $this->tokens[$this->i++];

                switch ($this->types[$this->i])
                {
                    /** @noinspection PhpMissingBreakStatementInspection */
                    case 243:
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
                    case 255:
                        $variable = Nodes\RegularVariableExpression::__instantiateUnchecked($this->phpVersion, $this->tokens[$this->i++]);
                        foundVariable:
                        // expr::$v -> access the static property named 'v'
                        // new expr::$v -> instantiate the class named by the value of expr::$v
                        // new expr::$v() -> same, () is part of the NewExpression
                        if ($this->types[$this->i] !== 105 || $newable)
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
                    case 102:
                        $variable = $this->variableVariable();
                        // all variations are the same as `expr::$v`, except the variable is variable
                        goto foundVariable;

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

                    staticCall:
                        // we jump here when we positively decide on a static call, and have set up $memberName
                        /** @var Nodes\MemberName $memberName */
                        $leftParenthesis = $this->read(105);
                        $arguments = $this->arguments();
                        $rightParenthesis = $this->read(106);
                        $node = Nodes\StaticMethodCallExpression::__instantiateUnchecked($this->phpVersion, $node, $operator, $memberName, $leftParenthesis, $arguments, $rightParenthesis);
                        break;

                    // TODO case T_STATIC etc
                    // which is either an access for the constant static or a call for the method static
                    // we'll probaby want to goto into to the T_STRING case here...
                    default:
                        if ($this->tokens[$this->i]->getSource() === 'class')
                        {
                            // TODO what is this!? undoing of Lexer/$forceIdentifier? maybe we should force the identifier in the parser
                            // TODO property handle reserved, semi reserved
                            $keyword = $this->tokens[$this->i++]->_withType(140);
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

        if ($this->types[$this->i] === 151)
        {
            $operator = $this->tokens[$this->i++];
            $node = Nodes\PostDecrementExpression::__instantiateUnchecked($this->phpVersion, $node, $operator);
        }
        else if ($this->types[$this->i] === 191)
        {
            $operator = $this->tokens[$this->i++];
            $node = Nodes\PostIncrementExpression::__instantiateUnchecked($this->phpVersion, $node, $operator);
        }

        return $node;
    }

    private function block(int $level = self::STMT_LEVEL_OTHER): Nodes\Block
    {
        if ($this->types[$this->i] === 124)
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
        while ($this->types[$this->i] !== 126)
        {
            $statements[] = $this->statement($level);
        }
        $rightBrace = $this->read(126);
        return Nodes\RegularBlock::__instantiateUnchecked($this->phpVersion, $leftBrace, $statements, $rightBrace);
    }

    private function altBlock(int $endKeywordType, array $implicitEndKeywords = []): Nodes\AlternativeFormatBlock
    {
        $colon = $this->read(113);

        $statements = [];
        while ($this->types[$this->i] !== $endKeywordType && !in_array($this->types[$this->i], $implicitEndKeywords))
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

    /** @return array<Node|null>[] */
    private function parameters(): array
    {
        $nodes = [null];
        if ($this->types[$this->i] !== 106)
        {
            $nodes[] = $this->parameter();
        }
        while ($this->types[$this->i] === 109)
        {
            $nodes[] = $this->tokens[$this->i++];
            $nodes[] = $this->parameter();
        }
        return $nodes;
    }

    private function parameter(): Nodes\Parameter
    {
        $type = $byReference = $ellipsis = null;

        if ($this->types[$this->i] !== 104 && $this->types[$this->i] !== 255 && $this->types[$this->i] !== 164)
        {
            $type = $this->type();
        }

        if ($this->types[$this->i] === 104)
        {
            $byReference = $this->tokens[$this->i++];
        }

        if ($this->types[$this->i] === 164)
        {
            $ellipsis = $this->tokens[$this->i++];
        }

        $variable = $this->read(255);

        if ($ellipsis && $this->types[$this->i] === 116)
        {
            throw ParseException::unexpected($this->tokens[$this->i]);
        }
        $default = $this->default_();

        return Nodes\Parameter::__instantiateUnchecked($this->phpVersion, $type, $byReference, $ellipsis, $variable, $default);
    }

    /** @return array<Node|null>[] */
    private function arguments(): array
    {
        $arguments = [null];
        while ($this->types[$this->i] !== 106)
        {
            $unpack = $this->types[$this->i] === 164 ? $this->tokens[$this->i++] : null;
            $value = $this->expression();
            $arguments[] = Nodes\Argument::__instantiateUnchecked($this->phpVersion, $unpack, $value);

            if ($this->types[$this->i] === 109)
            {
                $arguments[] = $this->tokens[$this->i++];

                // trailing comma is not allowed before 7.4
                if ($this->phpVersion < PhpVersion::PHP_7_4 && $this->types[$this->i] === 106)
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
        if ($this->types[$this->i] === 113)
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
        $nullableSymbol = $this->types[$this->i] === 118 ? $this->tokens[$this->i++] : null;

        if (in_array($this->types[$this->i], [130, 137], true))
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
        if ($this->types[$this->i] === 242)
        {
            return Nodes\SpecialName::__instantiateUnchecked($this->phpVersion, $this->tokens[$this->i++]);
        }
        else
        {
            $parts = [];
            $parts[] = $this->types[$this->i] === 219 ? $this->tokens[$this->i++] : null;
            $parts[] = $this->read(243);
            while ($this->types[$this->i] === 219 && $this->types[$this->i + 1] === 243)
            {
                $parts[] = $this->tokens[$this->i++];
                $parts[] = $this->tokens[$this->i++];
            }
            return Nodes\RegularName::__instantiateUnchecked($this->phpVersion, $parts);
        }
    }

    private function default_(): ?Nodes\Default_
    {
        if ($this->types[$this->i] === 116)
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
        if ($this->types[$this->i] === 243)
        {
            return Nodes\RegularMemberName::__instantiateUnchecked($this->phpVersion, $this->tokens[$this->i++]);
        }
        else if ($this->types[$this->i] === 255)
        {
            $expression = Nodes\RegularVariableExpression::__instantiateUnchecked($this->phpVersion, $this->tokens[$this->i++]);
            return Nodes\VariableMemberName::__instantiateUnchecked($this->phpVersion, null, $expression, null);
        }
        else if ($this->types[$this->i] === 102)
        {
            $expression = $this->variableVariable();
            return Nodes\VariableMemberName::__instantiateUnchecked($this->phpVersion, null, $expression, null);
        }
        else if ($this->types[$this->i] === 124)
        {
            $leftBrace = $this->tokens[$this->i++];
            $expr = $this->expression();
            $rightBrace = $this->read(126);
            return Nodes\VariableMemberName::__instantiateUnchecked($this->phpVersion, $leftBrace, $expr, $rightBrace);
        }
        else
        {
            throw ParseException::unexpected($this->tokens[$this->i]);
        }
    }

    private function variableVariable(): Nodes\VariableVariableExpression
    {
        $dollar = $this->read(102);
        $leftBrace = $rightBrace = null;
        if ($this->types[$this->i] === 255)
        {
            $expression = Nodes\RegularVariableExpression::__instantiateUnchecked($this->phpVersion, $this->tokens[$this->i++]);
        }
        else if ($this->types[$this->i] === 102)
        {
            $expression = $this->variableVariable();
        }
        else if ($this->types[$this->i] === 124)
        {
            $leftBrace = $this->tokens[$this->i++];
            $expression = $this->expression();
            $rightBrace = $this->read(126);
        }
        else
        {
            throw ParseException::unexpected($this->tokens[$this->i]);
        }

        return Nodes\VariableVariableExpression::__instantiateUnchecked($this->phpVersion, $dollar, $leftBrace, $expression, $rightBrace);
    }

    /** @return array<Node|null>[] */
    private function arrayItems(int $delimiter): array
    {
        $items = [null];
        while ($this->types[$this->i] !== $delimiter)
        {
            $key = $byReference = $value = null;

            if ($this->types[$this->i] === 104)
            {
                $byReference = $this->tokens[$this->i++];
            }

            if ($this->types[$this->i] !== 109 && $this->types[$this->i] !== $delimiter)
            {
                $value = $this->expression();
            }

            if ($this->types[$this->i] === 160)
            {
                if ($byReference)
                {
                    throw ParseException::unexpected($this->tokens[$this->i]);
                }

                $key = Nodes\Key::__instantiateUnchecked($this->phpVersion, $value, $this->tokens[$this->i++]);
                $value = null;
            }

            if ($this->types[$this->i] === 104)
            {
                $byReference = $this->tokens[$this->i++];
            }

            if ($this->types[$this->i] !== 109 && $this->types[$this->i] !== $delimiter)
            {
                $value = $this->expression();
            }

            $items[] = Nodes\ArrayItem::__instantiateUnchecked($this->phpVersion, $key, $byReference, $value);

            if ($this->types[$this->i] === 109)
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
