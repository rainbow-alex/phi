<?php

namespace Phi\Nodes\Generated;

use Phi\Node;
use Phi\Token;
use Phi\Nodes\Base\CompoundNode;
use Phi\Nodes\Base\NodesList;
use Phi\Nodes\Base\SeparatedNodesList;
use Phi\Exception\MissingNodeException;
use Phi\NodeConverter;
use Phi\Specification;
use Phi\Optional;
use Phi\Specifications\And_;
use Phi\Specifications\Any;
use Phi\Specifications\IsToken;
use Phi\Specifications\IsInstanceOf;
use Phi\Specifications\ValidCompoundNode;
use Phi\Specifications\EachItem;
use Phi\Specifications\EachSeparator;
use Phi\Nodes as Nodes;
use Phi\Specifications as Specs;

abstract class GeneratedRootNode extends CompoundNode
{
    /** @var Specification[] */
    private static $specifications;
    protected static function getSpecifications(): array
    {
        return self::$specifications ?? self::$specifications = [
            new ValidCompoundNode([
                'statements' => new EachItem(new IsInstanceOf(Nodes\Statement::class)),
                'eof' => new Optional(new IsToken(Token::EOF)),
            ]),
        ];
    }

    /**
     * @var NodesList|Nodes\Statement[]
     */
    private $statements;
    /**
     * @var Token|null
     */
    private $eof;

    /**
     */
    public function __construct()
    {
        parent::__construct();
        $this->statements = new NodesList();
    }

    /**
     * @param mixed[] $statements
     * @param Token|null $eof
     * @return static
     */
    public static function __instantiateUnchecked($statements, $eof)
    {
        $instance = new static();
        $instance->statements->__initUnchecked($statements);
        $instance->eof = $eof;
        return $instance;
    }

    public function &_getNodeRefs(): array
    {
        $refs = [
            'statements' => &$this->statements,
            'eof' => &$this->eof,
        ];
        return $refs;
    }

    /**
     * @return NodesList|Nodes\Statement[]
     */
    public function getStatements(): NodesList
    {
        return $this->statements;
    }

    /**
     * @param Nodes\Statement $statement
     */
    public function addStatement($statement): void
    {
        /** @var Nodes\Statement $statement */
        $statement = NodeConverter::convert($statement, Nodes\Statement::class);
        $this->statements->add($statement);
    }

    public function getEof(): ?Token
    {
        return $this->eof;
    }

    public function hasEof(): bool
    {
        return $this->eof !== null;
    }

    /**
     * @param Token|Node|string|null $eof
     */
    public function setEof($eof): void
    {
        if ($eof !== null)
        {
            /** @var Token $eof */
            $eof = NodeConverter::convert($eof, Token::class, $this->_phpVersion);
            $eof->_attachTo($this);
        }
        if ($this->eof !== null)
        {
            $this->eof->detach();
        }
        $this->eof = $eof;
    }
}
