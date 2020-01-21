<?php

$bin = $argv[1] ?? 'php7.2';

$templates = [
    'read 1' => 'echo EXPR;',
    'read 2' => '$v = EXPR;',
    'read 3' => '(EXPR);',
    'read 4' => '$v[EXPR];',

    'offset 1' => '[EXPR => 3];',

    'write 1' => 'EXPR = 1;',
    'write 2' => 'foreach ($v as EXPR) {}',
    'write 3' => 'foreach ($v as $k => EXPR) {}',

    'nontemporary 1' => 'EXPR[] = 2;',
    'nontemporary 2' => 'EXPR[3] = 2;',
    'nontemporary 3' => 'EXPR[] =& $x;',
    'nontemporary 4' => 'EXPR[1] =& $x;',

    'alias read 1' => '$v =& EXPR;',

    'alias write 1' => 'EXPR =& $v;',
    'alias write 2' => 'foreach ($v as EXPR => $x) {}',
    'alias write 3' => 'foreach ($v as $k => &EXPR) {}',
    'alias write 4' => 'EXPR += 1;',
];

$expressions = [
    '1',
    '$a',
    '($a)',
    '[]',
    '[1, 3]',
    '[1+2, 3]',
    '[strtoupper(1+2), 3]',
    '[$a, 3]',
    '[$a, , $c]',
    '$a[]',
    '$a[1]',
    'foo()',
    'foo()[1]',
    'foo()[]',
    '[$a]',
    'list($a)',
    '$$a',
    '${"a"}',
    '$a->b',
    '$a::b',
    '$a::$b',
    'foo()->b',
    '[1, 2][3]',
    '[$a, $b][3]',
    '[1, $b][3]',
    '$a++',
    '++$a',
];

$allowed = [];
$expressionTypes = [];

foreach ($templates as $templateAlias => $template)
{
    $allowed[$templateAlias] = [];
    foreach ($expressions as $expr)
    {
        $src = 'if(false){' . str_replace('EXPR', $expr, $template) . '}';
        system($bin . ' -r ' . escapeshellarg($src) . ' 2> /dev/null', $ret);
        if ($ret === 0)
        {
            $allowed[$templateAlias][] = $expr;
            $expressionTypes[$expr][] = $templateAlias;
        }
    }
}

foreach ($allowed as $k => $v) $allowed[$k] = implode('//', $v);
$allowedByExprs = [];
foreach ($allowed as $k => $v) $allowedByExprs[$v][] = $k;

echo $bin . "\n";
echo "\n";

foreach ($allowedByExprs as $a)
{
    echo implode(', ', $a) . "\n";
}

echo "\n";

foreach ($expressionTypes as $expr => $types)
{
    $types = array_unique(array_map(function ($s) { return preg_replace('{ \d}', '', $s); }, $types));
    echo $expr . " \t :: " . implode(', ', $types) . "\n";
}
