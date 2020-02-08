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
            if ($this->types[$this->i] !== 999)
            {
                throw ParseException::unexpected($this->tokens[$this->i]);
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
            if ($this->types[$this->i] !== 999)
            {
                throw ParseException::unexpected($this->tokens[$this->i]);
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
        if ($expectedTokenType !== null && $this->types[$this->i] !== $expectedTokenType)
        {
            throw ParseException::unexpected($this->tokens[$this->i]);
        }

        return $this->tokens[$this->i++];
    }

    private function parseRoot(): Nodes\RootNode
    {
        $statements = [];

        while ($this->types[$this->i] !== 999)
        {
            $statements[] = $this->statement(self::STMT_LEVEL_TOP);
        }

        $eof = $this->read(999);

        return Nodes\RootNode::__instantiateUnchecked($statements, $eof);
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

            $levels = null;
            if ($this->types[$this->i] === 208)
            {
                $levels = Nodes\Expressions\IntegerLiteral::__instantiateUnchecked($this->tokens[$this->i++]);
            }

            $semiColon = $this->statementDelimiter();
            return Nodes\Statements\BreakStatement::__instantiateUnchecked($keyword, $levels, $semiColon);
        }
        else if ($this->types[$this->i] === 147)
        {
            $keyword = $this->tokens[$this->i++];
            $name = $this->read(243);
            $equals = $this->read(116);
            $value = $this->expression();
            $delimiter = $this->statementDelimiter();
            return Nodes\Statements\ConstStatement::__instantiateUnchecked($keyword, $name, $equals, $value, $delimiter);
        }
        else if ($this->types[$this->i] === 149)
        {
            $keyword = $this->tokens[$this->i++];

            $levels = null;
            if ($this->types[$this->i] === 208)
            {
                $levels = Nodes\Expressions\IntegerLiteral::__instantiateUnchecked($this->tokens[$this->i++]);
            }

            $semiColon = $this->statementDelimiter();
            return Nodes\Statements\ContinueStatement::__instantiateUnchecked($keyword, $levels, $semiColon);
        }
        else if ($this->types[$this->i] === 152)
        {
            $keyword = $this->tokens[$this->i++];
            $leftParenthesis = $this->read(105);
            $directives = [];
            do
            {
                $directiveKey = $this->read(243);
                $directiveEquals = $this->read(116);
                $directiveValue = $this->expression();
                $directives[] = Nodes\Statements\DeclareDirective::__instantiateUnchecked($directiveKey, $directiveEquals, $directiveValue);
            }
            while ($this->types[$this->i] === 109 && $directives[] = $this->tokens[$this->i++]);
            $rightParenthesis = $this->read(106);
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
        else if ($this->types[$this->i] === 157)
        {
            $keyword1 = $this->tokens[$this->i++];
            $block = $this->block();
            $keyword2 = $this->read(256);
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
        }
        else if ($this->types[$this->i] === 163 || $this->types[$this->i] === 224)
        {
            $keyword = $this->tokens[$this->i++];
            $expressions = [];
            $expressions[] = $this->expression();
            while ($this->types[$this->i] === 109)
            {
                $expressions[] = $this->tokens[$this->i++];
                $expressions[] = $this->expression();
            }
            $semiColon = $this->statementDelimiter();
            return Nodes\Statements\EchoStatement::__instantiateUnchecked($keyword, $expressions, $semiColon);
        }
        else if ($this->types[$this->i] === 182)
        {
            $keyword = $this->tokens[$this->i++];
            $leftParenthesis = $this->read(105);

            $init = [];
            if ($this->types[$this->i] !== 114)
            {
                $init[] = $this->expression();
                while ($this->types[$this->i] === 109)
                {
                    $init[] = $this->tokens[$this->i++];
                    $init[] = $this->expression();
                }
            }

            $separator1 = $this->types[$this->i] === 109 ? $this->tokens[$this->i++] : $this->read(114);

            $test = [];
            if ($this->types[$this->i] !== 114)
            {
                $test[] = $this->expression();
                while ($this->types[$this->i] === 109)
                {
                    $test[] = $this->tokens[$this->i++];
                    $test[] = $this->expression();
                }
            }

            $separator2 = $this->types[$this->i] === 109 ? $this->tokens[$this->i++] : $this->read(114);

            $step = [];
            if ($this->types[$this->i] !== 106)
            {
                $step[] = $this->expression();
                while ($this->types[$this->i] === 109)
                {
                    $step[] = $this->tokens[$this->i++];
                    $step[] = $this->expression();
                }
            }

            $rightParenthesis = $this->read(106);

            $block = $this->types[$this->i] === 113 ? $this->altBlock(170) : $this->block();

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
        else if ($this->types[$this->i] === 183)
        {
            $keyword = $this->tokens[$this->i++];
            $leftParenthesis = $this->read(105);
            $iterable = $this->expression();
            $as = $this->read(132);

            $key = $byReference = null;
            $byReference = $this->types[$this->i] === 104 ? $this->tokens[$this->i++] : null;
            $value = $this->simpleExpression();

            if ($this->types[$this->i] === 160)
            {
                if ($byReference)
                {
                    throw ParseException::unexpected($this->tokens[$this->i]);
                }

                $key = Nodes\Helpers\Key::__instantiateUnchecked($value, $this->tokens[$this->i++]);

                if ($this->types[$this->i] === 104)
                {
                    $byReference = $this->tokens[$this->i++];
                }

                $value = $this->simpleExpression();
            }

            $rightParenthesis = $this->read(106);

            $block = $this->types[$this->i] === 113 ? $this->altBlock(171) : $this->block();

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
        else if ($this->types[$this->i] === 187)
        {
            $keyword = $this->tokens[$this->i++];
            $label = $this->read(243);
            $semiColon = $this->statementDelimiter();
            return Nodes\Statements\GotoStatement::__instantiateUnchecked(
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
                $elseifs[] = Nodes\Statements\Elseif_::__instantiateUnchecked(
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
        else if ($this->types[$this->i] === 235)
        {
            $keyword = $this->tokens[$this->i++];
            $expression = !$this->endOfStatement() ? $this->expression() : null;
            $semiColon = $this->statementDelimiter();
            return Nodes\Statements\ReturnStatement::__instantiateUnchecked($keyword, $expression, $semiColon);
        }
        else if ($this->types[$this->i] === 242 && $this->types[$this->i + 1] === 255)
        {
            $keyword = $this->tokens[$this->i++];
            $variables = [];
            do
            {
                $variable = $this->read(255);
                $default = $this->default_();
                $variables[] = Nodes\Statements\StaticVariable::__instantiateUnchecked($variable, $default);
            }
            while ($this->types[$this->i] === 109 && $variables[] = $this->tokens[$this->i++]);
            $semiColon = $this->statementDelimiter();
            return Nodes\Statements\StaticVariableStatement::__instantiateUnchecked($keyword, $variables, $semiColon);
        }
        else if ($this->types[$this->i] === 246)
        {
            $keyword = $this->tokens[$this->i++];

            $leftParenthesis = $this->read(105);
            $value = $this->expression();
            $rightParenthesis = $this->read(106);

            $leftBrace = $this->read(124);

            $cases = [];
            while ($this->types[$this->i] !== 126)
            {
                if ($this->types[$this->i] === 153)
                {
                    $caseKeyword = $this->tokens[$this->i++];
                }
                else
                {
                    $caseKeyword = $this->read(138);
                }
                $caseValue = null;
                if ($this->types[$this->i] !== 113)
                {
                    $caseValue = $this->expression();
                }
                $caseDelimiter = $this->types[$this->i] === 114 ? $this->tokens[$this->i++] : $this->read(113);
                $caseStatements = [];
                while ($this->types[$this->i] !== 138 && $this->types[$this->i] !== 153 && $this->types[$this->i] !== 126)
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
        }
        else if ($this->types[$this->i] === 247)
        {
            $keyword = $this->tokens[$this->i++];
            $expression = $this->expression();
            $semiColon = $this->statementDelimiter();
            return Nodes\Statements\ThrowStatement::__instantiateUnchecked($keyword, $expression, $semiColon);
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
                $catchTypes = [];
                $catchTypes[] = Nodes\Types\NamedType::__instantiateUnchecked($this->name());
                while ($this->types[$this->i] === 125)
                {
                    $catchTypes[] = $this->tokens[$this->i++];
                    $catchTypes[] = Nodes\Types\NamedType::__instantiateUnchecked($this->name());
                }
                $catchVariable = $this->read(255);
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
            if ($this->types[$this->i] === 181)
            {
                $finallyKeyword = $this->tokens[$this->i++];
                $finallyBlock = $this->regularBlock();
                $finally = Nodes\Statements\Finally_::__instantiateUnchecked($finallyKeyword, $finallyBlock);
            }
            return Nodes\Statements\TryStatement::__instantiateUnchecked($keyword, $block, $catches, $finally);
        }
        else if ($this->types[$this->i] === 256)
        {
            $keyword = $this->tokens[$this->i++];
            $leftParenthesis = $this->read(105);
            $test = $this->expression();
            $rightParenthesis = $this->read(106);

            $block = $this->types[$this->i] === 113 ? $this->altBlock(174) : $this->block();

            return Nodes\Statements\WhileStatement::__instantiateUnchecked($keyword, $leftParenthesis, $test, $rightParenthesis, $block);
        }
        else if ($this->types[$this->i] === 251)
        {
            $keyword = $this->tokens[$this->i++];
            $leftParenthesis = $this->read(105);
            $expressions = [];
            do
            {
                $expressions[] = $this->simpleExpression();
            }
            while ($this->types[$this->i] === 109 && $expressions[] = $this->tokens[$this->i++]);
            $rightParenthesis = $this->read(106);
            $semiColon = $this->statementDelimiter();
            return Nodes\Statements\UnsetStatement::__instantiateUnchecked($keyword, $leftParenthesis, $expressions, $rightParenthesis, $semiColon);
        }
        else if ($this->types[$this->i] === 124)
        {
            return Nodes\Statements\BlockStatement::__instantiateUnchecked($this->regularBlock());
        }
        else if ($this->types[$this->i] === 243 && $this->types[$this->i + 1] === 113)
        {
            return Nodes\Statements\LabelStatement::__instantiateUnchecked($this->tokens[$this->i++], $this->tokens[$this->i++]);
        }
        else if ($this->types[$this->i] === 194 || $this->types[$this->i] === 223)
        {
            $content = $this->types[$this->i] === 194 ? $this->tokens[$this->i++] : null;
            $open = $this->types[$this->i] === 223 ? $this->tokens[$this->i++] :     null;
            return Nodes\Statements\InlineHtmlStatement::__instantiateUnchecked($content, $open);
        }
        else if ($this->types[$this->i] === 114 || $this->types[$this->i] === 143)
        {
            return Nodes\Statements\NopStatement::__instantiateUnchecked($this->tokens[$this->i++]);
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
        $keyword = $this->read(216);
        $name = null;
        if ($this->types[$this->i] !== 124)
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
        return Nodes\Statements\NamespaceStatement::__instantiateUnchecked($keyword, $name, $block, $semiColon);
    }

    private function use_(): Nodes\Statements\UseStatement
    {
        $keyword = $this->read(253);
        $type = null;
        if ($this->types[$this->i] === 184 || $this->types[$this->i] === 147)
        {
            $type = $this->tokens[$this->i++];
        }

        $firstName = null;
        if ($this->types[$this->i] === 219 || $this->types[$this->i] === 243)
        {
            $firstName = $this->name();
        }

        if ($this->types[$this->i] === 219)
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
            if ($this->types[$this->i] === 132)
            {
                $aliasKeyword = $this->tokens[$this->i++];
                $alias = Nodes\Statements\UseAlias::__instantiateUnchecked($aliasKeyword, $this->read(243));
            }

            $declarations[] = Nodes\Statements\UseDeclaration::__instantiateUnchecked($name, $alias);

            if ($this->types[$this->i] === 109)
            {
                $declarations[] = $this->tokens[$this->i++];
            }
            else
            {
                $declarations[] = null;
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

    private function class_(): Nodes\Oop\ClassDeclaration
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
            $extendsNames = [];
            $extendsNames[] = $this->name();
            $extends = Nodes\Oop\Extends_::__instantiateUnchecked($extendsKeyword, $extendsNames);
        }
        else
        {
            $extends = null;
        }
        if ($this->types[$this->i] === 190)
        {
            $implementsKeyword = $this->tokens[$this->i++];
            $implementsNames = [];
            $implementsNames[] = $this->name();
            while ($this->types[$this->i] === 109)
            {
                $implementsNames[] = $this->tokens[$this->i++];
                $implementsNames[] = $this->name();
            }
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
            $members[] = $this->classLikeMember();
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
    }

    private function interface_(): Nodes\Oop\InterfaceDeclaration
    {
        $keyword = $this->read(197);
        $name = $this->read(243);
        if ($this->types[$this->i] === 178)
        {
            $extendsKeyword = $this->tokens[$this->i++];
            $extendsNames = [];
            $extendsNames[] = $this->name();
            while ($this->types[$this->i] === 109)
            {
                $extendsNames[] = $this->tokens[$this->i++];
                $extendsNames[] = $this->name();
            }
            $extends = Nodes\Oop\Extends_::__instantiateUnchecked($extendsKeyword, $extendsNames);
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
        $keyword = $this->read(248);
        $name = $this->read(243);
        $leftBrace = $this->read(124);
        $members = [];
        while ($this->types[$this->i] !== 126)
        {
            $members[] = $this->classLikeMember();
        }
        $rightBrace = $this->read(126);
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
        while (\in_array($this->types[$this->i], [128, 180, 232, 231, 230, 242], true))
        {
            $modifiers[] = $this->tokens[$this->i++];
        }

        if ($this->types[$this->i] === 184)
        {
            $keyword = $this->tokens[$this->i++];
            $byReference = $this->types[$this->i] === 104 ? $this->tokens[$this->i++] : null;
            if ($this->types[$this->i] === 243)
            {
                $name = $this->tokens[$this->i++];
            }
            else if (\in_array($this->types[$this->i], T::SEMI_RESERVED, true))
            {
                $this->tokens[$this->i]->_fudgeType(243);
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
            if ($this->types[$this->i] === 114)
            {
                $body = null;
                $semiColon = $this->tokens[$this->i++];
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
        else if ($this->types[$this->i] === 147)
        {
            $keyword = $this->tokens[$this->i++];
            if ($this->types[$this->i] === 243)
            {
                $name = $this->tokens[$this->i++];
            }
            else if (\in_array($this->types[$this->i], T::SEMI_RESERVED, true))
            {
                $this->tokens[$this->i]->_fudgeType(243);
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
        else if ($this->types[$this->i] === 253)
        {
            $keyword = $this->tokens[$this->i++];
            $names = [$this->name()];
            while ($this->types[$this->i] === 109)
            {
                $names[] = $this->tokens[$this->i++];
                $names[] = $this->name();
            }
            $leftBrace = $rightBrace = $semiColon = null;
            $modifications = [];
            if ($this->types[$this->i] === 124)
            {
                $leftBrace = $this->tokens[$this->i++];
                if ($this->types[$this->i + 1] === 132 || $this->types[$this->i] === 196)
                {
                    $ref = Nodes\Oop\TraitMethodRef::__instantiateUnchecked(null, null, $this->read(243));
                }
                else
                {
                    $trait = $this->name();
                    $doubleColon = $this->read(162);
                    $method = $this->read(243);
                    $ref = Nodes\Oop\TraitMethodRef::__instantiateUnchecked($trait, $doubleColon, $method);
                }
                if ($this->types[$this->i] === 196)
                {
                    $modKeyword = $this->tokens[$this->i++];
                    $excluded = [$this->name()];
                    while ($this->types[$this->i] === 109)
                    {
                        $excluded[] = $this->tokens[$this->i++];
                        $excluded[] = $this->name();
                    }
                    $modSemi = $this->read(114);
                    $modifications[] = Nodes\Oop\TraitUseInsteadof::__instantiateUnchecked($ref, $modKeyword, $excluded, $modSemi);
                }
                else if ($this->types[$this->i] === 132)
                {
                    $modKeyword = $this->tokens[$this->i++];
                    $modifier = null;
                    if ($this->types[$this->i] === 232 || $this->types[$this->i] === 231 || $this->types[$this->i] === 230)
                    {
                        $modifier = $this->tokens[$this->i++];
                    }
                    $alias = $this->read(243);
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
        else
        {
            $variable = $this->read(255);
            $default = $this->default_();
            $semiColon = $this->read(114);
            return Nodes\Oop\Property::__instantiateUnchecked($modifiers, $variable, $default, $semiColon);
        }
    }

    private function endOfStatement(): bool
    {
        $t = $this->types[$this->i];
        return $t === 114 || $t === 143 || $t === 999;
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

    private function expression(int $minPrecedence = 0): Nodes\Expression
    {
        $left = $this->simpleExpression();

        if ($this->types[$this->i] === 116)
        {
            if ($this->types[$this->i + 1] === 104)
            {
                $operator1 = $this->tokens[$this->i++];
                $operator2 = $this->tokens[$this->i++];
                $right = $this->simpleExpression();
                $left = Nodes\Expressions\AliasingExpression::__instantiateUnchecked($left, $operator1, $operator2, $right);
            }
            else
            {
                $operator = $this->tokens[$this->i++];
                $value = $this->expression(25);
                $left = Nodes\Expressions\AssignmentExpression::__instantiateUnchecked($left, $operator, $value);
            }
        }
        else if (\in_array($this->types[$this->i], T::COMBINED_ASSIGNMENTS, true))
        {
            $operator = $this->tokens[$this->i++];
            $value = $this->expression(25);
            $left = Nodes\Expressions\CombinedAssignmentExpression::__instantiateUnchecked($left, $operator, $value);
        }

        while (true)
        {
            if ($minPrecedence <= 62 && $this->types[$this->i] === 227)
            {
                $operator = $this->tokens[$this->i++];
                $right = $this->expression(62);
                $left = Nodes\Expressions\PowerExpression::__instantiateUnchecked($left, $operator, $right);
            }
            else if ($minPrecedence <= 60 && $this->types[$this->i] === 195)
            {
                $operator = $this->tokens[$this->i++];
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
                if ($this->types[$this->i] === 107)
                {
                    $operator = $this->tokens[$this->i++];
                    $right = $this->expression(49 + 1);
                    $left = Nodes\Expressions\MultiplyExpression::__instantiateUnchecked($left, $operator, $right);
                }
                else if ($this->types[$this->i] === 112)
                {
                    $operator = $this->tokens[$this->i++];
                    $right = $this->expression(49 + 1);
                    $left = Nodes\Expressions\DivideExpression::__instantiateUnchecked($left, $operator, $right);
                }
                else if ($this->types[$this->i] === 103)
                {
                    $operator = $this->tokens[$this->i++];
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
                if ($this->types[$this->i] === 108)
                {
                    $operator = $this->tokens[$this->i++];
                    $right = $this->expression(48 + 1);
                    $left = Nodes\Expressions\AddExpression::__instantiateUnchecked($left, $operator, $right);
                }
                else if ($this->types[$this->i] === 110)
                {
                    $operator = $this->tokens[$this->i++];
                    $right = $this->expression(48 + 1);
                    $left = Nodes\Expressions\SubtractExpression::__instantiateUnchecked($left, $operator, $right);
                }
                else if ($this->types[$this->i] === 111)
                {
                    $operator = $this->tokens[$this->i++];
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
                if ($this->types[$this->i] === 236)
                {
                    $operator = $this->tokens[$this->i++];
                    $right = $this->expression(47 + 1);
                    $left = Nodes\Expressions\ShiftLeftExpression::__instantiateUnchecked($left, $operator, $right);
                }
                else if ($this->types[$this->i] === 239)
                {
                    $operator = $this->tokens[$this->i++];
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
            if ($this->types[$this->i] === 115)
            {
                $operator = $this->tokens[$this->i++];
                $right = $this->expression(37 + 1);
                $left = Nodes\Expressions\LessThanExpression::__instantiateUnchecked($left, $operator, $right);
            }
            else if ($this->types[$this->i] === 205)
            {
                $operator = $this->tokens[$this->i++];
                $right = $this->expression(37 + 1);
                $left = Nodes\Expressions\LessThanOrEqualsExpression::__instantiateUnchecked($left, $operator, $right);
            }
            else if ($this->types[$this->i] === 117)
            {
                $operator = $this->tokens[$this->i++];
                $right = $this->expression(37 + 1);
                $left = Nodes\Expressions\GreaterThanExpression::__instantiateUnchecked($left, $operator, $right);
            }
            else if ($this->types[$this->i] === 201)
            {
                $operator = $this->tokens[$this->i++];
                $right = $this->expression(37 + 1);
                $left = Nodes\Expressions\GreaterThanOrEqualsExpression::__instantiateUnchecked($left, $operator, $right);
            }
        }

        if ($minPrecedence <= 36)
        {
            if ($this->types[$this->i] === 202)
            {
                $operator = $this->tokens[$this->i++];
                $right = $this->expression(36 + 1);
                $left = Nodes\Expressions\IsIdenticalExpression::__instantiateUnchecked($left, $operator, $right);
            }
            else if ($this->types[$this->i] === 204)
            {
                $operator = $this->tokens[$this->i++];
                $right = $this->expression(36 + 1);
                $left = Nodes\Expressions\IsNotIdenticalExpression::__instantiateUnchecked($left, $operator, $right);
            }
            else if ($this->types[$this->i] === 200)
            {
                $operator = $this->tokens[$this->i++];
                $right = $this->expression(36 + 1);
                $left = Nodes\Expressions\IsEqualExpression::__instantiateUnchecked($left, $operator, $right);
            }
            else if ($this->types[$this->i] === 203)
            {
                $operator = $this->tokens[$this->i++];
                $right = $this->expression(36 + 1);
                $left = Nodes\Expressions\IsNotEqualExpression::__instantiateUnchecked($left, $operator, $right);
            }
            else if ($this->types[$this->i] === 238)
            {
                $operator = $this->tokens[$this->i++];
                $right = $this->expression(36 + 1);
                $left = Nodes\Expressions\SpaceshipExpression::__instantiateUnchecked($left, $operator, $right);
            }
        }

        if ($minPrecedence <= 35)
        {
            while ($this->types[$this->i] === 104)
            {
                $operator = $this->tokens[$this->i++];
                $right = $this->expression(35 + 1);
                $left = Nodes\Expressions\BitwiseAndExpression::__instantiateUnchecked($left, $operator, $right);
            }
        }

        if ($minPrecedence <= 34)
        {
            while ($this->types[$this->i] === 122)
            {
                $operator = $this->tokens[$this->i++];
                $right = $this->expression(34 + 1);
                $left = Nodes\Expressions\BitwiseXorExpression::__instantiateUnchecked($left, $operator, $right);
            }
        }

        if ($minPrecedence <= 33)
        {
            while ($this->types[$this->i] === 125)
            {
                $operator = $this->tokens[$this->i++];
                $right = $this->expression(33 + 1);
                $left = Nodes\Expressions\BitwiseOrExpression::__instantiateUnchecked($left, $operator, $right);
            }
        }

        if ($minPrecedence <= 32)
        {
            while ($this->types[$this->i] === 133)
            {
                $operator = $this->tokens[$this->i++];
                $right = $this->expression(32 + 1);
                $left = Nodes\Expressions\SymbolAndExpression::__instantiateUnchecked($left, $operator, $right);
            }
        }

        if ($minPrecedence <= 31)
        {
            while ($this->types[$this->i] === 134)
            {
                $operator = $this->tokens[$this->i++];
                $right = $this->expression(31 + 1);
                $left = Nodes\Expressions\SymbolOrExpression::__instantiateUnchecked($left, $operator, $right);
            }
        }

        if ($minPrecedence <= 26)
        {
            if ($this->types[$this->i] === 144)
            {
                $operator = $this->tokens[$this->i++];
                $right = $this->expression(26);
                $left = Nodes\Expressions\CoalesceExpression::__instantiateUnchecked($left, $operator, $right);
            }
        }

        if ($minPrecedence <= 25)
        {
            while ($this->types[$this->i] === 118)
            {
                $questionMark = $this->tokens[$this->i++];
                // note: no precedence is passed here, delimiters on both sides allow for e.g. 1 ? 2 and 3 : 4
                $then = $this->types[$this->i] !== 113 ? $this->expression() : null;
                $colon = $this->read(113);
                $else = $this->expression(25 + 1);
                $left = Nodes\Expressions\TernaryExpression::__instantiateUnchecked($left, $questionMark, $then, $colon, $else);
            }
        }

        if ($minPrecedence <= 13)
        {
            while ($this->types[$this->i] === 209)
            {
                $operator = $this->tokens[$this->i++];
                $right = $this->expression(13 + 1);
                $left = Nodes\Expressions\KeywordAndExpression::__instantiateUnchecked($left, $operator, $right);
            }
        }

        if ($minPrecedence <= 12)
        {
            while ($this->types[$this->i] === 211)
            {
                $operator = $this->tokens[$this->i++];
                $right = $this->expression(12 + 1);
                $left = Nodes\Expressions\KeywordXorExpression::__instantiateUnchecked($left, $operator, $right);
            }
        }

        if ($minPrecedence <= 11)
        {
            while ($this->types[$this->i] === 210)
            {
                $operator = $this->tokens[$this->i++];
                $right = $this->expression(11 + 1);
                $left = Nodes\Expressions\KeywordOrExpression::__instantiateUnchecked($left, $operator, $right);
            }
        }

        return $left;
    }

    private function simpleExpression(bool $newable = false): Nodes\Expression
    {
        if ($this->types[$this->i] === 255)
        {
            $node = Nodes\Expressions\NormalVariableExpression::__instantiateUnchecked($this->tokens[$this->i++]);
        }
        else if ($this->types[$this->i] === 102)
        {
            $node = $this->variableVariable();
        }
        else if ($this->types[$this->i] === 243 || $this->types[$this->i] === 219)
        {
            $node = Nodes\Expressions\NameExpression::__instantiateUnchecked($this->name());
        }
        else if ($this->types[$this->i] === 242 && $this->types[$this->i + 1] !== 184)
        {
            $node = Nodes\Expressions\NameExpression::__instantiateUnchecked($this->name());
        }
        else if ($newable)
        {
            throw ParseException::unexpected($this->tokens[$this->i]);
        }
        else if ($this->types[$this->i] === 148)
        {
            $node = Nodes\Expressions\SingleQuotedStringLiteral::__instantiateUnchecked($this->tokens[$this->i++]);
        }
        else if ($this->types[$this->i] === 101)
        {
            $leftDelimiter = $this->tokens[$this->i++];
            $parts = $this->stringParts(101);
            $rightDelimiter = $this->tokens[$this->i++];
            $node = Nodes\Expressions\DoubleQuotedStringLiteral::__instantiateUnchecked($leftDelimiter, $parts, $rightDelimiter);
        }
        else if ($this->types[$this->i] === 241)
        {
            $leftDelimiter = $this->tokens[$this->i++];
            if ($leftDelimiter->getSource()[3] === "'")
            {
                if ($this->types[$this->i] === 175)
                {
                    $content = null;
                    $rightDelimiter = $this->tokens[$this->i++];
                }
                else
                {
                    $content = $this->read(168);
                    $rightDelimiter = $this->read(175);
                }
                $node = Nodes\Expressions\NowdocStringLiteral::__instantiateUnchecked($leftDelimiter, $content, $rightDelimiter);
            }
            else
            {
                $parts = $this->stringParts(175);
                $rightDelimiter = $this->tokens[$this->i++];
                $node = Nodes\Expressions\HeredocStringLiteral::__instantiateUnchecked($leftDelimiter, $parts, $rightDelimiter);
            }
        }
        else if ($this->types[$this->i] === 208)
        {
            $node = Nodes\Expressions\IntegerLiteral::__instantiateUnchecked($this->tokens[$this->i++]);
        }
        else if ($this->types[$this->i] === 156)
        {
            $node = Nodes\Expressions\FloatLiteral::__instantiateUnchecked($this->tokens[$this->i++]);
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
            $node = Nodes\Expressions\NewExpression::__instantiateUnchecked($keyword, $class, $leftParenthesis, $arguments, $rightParenthesis);
        }
        else if ($this->types[$this->i] === 120)
        {
            $leftBracket = $this->tokens[$this->i++];
            $items = $this->arrayItems(121);
            $rightBracket = $this->read(121);
            $node = Nodes\Expressions\ShortArrayExpression::__instantiateUnchecked($leftBracket, $items, $rightBracket);
        }
        else if ($this->types[$this->i] === 130)
        {
            $keyword = $this->tokens[$this->i++];
            $leftParenthesis = $this->read(105);
            $items = $this->arrayItems(106);
            $rightParenthesis = $this->read(106);
            $node = Nodes\Expressions\LongArrayExpression::__instantiateUnchecked($keyword, $leftParenthesis, $items, $rightParenthesis);
        }
        else if ($this->types[$this->i] === 105)
        {
            $leftParenthesis = $this->tokens[$this->i++];
            $expression = $this->expression();
            $rightParenthesis = $this->read(106);
            $node = Nodes\Expressions\ParenthesizedExpression::__instantiateUnchecked($leftParenthesis, $expression, $rightParenthesis);
        }
        else if ($this->types[$this->i] === 199)
        {
            $keyword = $this->tokens[$this->i++];
            $leftParenthesis = $this->read(105);
            $items = [];
            do
            {
                $items[] = $this->expression();
            }
            while ($this->types[$this->i] === 109 && $items[] = $this->tokens[$this->i++]);
            $rightParenthesis = $this->read(106);
            $node = Nodes\Expressions\IssetExpression::__instantiateUnchecked($keyword, $leftParenthesis, $items, $rightParenthesis);
        }
        else if ($this->types[$this->i] === 167)
        {
            $keyword = $this->tokens[$this->i++];
            $leftParenthesis = $this->read(105);
            $expression = $this->expression();
            $rightParenthesis = $this->read(106);
            $node = Nodes\Expressions\EmptyExpression::__instantiateUnchecked($keyword, $leftParenthesis, $expression, $rightParenthesis);
        }
        else if ($this->types[$this->i] === 151)
        {
            $operator = $this->tokens[$this->i++];
            $expression = $this->simpleExpression();
            $node = Nodes\Expressions\PreDecrementExpression::__instantiateUnchecked($operator, $expression);
        }
        else if ($this->types[$this->i] === 191)
        {
            $operator = $this->tokens[$this->i++];
            $expression = $this->simpleExpression();
            $node = Nodes\Expressions\PreIncrementExpression::__instantiateUnchecked($operator, $expression);
        }
        else if (\in_array($this->types[$this->i], T::MAGIC_CONSTANTS, true))
        {
            $node = Nodes\Expressions\MagicConstant::__instantiateUnchecked($this->tokens[$this->i++]);
        }
        else if ($this->types[$this->i] === 142)
        {
            $keyword = $this->tokens[$this->i++];
            $expression = $this->expression(70);
            $node = Nodes\Expressions\CloneExpression::__instantiateUnchecked($keyword, $expression);
        }
        else if ($this->types[$this->i] === 100)
        {
            $operator = $this->tokens[$this->i++];
            $expression = $this->expression(50);
            $node = Nodes\Expressions\NotExpression::__instantiateUnchecked($operator, $expression);
        }
        else if ($this->types[$this->i] === 259)
        {
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
            if ($expression && $this->types[$this->i] === 160)
            {
                $key = Nodes\Helpers\Key::__instantiateUnchecked($expression, $this->tokens[$this->i++]);
                $expression = $this->expression(25);
            }
            $node = Nodes\Expressions\YieldExpression::__instantiateUnchecked($keyword, $key, $expression);
        }
        else if ($this->types[$this->i] === 260)
        {
            $keyword = $this->tokens[$this->i++];
            $expression = $this->expression(25);
            $node = Nodes\Expressions\YieldFromExpression::__instantiateUnchecked($keyword, $expression);
        }
        else if (in_array($this->types[$this->i], [192, 193, 233, 234], true))
        {
            $keyword = $this->tokens[$this->i++];
            $expression = $this->expression();
            $node = Nodes\Expressions\IncludeLikeExpression::__instantiateUnchecked($keyword, $expression);
        }
        else if ($this->types[$this->i] === 127)
        {
            $symbol = $this->tokens[$this->i++];
            $expression = $this->expression(62);
            $node = Nodes\Expressions\BitwiseNotExpression::__instantiateUnchecked($symbol, $expression);
        }
        else if ($this->types[$this->i] === 110)
        {
            $symbol = $this->tokens[$this->i++];
            $expression = $this->expression(62);
            $node = Nodes\Expressions\UnaryMinusExpression::__instantiateUnchecked($symbol, $expression);
        }
        else if ($this->types[$this->i] === 108)
        {
            $symbol = $this->tokens[$this->i++];
            $expression = $this->expression(62);
            $node = Nodes\Expressions\UnaryPlusExpression::__instantiateUnchecked($symbol, $expression);
        }
        else if (\in_array($this->types[$this->i], T::CASTS, true))
        {
            $cast = $this->tokens[$this->i++];
            $expression = $this->expression(61);
            $node = Nodes\Expressions\CastExpression::__instantiateUnchecked($cast, $expression);
        }
        else if ($this->types[$this->i] === 119)
        {
            $operator = $this->tokens[$this->i++];
            $expression = $this->expression(62);
            $node = Nodes\Expressions\SuppressErrorsExpression::__instantiateUnchecked($operator, $expression);
        }
        else if ($this->types[$this->i] === 207)
        {
            $keyword = $this->tokens[$this->i++];
            $leftParenthesis = $this->read(105);
            $items = $this->arrayItems(106);
            $rightParenthesis = $this->tokens[$this->i++];
            $node = Nodes\Expressions\ListExpression::__instantiateUnchecked($keyword, $leftParenthesis, $items, $rightParenthesis);
        }
        else if ($this->types[$this->i] === 177)
        {
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
        }
        else if ($this->types[$this->i] === 229)
        {
            $keyword = $this->tokens[$this->i++];
            $expression = $this->expression(25);
            $node = Nodes\Expressions\PrintExpression::__instantiateUnchecked($keyword, $expression);
        }
        else if ($this->types[$this->i] === 176)
        {
            $keyword = $this->tokens[$this->i++];
            $leftParenthesis = $this->read(105);
            $expression = $this->expression();
            $rightParenthesis = $this->read(106);
            $node = Nodes\Expressions\EvalExpression::__instantiateUnchecked($keyword, $leftParenthesis, $expression, $rightParenthesis);
        }
        else if ($this->types[$this->i] === 123)
        {
            $leftDelimiter = $this->tokens[$this->i++];
            $command = $this->read(168);
            $rightDelimiter = $this->read(123);
            $node = Nodes\Expressions\ExecExpression::__instantiateUnchecked($leftDelimiter, $command, $rightDelimiter);
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
                $useBindings = [];
                while (true)
                {
                    $useBindingByReference = null;
                    if ($this->types[$this->i] === 104)
                    {
                        $useBindingByReference = $this->tokens[$this->i++];
                    }
                    $useBindingVariable = $this->read(255);
                    $useBindings[] = Nodes\Expressions\AnonymousFunctionUseBinding::__instantiateUnchecked($useBindingByReference, $useBindingVariable);

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
                $node = Nodes\Expressions\FunctionCallExpression::__instantiateUnchecked($node, $leftParenthesis, $arguments, $rightParenthesis);
            }
            else if ($this->types[$this->i] === 120)
            {
                $leftBracket = $this->tokens[$this->i++];
                $index = $this->types[$this->i] === 121 ? null : $this->expression();
                $rightBracket = $this->read(121);
                $node = Nodes\Expressions\ArrayAccessExpression::__instantiateUnchecked($node, $leftBracket, $index, $rightBracket);
            }
            else if ($this->types[$this->i] === 162)
            {
                $operator = $this->tokens[$this->i++];

                switch ($this->types[$this->i])
                {
                    /** @noinspection PhpMissingBreakStatementInspection */
                    case 243:
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
                    case 255:
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
                        /** @var \Phi\Nodes\Helpers\MemberName $memberName */
                        $leftParenthesis = $this->read(105);
                        $arguments = $this->arguments();
                        $rightParenthesis = $this->read(106);
                        $node = Nodes\Expressions\StaticMethodCallExpression::__instantiateUnchecked($node, $operator, $memberName, $leftParenthesis, $arguments, $rightParenthesis);
                        break;

                    default:
                        fudgeSpecialNameToString:
                        if (\in_array($this->types[$this->i], T::SEMI_RESERVED, true))
                        {
                            $this->tokens[$this->i]->_fudgeType(243);
                            goto doubleColonString;
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
            $node = Nodes\Expressions\PostDecrementExpression::__instantiateUnchecked($node, $operator);
        }
        else if ($this->types[$this->i] === 191)
        {
            $operator = $this->tokens[$this->i++];
            $node = Nodes\Expressions\PostIncrementExpression::__instantiateUnchecked($node, $operator);
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
            return Nodes\Blocks\ImplicitBlock::__instantiateUnchecked($this->statement());
        }
    }

    private function regularBlock(int $level = self::STMT_LEVEL_OTHER): Nodes\Blocks\RegularBlock
    {
        $leftBrace = $this->tokens[$this->i++];
        $statements = [];
        while ($this->types[$this->i] !== 126)
        {
            $statements[] = $this->statement($level);
        }
        $rightBrace = $this->read(126);
        return Nodes\Blocks\RegularBlock::__instantiateUnchecked($leftBrace, $statements, $rightBrace);
    }

    /**
     * @param array<int> $implicitEndKeywords
     */
    private function altBlock(int $endKeywordType, array $implicitEndKeywords = []): Nodes\Blocks\AlternativeFormatBlock
    {
        $colon = $this->read(113);

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

        return Nodes\Blocks\AlternativeFormatBlock::__instantiateUnchecked($colon, $statements, $endKeyword, $semiColon);
    }

    /** @return array<\Phi\Nodes\Helpers\Parameter|Token|null> */
    private function parameters(): array
    {
        $nodes = [];
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

    private function parameter(): Nodes\Helpers\Parameter
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

        return Nodes\Helpers\Parameter::__instantiateUnchecked($type, $byReference, $ellipsis, $variable, $default);
    }

    /** @return array<\Phi\Nodes\Helpers\Argument|Token|null> */
    private function arguments(): array
    {
        $arguments = [];
        while ($this->types[$this->i] !== 106)
        {
            $unpack = $this->types[$this->i] === 164 ? $this->tokens[$this->i++] : null;
            $value = $this->expression();
            $arguments[] = Nodes\Helpers\Argument::__instantiateUnchecked($unpack, $value);

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

    private function returnType(): ?Nodes\Helpers\ReturnType
    {
        if ($this->types[$this->i] === 113)
        {
            $symbol = $this->tokens[$this->i++];
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
        $nullableSymbol = $this->types[$this->i] === 118 ? $this->tokens[$this->i++] : null;
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
        switch ($this->types[$this->i])
        {
            case 242:
            case 130:
            case 137:
                $parts[] = $this->tokens[$this->i++];
                break;
            /** @noinspection PhpMissingBreakStatementInspection */
            case 219:
                $parts[] = $this->tokens[$this->i++];
            default:
                $parts[] = $this->read(243);
                while ($this->types[$this->i] === 219)
                {
                    $parts[] = $this->tokens[$this->i++];
                    $parts[] = $this->read(243);
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
        if ($this->types[$this->i] === 116)
        {
            $symbol = $this->tokens[$this->i++];
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
        if ($this->types[$this->i] === 243)
        {
            return Nodes\Helpers\NormalMemberName::__instantiateUnchecked($this->tokens[$this->i++]);
        }
        else if ($this->types[$this->i] === 255)
        {
            $expression = Nodes\Expressions\NormalVariableExpression::__instantiateUnchecked($this->tokens[$this->i++]);
            return Nodes\Helpers\VariableMemberName::__instantiateUnchecked(null, $expression, null);
        }
        else if ($this->types[$this->i] === 102)
        {
            $expression = $this->variableVariable();
            return Nodes\Helpers\VariableMemberName::__instantiateUnchecked(null, $expression, null);
        }
        else if ($this->types[$this->i] === 124)
        {
            $leftBrace = $this->tokens[$this->i++];
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
        if ($this->types[$this->i] === 255)
        {
            $expression = Nodes\Expressions\NormalVariableExpression::__instantiateUnchecked($this->tokens[$this->i++]);
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

        return Nodes\Expressions\VariableVariableExpression::__instantiateUnchecked($dollar, $leftBrace, $expression, $rightBrace);
    }

    /** @return array<Node|null> */
    private function arrayItems(int $delimiter): array
    {
        $items = [];
        while ($this->types[$this->i] !== $delimiter)
        {
            $key = $byReference = $value = null;

            if ($this->types[$this->i] !== 109 && $this->types[$this->i] !== $delimiter)
            {
                if ($this->types[$this->i] === 104)
                {
                    $byReference = $this->tokens[$this->i++];
                }

                $value = $this->expression();

                if ($this->types[$this->i] === 160)
                {
                    if ($byReference)
                    {
                        throw ParseException::unexpected($this->tokens[$this->i]);
                    }

                    $key = Nodes\Helpers\Key::__instantiateUnchecked($value, $this->tokens[$this->i++]);

                    if ($this->types[$this->i] === 104)
                    {
                        $byReference = $this->tokens[$this->i++];
                    }

                    $value = $this->expression();
                }
            }

            $items[] = Nodes\Expressions\ArrayItem::__instantiateUnchecked($key, $byReference, $value);

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

    /** @return \Phi\Nodes\Expressions\StringInterpolation\InterpolatedStringPart[] */
    private function stringParts(int $delimiter): array
    {
        $parts = [];
        while ($this->types[$this->i] !== $delimiter)
        {
            if ($this->types[$this->i] === 168)
            {
                $parts[] = Nodes\Expressions\StringInterpolation\ConstantInterpolatedStringPart::__instantiateUnchecked($this->tokens[$this->i++]);
            }
            else if ($this->types[$this->i] === 255)
            {
                $var = Nodes\Expressions\StringInterpolation\NormalInterpolatedStringVariable::__instantiateUnchecked($this->tokens[$this->i++]);
                if ($this->types[$this->i] === 120)
                {
                    $leftBracket = $this->tokens[$this->i++];
                    $minus = $this->types[$this->i] === 110 ? $this->tokens[$this->i++] : null;
                    $value = $this->tokens[$this->i++];
                    // TODO screw this, emulate this ugliness away and validate the expression type
                    if ($value->getType() !== 220 && $value->getType() !== 243 && $value->getType() !== 255)
                    {
                        throw ParseException::unexpected($value);
                    }
                    $index = Nodes\Expressions\StringInterpolation\InterpolatedArrayAccessIndex::__instantiateUnchecked($minus, $value);
                    $rightBracket = $this->read(121);
                    $parts[] = Nodes\Expressions\StringInterpolation\ArrayAccessInterpolatedStringVariable::__instantiateUnchecked($var, $leftBracket, $index, $rightBracket);
                }
                else if ($this->types[$this->i] === 222)
                {
                    $operator = $this->tokens[$this->i++];
                    $name = $this->read(243);
                    $parts[] = Nodes\Expressions\StringInterpolation\PropertyAccessInterpolatedStringVariable::__instantiateUnchecked($var, $operator, $name);
                }
                else
                {
                    $parts[] = $var;
                }
            }
            else if ($this->types[$this->i] === 159)
            {
                $leftDelimiter = $this->tokens[$this->i++];
                if ($this->types[$this->i] === 245 && $this->types[$this->i + 1] === 126)
                {
                    $name = $this->tokens[$this->i++];
                    $rightDelimiter = $this->tokens[$this->i++];
                    $parts[] = Nodes\Expressions\StringInterpolation\BracedInterpolatedStringVariable::__instantiateUnchecked($leftDelimiter, $name, $rightDelimiter);
                }
                else
                {
                    $name = $this->expression();
                    $rightDelimiter = $this->read(126);
                    $parts[] = Nodes\Expressions\StringInterpolation\VariableInterpolatedStringVariable::__instantiateUnchecked($leftDelimiter, $name, $rightDelimiter);
                }
            }
            else if ($this->types[$this->i] === 124)
            {
                $leftBrace = $this->tokens[$this->i++];
                $expression = $this->simpleExpression();
                $rightBrace = $this->read(126);
                $parts[] = Nodes\Expressions\StringInterpolation\InterpolatedStringExpression::__instantiateUnchecked($leftBrace, $expression, $rightBrace);
            }
            else
            {
                throw ParseException::unexpected($this->tokens[$this->i]);
            }
        }
        return $parts;
    }
}
