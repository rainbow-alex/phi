<?php

namespace Phi;

use Phi\Exception\ValidationException;

interface Node
{
    /** @see PhpVersion */
    public function setPhpVersion(int $version): void;
    public function getId(): int;

    public function isAttached(): bool;
    public function getParent(): ?Node;
    public function detach(): void;

    /** @internal */
    public function _attachTo(Node $parent): void;
    /** @internal */
    public function _detachChild(Node $childToDetach): void;

    /** @return Node[] */
    public function childNodes(): array;
    /** @return iterable|Token[] */
    public function tokens(): iterable;

    public function getLeftWhitespace(): string;
    public function getRightWhitespace(): string;

    /**
     * @throws ValidationException
     */
    public function validate(): void;
    public function autocorrect(): ?Node;

    public function repr(): string;
    /** convert the node to php code */
    public function __toString(): string;

    public function debugDump(string $indent = ''): void;
}
