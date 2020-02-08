<?php

declare(strict_types=1);

use Phi\Meta\NodeChildDef;
use Phi\Meta\NodeDef;

/** @var NodeDef $node */

?>
<?php echo "<?php\n"; ?>

declare(strict_types=1);

<?php /* This is the source template, ignore the next comment :) */ ?>
/**
 * This code is generated.
 * @see meta/
 */

namespace Phi\Nodes\Generated;

<?php foreach (IMPORTS as $k => $v): ?>
<?php if (is_int($k)): ?>
use <?= $v ?>;
<?php else: ?>
use <?= $k ?> as <?= $v ?>;
<?php endif ?>
<?php endforeach ?>

trait <?= $node->shortGeneratedClassName() ?><?= "\n" ?>
{
<?php foreach ($node->children as $child): ?>
    /**
     * @var <?= $child->docType() ?><?= "\n" ?>
<?php if ($child->isList): ?>
     * @phpstan-var <?= $child->phpstanDocType() ?><?= "\n" ?>
<?php endif ?>
     */
    private $<?= $child->name ?>;

<?php endforeach ?>
    /**
<?php foreach ($node->children as $child): ?>
<?php if ($child->isList): ?>
<?php if (\in_array($child->singularName(), $node->constructor, true)): ?>
     * @param <?= $child->itemDocType() ?> <?= $child->itemVar() ?><?= "\n" ?>
<?php endif ?>
<?php else: ?>
<?php if (\in_array($child->name, $node->constructor, true)): ?>
     * @param <?= $child->setterDocType() ?> $<?= $child->name ?><?= "\n" ?>
<?php endif ?>
<?php endif ?>
<?php endforeach ?>
     */
    public function __construct(<?= implode(", ", array_map(function (string $a) { return '$' . $a . ' = null'; }, $node->constructor)) ?>)
    {
<?php foreach ($node->children as $child): ?>
<?php if ($child->isList): ?>
        $this-><?= $child->name ?> = new <?= $child->phpType() ?>(<?= $child->itemType() ?>::class);
<?php if (\in_array($child->singularName(), $node->constructor, true)): ?>
        if (<?= $child->itemVar() ?> !== null)
        {
            $this-><?= $child->name ?>->add(<?= $child->itemVar() ?>);
        }
<?php endif ?>
<?php else: ?>
<?php if (\in_array($child->name, $node->constructor, true)): ?>
        if ($<?= $child->name ?> !== null)
        {
            $this-><?= $child->setter() ?>($<?= $child->name ?>);
        }
<?php endif ?>
<?php endif ?>
<?php endforeach ?>
    }

    /**
<?php foreach ($node->children as $child): ?>
<?php if ($child->isList): ?>
     * @param mixed[] $<?= $child->name ?><?= "\n" ?>
<?php else: ?>
     * @param <?= $child->docType(false) ?> $<?= $child->name ?><?= "\n" ?>
<?php endif ?>
<?php endforeach ?>
     * @return self
     */
    public static function __instantiateUnchecked(<?= implode(", ", array_map(function (NodeChildDef $c) { return '$' . $c->name; }, $node->children)) ?>)
    {
        $instance = new self;
<?php foreach ($node->children as $child): ?>
<?php if ($child->isList): ?>
        $instance-><?= $child->name ?>->__initUnchecked($<?= $child->name ?>);
<?php else: ?>
        $instance-><?= $child->name ?> = $<?= $child->name ?>;
<?php endif ?>
<?php if ($child->isList): ?>
        $instance-><?= $child->name ?>->parent = $instance;
<?php elseif ($child->optional): ?>
        if ($<?= $child->name ?>) $<?= $child->name ?>->parent = $instance;
<?php else: ?>
        $<?= $child->name ?>->parent = $instance;
<?php endif ?>
<?php endforeach ?>
        return $instance;
    }

    public function getChildNodes(): array
    {
        return \array_values(\array_filter([
<?php foreach ($node->children as $child): ?>
            $this-><?= $child->name ?>,
<?php endforeach ?>
        ]));
    }

    protected function &getChildRef(Node $childToDetach): Node
    {
<?php foreach ($node->children as $child): ?>
<?php if (!$child->isList): ?>
        if ($this-><?= $child->name ?> === $childToDetach)
        {
            return $this-><?= $child->name ?>;
        }
<?php endif ?>
<?php endforeach ?>
        throw new \LogicException();
    }
<?php foreach ($node->children as $child): ?>
<?php if ($child->isList): ?>

    /**
     * @return <?= $child->docType() ?><?= "\n" ?>
<?php if ($child->isList): ?>
     * @phpstan-return <?= $child->phpstanDocType() ?><?= "\n" ?>
<?php endif ?>
     */
    public function <?= $child->getter() ?>(): <?= $child->getterReturnType() ?><?= "\n" ?>
    {
        return $this-><?= $child->name ?>;
    }
<?php else: ?>

    public function <?= $child->getter() ?>(): <?= $child->getterReturnType() ?><?= "\n" ?>
    {
<?php if (!$child->optional): ?>
        if ($this-><?= $child->name ?> === null)
        {
            throw TreeException::missingNode($this, "<?= $child->name ?>");
        }
<?php endif ?>
        return $this-><?= $child->name ?>;
    }

    public function <?= $child->hasser() ?>(): bool
    {
        return $this-><?= $child->name ?> !== null;
    }

    /**
     * @param <?= $child->setterDocType() ?> $<?= $child->name ?><?= "\n" ?>
     */
    public function <?= $child->setter() ?>($<?= $child->name ?>): void
    {
        if ($<?= $child->name ?> !== null)
        {
            /** @var <?= $child->phpType() ?> $<?= $child->name ?> */
            $<?= $child->name ?> = NodeCoercer::coerce($<?= $child->name ?>, <?= $child->phpType() ?>::class, $this->getPhpVersion());
            $<?= $child->name ?>->detach();
            $<?= $child->name ?>->parent = $this;
        }
        if ($this-><?= $child->name ?> !== null)
        {
            $this-><?= $child->name ?>->detach();
        }
        $this-><?= $child->name ?> = $<?= $child->name ?>;
    }
<?php endif ?>
<?php endforeach ?>

    public function _validate(int $flags): void
    {
<?php foreach ($node->children as $child): ?>
<?php if (!$child->optional && !$child->isList): ?>
        if ($this-><?= $child->name ?> === null)
            throw ValidationException::missingChild($this, "<?= $child->name ?>");
<?php endif ?>
<?php endforeach ?>
<?php foreach ($node->children as $child): ?>
<?php   if ($child->tokenTypes): ?>
<?php       if (!$child->isList): ?>
<?php           if ($child->optional): ?>
        if ($this-><?= $child->name ?>)
<?php           endif ?>
<?php           if (count($child->tokenTypes) === 1): ?>
        if ($this-><?= $child->name ?>->getType() !== <?= $child->tokenTypes[0] ?>)
<?php           else: ?>
        if (!\in_array($this-><?= $child->name ?>->getType(), [<?= implode(", ", $child->tokenTypes) ?>], true))
<?php           endif ?>
            throw ValidationException::invalidSyntax($this-><?= $child->name ?>, [<?= implode(", ", $child->tokenTypes) ?>]);
<?php       else: ?>
        foreach ($this-><?= $child->name ?> as $t)
<?php           if (count($child->tokenTypes) === 1): ?>
            if ($t->getType() !== <?= $child->tokenTypes[0] ?>)
<?php           else: ?>
            if (!\in_array($t->getType(), [<?= implode(", ", $child->tokenTypes) ?>], true))
<?php           endif ?>
                throw ValidationException::invalidSyntax($t, [<?= implode(", ", $child->tokenTypes) ?>]);
<?php       endif ?>
<?php   endif ?>
<?php   if ($child->separatorTypes): ?>
        foreach ($this-><?= $child->name ?>->getSeparators() as $t)
<?php       if (count($child->separatorTypes) === 1): ?>
            if ($t && $t->getType() !== <?= $child->separatorTypes[0] ?>)
<?php       else: ?>
            if ($t && !\in_array($t->getType(), [<?= implode(", ", $child->separatorTypes) ?>], true))
<?php       endif ?>
                throw ValidationException::invalidSyntax($t, [<?= implode(", ", $child->separatorTypes) ?>]);
<?php   endif ?>
<?php endforeach ?>

<?php if ($node->invalidContexts): ?>
        if ($flags & <?= $node->invalidContexts ?>)
            throw ValidationException::invalidExpressionInContext($this);
<?php endif ?>

        $this->extraValidation($flags);

<?php if ($node->validateChildren): ?>
<?php foreach ($node->children as $child): ?>
<?php if (($child->isList ? $child->itemClass : $child->class) !== \Phi\Token::class): /* tokens or lists of tokens don't have extra validation */ ?>
<?php if ($child->optional): ?>
        if ($this-><?= $child->name ?>)
            $this-><?= $child->name ?>->_validate(<?= $child->validationFlags ?>);
<?php elseif ($child->isList): ?>
        foreach ($this-><?= $child->name ?> as $t)
            $t->_validate(<?= $child->validationFlags ?>);
<?php else: ?>
        $this-><?= $child->name ?>->_validate(<?= $child->validationFlags ?>);
<?php endif ?>
<?php endif ?>
<?php endforeach ?>
<?php endif ?>
    }

    public function _autocorrect(): void
    {
<?php foreach ($node->children as $child): ?>
<?php if (($child->isList ? $child->itemClass : $child->class) !== \Phi\Token::class): /* tokens or lists of tokens don't have autocorrect */ ?>
<?php if ($child->isList): ?>
        foreach ($this-><?= $child->name ?> as $t)
            $t->_autocorrect();
<?php else: ?>
        if ($this-><?= $child->name ?>)
            $this-><?= $child->name ?>->_autocorrect();
<?php endif ?>
<?php endif ?>
<?php endforeach ?>

        $this->extraAutocorrect();
    }
}
