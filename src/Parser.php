<?php
/**
 * Created by PhpStorm.
 * User: sharif
 * Date: 10/18/2015
 * Time: 8:24 PM
 */

namespace PHPParser;
use PHPParser\Lexer\Lexer;

class Parser
{
    protected $original_sql;
    protected $type;
    public $ParseObject;
    protected $lexer;
    /**
     * @param $sql
     */
    public function __construct($sql=false)
    {
        $this->lexer = new Lexer();
        if($sql)
        {
           $this->parse($sql);
        }

    }

    /**
     * calculate this sql what is type
     */
    protected function findType()
    {
        $sql_chars = explode(' ',$this->original_sql);
        if(!$sql_chars[0])
            exit('Bad sql file check the file');
        $types = array('SELECT','INSERT','UPDATE','DELETE',
                        'REPLACE','RENAME','SHOW','SET','DROP',
                         'EXPLAIN','CREATE',
                         'DESCRIBE');

        if(!in_array(strtoupper($sql_chars['0']),$types))
            exit('This file is not sql or This library doesn\'t support it');
        // check create
        if($sql_chars[0] =='CREATE')
        {
            if($sql_chars[1] =='TABLE')
                $this->type = 'CreateTable';

            elseif($sql_chars[1] =='INDEX')
                $this->type = 'CreateIndex';
            else
            exit('This file is not sql or This library doesn\'t support it');
        }
        else
        {
            $this->type = ucfirst(strtolower($sql_chars[0]));
        }

    }
    public function parse($sql)
    {
        $this->lexer->lex(trim($sql));
        $this->original_sql = $this->lexer->clean();
        // find type of query
        $this->findType();
        if($this->type)
        {
            $class = "\\PHPParser\\Parser\\".strval($this->type);
            $this->ParseObject = new $class($this->original_sql,$this->type);
        }
    }
}