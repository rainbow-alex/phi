<?php

use Phi\Meta\NodeChildDef;
use Phi\Meta\NodeDef;
use Phi\Nodes\Base\CompoundNode;

/** @var NodeDef $node */

?>
<?php echo "<?php\n"; ?>

namespace Phi\Nodes\Generated;

<?php foreach (IMPORTS as $k => $v): ?>
<?php if (is_int($k)): ?>
use <?= $v ?>;
<?php else: ?>
use <?= $k ?> as <?= $v ?>;
<?php endif ?>
<?php endforeach ?>

abstract class <?= $node->shortClassName() ?> extends <?= imported($node->extends ?? CompoundNode::class) ?><?= "\n" ?>
{
<?php foreach ($node->children as $child): ?>
    /**
     * @var <?= $child->docType() ?><?= "\n" ?>
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
        $this-><?= $child->name ?> = new <?= $child->phpType() ?>();
<?php if (\in_array($child->singularName(), $node->constructor, true)): ?>
        if (<?= $child->itemVar() ?> !== null)
        {
            $this-><?= $child->adder() ?>(<?= $child->itemVar() ?>);
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
     * @param int $phpVersion
<?php foreach ($node->children as $child): ?>
<?php if ($child->isList): ?>
     * @param mixed[] $<?= $child->name ?><?= "\n" ?>
<?php else: ?>
     * @param <?= $child->docType() ?> $<?= $child->name ?><?= "\n" ?>
<?php endif ?>
<?php endforeach ?>
     * @return static
     */
    public static function __instantiateUnchecked($phpVersion, <?= implode(", ", array_map(function (NodeChildDef $c) { return '$' . $c->name; }, $node->children)) ?>)
    {
        $instance = new static;
        $instance->phpVersion = $phpVersion;
<?php foreach ($node->children as $child): ?>
<?php if ($child->isList): ?>
        $instance-><?= $child->name ?>->__initUnchecked($<?= $child->name ?>);
<?php else: ?>
        $instance-><?= $child->name ?> = $<?= $child->name ?>;
<?php endif ?>
<?php if ($child->optional): ?>
        if ($<?= $child->name ?>)
        {
            $instance-><?= $child->name ?>->parent = $instance;
        }
<?php else: ?>
        $instance-><?= $child->name ?>->parent = $instance;
<?php endif ?>
<?php endforeach ?>
        return $instance;
    }

    protected function &_getNodeRefs(): array
    {
        $refs = [
<?php foreach ($node->children as $child): ?>
<?php /* TODO get rid of keys */ ?>
            '<?= $child->name ?>' => &$this-><?= $child->name ?>,
<?php endforeach ?>
        ];
        return $refs;
    }
<?php foreach ($node->children as $child): ?>
<?php if ($child->isList): ?>

    /**
     * @return <?= $child->docType() ?><?= "\n" ?>
     */
    public function <?= $child->getter() ?>(): <?= $child->getterReturnType() ?><?= "\n" ?>
    {
        return $this-><?= $child->name ?>;
    }

    /**
     * @param <?= $child->itemDocType() ?> <?= $child->itemVar() ?><?= "\n" ?>
     */
    public function <?= $child->adder() ?>(<?= $child->itemVar() ?>): void
    {
        /** @var <?= $child->itemType() ?> <?= $child->itemVar() ?> */
        <?= $child->itemVar() ?> = NodeConverter::convert(<?= $child->itemVar() ?>, <?= $child->itemType() ?>::class, $this->phpVersion);
        $this-><?= $child->name ?>->add(<?= $child->itemVar() ?>);
    }
<?php else: ?>

    public function <?= $child->getter() ?>(): <?= $child->getterReturnType() ?><?= "\n" ?>
    {
<?php if (!$child->optional): ?>
        if ($this-><?= $child->name ?> === null)
        {
            throw new MissingNodeException($this, __FUNCTION__);
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
            $<?= $child->name ?> = NodeConverter::convert($<?= $child->name ?>, <?= $child->phpType() ?>::class, $this->phpVersion);
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

    protected function _validate(int $flags): void
    {
<?php /* TODO if hasValidators($flag) */ ?>
        if ($flags & self::VALIDATE_TYPES)
        {
<?php foreach ($node->children as $child): ?>
<?php if (!$child->optional && !$child->isList): ?>
            if ($this-><?= $child->name ?> === null) throw ValidationException::childRequired($this, '<?= $child->name ?>');
<?php endif ?>
<?php endforeach ?>
        }
        if ($flags & self::VALIDATE_EXPRESSION_CONTEXT)
        {
<?php foreach ($node->children as $child): ?>
<?php /* TODO need validators for this */ ?>
<?php endforeach ?>
        }
        if ($flags & self::VALIDATE_TOKENS)
        {
<?php foreach ($node->children as $child): ?>
<?php /* TODO need validators for this */ ?>
<?php endforeach ?>
        }
<?php foreach ($node->children as $child): ?>
<?php if (($child->isList ? $child->itemClass : $child->class) !== \Phi\Token::class): ?>
<?php if ($child->optional): ?>
        if ($this-><?= $child->name ?>)
        {
            $this-><?= $child->name ?>->_validate($flags);
        }
<?php else: ?>
        $this-><?= $child->name ?>->_validate($flags);
<?php endif ?>
<?php endif ?>
<?php endforeach ?>
    }
}
