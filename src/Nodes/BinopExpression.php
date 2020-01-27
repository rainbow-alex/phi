<?php

namespace Phi\Nodes;

abstract class BinopExpression extends Expression
{
    abstract public function getLeft(): Expression;
    abstract public function getRight(): Expression;
}
