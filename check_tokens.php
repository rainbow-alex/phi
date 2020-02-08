#!/usr/bin/env php
<?php

/** @noinspection PhpComposerExtensionStubsInspection */

use Phi\PhpVersion;

require __DIR__ . '/vendor/autoload.php';

if (count($argv) > 1)
{
    foreach (array_slice($argv, 1) as $arg)
    {
        $arg = str_replace('\n', "\n", $arg);
        echo "\e[45mphp -l\e[0m\n";
        system('echo ' . escapeshellarg('<?php ' . $arg . ';') . ' | php -l', $r);
        if ($r === 0)
        {
            $tokens = token_get_all('<?php ' . $arg . ';');
            foreach ($tokens as $t)
            {
                if (is_array($t))
                {
                    echo token_name($t[0]) . ": " . var_export($t[1], true) . "\n";
                }
                else
                {
                    echo var_export($t) . "\n";
                }
            }
        }

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
            $tokens2 = (new \Phi\Lexer(PhpVersion::PHP_7_2))->lex(null, '<?php ' . $arg);
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
