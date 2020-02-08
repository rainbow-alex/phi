<?php

declare(strict_types=1);

namespace Phi\Exception;

use Exception;
use Phi\Node;
use Phi\Token;

class PhiException extends Exception
{
    /** @var Node|null */
    private $node;

    public function __construct(string $message, ?Node $node)
    {
        parent::__construct($message);
        $this->node = $node;
    }

    public function getNode(): ?Node
    {
        return $this->node;
    }

    private function token(): ?Token
    {
        if ($this->node)
        {
            foreach ($this->node->iterTokens() as $token)
            {
                return $token;
            }
        }

        return null;
    }

    public function getSourceLine(): ?int
    {
        $token = $this->token();
        return $token ? $token->getLine() : null;
    }

    public function getSourceFilename(): ?string
    {
        $token = $this->token();
        return $token ? $token->getFilename() : null;
    }

    public function getContext(): string
    {
        return ($this->getSourceFilename() ?? "<unknown>") . " on line " . ($this->getSourceLine() ?? "?");
    }

    public function getMessageWithContext(): string
    {
        return $this->message . " in " . $this->getContext();
    }
}
