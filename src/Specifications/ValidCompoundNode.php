<?php

namespace Phi\Specifications;

use Phi\Exception\ValidationException;
use Phi\Node;
use Phi\Nodes\Base\CompoundNode;
use Phi\Optional;
use Phi\Specification;

class ValidCompoundNode extends Specification
{
    /** @var array<Specification|Optional> */
    private $childSpecifications;

    /**
     * @param array<Specification|Optional> $childSpecifications
     */
    public function __construct(array $childSpecifications)
    {
        $this->childSpecifications = $childSpecifications;
    }

    public function isSatisfiedBy(Node $node): bool
    {
        if (!$node instanceof CompoundNode)
        {
            return false;
        }

        foreach ($node->_getNodeRefs() as $name => $child)
        {
            $specification = $this->childSpecifications[$name];

            if ($specification instanceof Optional)
            {
                if ($child === null)
                {
                    continue;
                }
                else
                {
                    $specification = $specification->getSpecification();
                }
            }

            if ($child === null || !$specification->isSatisfiedBy($child))
            {
                return false;
            }
        }

        return true;
    }

    public function validate(Node $node): void
    {
        if (!$node instanceof CompoundNode)
        {
            parent::validate($node);
        }
        else
        {
            foreach ($node->_getNodeRefs() as $name => $child)
            {
                $specification = $this->childSpecifications[$name];

                if ($specification instanceof Optional)
                {
                    if ($child === null)
                    {
                        continue;
                    }
                    else
                    {
                        $specification = $specification->getSpecification();
                    }
                }

                if ($child === null)
                {
                    throw new ValidationException('Child \'' . $name . '\' of ' . $node->repr() . ' is required', $node);
                }
                else
                {
                    $specification->validate($child);
                }
            }
        }
    }

    public function autocorrect(?Node $node): ?Node
    {
        $node = parent::autocorrect($node);

        if ($node instanceof CompoundNode)
        {
            /** @var Node|null $child */
            foreach ($node->_getNodeRefs() as $name => &$child)
            {
                if ($child)
                {
                    $child = $child->autocorrect();
                }

                $specification = $this->childSpecifications[$name];

                if ($specification instanceof Optional)
                {
                    if ($child === null)
                    {
                        continue;
                    }
                    else
                    {
                        $specification = $specification->getSpecification();
                    }
                }

                $child = $specification->autocorrect($child);
            }
        }

        return $node;
    }
}
