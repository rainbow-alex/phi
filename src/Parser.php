<?php

namespace Phi;

use Phi\Exception\ParseException;
use Phi\Exception\TodoException;
use Phi\Nodes;

class Parser
{
    private const EOF = 0;

    /**
     * @var int
     * @see PhpVersion
     */
    private $phpVersion;

    /** @var Token[] */
    private $tokens;
    /** @var int */
    private $i = 0;

    /** @var (int|string)[] */
    private $types;

    public function __construct(int $phpVersion)
    {
        // TODO validate
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
        $this->fixNodeProps($ast);
        return $ast;
    }

    /**
     * @throws ParseException
     */
    public function parseStatement(string $source): Nodes\Statement
    {
        $this->init((new Lexer($this->phpVersion))->lexFragment($source));
        $ast = $this->topStatement();
        $this->deinit();
        $this->fixNodeProps($ast);
        return $ast;
    }

    /**
     * @throws ParseException
     */
    public function parseExpression(string $source): Nodes\Expression
    {
        $this->init((new Lexer($this->phpVersion))->lexFragment($source));
        $ast = $this->expression();
        $this->deinit();
        $this->fixNodeProps($ast);
        return $ast;
    }

    /**
     * @throws ParseException
     */
    public function parseFragment(string $source): Node
    {
        $tokens = (new Lexer($this->phpVersion))->lexFragment($source);

        if (count($tokens) === 2) // a single token + eof TODO $forcePhp -> $phpFragment?
        {
            return $tokens[0];
        }

        $this->init($tokens);

        $node = $this->statement();
        $this->deinit();

        if ($node instanceof Nodes\ExpressionStatement && !$node->getSemiColon())
        {
            $node = $node->getExpression();
        }

        $this->fixNodeProps($node);
        return $node;
    }

    /**
     * @param Token[] $tokens
     */
    private function init(array $tokens): void
    {
        $this->tokens = $tokens;
        $this->i = 0;

        // push some extra eof tokens to eliminate the need for out of bounds checks when peeking
        $eof = end($tokens);
        assert($eof !== false);
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

    private function fixNodeProps(Node $rootNode): void
    {
        assert($rootNode instanceof Nodes\Base\AbstractNode);

        /** @var Nodes\Base\AbstractNode[] $nodes */
        $nodes = [$rootNode];
        $i = 0;
        while ($i < count($nodes))
        {
            $node = $nodes[$i++];

            $node->_phpVersion = $this->phpVersion;

            foreach ($node->childNodes() as $n)
            {
                assert($n instanceof Nodes\Base\AbstractNode);
                $n->_parent = $node;
                $nodes[] = $n;
            }
        }
    }

    private function parseRoot(): Nodes\RootNode
    {
        $statements = [];

        if ($this->types[$this->i] === \T_INLINE_HTML)
        {
            $content = $this->read();
        }
        else
        {
            $content = null;
        }

        if ($this->types[$this->i] === \T_OPEN_TAG)
        {
            $statements[] = Nodes\InlineHtmlStatement::__instantiateUnchecked(null, $content, $this->read());
        }

        while ($this->types[$this->i] !== self::EOF)
        {
            $statements[] = $this->topStatement();
        }

        $eof = $this->read(Token::EOF);

        return Nodes\RootNode::__instantiateUnchecked($statements, $eof);
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

    private function topStatement(): Nodes\Statement
    {
        if ($this->types[$this->i] === \T_NAMESPACE)
        {
            return $this->namespace_();
        }
        else if ($this->types[$this->i] === \T_USE)
        {
            return $this->use_();
        }
        else if (in_array($this->types[$this->i], [\T_ABSTRACT, \T_CLASS, \T_FINAL], true))
        {
            return $this->class_();
        }
        else if ($this->types[$this->i] === \T_INTERFACE)
        {
            return $this->interface_();
        }
        else if ($this->types[$this->i] === \T_TRAIT)
        {
            return $this->trait_();
        }
        else
        {
            return $this->statement();
        }
    }

    private function namespace_(): Nodes\NamespaceStatement
    {
        $keyword = $this->read(\T_NAMESPACE);
        $name = null;
        if ($this->types[$this->i] !== '{' && $this->types[$this->i] !== ';')
        {
            $name = $this->regularName();
        }
        if ($this->types[$this->i] === '{')
        {
            $block = $this->block(true);
            $semiColon = null;
        }
        else
        {
            $block = null;
            $semiColon = $this->statementDelimiter();
        }
        return Nodes\NamespaceStatement::__instantiateUnchecked($keyword, $name, $block, $semiColon);
    }

    private function use_(): Nodes\UseStatement
    {
        $keyword = $this->read(\T_USE);
        $type = $this->types[$this->i] === \T_FUNCTION || $this->types[$this->i] === \T_CONST ? $this->read() : null;
        // TODO take advantage of $uses being the same for both forms and simplify this...
        if ($this->types[$this->i] === '{')
        {
            $prefix = null;
        }
        else
        {
            $name = $this->regularName();
            if ($this->types[$this->i] !== \T_NS_SEPARATOR || $this->types[$this->i + 1] !== '{')
            {
                $uses = [null];
                $alias = null;
                if ($this->types[$this->i] === \T_AS)
                {
                    $aliasKeyword = $this->read();
                    $alias = Nodes\UseAlias::__instantiateUnchecked($aliasKeyword, $this->read(\T_STRING));
                }
                $uses[] = Nodes\UseName::__instantiateUnchecked($name, $alias);
                while ($this->types[$this->i] === ',')
                {
                    $uses[] = $this->read();
                    $name = $this->regularName();
                    $alias = null;
                    if ($this->types[$this->i] === \T_AS)
                    {
                        $aliasKeyword = $this->read();
                        $alias = Nodes\UseAlias::__instantiateUnchecked($aliasKeyword, $this->read(\T_STRING));
                    }
                    $uses[] = Nodes\UseName::__instantiateUnchecked($name, $alias);
                }
                $semiColon = $this->statementDelimiter();
                return Nodes\RegularUseStatement::__instantiateUnchecked(
                    $keyword,
                    $type,
                    $uses,
                    $semiColon
                );
            }
            $prefix = Nodes\GroupedUsePrefix::__instantiateUnchecked($name, $this->read());
        }
        $leftBrace = $this->read();
        $uses = [null];
        do
        {
            $useName = $this->regularName();
            $useAlias = null;
            if ($this->types[$this->i] === \T_AS)
            {
                $useAliasKeyword = $this->read();
                $useAlias = Nodes\UseAlias::__instantiateUnchecked($useAliasKeyword, $this->read(\T_STRING));
            }
            $uses[] = Nodes\UseName::__instantiateUnchecked($useName, $useAlias);
            if ($this->types[$this->i] !== ',')
            {
                break;
            }
            $uses[] = $this->read();
        }
        while ($this->types[$this->i] !== '}');
        $rightBrace = $this->read();
        $semiColon = $this->statementDelimiter();
        return Nodes\GroupedUseStatement::__instantiateUnchecked(
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
        while (in_array($this->types[$this->i], [\T_ABSTRACT, \T_FINAL], true))
        {
            $modifiers[] = $this->read();
        }
        $keyword = $this->read(\T_CLASS);
        $name = $this->read(\T_STRING);
        if ($this->types[$this->i] === \T_EXTENDS)
        {
            $extendsKeyword = $this->read();
            $extendsNames = [null];
            $extendsNames[] = $this->regularName();
            $extends = Nodes\Extends_::__instantiateUnchecked($extendsKeyword, $extendsNames);
        }
        else
        {
            $extends = null;
        }
        if ($this->types[$this->i] === \T_IMPLEMENTS)
        {
            $implementsKeyword = $this->read();
            $implementsNames = [null];
            $implementsNames[] = $this->regularName();
            while ($this->types[$this->i] === ',')
            {
                $implementsNames[] = $this->read();
                $implementsNames[] = $this->regularName();
            }
            $implements = Nodes\Implements_::__instantiateUnchecked($implementsKeyword, $implementsNames);
        }
        else
        {
            $implements = null;
        }
        $leftBrace = $this->read('{');
        $members = [];
        while ($this->types[$this->i] !== '}')
        {
            $members[] = $this->classLikeMember();
        }
        $rightBrace = $this->read('}');
        return Nodes\ClassStatement::__instantiateUnchecked(
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
        $keyword = $this->read(\T_INTERFACE);
        $name = $this->read(\T_STRING);
        if ($this->types[$this->i] === \T_EXTENDS)
        {
            $extendsKeyword = $this->read();
            $extendsNames = [null];
            $extendsNames[] = $this->regularName();
            while ($this->types[$this->i] === ',')
            {
                $extendsNames[] = $this->read();
                $extendsNames[] = $this->regularName();
            }
            $extends = Nodes\Extends_::__instantiateUnchecked($extendsKeyword, $extendsNames);
        }
        else
        {
            $extends = null;
        }
        $leftBrace = $this->read('{');
        $members = [];
        while ($this->types[$this->i] !== '}')
        {
            $members[] = $this->classLikeMember();
        }
        $rightBrace = $this->read('}');
        return Nodes\InterfaceStatement::__instantiateUnchecked(
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
        $keyword = $this->read(\T_TRAIT);
        $name = $this->read(\T_STRING);
        $leftBrace = $this->read('{');
        $members = [];
        while ($this->types[$this->i] !== '}')
        {
            $members[] = $this->classLikeMember();
        }
        $rightBrace = $this->read('}');
        return Nodes\TraitStatement::__instantiateUnchecked(
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
        while (\in_array($this->types[$this->i], [\T_ABSTRACT, \T_FINAL, \T_PUBLIC, \T_PROTECTED, \T_PRIVATE, \T_STATIC], true))
        {
            $modifiers[] = $this->read();
        }

        if ($this->types[$this->i] === \T_FUNCTION)
        {
            $keyword = $this->read();
            $byReference = $this->types[$this->i] === '&' ? $this->read() : null;
            $name = $this->read(\T_STRING);
            $leftParenthesis = $this->read('(');
            $parameters = $this->parameters();
            $rightParenthesis = $this->read(')');
            $returnType = $this->returnType();
            if ($this->types[$this->i] === ';')
            {
                $body = null;
                $semiColon = $this->read();
            }
            else
            {
                $body = $this->block();
                $semiColon = null;
            }
            return Nodes\Method::__instantiateUnchecked(
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
        else if ($this->types[$this->i] === \T_CONST)
        {
            $keyword = $this->read();
            $name = $this->read(\T_STRING);
            $equals = $this->read('=');
            $value = $this->expression();
            $semiColon = $this->read(';');
            return Nodes\ClassConstant::__instantiateUnchecked($modifiers, $keyword, $name, $equals, $value, $semiColon);
        }
        else if ($this->types[$this->i] === \T_USE)
        {
            $keyword = $this->read();
            $names = [null];
            do
            {
                $names[] = $this->name();
            }
            while ($this->types[$this->i] === ',' && $names[] = $this->read(','));
            $leftBrace = $rightBrace = $semiColon = null;
            $modifications = [];
            if ($this->types[$this->i] === '{')
            {
                $leftBrace = $this->read();
                throw new TodoException('trait use modifications');
                $rightBrace = $this->read('}');
            }
            else
            {
                $semiColon = $this->read(';');
            }
            return Nodes\TraitUse::__instantiateUnchecked(
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
            $variable = $this->read(\T_VARIABLE);
            $default = $this->default_();
            $semiColon = $this->read(';');
            return Nodes\Property::__instantiateUnchecked($modifiers, $variable, $default, $semiColon);
        }
    }

    private function statement(): Nodes\Statement
    {
        if ($this->types[$this->i] === \T_BREAK)
        {
            $keyword = $this->read();
            $levels = !$this->endOfStatement() ? $this->simpleExpression() : null;
            $semiColon = $this->statementDelimiter();
            return Nodes\BreakStatement::__instantiateUnchecked($keyword, $levels, $semiColon);
        }
        else if ($this->types[$this->i] === \T_CONTINUE)
        {
            $keyword = $this->read();
            $levels = !$this->endOfStatement() ? $this->simpleExpression() : null;
            $semiColon = $this->statementDelimiter();
            return Nodes\ContinueStatement::__instantiateUnchecked($keyword, $levels, $semiColon);
        }
        else if ($this->types[$this->i] === \T_DECLARE)
        {
            $keyword = $this->read();
            $leftParenthesis = $this->read('(');
            $directives = [null];
            do
            {
                $directiveKey = $this->read(\T_STRING);
                $directiveEquals = $this->read('=');
                $directiveValue = $this->expression();
                $directives[] = Nodes\DeclareDirective::__instantiateUnchecked($directiveKey, $directiveEquals, $directiveValue);
            }
            while ($this->types[$this->i] === ',' && $directives[] = $this->read());
            $rightParenthesis = $this->read(')');
            if ($this->types[$this->i] !== ';')
            {
                $block = $this->block(true);
                $semiColon = null;
            }
            else
            {
                $block = null;
                $semiColon = $this->read();
            }
            return Nodes\DeclareStatement::__instantiateUnchecked(
                $keyword,
                $leftParenthesis,
                $directives,
                $rightParenthesis,
                $block,
                $semiColon
            );
        }
        else if ($this->types[$this->i] === \T_DO)
        {
            $keyword1 = $this->read();
            $block = $this->statement();
            $keyword2 = $this->read(\T_WHILE);
            $leftParenthesis = $this->read('(');
            $test = $this->expression();
            $rightParenthesis = $this->read(')');
            $semiColon = $this->statementDelimiter();
            return Nodes\DoWhileStatement::__instantiateUnchecked(
                $keyword1,
                $block,
                $keyword2,
                $leftParenthesis,
                $test,
                $rightParenthesis,
                $semiColon
            );
        }
        else if ($this->types[$this->i] === \T_ECHO)
        {
            $keyword = $this->read();
            $expressions = [null];
            $expressions[] = $this->expression();
            while ($this->types[$this->i] === ',')
            {
                $expressions[] = $this->read();
                $expressions[] = $this->expression();
            }
            $semiColon = $this->statementDelimiter();
            return Nodes\EchoStatement::__instantiateUnchecked($keyword, $expressions, $semiColon);
        }
        else if ($this->types[$this->i] === \T_FOR)
        {
            $keyword = $this->read();
            $leftParenthesis = $this->read('(');
            $init = $this->types[$this->i] !== ';' ? $this->expression() : null;
            $separator1 = $this->read(';');
            $test = $this->types[$this->i] !== ';' ? $this->expression() : null;
            $separator2 = $this->read(';');
            $step = $this->types[$this->i] !== ')' ? $this->expression() : null;
            $rightParenthesis = $this->read(')');
            $block = $this->statement();
            return Nodes\ForStatement::__instantiateUnchecked(
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
        else if ($this->types[$this->i] === \T_FOREACH)
        {
            $keyword = $this->read();
            $leftParenthesis = $this->read('(');
            $iterable = $this->expression();
            $as = $this->read(\T_AS);
            $key = null;
            $byReference = null;
            if ($this->types[$this->i] === '&')
            {
                $byReference = $this->read();
                $value = $this->simpleExpression();

                if (!$value->isAliasRead())
                {
                    throw ParseException::unexpected($this->tokens[$this->i]);
                }
            }
            else
            {
                $value = $this->simpleExpression(self::EXPR_DESTRUCT);
                if ($this->types[$this->i] === \T_DOUBLE_ARROW)
                {
                    $key = Nodes\Key::__instantiateUnchecked($value, $this->read());
                    if ($this->types[$this->i] === '&')
                    {
                        $byReference = $this->read();
                    }
                    $value = $this->simpleExpression(self::EXPR_DESTRUCT);
                }
            }

            $rightParenthesis = $this->read(')');
            $block = $this->statement();
            return Nodes\ForeachStatement::__instantiateUnchecked(
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
            $this->types[$this->i] === \T_FUNCTION
            && (
                $this->types[$this->i + 1] === \T_STRING
                || $this->types[$this->i + 1] === '&' && $this->types[$this->i + 2] === \T_STRING
            )
        )
        {
            $keyword = $this->read();
            $byReference = $this->types[$this->i] === '&' ? $this->read() : null;
            $name = $this->read();
            $leftParenthesis = $this->read('(');
            $parameters = $this->parameters();
            $rightParenthesis = $this->read(')');
            $returnType = $this->returnType();
            $body = $this->block();
            return Nodes\FunctionStatement::__instantiateUnchecked(
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
        else if ($this->types[$this->i] === \T_IF)
        {
            $keyword = $this->read();
            $leftParenthesis = $this->read('(');
            $test = $this->expression();
            $rightParenthesis = $this->read(')');
            $block = $this->statement();
            $elseifs = [];
            while ($this->types[$this->i] === \T_ELSEIF)
            {
                $elseifKeyword = $this->read();
                $elseifLeftParenthesis = $this->read('(');
                $elseifTest = $this->expression();
                $elseifRightParenthesis = $this->read(')');
                $elseifBlock = $this->statement();
                $elseifs[] = Nodes\Elseif_::__instantiateUnchecked(
                    $elseifKeyword,
                    $elseifLeftParenthesis,
                    $elseifTest,
                    $elseifRightParenthesis,
                    $elseifBlock
                );
            }
            $else = null;
            if ($this->types[$this->i] === \T_ELSE)
            {
                $elseKeyword = $this->read();
                $elseBlock = $this->statement();
                $else = Nodes\Else_::__instantiateUnchecked($elseKeyword, $elseBlock);
            }
            return Nodes\IfStatement::__instantiateUnchecked(
                $keyword,
                $leftParenthesis,
                $test,
                $rightParenthesis,
                $block,
                $elseifs,
                $else
            );
        }
        else if ($this->types[$this->i] === \T_RETURN)
        {
            $keyword = $this->read();
            $expression = !$this->endOfStatement() ? $this->expression() : null;
            $semiColon = $this->statementDelimiter();
            return Nodes\ReturnStatement::__instantiateUnchecked($keyword, $expression, $semiColon);
        }
        else if ($this->types[$this->i] === \T_STATIC && $this->types[$this->i + 1] === \T_VARIABLE)
        {
            $keyword = $this->read();
            $variables = [null];
            do
            {
                $variable = $this->read(\T_VARIABLE);
                $default = $this->default_();
                $variables[] = Nodes\StaticVariable::__instantiateUnchecked($variable, $default);
            }
            while ($this->types[$this->i] === ',' && $variables[] = $this->read());
            $semiColon = $this->statementDelimiter();
            return Nodes\StaticVariableDeclaration::__instantiateUnchecked($keyword, $variables, $semiColon);
        }
        else if ($this->types[$this->i] === \T_SWITCH)
        {
            $keyword = $this->read();
            $leftParenthesis = $this->read('(');
            $value = $this->expression();
            $rightParenthesis = $this->read(')');
            $leftBrace = $this->read('{');
            $cases = [];
            while ($this->types[$this->i] !== \T_DEFAULT && $this->types[$this->i] !== '}')
            {
                $caseKeyword = $this->read(\T_CASE);
                $caseValue = $this->expression();
                $caseDelimiter = $this->types[$this->i] === ';' ? $this->read() : $this->read(':');
                $caseStatements = [];
                while ($this->types[$this->i] !== \T_CASE && $this->types[$this->i] !== \T_DEFAULT && $this->types[$this->i] !== '}')
                {
                    $caseStatements[] = $this->statement();
                }
                $cases[] = Nodes\SwitchCase::__instantiateUnchecked($caseKeyword, $caseValue, $caseDelimiter, $caseStatements);
            }
            $default = null;
            if ($this->types[$this->i] === \T_DEFAULT)
            {
                $defaultKeyword = $this->read();
                $defaultColon = $this->read(':');
                $defaultStatements = [];
                while ($this->types[$this->i] !== '}')
                {
                    $defaultStatements[] = $this->statement();
                }
                $default = Nodes\SwitchDefault::__instantiateUnchecked($defaultKeyword, $defaultColon, $defaultStatements);
            }
            $rightBrace = $this->read('}');
            return Nodes\SwitchStatement::__instantiateUnchecked(
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
        else if ($this->types[$this->i] === \T_THROW)
        {
            $keyword = $this->read();
            $expression = $this->expression();
            $semiColon = $this->statementDelimiter();
            return Nodes\ThrowStatement::__instantiateUnchecked($keyword, $expression, $semiColon);
        }
        else if ($this->types[$this->i] === \T_TRY)
        {
            $keyword = $this->read();
            $block = $this->block();
            $catches = [];
            while ($this->types[$this->i] === \T_CATCH)
            {
                $catchKeyword = $this->read();
                $catchLeftParenthesis = $this->read('(');
                $catchTypes = [null];
                $catchTypes[] = Nodes\NamedType::__instantiateUnchecked($this->name());
                while ($this->types[$this->i] === '|')
                {
                    $catchTypes[] = $this->read();
                    $catchTypes[] = Nodes\NamedType::__instantiateUnchecked($this->name());
                }
                $catchVariable = $this->read(\T_VARIABLE);
                $catchRightParenthesis = $this->read(')');
                $catchBlock = $this->block();
                $catches[] = Nodes\Catch_::__instantiateUnchecked(
                    $catchKeyword,
                    $catchLeftParenthesis,
                    $catchTypes,
                    $catchVariable,
                    $catchRightParenthesis,
                    $catchBlock
                );
            }
            $finally = null;
            if ($this->types[$this->i] === \T_FINALLY)
            {
                $finallyKeyword = $this->read();
                $finallyBlock = $this->block();
                $finally = Nodes\Finally_::__instantiateUnchecked($finallyKeyword, $finallyBlock);
            }
            return Nodes\TryStatement::__instantiateUnchecked($keyword, $block, $catches, $finally);
        }
        else if ($this->types[$this->i] === \T_WHILE)
        {
            $keyword = $this->read();
            $leftParenthesis = $this->read('(');
            $test = $this->expression();
            $rightParenthesis = $this->read(')');
            $block = $this->statement();
            return Nodes\WhileStatement::__instantiateUnchecked($keyword, $leftParenthesis, $test, $rightParenthesis, $block);
        }
        else if ($this->types[$this->i] === '{')
        {
            return $this->block();
        }
        else if ($this->types[$this->i] === ';')
        {
            return Nodes\NopStatement::__instantiateUnchecked($this->read());
        }
        else
        {
            $expression = $this->expression();

            if (!$expression->isRead())
            {
                throw ParseException::unexpected($this->tokens[$this->i]);
            }

            return Nodes\ExpressionStatement::__instantiateUnchecked($expression, $this->statementDelimiter());
        }
    }

    private function endOfStatement(): bool
    {
        return in_array($this->types[$this->i], [';', \T_CLOSE_TAG, self::EOF], true);
    }

    private function statementDelimiter(): ?Token
    {
        if ($this->types[$this->i] === \T_CLOSE_TAG || $this->types[$this->i] === self::EOF)
        {
            return null;
        }
        else
        {
            return $this->read(';');
        }
    }

    private const PRECEDENCE_ASSIGN_LVALUE = 60;
    private const PRECEDENCE_POW = 52;
    private const PRECEDENCE_CAST = 51;
    private const PRECEDENCE_INSTANCEOF = 50;
    private const PRECEDENCE_BOOLEAN_NOT = 40;
    private const PRECEDENCE_MUL = 39;
    private const PRECEDENCE_PLUS = 38;
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
            $left = $this->simpleExpression(self::EXPR_DESTRUCT);

            if ($this->types[$this->i] === '=')
            {
                if ($this->types[$this->i + 1] === '&')
                {
                    if (!$left->isAliasWrite())
                    {
                        throw ParseException::unexpected($this->tokens[$this->i]);
                    }

                    $operator1 = $this->read();
                    $operator2 = $this->read();
                    $right = $this->simpleExpression();

                    if (!$right->isAliasRead())
                    {
                        throw ParseException::unexpected($this->tokens[$this->i]);
                    }

                    $left = Nodes\AliasingExpression::__instantiateUnchecked($left, $operator1, $operator2, $right);
                }
                else
                {
                    if (!$left->isWrite())
                    {
                        throw ParseException::unexpected($this->tokens[$this->i]);
                    }

                    $operator = $this->read();
                    $value = $this->expression(self::PRECEDENCE_ASSIGN_RVALUE);
                    $left = Nodes\RegularAssignmentExpression::__instantiateUnchecked($left, $operator, $value);
                }
            }
            else if ($left instanceof Nodes\ListExpression)
            {
                throw ParseException::expected('=', $this->tokens[$this->i]);
            }
            else if (\in_array($this->types[$this->i], Token::COMBINED_ASSIGNMENT, true))
            {
                $operator = $this->read();
                $value = $this->expression(self::PRECEDENCE_ASSIGN_RVALUE);
                $left = Nodes\CombinedAssignmentExpression::__instantiateUnchecked($left, $operator, $value);
            }
        }
        else
        {
            $left = $this->simpleExpression();
        }

        if ($minPrecedence <= self::PRECEDENCE_POW)
        {
            while ($this->types[$this->i] === \T_POW)
            {
                $operator = $this->read();
                $right = $this->simpleExpression();
                $left = Nodes\PowerExpression::__instantiateUnchecked($left, $operator, $right);
            }
        }

        if ($minPrecedence <= self::PRECEDENCE_INSTANCEOF)
        {
            while ($this->types[$this->i] === \T_INSTANCEOF)
            {
                $operator = $this->read();
                $type = $this->simpleExpression();
                $left = Nodes\InstanceofExpression::__instantiateUnchecked($left, $operator, $type);
            }
        }

        if ($minPrecedence <= self::PRECEDENCE_MUL)
        {
            while (true)
            {
                if ($this->types[$this->i] === '*')
                {
                    $operator = $this->read();
                    $right = $this->expression(self::PRECEDENCE_MUL + 1);
                    $left = Nodes\MultiplyExpression::__instantiateUnchecked($left, $operator, $right);
                }
                else if ($this->types[$this->i] === '/')
                {
                    $operator = $this->read();
                    $right = $this->expression(self::PRECEDENCE_MUL + 1);
                    $left = Nodes\DivideExpression::__instantiateUnchecked($left, $operator, $right);
                }
                else if ($this->types[$this->i] === '%')
                {
                    $operator = $this->read();
                    $right = $this->expression(self::PRECEDENCE_MUL + 1);
                    $left = Nodes\ModuloExpression::__instantiateUnchecked($left, $operator, $right);
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
                if ($this->types[$this->i] === '+')
                {
                    $operator = $this->read();
                    $right = $this->expression(self::PRECEDENCE_PLUS + 1);
                    $left = Nodes\AddExpression::__instantiateUnchecked($left, $operator, $right);
                }
                else if ($this->types[$this->i] === '-')
                {
                    $operator = $this->read();
                    $right = $this->expression(self::PRECEDENCE_PLUS + 1);
                    $left = Nodes\SubtractExpression::__instantiateUnchecked($left, $operator, $right);
                }
                else if ($this->types[$this->i] === '.')
                {
                    $operator = $this->read();
                    $right = $this->expression(self::PRECEDENCE_PLUS + 1);
                    $left = Nodes\ConcatExpression::__instantiateUnchecked($left, $operator, $right);
                }
                else
                {
                    break;
                }
            }
        }

        if ($minPrecedence <= self::PRECEDENCE_COMPARISON2)
        {
            while (true)
            {
                if ($this->types[$this->i] === '<')
                {
                    $operator = $this->read();
                    $right = $this->expression(self::PRECEDENCE_COMPARISON2 + 1);
                    $left = Nodes\LessThanExpression::__instantiateUnchecked($left, $operator, $right);
                }
                else if ($this->types[$this->i] === \T_IS_SMALLER_OR_EQUAL)
                {
                    $operator = $this->read();
                    $right = $this->expression(self::PRECEDENCE_COMPARISON2 + 1);
                    $left = Nodes\LessThanOrEqualsExpression::__instantiateUnchecked($left, $operator, $right);
                }
                else if ($this->types[$this->i] === '>')
                {
                    $operator = $this->read();
                    $right = $this->expression(self::PRECEDENCE_COMPARISON2 + 1);
                    $left = Nodes\GreaterThanExpression::__instantiateUnchecked($left, $operator, $right);
                }
                else if ($this->types[$this->i] === \T_IS_GREATER_OR_EQUAL)
                {
                    $operator = $this->read();
                    $right = $this->expression(self::PRECEDENCE_COMPARISON2 + 1);
                    $left = Nodes\GreaterThanOrEqualsExpression::__instantiateUnchecked($left, $operator, $right);
                }
                else
                {
                    break;
                }
            }
        }

        if ($minPrecedence <= self::PRECEDENCE_COMPARISON1)
        {
            while (true)
            {
                if ($this->types[$this->i] === \T_IS_IDENTICAL)
                {
                    $operator = $this->read();
                    $right = $this->expression(self::PRECEDENCE_COMPARISON1 + 1);
                    $left = Nodes\IsIdenticalExpression::__instantiateUnchecked($left, $operator, $right);
                }
                else if ($this->types[$this->i] === \T_IS_NOT_IDENTICAL)
                {
                    $operator = $this->read();
                    $right = $this->expression(self::PRECEDENCE_COMPARISON1 + 1);
                    $left = Nodes\IsNotIdenticalExpression::__instantiateUnchecked($left, $operator, $right);
                }
                else if ($this->types[$this->i] === \T_IS_EQUAL)
                {
                    $operator = $this->read();
                    $right = $this->expression(self::PRECEDENCE_COMPARISON1 + 1);
                    $left = Nodes\IsEqualExpression::__instantiateUnchecked($left, $operator, $right);
                }
                else if ($this->types[$this->i] === \T_IS_NOT_EQUAL)
                {
                    $operator = $this->read();
                    $right = $this->expression(self::PRECEDENCE_COMPARISON1 + 1);
                    $left = Nodes\IsNotEqualExpression::__instantiateUnchecked($left, $operator, $right);
                }
                else if ($this->types[$this->i] === \T_SPACESHIP)
                {
                    $operator = $this->read();
                    $right = $this->expression(self::PRECEDENCE_COMPARISON1 + 1);
                    $left = Nodes\SpaceshipExpression::__instantiateUnchecked($left, $operator, $right);
                }
                else
                {
                    break;
                }
            }
        }

        if ($minPrecedence <= self::PRECEDENCE_BITWISE_AND)
        {
            while ($this->types[$this->i] === '&')
            {
                $operator = $this->read();
                $right = $this->expression(self::PRECEDENCE_BITWISE_AND + 1);
                $left = Nodes\BitwiseAndExpression::__instantiateUnchecked($left, $operator, $right);
            }
        }

        if ($minPrecedence <= self::PRECEDENCE_BITWISE_XOR)
        {
            while ($this->types[$this->i] === '^')
            {
                $operator = $this->read();
                $right = $this->expression(self::PRECEDENCE_BITWISE_XOR + 1);
                $left = Nodes\BitwiseXorExpression::__instantiateUnchecked($left, $operator, $right);
            }
        }

        if ($minPrecedence <= self::PRECEDENCE_BITWISE_OR)
        {
            while ($this->types[$this->i] === '|')
            {
                $operator = $this->read();
                $right = $this->expression(self::PRECEDENCE_BITWISE_OR + 1);
                $left = Nodes\BitwiseOrExpression::__instantiateUnchecked($left, $operator, $right);
            }
        }

        if ($minPrecedence <= self::PRECEDENCE_BOOLEAN_AND_SYMBOL)
        {
            while ($this->types[$this->i] === \T_BOOLEAN_AND)
            {
                $operator = $this->read();
                $right = $this->expression(self::PRECEDENCE_BOOLEAN_AND_SYMBOL + 1);
                $left = Nodes\SymbolBooleanAndExpression::__instantiateUnchecked($left, $operator, $right);
            }
        }

        if ($minPrecedence <= self::PRECEDENCE_BOOLEAN_OR_SYMBOL)
        {
            while ($this->types[$this->i] === \T_BOOLEAN_OR)
            {
                $operator = $this->read();
                $right = $this->expression(self::PRECEDENCE_BOOLEAN_OR_SYMBOL + 1);
                $left = Nodes\SymbolBooleanOrExpression::__instantiateUnchecked($left, $operator, $right);
            }
        }

        if ($minPrecedence <= self::PRECEDENCE_COALESCE)
        {
            while ($this->types[$this->i] === \T_COALESCE)
            {
                $operator = $this->read();
                $right = $this->expression(self::PRECEDENCE_COALESCE + 1);
                $left = Nodes\CoalesceExpression::__instantiateUnchecked($left, $operator, $right);
            }
        }

        if ($minPrecedence <= self::PRECEDENCE_TERNARY)
        {
            while ($this->types[$this->i] === '?')
            {
                $questionMark = $this->read();
                // note: no precedence is passed here, delimiters on both sides allow for e.g. 1 ? 2 and 3 : 4
                $then = $this->types[$this->i] !== ':' ? $this->expression() : null;
                $colon = $this->read(':');
                $else = $this->expression(self::PRECEDENCE_TERNARY + 1);
                $left = Nodes\TernaryExpression::__instantiateUnchecked($left, $questionMark, $then, $colon, $else);
            }
        }

        if ($minPrecedence <= self::PRECEDENCE_BOOLEAN_AND_KEYWORD)
        {
            while ($this->types[$this->i] === \T_LOGICAL_AND)
            {
                $operator = $this->read();
                $right = $this->expression(self::PRECEDENCE_BOOLEAN_AND_KEYWORD + 1);
                $left = Nodes\KeywordBooleanAndExpression::__instantiateUnchecked($left, $operator, $right);
            }
        }

        if ($minPrecedence <= self::PRECEDENCE_BOOLEAN_XOR_KEYWORD)
        {
            while ($this->types[$this->i] === \T_LOGICAL_XOR)
            {
                $operator = $this->read();
                $right = $this->expression(self::PRECEDENCE_BOOLEAN_XOR_KEYWORD + 1);
                $left = Nodes\KeywordBooleanXorExpression::__instantiateUnchecked($left, $operator, $right);
            }
        }

        if ($minPrecedence <= self::PRECEDENCE_BOOLEAN_OR_KEYWORD)
        {
            while ($this->types[$this->i] === \T_LOGICAL_OR)
            {
                $operator = $this->read();
                $right = $this->expression(self::PRECEDENCE_BOOLEAN_OR_KEYWORD + 1);
                $left = Nodes\KeywordBooleanOrExpression::__instantiateUnchecked($left, $operator, $right);
            }
        }

        return $left;
    }

    private const EXPR_NEWABLE = 0x01;
    private const EXPR_DESTRUCT = 0x02;

    private function simpleExpression(int $flags = 0): Nodes\Expression
    {
        $node = $this->atomExpression($flags);

        parseAccess:

        if ($node->isRead())
        {
            if ($this->types[$this->i] === \T_DOUBLE_COLON)
            {
                $operator = $this->read();
                if ($this->types[$this->i] === \T_VARIABLE)
                {
                    $name = $this->read(\T_VARIABLE);
                    $node = Nodes\StaticPropertyAccessExpression::__instantiateUnchecked($node, $operator, $name);
                }
                else if ($this->tokens[$this->i]->getSource() === 'class')
                {
                    $keyword = $this->read()->_withType(\T_CLASS);
                    return Nodes\ClassNameResolutionExpression::__instantiateUnchecked($node, $operator, $keyword);
                }
                else
                {
                    $name = $this->read(\T_STRING);
                    $node = Nodes\StaticMemberAccessExpression::__instantiateUnchecked($node, $operator, $name);
                }

                goto parseAccess;
            }
            else if ($this->types[$this->i] === \T_OBJECT_OPERATOR)
            {
                $operator = $this->read();
                $name = $this->read(\T_STRING);
                $node = Nodes\MemberAccessExpression::__instantiateUnchecked($node, $operator, $name);
                goto parseAccess;
            }
            else if (!($flags & self::EXPR_NEWABLE) && $this->types[$this->i] === '(')
            {
                $leftParenthesis = $this->read();
                $arguments = $this->arguments();
                $rightParenthesis = $this->read(')');
                $node = Nodes\CallExpression::__instantiateUnchecked($node, $leftParenthesis, $arguments, $rightParenthesis);
                goto parseAccess;
            }
        }

        if ($node->isRead() || !$node->isTemporary())
        {
            if ($this->types[$this->i] === '[')
            {
                $leftBracket = $this->read();
                $index = $this->types[$this->i] !== ']' ? $this->expression() : null;
                $rightBracket = $this->read(']');
                $node = Nodes\ArrayAccessExpression::__instantiateUnchecked($node, $leftBracket, $index, $rightBracket);
                goto parseAccess;
            }
        }

        if ($node->isWrite())
        {
            if ($this->types[$this->i] === \T_DEC)
            {
                $operator = $this->read();
                $node = Nodes\PostDecrementExpression::__instantiateUnchecked($node, $operator);
            }
            else if ($this->types[$this->i] === \T_INC)
            {
                $operator = $this->read();
                $node = Nodes\PostIncrementExpression::__instantiateUnchecked($node, $operator);
            }
        }

        return $node;
    }

    private function atomExpression(int $flags = 0): Nodes\Expression
    {
        if ($this->types[$this->i] === \T_STRING || $this->types[$this->i] === \T_NS_SEPARATOR)
        {
            return Nodes\NameExpression::__instantiateUnchecked($this->name());
        }
        else if ($this->types[$this->i] === \T_CONSTANT_ENCAPSED_STRING)
        {
            return Nodes\ConstantStringLiteral::__instantiateUnchecked($this->read());
        }
        else if ($this->types[$this->i] === '"' || $this->types[$this->i] === \T_START_HEREDOC)
        {
            $leftDelimiter = $this->read();
            $parts = [];
            while ($this->types[$this->i] !== '"' && $this->types[$this->i] !== \T_END_HEREDOC)
            {
                if ($this->types[$this->i] === \T_ENCAPSED_AND_WHITESPACE)
                {
                    $parts[] = Nodes\ConstantInterpolatedStringPart::__instantiateUnchecked($this->read());
                }
                else if ($this->types[$this->i] === \T_CURLY_OPEN)
                {
                    $leftBrace = $this->read();
                    $expression = $this->expression();
                    $rightBrace = $this->read('}');
                    $parts[] = Nodes\ComplexInterpolatedStringExpression::__instantiateUnchecked($leftBrace, $expression, $rightBrace);
                }
                else
                {
                    $parts[] = Nodes\SimpleInterpolatedStringExpression::__instantiateUnchecked($this->expression());
                }
            }
            $rightDelimiter = $this->read();
            return Nodes\InterpolatedString::__instantiateUnchecked($leftDelimiter, $parts, $rightDelimiter);
        }
        else if ($this->types[$this->i] === \T_START_HEREDOC)
        {
            $this->read()->debugDump();
            var_dump(__FILE__, __LINE__); die();
        }
        else if ($this->types[$this->i] === \T_VARIABLE)
        {
            return Nodes\RegularVariableExpression::__instantiateUnchecked($this->read());
        }
        else if ($this->types[$this->i] === \T_LNUMBER || $this->types[$this->i] === \T_DNUMBER)
        {
            return Nodes\NumberLiteral::__instantiateUnchecked($this->read());
        }
        else if ($this->types[$this->i] === \T_NEW)
        {
            $keyword = $this->read();
            if ($this->types[$this->i] === \T_VARIABLE || $this->types[$this->i] === '$')
            {
                $class = $this->simpleExpression(self::EXPR_NEWABLE);
            }
            else
            {
                $class = Nodes\NameExpression::__instantiateUnchecked($this->name());
            }
            $leftParenthesis = $rightParenthesis = null;
            $arguments = [];
            if ($this->types[$this->i] === '(')
            {
                $leftParenthesis = $this->read();
                $arguments = $this->arguments();
                $rightParenthesis = $this->read(')');
            }
            return Nodes\NewExpression::__instantiateUnchecked($keyword, $class, $leftParenthesis, $arguments, $rightParenthesis);
        }
        else if ($this->types[$this->i] === '[')
        {
            $leftBracket = $this->read();
            $items = $this->arrayItems(']');
            $rightBracket = $this->read(']');
            return Nodes\ShortArrayExpression::__instantiateUnchecked($leftBracket, $items, $rightBracket);
        }
        else if ($this->types[$this->i] === \T_ARRAY)
        {
            $keyword = $this->read();
            $leftParenthesis = $this->read('(');
            $items = $this->arrayItems(')');
            $rightParenthesis = $this->read(')');
            return Nodes\LongArrayExpression::__instantiateUnchecked($keyword, $leftParenthesis, $items, $rightParenthesis);
        }
        else if ($this->types[$this->i] === '(')
        {
            $leftParenthesis = $this->read();
            $expression = $this->expression();
            $rightParenthesis = $this->read(')');
            return Nodes\ParenthesizedExpression::__instantiateUnchecked($leftParenthesis, $expression, $rightParenthesis);
        }
        else if ($this->types[$this->i] === \T_ISSET)
        {
            $keyword = $this->read();
            $leftParenthesis = $this->read('(');
            $expressions = [null];
            do
            {
                $expressions[] = $this->expression();
            }
            while ($this->types[$this->i] === ',' && $expressions[] = $this->read());
            $rightParenthesis = $this->read(')');
            return Nodes\IssetExpression::__instantiateUnchecked($keyword, $leftParenthesis, $expressions, $rightParenthesis);
        }
        else if ($this->types[$this->i] === \T_UNSET)
        {
            $keyword = $this->read();
            $leftParenthesis = $this->read('(');
            $expressions = [null];
            do
            {
                $expressions[] = $this->simpleExpression();
            }
            while ($this->types[$this->i] === ',' && $expressions[] = $this->read());
            $rightParenthesis = $this->read(')');
            return Nodes\UnsetExpression::__instantiateUnchecked($keyword, $leftParenthesis, $expressions, $rightParenthesis);
        }
        else if ($this->types[$this->i] === \T_EMPTY)
        {
            $keyword = $this->read();
            $leftParenthesis = $this->read('(');
            $expression = $this->expression();
            $rightParenthesis = $this->read(')');
            return Nodes\EmptyExpression::__instantiateUnchecked($keyword, $leftParenthesis, $expression, $rightParenthesis);
        }
        else if ($this->types[$this->i] === \T_DEC)
        {
            $operator = $this->read();
            $expression = $this->simpleExpression();
            if (!$expression->isWrite()) // TODO
            {
                throw ParseException::unexpected($this->tokens[$this->i]);
            }
            return Nodes\PreDecrementExpression::__instantiateUnchecked($operator, $expression);
        }
        else if ($this->types[$this->i] === \T_INC)
        {
            $operator = $this->read();
            $expression = $this->simpleExpression();
            if (!$expression->isWrite()) // TODO
            {
                throw ParseException::unexpected($this->tokens[$this->i]);
            }
            return Nodes\PreIncrementExpression::__instantiateUnchecked($operator, $expression);
        }
        else if ($this->types[$this->i] === \T_STATIC && $this->types[$this->i + 1] !== \T_FUNCTION)
        {
            return Nodes\NameExpression::__instantiateUnchecked($this->name());
        }
        else if (
            $this->types[$this->i] === \T_FUNCTION ||
            ($this->types[$this->i] === \T_STATIC && $this->types[$this->i + 1] === \T_FUNCTION)
        )
        {
            $static = $this->types[$this->i] === \T_STATIC ? $this->read() : null;
            $keyword = $this->read();
            $leftParenthesis = $this->read('(');
            $parameters = $this->parameters();
            $rightParenthesis = $this->read(')');
            $use = null;
            if ($this->types[$this->i] === \T_USE)
            {
                $useKeyword = $this->read();
                $useLeftParenthesis = $this->read('(');
                $useBindings = [null];
                while (true)
                {
                    $useBindingByReference = null;
                    if ($this->types[$this->i] === '&')
                    {
                        $useBindingByReference = $this->read();
                    }
                    $useBindingVariable = $this->read(\T_VARIABLE);
                    $useBindings[] = Nodes\AnonymousFunctionUseBinding::__instantiateUnchecked($useBindingByReference, $useBindingVariable);

                    if ($this->types[$this->i] === ',')
                    {
                        $useBindings[] = $this->read();
                    }
                    else
                    {
                        break;
                    }
                }
                $useRightParenthesis = $this->read(')');
                $use = Nodes\AnonymousFunctionUse::__instantiateUnchecked($useKeyword, $useLeftParenthesis, $useBindings, $useRightParenthesis);
            }
            $returnType = $this->returnType();
            $body = $this->block();
            return Nodes\AnonymousFunctionExpression::__instantiateUnchecked(
                $static,
                $keyword,
                $leftParenthesis,
                $parameters,
                $rightParenthesis,
                $use,
                $returnType,
                $body
            );
        }
        else if (\in_array($this->types[$this->i], Token::MAGIC_CONSTANTS, true))
        {
            return Nodes\MagicConstant::__instantiateUnchecked($this->read());
        }
        else if ($this->types[$this->i] === \T_CLONE)
        {
            $keyword = $this->read();
            $expression = $this->simpleExpression(); // TODO test precedence
            return Nodes\CloneExpression::__instantiateUnchecked($keyword, $expression);
        }
        else if ($this->types[$this->i] === '!')
        {
            $operator = $this->read();
            $expression = $this->expression(self::PRECEDENCE_BOOLEAN_NOT);
            return Nodes\BooleanNotExpression::__instantiateUnchecked($operator, $expression);
        }
        else if ($this->types[$this->i] === \T_YIELD)
        {
            $keyword = $this->read();
            $key = null;
            $expression = $this->expression(self::PRECEDENCE_TERNARY);
            if ($this->types[$this->i] === \T_DOUBLE_ARROW)
            {
                $key = Nodes\Key::__instantiateUnchecked($expression, $this->read());
                $expression = $this->expression(self::PRECEDENCE_TERNARY);
            }
            return Nodes\YieldExpression::__instantiateUnchecked($keyword, $key, $expression);
        }
        else if ($this->types[$this->i] === \T_YIELD_FROM)
        {
            $keyword = $this->read();
            $expression = $this->expression(self::PRECEDENCE_TERNARY);
            return Nodes\YieldFromExpression::__instantiateUnchecked($keyword, $expression);
        }
        else if (in_array($this->types[$this->i], Token::CASTS, true))
        {
            $cast = $this->read();
            $expression = $this->expression(self::PRECEDENCE_CAST);
            return Nodes\CastExpression::__instantiateUnchecked($cast, $expression);
        }
        else if ($this->types[$this->i] === '@')
        {
            $operator = $this->read();
            $expression = $this->expression(self::PRECEDENCE_POW); // TODO test precedence
            return Nodes\SuppressErrorsExpression::__instantiateUnchecked($operator, $expression);
        }
        else if (in_array($this->types[$this->i], [\T_INCLUDE, \T_INCLUDE_ONCE, \T_REQUIRE, \T_REQUIRE_ONCE], true))
        {
            $keyword = $this->read();
            $expression = $this->expression();
            return Nodes\IncludeLikeExpression::__instantiateUnchecked($keyword, $expression);
        }
        else if ($this->types[$this->i] === '-')
        {
            $symbol = $this->read();
            $expression = $this->simpleExpression(); // TODO test precedence, maybe in combination with pow?
            return Nodes\NegationExpression::__instantiateUnchecked($symbol, $expression);
        }
        else if ($flags & self::EXPR_DESTRUCT && $this->types[$this->i] === \T_LIST)
        {
            $keyword = $this->read();
            $leftParenthesis = $this->read('(');
            $expressions = [null];
            do
            {
                $expressions[] = $this->simpleExpression($flags);
            }
            while ($this->types[$this->i] === ',' && $expressions[] = $this->read());
            $rightParenthesis = $this->read(')');
            return Nodes\ListExpression::__instantiateUnchecked($keyword, $leftParenthesis, $expressions, $rightParenthesis);
        }
        else if ($this->types[$this->i] === \T_EXIT)
        {
            $keyword = $this->read();
            $leftParenthesis = $this->read('(');
            $expression = $this->types[$this->i] !== ')' ? $this->expression() : null;
            $rightParenthesis = $this->read(')');
            return Nodes\ExitExpression::__instantiateUnchecked($keyword, $leftParenthesis, $expression, $rightParenthesis);
        }
        else if ($this->types[$this->i] === \T_PRINT)
        {
            $keyword = $this->read();
            $expression = $this->expression(self::PRECEDENCE_BOOLEAN_NOT); // TODO determine precedence
            return Nodes\PrintExpression::__instantiateUnchecked($keyword, $expression);
        }
        else if ($this->types[$this->i] === \T_EVAL)
        {
            $keyword = $this->read();
            $leftParenthesis = $this->read('(');
            $expression = $this->expression();
            $rightParenthesis = $this->read(')');
            return Nodes\EvalExpression::__instantiateUnchecked($keyword, $leftParenthesis, $expression, $rightParenthesis);
        }
        else
        {
            throw ParseException::expected('expression', $this->tokens[$this->i]);
        }
    }

    private function block(bool $top = false): Nodes\Block
    {
        $leftBrace = $this->read('{');
        $statements = [];
        while ($this->types[$this->i] !== '}')
        {
            $statements[] = $top ? $this->topStatement() : $this->statement();
        }
        $rightBrace = $this->read('}');
        return Nodes\Block::__instantiateUnchecked($leftBrace, $statements, $rightBrace);
    }

    /** @return array<Node|null>[] */
    private function parameters(): array
    {
        $nodes = [null];
        if ($this->types[$this->i] !== ')')
        {
            $nodes[] = $this->parameter();
        }
        while ($this->types[$this->i] === ',')
        {
            $nodes[] = $this->read();
            $nodes[] = $this->parameter();
        }
        return $nodes;
    }

    private function parameter(): Nodes\Parameter
    {
        $type = $byReference = $ellipsis = null;
        if ($this->types[$this->i] !== '&' && $this->types[$this->i] !== \T_VARIABLE && $this->types[$this->i] !== \T_ELLIPSIS)
        {
            $type = $this->type();
        }
        if ($this->types[$this->i] === '&')
        {
            $byReference = $this->read();
        }
        if ($this->types[$this->i] === \T_ELLIPSIS)
        {
            $ellipsis = $this->read();
        }
        $variable = $this->read(\T_VARIABLE);
        $default = $this->default_();
        return Nodes\Parameter::__instantiateUnchecked($type, $byReference, $ellipsis, $variable, $default);
    }

    /** @return array<Node|null>[] */
    private function arguments(): array
    {
        $arguments = [null];
        while ($this->types[$this->i] !== ')')
        {
            $unpack = $this->types[$this->i] === \T_ELLIPSIS ? $this->read() : null;
            $arguments[] = Nodes\Argument::__instantiateUnchecked($unpack, $this->expression());
            if ($this->types[$this->i] === ',')
            {
                $arguments[] = $this->read();
            }
            else
            {
                break;
            }
        }
        return $arguments;
    }

    private function returnType(): ?Nodes\ReturnType
    {
        if ($this->types[$this->i] === ':')
        {
            $symbol = $this->read();
            $type = $this->type();
            return Nodes\ReturnType::__instantiateUnchecked($symbol, $type);
        }
        else
        {
            return null;
        }
    }

    private function type(): Nodes\Type
    {
        if ($this->types[$this->i] === '?')
        {
            $symbol = $this->read();
            $type = $this->simpleType();
            return Nodes\NullableType::__instantiateUnchecked($symbol, $type);
        }
        else
        {
            return $this->simpleType();
        }
    }

    private function simpleType(): Nodes\Type
    {
        if (in_array($this->types[$this->i], [\T_ARRAY, \T_CALLABLE], true))
        {
            $token = $this->read();
            return Nodes\SpecialType::__instantiateUnchecked($token);
        }
        else
        {
            $name = $this->name();
            return Nodes\NamedType::__instantiateUnchecked($name);
        }
    }

    private function name(): Nodes\Name
    {
        if ($this->types[$this->i] === \T_STATIC)
        {
            return Nodes\SpecialName::__instantiateUnchecked($this->read());
        }
        else if (
            $this->types[$this->i] === \T_STRING
            && $this->tokens[$this->i + 1] !== \T_NS_SEPARATOR
            && in_array($this->tokens[$this->i]->getSource(), Token::SPECIAL_CLASSES, true)
        )
        {
            return Nodes\SpecialName::__instantiateUnchecked($this->read());
        }
        else
        {
            return $this->regularName();
        }
    }

    private function regularName(): Nodes\RegularName
    {
        $parts = [];
        $parts[] = $this->types[$this->i] === \T_NS_SEPARATOR ? $this->read() : null;
        $parts[] = $this->read(\T_STRING);
        while ($this->types[$this->i] === \T_NS_SEPARATOR && $this->types[$this->i + 1] === \T_STRING)
        {
            $parts[] = $this->read();
            $parts[] = $this->read();
        }
        return Nodes\RegularName::__instantiateUnchecked($parts);
    }

    private function default_(): ?Nodes\Default_
    {
        if ($this->types[$this->i] === '=')
        {
            $symbol = $this->read();
            $value = $this->expression();
            return Nodes\Default_::__instantiateUnchecked($symbol, $value);
        }
        else
        {
            return null;
        }
    }

    /** @return array<Node|null>[] */
    private function arrayItems(string $delimiter): array
    {
        $items = [null];
        while ($this->types[$this->i] !== $delimiter)
        {
            $key = $byReference = null;
            if ($this->types[$this->i] === '&')
            {
                $byReference = $this->read();
                $value = $this->expression();
            }
            else
            {
                $value = $this->expression();
                if ($this->types[$this->i] === \T_DOUBLE_ARROW)
                {
                    $key = Nodes\Key::__instantiateUnchecked($value, $this->read());
                    if ($this->types[$this->i] === '&')
                    {
                        $byReference = $this->read();
                    }
                    $value = $this->expression();
                }
            }
            $items[] = Nodes\ArrayItem::__instantiateUnchecked($key, $byReference, $value);
            if ($this->types[$this->i] === ',')
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
