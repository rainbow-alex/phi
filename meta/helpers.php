<?php

use Phi\Nodes\Argument;
use Phi\Nodes\ArrayItem;
use Phi\Nodes\ComplexInterpolatedStringExpression;
use Phi\Nodes\ConstantInterpolatedStringPart;
use Phi\Nodes\Default_;
use Phi\Nodes\Expression;
use Phi\Nodes\Extends_;
use Phi\Nodes\Implements_;
use Phi\Nodes\InterpolatedStringExpression;
use Phi\Nodes\InterpolatedStringPart;
use Phi\Nodes\Key;
use Phi\Nodes\Name;
use Phi\Nodes\Parameter;
use Phi\Nodes\RegularName;
use Phi\Nodes\ReturnType;
use Phi\Nodes\RootNode;
use Phi\Nodes\SimpleInterpolatedStringExpression;
use Phi\Nodes\SpecialName;
use Phi\Nodes\Statement;
use Phi\Nodes\Type;
use Phi\Nodes\UseAlias;
use Phi\Nodes\UseName;
use Phi\Specifications\EachItem;
use Phi\Specifications\IsReadExpression;
use Phi\Specifications\IsToken;
use Phi\Token;

return [
    (new NodeDef(Argument::class))
        ->withOptToken('unpack', T_ELLIPSIS)
        ->withChild('expression', Expression::class, [new IsReadExpression])
        ->withConstructor('expression'),

    (new NodeDef(ArrayItem::class))
        ->withOptChild('key', Key::class)
        ->withOptToken('byReference', '&')
        ->withOptChild('value', Expression::class)
        ->withConstructor('value'),

    (new NodeDef(ConstantInterpolatedStringPart::class))
        ->withImplements(InterpolatedStringPart::class)
        ->withToken('content', T_ENCAPSED_AND_WHITESPACE)
        ->withConstructor('content'),

    (new NodeDef(ComplexInterpolatedStringExpression::class))
        ->withImplements(InterpolatedStringExpression::class)
        ->withToken('leftBrace', T_CURLY_OPEN) // for some reason this is not '{'
        ->withChild('expression', Expression::class, [new IsReadExpression])
        ->withToken('rightBrace', '}')
        ->withConstructor('expression'),

    (new NodeDef(Default_::class))
        ->withToken('symbol', '=')
        ->withChild('value', Expression::class)
        ->withConstructor('value'),

    (new NodeDef(Extends_::class))
        ->withToken('keyword', T_EXTENDS)
        ->withSepList('names', RegularName::class, ',')
        ->withConstructor('name'),

    (new NodeDef(Implements_::class))
        ->withToken('keyword', T_IMPLEMENTS)
        ->withSepList('names', RegularName::class, ',')
        ->withConstructor('name'),

    (new NodeDef(Key::class))
        ->withChild('value', Expression::class)
        ->withToken('arrow', T_DOUBLE_ARROW)
        ->withConstructor('value'),

    (new NodeDef(Parameter::class))
        ->withOptChild('type', Type::class)
        ->withOptToken('byReference', '&')
        ->withOptToken('ellipsis', T_ELLIPSIS)
        ->withToken('variable', T_VARIABLE)
        ->withOptChild('default', Default_::class)
        ->withConstructor('variable'),

    (new NodeDef(ReturnType::class))
        ->withToken('symbol', ':')
        ->withChild('type', Type::class)
        ->withConstructor('type'),

    (new NodeDef(RootNode::class))
        ->withList('statements', Statement::class)
        ->withOptToken('eof', Token::EOF),

    (new NodeDef(RegularName::class))
        ->withImplements(Name::class)
        ->withSepList('parts', Token::class, T_NS_SEPARATOR, [new EachItem(new IsToken(T_STRING))]),

    (new NodeDef(SimpleInterpolatedStringExpression::class))
        ->withImplements(InterpolatedStringExpression::class)
        ->withChild('expression', Expression::class, [new IsReadExpression])
        ->withConstructor('expression'),

    (new NodeDef(SpecialName::class))
        ->withImplements(Name::class)
        ->withChild('token', Token::class) // TODO validate
        ->withConstructor('token'),

    (new NodeDef(UseName::class))
        ->withChild('name', RegularName::class)
        ->withOptChild('alias', UseAlias::class),

    (new NodeDef(UseAlias::class))
        ->withToken('keyword', T_AS)
        ->withToken('name', T_STRING),
];
