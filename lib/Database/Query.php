<?php
namespace lib\Database;

class Query{

    public $query = "";
    public $binding = array();
    public $instance;
    
    public function __construct(){
        $this->instance =  SQL::getInstance();
    }

    public function select(){
         $this->query .= 'SELECT ' . (func_num_args() == 0 ? '*' : '`' . implode("`, `", func_get_args()) . '`');
         return $this;
    }
    public function from(){
        $tables = func_get_args();
        $this->query .=' FROM '; 
        $this->query .= implode(",",$tables);
        return $this;
    }
    public function where(){
        $this->query .= " WHERE ";
        return;
    }
    public function join($type,$table){
        $this->query .= "INNER JOIN $table";
    }
    public function and(){
        $this->query .= " AND ";
        return;
    }
    public function like(){
        $this->query .= "";
    }
    public function get(){

    }
}

?>