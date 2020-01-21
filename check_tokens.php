#!/usr/bin/env php
<?php

/** @noinspection PhpComposerExtensionStubsInspection */

require __DIR__ . '/vendor/autoload.php';

if (count($argv) > 1)
{
    foreach (array_slice($argv, 1) as $arg)
    {
        echo "\e[45mphp -l\e[0m\n";
        system('echo ' . escapeshellarg('<?php ' . $arg . ';') . ' | php -l');

        echo "\e[45mnikic/php-parser\e[0m\n";
        try
        {
            $lexer1 = new \PhpParser\Lexer();
            $lexer1->startLexing('<?php ' . $arg . ';');
            $tokens1 = $lexer1->getTokens();
            foreach ($tokens1 as $t)
            {
                if (is_array($t))
                {
                    $t[0] = token_name($t[0]);
                }
                echo json_encode($t), "\n";
            }
        }
        catch (\Throwable $t)
        {
            echo "$t\n";
        }

        echo "\e[45mphi\e[0m\n";
        try
        {
            $tokens2 = (new \Phi\Lexer())->lex(null, '<?php ' . $arg);
            foreach ($tokens2 as $t)
            {
                $t->debugDump();
            }
        }
        catch (\Throwable $t)
        {
            echo "$t\n";
        }
    }

    exit();
}
else
{
    while (true)
    {
        echo "\e[1m>>>\e[0m ";
        $line = readline('');

        if ($line === false)
        {
            echo "\n";
            exit(1);
        }

        readline_add_history($line);
        passthru('PHI_DEBUG_COLOR=1 ' . $argv[0] . ' ' . escapeshellarg($line));
    }
}
