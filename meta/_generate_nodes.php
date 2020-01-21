#!/usr/bin/env php
<?php

use Phi\Exception\MissingNodeException;
use Phi\Node;
use Phi\NodeConverter;
use Phi\Nodes\Base\CompoundNode;
use Phi\Nodes\Base\NodesList;
use Phi\Nodes\Base\SeparatedNodesList;
use Phi\Specification;
use Phi\Specifications\And_;
use Phi\Specifications\Any;
use Phi\Specifications\IsInstanceOf;
use Phi\Specifications\IsToken;
use Phi\Specifications\EachItem;
use Phi\Specifications\EachSeparator;
use Phi\Optional;
use Phi\Specifications\ValidCompoundNode;
use Phi\Token;

require_once __DIR__ . '/../vendor/autoload.php';

const IMPORTS = [
    Node::class,
    Token::class,
    CompoundNode::class,
    NodesList::class,
    SeparatedNodesList::class,
    MissingNodeException::class,
    NodeConverter::class,
    Specification::class,
    Optional::class,
    And_::class,
    Any::class,
    IsToken::class,
    IsInstanceOf::class,
    ValidCompoundNode::class,
    EachItem::class,
    EachSeparator::class,
    'Phi\\Nodes' => 'Nodes',
    'Phi\\Specifications' => 'Specs',
];

function main()
{
    foreach (glob(__DIR__ . '/../src/Nodes/Generated/*.php') as $f)
    {
        unlink($f);
    }

    $nodes = array_merge(
        require __DIR__ . '/expressions.php',
        require __DIR__ . '/statements.php',
        require __DIR__ . '/members.php',
        require __DIR__ . '/types.php',
        require __DIR__ . '/helpers.php'
    );

    foreach ($nodes as $node)
    {
        assert($node instanceof NodeDef);

        $body = '';

        $body .= "/** @var Specification[] */\n";
        $body .= "private static \$specifications;\n";
        $body .= "protected static function getSpecifications(): array\n";
        $body .= "{\n";
        $body .= "    return self::\$specifications ?? self::\$specifications = [\n";
        $body .= "        new ValidCompoundNode([\n";
        foreach ($node->children as $child)
        {
            $body .= "            '{$child->name}' => {$child->specification()},\n";
        }
        $body .= "        ]),\n";
        $body .= "    ];\n";
        $body .= "}\n";
        $body .= "\n";

        foreach ($node->children as $child)
        {
            $body .= "/**\n";
            $body .= " * @var {$child->docType()}\n";
            $body .= " */\n";
            $body .= "private \${$child->name};\n";
        }

        $body .= "\n";
        $body .= "/**\n";
        foreach ($node->children as $child)
        {
            if ($child->isList)
            {
                if (in_array($child->singularName(), $node->constructor, true))
                {
                    $body .= " * @param {$child->itemDocType()} {$child->itemVar()}\n";
                }
            }
            else if (in_array($child->name, $node->constructor, true))
            {
                $body .= " * @param {$child->setterDocType()} {$child->var()}\n";
            }
        }
        $body .= " */\n";
        $body .= "public function __construct(" . implode(", ", array_map(function (string $arg)
        {
            return "\$$arg = null";
        }, $node->constructor)) . ")\n";
        $body .= "{\n";
        $body .= "    parent::__construct();\n";
        foreach ($node->children as $child)
        {
            if ($child->isList)
            {
                $body .= "    \$this->{$child->name} = new {$child->phpType()}();\n";
                if (in_array($child->singularName(), $node->constructor, true))
                {
                    $body .= "    if ({$child->itemVar()} !== null)\n";
                    $body .= "    {\n";
                    $body .= "        \$this->{$child->adder()}({$child->itemVar()});\n";
                    $body .= "    }\n";
                }
            }
            else if (in_array($child->name, $node->constructor, true))
            {
                $body .= "    if ({$child->var()} !== null)\n";
                $body .= "    {\n";
                $body .= "        \$this->{$child->setter()}({$child->var()});\n";
                $body .= "    }\n";
            }
        }
        $body .= "}\n";

        $body .= "\n";
        $body .= "/**\n";
        // TODO improve doctypes for phpstan
        foreach ($node->children as $child)
        {
            if ($child->isList)
            {
                $body .= " * @param mixed[] {$child->var()}\n";
            }
            else
            {
                $body .= " * @param {$child->docType()} {$child->var()}\n";
            }
        }
        $body .= " * @return static\n";
        $body .= " */\n";
        $body .= "public static function __instantiateUnchecked(" . implode(", ", array_map(function (NodeChildDef $child)
        {
            return $child->var();
        }, $node->children)) . ")\n";
        $body .= "{\n";
        $body .= "    \$instance = new static();\n";
        foreach ($node->children as $child)
        {
            if ($child->isList)
            {
                $body .= "    \$instance->{$child->name}->__initUnchecked({$child->var()});\n";
            }
            else
            {
                $body .= "    \$instance->{$child->name} = {$child->var()};\n";
            }
        }
        $body .= "    return \$instance;\n";
        $body .= "}\n";

        $body .= "\n";
        $body .= "public function &_getNodeRefs(): array\n";
        $body .= "{\n";
        $body .= "    \$refs = [\n";
        foreach ($node->children as $child)
        {
            $body .= "        '{$child->name}' => &\$this->{$child->name},\n";
        }
        $body .= "    ];\n";
        $body .= "    return \$refs;\n";
        $body .= "}\n";

        foreach ($node->children as $child)
        {
            if ($child->isList)
            {
                $body .= "\n";
                $body .= "/**\n";
                $body .= " * @return {$child->doctype()}\n";
                $body .= " */\n";
                $body .= "public function {$child->getter()}(): {$child->getterReturnType()}\n";
                $body .= "{\n";
                $body .= "    return \$this->{$child->name};\n";
                $body .= "}\n";

                $body .= "\n";
                $body .= "/**\n";
                $body .= " * @param {$child->itemDocType()} {$child->itemVar()}\n";
                $body .= " */\n";
                $body .= "public function {$child->adder()}({$child->itemVar()}): void\n";
                $body .= "{\n";
                $body .= "    /** @var {$child->itemDocType()} {$child->itemVar()} */\n";
                $body .= "    {$child->itemVar()} = NodeConverter::convert({$child->itemVar()}, {$child->itemType()}::class);\n";
                $body .= "    \$this->{$child->name}->add({$child->itemVar()});\n";
                $body .= "}\n";
            }
            else
            {
                $body .= "\n";
                $body .= "public function {$child->getter()}(): {$child->getterReturnType()}\n";
                $body .= "{\n";
                if (!$child->optional)
                {
                    $body .= "    if (\$this->{$child->name} === null)\n";
                    $body .= "    {\n";
                    $body .= "        throw new MissingNodeException(\$this, __FUNCTION__);\n";
                    $body .= "    }\n";
                }
                $body .= "    return \$this->{$child->name};\n";
                $body .= "}\n";

                $body .= "\n";
                $body .= "public function {$child->hasser()}(): bool\n";
                $body .= "{\n";
                $body .= "    return \$this->{$child->name} !== null;\n";
                $body .= "}\n";

                $body .= "\n";
                $body .= "/**\n";
                $body .= " * @param {$child->setterDocType()} {$child->var()}\n";
                $body .= " */\n";
                $body .= "public function {$child->setter()}({$child->var()}): void\n";
                $body .= "{\n";
                $body .= "    if ({$child->var()} !== null)\n";
                $body .= "    {\n";
                $body .= "        /** @var {$child->phpType()} {$child->var()} */\n";
                $body .= "        {$child->var()} = NodeConverter::convert({$child->var()}, {$child->phpType()}::class, \$this->_phpVersion);\n";
                $body .= "        {$child->var()}->_attachTo(\$this);\n";
                $body .= "    }\n";
                $body .= "    if (\$this->{$child->name} !== null)\n";
                $body .= "    {\n";
                $body .= "        \$this->{$child->name}->detach();\n";
                $body .= "    }\n";
                $body .= "    \$this->{$child->name} = {$child->var()};\n";
                $body .= "}\n";
            }
        }

        $src = "<?php\n";
        $src .= "\n";
        $src .= "namespace Phi\\Nodes\\Generated;\n";
        $src .= "\n";

        foreach (IMPORTS as $k => $v)
        {
            if (is_int($k))
            {
                $src .= "use $v;\n";
            }
            else
            {
                $src .= "use $k as $v;\n";
            }
        }

        $src .= "\n";
        $src .= "abstract class {$node->shortClassName()} extends CompoundNode";
        if ($node->implements)
        {
            $src .= " implements " . implode(", ", array_map('imported', $node->implements));
        }
        $src .= "\n";
        $src .= "{\n";

        $body = trim($body, "\n") . "\n"; // strip trailing empty lines
        $body = preg_replace('{^(?!$)}m', '    ', $body); // indent
        $src .= $body;

        $src .= "}\n";

        file_put_contents(__DIR__ . '/../src/Nodes/Generated/' . $node->shortClassName() . '.php', $src);
    }
}

class NodeDef
{
    /** @var string */
    public $className;
    /** @var string[] */
    public $implements = [];
    /** @var NodeChildDef[] */
    public $children = [];
    /** @var string[] */
    public $constructor = [];

    public function __construct(string $className)
    {
        $this->className = $className;
    }

    public function withImplements(string ...$interfaces): self
    {
        assert(!array_intersect($this->implements, $interfaces));
        $clone = clone $this;
        $clone->implements = array_merge($clone->implements, $interfaces);
        return $clone;
    }

    public function withChild(string $name, string $class, array $validation = [])
    {
        assert(!isset($this->children[$name]));
        $clone = clone $this;
        $clone->children[$name] = new NodeChildDef(
            $name,
            false,
            $class,
            null,
            null,
            null,
            $validation
        );
        return $clone;
    }

    public function withOptChild(string $name, string $class, array $validation = [])
    {
        assert(!isset($this->children[$name]));
        $clone = clone $this;
        $clone->children[$name] = new NodeChildDef(
            $name,
            true,
            $class,
            null,
            null,
            null,
            $validation
        );
        return $clone;
    }

    public function withToken(string $name, $tokenTypes, array $validation = [])
    {
        assert(!isset($this->children[$name]));
        $clone = clone $this;
        $clone->children[$name] = new NodeChildDef(
            $name,
            false,
            Token::class,
            null,
            ensure_array($tokenTypes),
            null,
            $validation
        );
        return $clone;
    }

    public function withOptToken(string $name, $tokenTypes, array $validation = [])
    {
        assert(!isset($this->children[$name]));
        $clone = clone $this;
        $clone->children[$name] = new NodeChildDef(
            $name,
            true,
            Token::class,
            null,
            ensure_array($tokenTypes),
            null,
            $validation
        );
        return $clone;
    }

    public function withList(string $name, string $itemClass, array $validation = []): self
    {
        assert(!isset($this->children[$name]));
        $clone = clone $this;
        $clone->children[$name] = new NodeChildDef(
            $name,
            false,
            NodesList::class,
            $itemClass,
            null,
            null,
            $validation
        );
        return $clone;
    }

    public function withTokenList(string $name, array $tokenTypes, array $validation = []): self
    {
        assert(!isset($this->children[$name]));
        $clone = clone $this;
        $clone->children[$name] = new NodeChildDef(
            $name,
            false,
            NodesList::class,
            Token::class,
            $tokenTypes,
            null,
            $validation
        );
        return $clone;
    }

    public function withSepList(string $name, string $itemClass, $separator, array $validation = []): self
    {
        assert(!isset($this->children[$name]));
        $clone = clone $this;
        $clone->children[$name] = new NodeChildDef(
            $name,
            false,
            SeparatedNodesList::class,
            $itemClass,
            null,
            ensure_array($separator),
            $validation
        );
        return $clone;
    }

    public function withConstructor(string ...$params): self
    {
        assert(!$this->constructor);
        $clone = clone $this;
        $clone->constructor = $params;
        return $clone;
    }

    public function shortClassName(): string
    {
        $parts = explode('\\', $this->className);
        return 'Generated' . rtrim(array_pop($parts), '_');
    }
}

class NodeChildDef
{
    /** @var string */
    public $name;
    /** @var bool */
    public $optional = false;
    /** @var bool */
    public $isList = false;
    /** @var string */
    public $class;
    /** @var string|null */
    public $itemClass = null;
    /** @var array<int|string>|null */
    public $tokenTypes;
    /** @var array<int|string>|null */
    public $separatorTypes;
    /** @var Specification[] */
    public $validation = [];

    /**
     * @param array<int|string>|null $tokenTypes
     * @param array<int|string>|null $separatorTypes
     * @param Specification[] $validation
     */
    public function __construct(string $name, bool $optional, string $class, ?string $itemClass, array $tokenTypes = null, array $separatorTypes = null, array $validation = [])
    {
        $this->name = $name;
        $this->optional = $optional;
        $this->isList = $itemClass !== null;
        assert(preg_match('{^Phi\\\\}', $class));
        $this->class = $class;
        assert($itemClass === null || preg_match('{^Phi\\\\}', $itemClass));
        $this->itemClass = $itemClass;
        $this->tokenTypes = $tokenTypes;
        $this->separatorTypes = $separatorTypes;
        $this->validation = $validation;
    }

    public function ucName(): string
    {
        return ucwords($this->name);
    }

    public function singularName(): ?string
    {
        return singular($this->name);
    }

    public function ucSingularName(): string
    {
        return ucwords(singular($this->name));
    }

    public function var()
    {
        return "\$$this->name";
    }

    public function itemVar(): string
    {
        return "\${$this->singularName()}";
    }

    public function phpType(): string
    {
        return imported($this->class);
    }

    public function docType(): string
    {
        if ($this->isList)
        {
            return $this->phpType() . "|" . imported($this->itemClass) . "[]";
        }
        else
        {
            return $this->phpType() . "|null";
        }
    }

    public function getter(): string
    {
        return "get{$this->ucName()}";
    }

    public function getterReturnType(): string
    {
        return ($this->optional ? '?' : '') . $this->phpType();
    }

    public function hasser(): string
    {
        return "has{$this->ucName()}";
    }

    public function setter(): string
    {
        return "set{$this->ucName()}";
    }

    public function setterDocType(): string
    {
        if ($this->isList)
        {
            throw new LogicException;
        }
        else
        {
            return $this->phpType() . "|" . imported(Node::class) . "|string|null";
        }
    }

    public function itemType(): string
    {
        assert($this->isList);
        return imported($this->itemClass);
    }

    public function itemDocType(): string
    {
        return $this->itemType();
    }

    public function adder(): string
    {
        return "add{$this->ucSingularName()}";
    }

    public function specification(): string
    {
        $specification = [];
        if ($this->isList)
        {
            $specification[] = new EachItem($this->tokenTypes ? new IsToken(...$this->tokenTypes) : new IsInstanceOf($this->itemClass));
            if ($this->separatorTypes)
            {
                $specification[] = new EachSeparator(new IsToken(...$this->separatorTypes));
            }
        }
        else if ($this->tokenTypes)
        {
            $specification[] = new IsToken(...$this->tokenTypes);
        }

        $specification = merge_specifications(...$specification, ...$this->validation);

        if ($this->optional)
        {
            $specification = new Optional($specification);
        }

        return value_to_php($specification);
    }
}

function imported(string $name): string
{
    foreach (IMPORTS as $k => $v)
    {
        if (is_int($k))
        {
            if ($name === $v)
            {
                return (new ReflectionClass($v))->getShortName();
            }
        }
        else
        {
            if (substr($name, 0, strlen($k) + 1) === "$k\\")
            {
                return $v . substr($name, strlen($k));
            }
        }
    }

    return $name;
}

function singular(string $s): ?string
{
    switch ($s)
    {
        case 'names':
            return 'name';

        default:
            if (substr($s, -3) === 'ies')
            {
                return substr($s, 0, -3) . 'y';
            }
            else if (substr($s, -2) === 'es')
            {
                return substr($s, 0, -2);
            }
            else if (substr($s, -1) === 's')
            {
                return substr($s, 0, -1);
            }
            else
            {
                return null;
            }
    }
}

/** @param int|string $token */
function token_type_to_php($token): string
{
    if (is_string($token))
    {
        return var_export($token, true);
    }
    else if ($token === Token::EOF)
    {
        return "Token::EOF";
    }
    else
    {
        return "\\" . token_name($token);
    }
}

function merge_specifications(Specification ...$s): Specification
{
    for ($i = 0; $i < count($s) - 1; $i++)
    {
        for ($j = $i + 1; $j < count($s); $j++)
        {
            // TODO And_?

            if ($s[$i] instanceof EachItem && $s[$j] instanceof EachItem)
            {
                $s[$i] = new EachItem(merge_specifications($s[$i]->getItemSpecification(), $s[$j]->getItemSpecification()));
                unset($s[$j]);
                continue;
            }

            if ($s[$i] instanceof EachSeparator && $s[$j] instanceof EachSeparator)
            {
                $s[$i] = new EachSeparator(merge_specifications($s[$i]->getItemSpecification(), $s[$j]->getItemSpecification()));
                unset($s[$j]);
                continue;
            }
        }
    }

    if (count($s) > 1)
    {
        return new And_(...$s);
    }
    else if (count($s) === 1)
    {
        return $s[0];
    }
    else
    {
        return new Any;
    }
}

function value_to_php($v): string
{
    if (is_object($v))
    {
        if ($v instanceof Optional)
        {
            return "new Optional(" . value_to_php($v->getSpecification()) . ")";
        }
        else if ($v instanceof Specification)
        {
            $class = imported(get_class($v));
            $props = (new ReflectionClass($v))->getProperties();
            if (count($props) === 1)
            {
                $props[0]->setAccessible(true);
                $args = ensure_array($props[0]->getValue($v));
                if ($v instanceof IsToken)
                {
                    $args = array_map('token_type_to_php', $args);
                }
                else
                {
                    $args = array_map('value_to_php', $args);
                }
            }
            else
            {
                assert(!$props);
                $args = [];
            }
            return "new $class" . ($args ? "(" . implode(", ", $args) . ")" : "");
        }
        else
        {
            throw new RuntimeException;
        }
    }
    else if (is_string($v) && preg_match('{^Phi\\\\}', $v))
    {
        return imported($v) . '::class';
    }
    else
    {
        return var_export($v, true);
    }
}

function ensure_array($v)
{
    return is_array($v) ? $v : [$v];
}

main();
