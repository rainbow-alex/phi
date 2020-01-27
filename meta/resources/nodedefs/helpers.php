<?php

use Phi\Meta\NodeDef;
use Phi\Nodes\AlternativeFormatBlock;
use Phi\Nodes\Argument;
use Phi\Nodes\ArrayItem;
use Phi\Nodes\Block;
use Phi\Nodes\CInterpolatedStringExpression;
use Phi\Nodes\CInterpolatedStringPart;
use Phi\Nodes\ComplexInterpolatedStringExpression;
use Phi\Nodes\ConstantInterpolatedStringPart;
use Phi\Nodes\Default_;
use Phi\Nodes\Expression;
use Phi\Nodes\VariableMemberName;
use Phi\Nodes\Extends_;
use Phi\Nodes\Implements_;
use Phi\Nodes\ImplicitBlock;
use Phi\Nodes\Key;
use Phi\Nodes\MemberName;
use Phi\Nodes\Name;
use Phi\Nodes\Parameter;
use Phi\Nodes\RegularBlock;
use Phi\Nodes\RegularMemberName;
use Phi\Nodes\RegularName;
use Phi\Nodes\ReturnType;
use Phi\Nodes\RootNode;
use Phi\Nodes\SimpleInterpolatedStringExpression;
use Phi\Nodes\SpecialName;
use Phi\Nodes\Statement;
use Phi\Nodes\Type;
use Phi\Nodes\UseAlias;
use Phi\Nodes\UseName;
use Phi\Token;

return [
    (new NodeDef(Argument::class))
        ->withOptToken('unpack', Token::PH_T_ELLIPSIS)
        ->withChild('expression', Expression::class)
        ->withConstructor('expression'),

    (new NodeDef(ArrayItem::class))
        ->withOptChild('key', Key::class)
        ->withOptToken('byReference', Token::PH_S_AMPERSAND)
        ->withOptChild('value', Expression::class)
        ->withConstructor('value'),

    /** @see Block */
    (new NodeDef(RegularBlock::class))
        ->withExtends(Block::class)
        ->withToken('leftBrace', Token::PH_S_LEFT_CURLY_BRACE)
        ->withList('statements', Statement::class)
        ->withToken('rightBrace', Token::PH_S_RIGHT_CURLY_BRACE)
        ->withConstructor('statement'),
    (new NodeDef(ImplicitBlock::class))
        ->withExtends(Block::class)
        ->withChild('statement', Statement::class)
        ->withConstructor('statement'),
    (new NodeDef(AlternativeFormatBlock::class))
        ->withExtends(Block::class)
        ->withToken('colon', Token::PH_S_COLON)
        ->withList('statements', Statement::class)
        ->withOptToken('endKeyword', [Token::PH_T_ENDDECLARE, Token::PH_T_ENDFOR, Token::PH_T_ENDFOREACH, Token::PH_T_ENDIF, Token::PH_T_ENDSWITCH, Token::PH_T_ENDWHILE])
        ->withOptToken('semiColon', Token::PH_S_SEMICOLON)
        ->withConstructor('statement'),

    (new NodeDef(Default_::class))
        ->withToken('symbol', Token::PH_S_EQUALS)
        ->withChild('value', Expression::class)
        ->withConstructor('value'),

    (new NodeDef(Extends_::class))
        ->withToken('keyword', Token::PH_T_EXTENDS)
        ->withSepList('names', Name::class, Token::PH_S_COMMA)
        ->withConstructor('name'),

    /** @see CInterpolatedStringPart */
    /** @see CInterpolatedStringExpression */
    (new NodeDef(ConstantInterpolatedStringPart::class))
        ->withExtends(CInterpolatedStringPart::class)
        ->withToken('content', Token::PH_T_ENCAPSED_AND_WHITESPACE)
        ->withConstructor('content'),
    (new NodeDef(SimpleInterpolatedStringExpression::class))
        ->withExtends(CInterpolatedStringExpression::class)
        ->withChild('expression', Expression::class)
        ->withConstructor('expression'),
    (new NodeDef(ComplexInterpolatedStringExpression::class))
        ->withExtends(CInterpolatedStringExpression::class)
        ->withToken('leftBrace', Token::PH_T_CURLY_OPEN)
        ->withChild('expression', Expression::class)
        ->withToken('rightBrace', Token::PH_S_RIGHT_CURLY_BRACE)
        ->withConstructor('expression'),

    (new NodeDef(Implements_::class))
        ->withToken('keyword', Token::PH_T_IMPLEMENTS)
        ->withSepList('names', Name::class, Token::PH_S_COMMA)
        ->withConstructor('name'),

    (new NodeDef(Key::class))
        ->withChild('value', Expression::class)
        ->withToken('arrow', Token::PH_T_DOUBLE_ARROW)
        ->withConstructor('value'),

    /** @see MemberName */
    (new NodeDef(RegularMemberName::class))
        ->withExtends(MemberName::class)
        ->withToken('token', Token::PH_T_STRING)
        ->withConstructor('token'),
    (new NodeDef(VariableMemberName::class))
        ->withExtends(MemberName::class)
        ->withOptToken('leftBrace', Token::PH_T_STRING) // TODO validate braces
        ->withChild('expression', Expression::class)
        ->withOptToken('rightBrace', Token::PH_T_STRING)
        ->withConstructor('expression'),

    /** @see Name */
    (new NodeDef(RegularName::class))
        ->withExtends(Name::class)
        ->withSepTokenList('parts', Token::class, Token::PH_T_NS_SEPARATOR),
    (new NodeDef(SpecialName::class))
        ->withExtends(Name::class)
        ->withToken('token', Token::PH_T_STATIC)
        ->withConstructor('token'),

    (new NodeDef(Parameter::class))
        ->withOptChild('type', Type::class)
        ->withOptToken('byReference', Token::PH_S_AMPERSAND)
        ->withOptToken('ellipsis', Token::PH_T_ELLIPSIS)
        ->withToken('variable', Token::PH_T_VARIABLE)
        ->withOptChild('default', Default_::class)
        ->withConstructor('variable'),

    (new NodeDef(ReturnType::class))
        ->withToken('symbol', Token::PH_S_COLON)
        ->withChild('type', Type::class)
        ->withConstructor('type'),

    (new NodeDef(RootNode::class))
        ->withList('statements', Statement::class)
        ->withOptToken('eof', Token::PH_T_EOF)
        ->withConstructor('statement'),

    /** @see UseStatement */
    (new NodeDef(UseName::class))
        ->withChild('name', Name::class)
        ->withOptChild('alias', UseAlias::class),
    (new NodeDef(UseAlias::class))
        ->withToken('keyword', Token::PH_T_AS)
        ->withToken('name', Token::PH_T_STRING),
];
