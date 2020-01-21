<?php

namespace Phi;

class Lexer
{
    /**
     * @var int
     * @see PhpVersion
     */
    private $phpVersion;

    public function __construct(int $phpVersion)
    {
        $this->phpVersion = $phpVersion;
    }

    /**
     * @return Token[]
     */
    public function lex(?string $filename, string $source, bool $forcePhp = false): array
    {
        $tokens = [];
        $whitespace = '';
        /** @var Token|null $previous */
        $previous = null;
        $line = 1;
        $column = 1;
        $forceIdentifier = false;

        if ($forcePhp)
        {
            $phpTokens = \token_get_all('<?php ' . $source);
            array_shift($phpTokens);
        }
        else
        {
            $phpTokens = \token_get_all($source);
        }

        foreach ($phpTokens as $phpToken)
        {
            try
            {
                if (is_array($phpToken))
                {
                    $type = $phpToken[0];
                    $source = $phpToken[1];

                    if ($phpToken[0] === \T_WHITESPACE || $phpToken[0] === \T_COMMENT || $phpToken[0] === \T_DOC_COMMENT)
                    {
                        $whitespace .= $phpToken[1];
                        continue;
                    }
                }
                else
                {
                    $type = $source = $phpToken;
                }

                if (
                    $forceIdentifier
                    && $type !== \T_STRING
                    && \in_array($type, Token::IDENTIFIER_KEYWORDS, true)
                )
                {
                    $type = \T_STRING;
                }

                $token = new Token($type, $source, $line, $column, $filename, $previous);
                $token->setLeftWhitespace($whitespace);
                $tokens[] = $token;

                $forceIdentifier = (
                    in_array($type, [\T_OBJECT_OPERATOR, \T_DOUBLE_COLON, \T_CONST, \T_FUNCTION], true)
                    || $forceIdentifier && $type === '&'
                );

                $whitespace = '';
                $previous = $token;
            }
            finally
            {
                $c = strrpos($source, "\n");
                if ($c !== false)
                {
                    $column = \strlen($source) - $c;
                    $line += \substr_count($source, "\n");
                }
                else
                {
                    $column += \strlen($source);
                }
            }
        }

        $eof = new Token(Token::EOF, '', $line, $column);
        $eof->setLeftWhitespace($whitespace);
        $tokens[] = $eof;

        return $tokens;
    }

    /**
     * @return Token[]
     */
    public function lexFragment(string $source): array
    {
        return $this->lex(null, $source, true);
    }
}
