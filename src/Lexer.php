<?php

declare(strict_types=1);

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
        if ($forcePhp)
        {
            $phpTokens = @\token_get_all("<?php " . $source);
            \array_shift($phpTokens);
        }
        else
        {
            $phpTokens = @\token_get_all($source);
        }

        $tokens = [];
        $whitespace = "";
        $line = 1;
        $typeMap = TokenType::getPhpTypeMap();
        foreach ($phpTokens as $phpToken)
        {
            if (\is_array($phpToken))
            {
                [$phpType, $source, $line] = $phpToken;

                if ($phpType === \T_WHITESPACE || $phpType === \T_COMMENT || $phpType === \T_DOC_COMMENT)
                {
                    $whitespace .= $source;
                    continue;
                }
                else if ($phpType === \T_CONSTANT_ENCAPSED_STRING  && $source[0] === '"')
                {
                    $tokens[] = new Token(TokenType::S_DOUBLE_QUOTE, '"', $filename, $line, $whitespace);
                    $whitespace = "";
                    $tokens[] = new Token(TokenType::T_ENCAPSED_AND_WHITESPACE, \substr($source, 1, \strlen($source) - 2), $filename, $line);
                    $line += \substr_count($source, "\n");
                    $tokens[] = new Token(TokenType::S_DOUBLE_QUOTE, '"', $filename, $line);
                    continue;
                }
                else if ($phpType === \T_CURLY_OPEN)
                {
                    $phpType = '{';
                }
            }
            else
            {
                $phpType = $source = $phpToken;
            }

            $tokens[] = new Token($typeMap[$phpType], $source, $filename, $line, $whitespace);
            $whitespace = "";
        }

        $tokens[] = new Token(TokenType::T_EOF, "", $filename, $line, $whitespace);

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
