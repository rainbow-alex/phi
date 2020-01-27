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
        PhpVersion::validate($phpVersion);
        $this->phpVersion = $phpVersion;
    }

    /**
     * @return Token[]
     */
    public function lex(?string $filename, string $source, bool $forcePhp = false): array
    {
        $tokens = [];
        $whitespace = "";
        $line = 1;
        $column = 1;
        $forceIdentifier = false;
        $typeMap = Token::getPhpTypeMap();

        if ($forcePhp)
        {
            $phpTokens = \token_get_all("<?php " . $source);
            \array_shift($phpTokens);
        }
        else
        {
            $phpTokens = \token_get_all($source);
        }

        foreach ($phpTokens as $phpToken)
        {
            if (\is_array($phpToken))
            {
                $phpType = $phpToken[0];
                $source = $phpToken[1];

                if ($phpType === \T_WHITESPACE || $phpType === \T_COMMENT || $phpType === \T_DOC_COMMENT)
                {
                    $whitespace .= $source;
                    goto updateColumnAndLine;
                }

                if (
                    $forceIdentifier
                    && $phpType !== \T_STRING
                    && \in_array($phpType, Token::IDENTIFIER_KEYWORDS, true)
                )
                {
                    $phpType = \T_STRING;
                    $forceIdentifier = false;
                }
                else
                {
                    $forceIdentifier = \in_array($phpType, [\T_OBJECT_OPERATOR, \T_DOUBLE_COLON, \T_CONST, \T_FUNCTION], true);
                }
            }
            else
            {
                $phpType = $source = $phpToken;
                $forceIdentifier = ($forceIdentifier && $phpType === "&");
            }

            $tokens[] = new Token($typeMap[$phpType], $source, $line, $column, $filename, $whitespace);
            $whitespace = "";

            updateColumnAndLine:
            // TODO optimize using $phpToken[2]?
            if (\is_array($phpToken))
            {
                $c = \strrpos($source, "\n");
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
            else
            {
                $column += 1;
            }
        }

        $tokens[] = new Token(Token::PH_T_EOF, "", $line, $column, $filename, $whitespace);

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
