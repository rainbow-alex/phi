<?php

namespace Phi\Exception;

use Phi\Node;
use Phi\Token;
use Exception;

abstract class PhiException extends Exception
{
    /** @var Node */
    private $node;

    public function __construct(string $message, Node $node)
    {
        parent::__construct($message);
        $this->node = $node;
    }

    public function getNode(): Node
    {
        return $this->node;
    }

    private function token(): ?Token
    {
        foreach ($this->node->tokens() as $token)
        {
            return $token;
        }

        return null;
    }

    public function getSourceLine(): ?int
    {
        $token = $this->token();
        return $token ? $token->getLine() : null;
    }

    public function getSourceColumn(): ?int
    {
        $token = $this->token();
        return $token ? $token->getColumn() : null;
    }

    public function getSourceFilename(): ?string
    {
        $token = $this->token();
        return $token ? $token->getFilename() : null;
    }

    public function getContext(): string
    {
        return ($this->getSourceFilename() ?? '<unknown>') . ' on line ' . ($this->getSourceLine() ?? '?');
    }

    public function getMessageWithContext(): string
    {
        return $this->message . ' in ' . $this->getContext();
    }
}
