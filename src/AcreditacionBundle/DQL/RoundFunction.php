<?php

namespace AcreditacionBundle\DQL; 

use Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\AST\Functions\FunctionNode;

/**
 * RoundFunction  ::= "ROUND" "(" ArithmeticPrimary "," ArithmeticPrimary ")"
 */

class RoundFunction extends FunctionNode
{
    public $expression = null;
    public $numericExpression = null;

    public function parse(\Doctrine\ORM\Query\Parser $parser)
    {
        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);
        $this->expression = $parser->ScalarExpression();
        $parser->match(Lexer::T_COMMA);
        $this->numericExpression = $parser->ArithmeticPrimary();
        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }

    public function getSql(\Doctrine\ORM\Query\SqlWalker $sqlWalker)
    {
        return "ROUND(" . $this->expression->dispatch($sqlWalker) . ", ". $this->numericExpression->dispatch($sqlWalker) .")";
    }
}