<?php

declare(strict_types=1);

namespace Phi\Tests\Testing\Fuzz;

class FuzzGenerator
{
    /** @var FuzzRule[] */
    private $rules;

    /** @param FuzzRule[] $rules */
    public function __construct(array $rules)
    {
        $this->rules = $rules;
    }

    public function getRule(string $name): FuzzRule
    {
        return $this->rules[$name];
    }

    public function addRule(string $name, FuzzRule $rule): void
    {
        assert(!isset($this->rules[$name]));
        $this->rules[$name] = $rule;
    }

    public function generate(string $expr, &$state = [])
    {
        foreach ($this->rules as $name => $rule)
        {
            if (!\array_key_exists($name, $state))
            {
                $rule->initState($state[$name]);
            }
        }

        $firstI = \PHP_INT_MAX;
        $match = null;
        foreach ($this->rules as $name => $rule)
        {
            \preg_match('{(\b|(?<=\W))' . \preg_quote($name) . '(\b|(?=\W))}', $expr, $m, \PREG_OFFSET_CAPTURE);
            if ($m && $m[0][1] < $firstI)
            {
                $firstI = $m[0][1];
                $match = $name;
            }
        }

        if ($match !== null)
        {
            foreach ($this->rules[$match]->generateRhs($state[$match]) as $rhs)
            {
                $subExpr = \substr($expr, 0, $firstI) . $rhs . \substr($expr, $firstI + \strlen($match));
                $subExpr = \str_replace('THIS', $match, $subExpr);
                yield from $this->generate($subExpr, $state);
            }
        }
        else
        {
            yield $expr;
        }
    }

    public static function parseDir(string $dir): self
    {
        $rules = [];

        foreach (\glob($dir . "/*.txt") as $def)
        {
            $name = \substr(\basename($def), 0, -4);
            [$meta, $src] = \explode("\n\n", \file_get_contents($def), 2);
            $meta = \json_decode($meta, true);
            \assert(!\json_last_error(), \json_last_error_msg() . " in " . $def);

            $options = \explode($meta["separator"] ?? "\n", $src);

            $options = \array_values(\array_filter(
                \array_map("trim", $options),
                function ($r)
                {
                    return $r !== "" && substr($r, 0, 2) !== "//";
                }
            ));

            switch ($meta["algo"])
            {
                case "weightedPermute":
                    $weightedOptions = [];
                    foreach ($options as $option)
                    {
                        \preg_match('{^(.+) \[\[(\d+)]]$}', $option, $m);
                        if ($m)
                        {
                            $weightedOptions[$m[1]] = (int) $m[2];
                        }
                        else
                        {
                            $weightedOptions[$option] = 1;
                        }
                    }
                    $rules[$name] = new WeightedPermute($meta["max"], $weightedOptions);
                    break;
                case "permute":
                    $rules[$name] = new Permute($options);
                    break;
                case "cycle":
                    $rules[$name] = new Cycle($options);
                    break;
                default:
                    \assert(false);
            }

            $srcs[$name] = $src;
        }

        return new self($rules);
    }
}
