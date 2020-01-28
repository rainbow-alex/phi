#!/usr/bin/env php
<?php

use Phi\Lexer;
use Phi\Parser;
use Phi\PhpVersion;
use Phi\Tests\Testing\TestRepr;

require __DIR__ . "/../../vendor/autoload.php";

// TODO extract resources

const RULES = [
    'ROOT' => [
        '$v #BINOP_ALL# #EXPR#',
        '#EXPR# #BINOP_ALL# $v',

        'new #EXPR#',
        'new #EXPR#()',
        'new $v #BINOP# #EXPR#',
        'clone #EXPR#',
        'clone $v #BINOP# #EXPR#',

        'include #EXPR#',
        'include_once #EXPR#',
        'require #EXPR#',
        'require_once #EXPR#',

        '++#EXPR#',
        '--#EXPR#',
        '#EXPR#++',
        '#EXPR#--',

        '@#EXPR#',

        '#CAST# #EXPR#',

        '#EXPR#()',
        'foo($v, $v, $v)',
        'foo($v,)',
        'foo(#EXPR#)',
        'foo(...#EXPR#)',
        'foo(...$v, $v)',

        '#EXPR#[$v]',

//        'isset(#EXPR#)',
//        'isset($v, $v)',

        'function () {}',
        'static function () {}',
        'function & () {}',
        'function ($v, $v) {}',
        'function ($v,) {}',
        'function (): #EXPR# {}',
        'function () use ($v) {}',
        'function () use ($v, &$v) {}',
        'function () use ($v,) {}',
        'function () { #STMT# }',

        '$#EXPR#',
        '${#EXPR#}',

        'foo(#RWA_EXPR#)',
        '#RWA_EXPR# = $v',
        '$v = #RWA_EXPR#',
        '#RWA_EXPR# =& $v',
        '$v =& #RWA_EXPR#',
        'foreach ($v as #RWA_EXPR#) {}',
        'foreach ($v as &#RWA_EXPR#) {}',
        'foreach ($v as #RWA_EXPR# => $v) {}',
        'foreach ($v as $v => &#RWA_EXPR#) {}',

        'function foo() {}',
        'function &foo() {}',
        'function foo($v, $v) {}',
        'function foo($v = 0, &$v, ...$v) {}',
        'function foo(&...$v) {}',
        'function foo(...$v = 0) {}',
        'function foo($v = #EXPR#) {}',
        'function foo($v,) {}',
        'function foo(): #EXPR# {}',
        'function foo() { #STMT# }',

        'while (0) break;',
        'while (0) break -1;',
        'while (0) break 0;',
        'while (0) break 1;',
        'while (0) break #EXPR#;',
        'break;',
        'while (0) { function foo() { break; } }',

        'while (0) continue;',
        'while (0) continue -1;',
        'while (0) continue 0;',
        'while (0) continue 1;',
        'while (0) continue #EXPR#;',
        'continue;',
        'while (0) { function foo() { continue; } }',

        'if ($v) #STMT#',
        'if ($v) #STMT# elseif ($v) #STMT#',
        'if ($v) #STMT# elseif ($v) #STMT# elseif ($v) #STMT#',
        'if ($v) #STMT# else #STMT#',
        'if ($v) #STMT# elseif ($v) #STMT# else #STMT#',
        'if ($v) #STMT# else if ($v) #STMT# else #STMT#',
        'if ($v): #STMT# endif',
        'if ($v): #STMT# elseif: ($v): #STMT# endif',
        'if ($v): #STMT# elseif: ($v) #STMT# elseif: ($v) #STMT# endif',
        'if ($v): #STMT# else: #STMT# endif',
        'if ($v): #STMT# elseif ($v): #STMT# else: #STMT# endif',
        'if ($v): #STMT# else if ($v): #STMT# else: #STMT# endif',

        'for (;;) #STMT#',
        'for (#EXPR#; $v; $v) {}',
        'for ($v; #EXPR#; $v) {}',
        'for ($v; $v; #EXPR#) {}',
        'for ($v, $v, $v) {}',
        'for (;;): #STMT# endfor',

        'foreach ($v as $v) #STMT#',
        'foreach (#EXPR# as $v) {}',
        'foreach ($v as #EXPR#) {}',
        'foreach ($v as &#EXPR#) {}',
        'foreach ($v as $v => #EXPR#) {}',
        'foreach ($v as $v => &#EXPR#) {}',
        'foreach ($v as #EXPR# => $v) {}',
        'foreach ($v as $v): #STMT# endforeach',

        'while ($v) #STMT#',
        'while ($v): #STMT# endwhile',

        'echo #EXPR#;',
        'echo $v, $v;',

        'return #EXPR#;',
        'return;',

        'throw #EXPR#;',

        '{ #STMT# }',
    ],

    'STMT' => [
        '{}',
        'echo $v;',
        'echo $v; echo $v;',
        '?>foo<?php',
    ],

    'EXPR' => [
        '$v #BINOP# $v',

        '$v[]',
        '$v[$v]',

        'foo()',

        '$v->foo',
        '$v->$v',
        '$v->{$v}',
        '$v::foo',
        '$v::$v',
        '$v::{$v}',
        '$v->foo()',
        '$v->$v()',
        '$v->{$v}()',
        '$v::foo()',
        '$v::$v()',
        '$v::{$v}()',

        'new foo',
        'new foo()',
        'new $v',
        'new $v()',
        'clone $v',
        'print $v',
        'include $v',

        '$v++',
        '$v--',
        '++$v',
        '--$v',

        '-$v',
        '+$v',
        '!$v',
        '~$v',

        '@$v',
        '(int) $v',
        '(string) $v',
        '(array) $v',
        '(object) $v',
        '(unset) $v',

        '($v)',

        'die()',
        'exit()',

        '[]',
        '[$v, $v]',
        '[1, 2]',
        'list($v)',

        'function () {}',
        'static function () {}',

        'foo',
        '\\foo',
        'foo\\foo',
        '\\foo\\foo',

        '$v',
        '$$v',
        '${$v}',

        '"foo"',
        "123",
        "4.5",
        ".6",
    ],
    'BINOP_ALL' => [
        '=',
        '=&',

        'and',
        'or',
        'xor',

        '&&',
        '||',

        '===',
        '!==',
        '==',
        '!=',
        '<',
        '<=',
        '>',
        '>=',
        '<=>',
        '??',
        '?:',

        'instanceof',

        '+',
        '-',
        '.',
        '*',
        '/',
        '%',

        '&',
        '|',
        '^',
        '<<',
        '>>',
        '**',

        '+=',
        '-=',
        '.=',
        '*=',
        '/=',
        '%=',
        '**=',

        '&=',
        '|=',
        '^=',
        '<<=',
        '>>=',
    ],
    'BINOP' => [
        '=',
        '=&',

        'and',
        'or',
        'xor',

        '&&',
        '||',

        '===',
//        '!==',
//        '==',
//        '!=',
//        '<',
//        '<=',
//        '>',
//        '>=',
        '<=>',
        '??',
        '?:',

        'instanceof',

        '+',
//        '-',
//        '.',
        '*',
//        '/',
//        '%',

        '&',
        '|',
        '^',
        '<<',
        '>>',
        '**',

        '+=',
//        '-=',
//        '.=',
//        '*=',
//        '/=',
//        '%=',
//        '**=',

//        '&=',
//        '|=',
//        '^=',
//        '<<=',
//        '>>=',
    ],
    'CAST' => [
        '(array)',
        '(bool)',
        '(boolean)',
        '(double)',
        '(float)',
        '(int)',
        '(integer)',
        '(object)',
        '(real)',
        '(string)',
        '(unset)',
    ],

    'RWA_EXPR' => [
        '#RWA_ATOM#',
        '#RWA_ATOM# = #RWA_ATOM#',
        '#RWA_ATOM# =& #RWA_ATOM#',
        '[#RWA_ATOM#]',
        '[&#RWA_ATOM#]',
        '[#RWA_ATOM#, #RWA_ATOM#]',
        '[#RWA_ATOM# => #RWA_ATOM#]',
//        'list(#RWA_ATOM#)',
//        'list(&#RWA_ATOM#)',
//        'list(#RWA_ATOM#, #RWA_ATOM#)',
//        'list(#RWA_ATOM# => #RWA_ATOM#)',
    ],
    'RWA_ATOM' => [
        'foo()',
        '[]',
        '[$v]',
        '[&$v]',
//        'list($v)',
        '$v[]',
        '$v',
        '0',
        '',
    ]
];

/**
 * @return Generator<string>
 */
function generate(string $expr, array &$rec = [])
{
    if (preg_match('{#([A-Z0-9_]+)#}', $expr, $m, PREG_OFFSET_CAPTURE))
    {
        [[$placeholder, $offset], [$ruleName, /**/]] = $m;

        foreach (RULES[$ruleName] as $sub)
        {
            $rec[$sub] = $rec[$sub] ?? 0;
            if ($rec[$sub] < 1)
            {
                $rec[$sub]++;

                $subTemplate = substr($expr, 0, $offset) . $sub . substr($expr, $offset + strlen($placeholder));
                yield from generate($subTemplate, $rec);

                $rec[$sub]--;
            }
        }
    }
    else
    {
        yield $expr;
    }
}

$syntaxCheckCache = json_decode(file_get_contents(__DIR__ . "/.php-l-cache"), true) ?: [];
declare(ticks=1);
pcntl_signal(SIGINT, function () { exit(1); });
register_shutdown_function(function () use (&$syntaxCheckCache)
{
    file_put_contents(__DIR__ . "/.php-l-cache", json_encode($syntaxCheckCache, JSON_PRETTY_PRINT));
});

$lexer = new Lexer(PhpVersion::PHP_7_2);
$parser = new Parser(PhpVersion::PHP_7_2);

$cases = [];

foreach (RULES["ROOT"] as $root)
{
    echo $root . " ... ";

    $group = [];
    foreach (generate($root) as $src)
    {
        $v = "a";
        $src = preg_replace_callback('{\\$v}', function () use (&$v) { return "$" . $v++; }, $src);
        $w = 0;
        $src = preg_replace_callback('{foo}', function () use (&$w) { return ["foo", "bar", "baz", "qux", "quux", "corge"][$w++]; }, $src);

        $group[] = $src;
    }
    $group = array_unique($group);
    sort($group);

    $cases[$root] = [];

    foreach ($group as $i => $src)
    {
        $case = ["source" => $src];

        if (strpos($syntaxCheckCache[$src] ?? "", "Unexpected character in input") !== false)
        {
            unset($syntaxCheckCache[$src]);
        }

        // figure out if php will parse this
        $syntaxCheck = $syntaxCheckCache[$src] ?? $syntaxCheckCache[$src] = (function () use ($src)
        {
            $h = proc_open("php -l", [["pipe", "r"], ["pipe", "w"], ["pipe", "w"]], $pipes);
            fwrite($pipes[0], "<?php " . $src . " ?>\n");
            fclose($pipes[0]);
            $out = trim(stream_get_contents($pipes[2]));
            $r = proc_close($h);

            $out = str_replace("Errors parsing Standard input code\n", "", $out);

            return $r === 0 ? true : $out;
        })();

        if ($syntaxCheck !== true)
        {
            $syntaxCheck = str_replace("PHP Fatal error:  ", "", $syntaxCheck);
            $syntaxCheck = str_replace("PHP Parse error:  ", "", $syntaxCheck);
            $syntaxCheck = str_replace(" in Standard input code on line 1", "", $syntaxCheck);
            $case["php"] = ["valid" => false, "error" => $syntaxCheck];
        }
        else
        {
            $case["php"] = ["valid" => true];
        }

        // lex & parse the source using phi
        try
        {
            $ast = $parser->parse(null, "<?php " . $src . " ?>");
            $case["phi"] = ["valid" => true, "repr" => TestRepr::node($ast)];
        }
        catch (Throwable $t)
        {
            $ast = null;
            $case["phi"] = ["valid" => false, "error" => $t->getMessage()];
        }

        $cases[$root][] = $case;
    }

    echo count($group) . "\n";
}

file_put_contents(__DIR__ . "/../../tests/Parser/data/data.json", json_encode($cases, JSON_PRETTY_PRINT));
