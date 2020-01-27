<?php

use Phi\Nodes;
use Phi\Parser;
use Phi\Token;

/** @var Parser $this */

// TODO document if worthwhile

/* START */

switch (substr($this->typezip, $this->i * 3, 6))
{
    case Token::PH_T_LNUMBER . '000':
    case Token::PH_T_DNUMBER . '000':
        return Nodes\IntegerLiteral::__instantiateUnchecked($this->phpVersion, $this->read());
    case Token::PH_T_STRING . '000':
        return Nodes\NameExpression::__instantiateUnchecked($this->phpVersion, $this->name()); /* TODO further optim possible here (?) */
    case Token::PH_T_VARIABLE . '000':
        return Nodes\RegularVariableExpression::__instantiateUnchecked($this->phpVersion, $this->read());
    case Token::PH_T_CONSTANT_ENCAPSED_STRING . '000':
        return Nodes\ConstantStringLiteral::__instantiateUnchecked($this->phpVersion, $this->read());
    case Token::PH_T_LINE . '000':
    case Token::PH_T_FILE . '000':
    case Token::PH_T_DIR . '000':
    case Token::PH_T_CLASS_C . '000':
    case Token::PH_T_TRAIT_C . '000':
    case Token::PH_T_METHOD_C . '000':
    case Token::PH_T_FUNC_C . '000':
        return Nodes\MagicConstant::__instantiateUnchecked($this->phpVersion, $this->read());
}

switch (substr($this->typezip, $this->i * 3, 9))
{
    case Token::PH_T_NS_SEPARATOR . Token::PH_T_STRING . '000':
        return Nodes\NameExpression::__instantiateUnchecked($this->phpVersion, Nodes\RegularName::__instantiateUnchecked($this->phpVersion, [$this->read(), $this->read()]));
    case Token::PH_T_DEC . Token::PH_T_VARIABLE . '000':
        return Nodes\PreDecrementExpression::__instantiateUnchecked($this->phpVersion, $this->read(), Nodes\RegularVariableExpression::__instantiateUnchecked($this->phpVersion, $this->read()));
    case Token::PH_T_INC . Token::PH_T_VARIABLE . '000':
        return Nodes\PreIncrementExpression::__instantiateUnchecked($this->phpVersion, $this->read(), Nodes\RegularVariableExpression::__instantiateUnchecked($this->phpVersion, $this->read()));
    case Token::PH_T_VARIABLE . Token::PH_T_DEC . '000':
        return Nodes\PostDecrementExpression::__instantiateUnchecked($this->phpVersion, Nodes\RegularVariableExpression::__instantiateUnchecked($this->phpVersion, $this->read()), $this->read());
    case Token::PH_T_VARIABLE . Token::PH_T_INC . '000':
        return Nodes\PostIncrementExpression::__instantiateUnchecked($this->phpVersion, Nodes\RegularVariableExpression::__instantiateUnchecked($this->phpVersion, $this->read()), $this->read());
    case Token::PH_T_NEW . Token::PH_T_STRING . '000':
        return Nodes\NewExpression::__instantiateUnchecked($this->phpVersion, $this->read(), Nodes\NameExpression::__instantiateUnchecked($this->phpVersion, $this->name()), null, [], null);
}
