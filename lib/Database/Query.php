<?php
namespace lib\Database;

use lib\Exception\InvalidInputException;
use lib\Database\Connection;

class Query{

    public $instance;
    
    public $query = "";
    public $like    = array();
    public $from    = array();
    public $where   = array();
    public $and     = array();
    public $columns = array();

    public function __construct($con = null,$config = "primary"){
        if($con != null){
            $this->instance = $con;
        }else{
            $c = new Connection($this->config['host'],$this->config['databasename'],$this->config['username'],$this->config['password']);
            if($c->isClosed()){
                $c->open();
            } 
            $this->instance = $c->connection;
        }
    }

    public function select(){
         $this->query .= 'SELECT ' . (func_num_args() == 0 ? '*' : '`' . implode("`, `", func_get_args()) . '`');
         foreach(func_get_args() as $column){
            array_push($this->columns, $column);
         }
         return $this;
    }
    public function from(){
        $this->query .=' FROM '.implode(",",func_get_args()); 
        return $this;
    }
    public function where(){
        $args = func_get_args();
        if(count($args) < 3){
            if(count($args) == 1){
                if(is_array($args[0])){
                    if(count($args[0]) > 1){
                        throw new InvalidInputException("Array can only contain 1 key and value.");
                    }
                    $column = array_keys($args(0))[0];
                    $value  = func_get_arg(0)[$column]; 
                    $key = $this->create_key();
                    $this->where[$key] = $value;
                    $this->query .= " WHERE ".$column."=:".$key;
                }
            }elseif(count($args) == 2){
                $column = func_get_arg(0);
                $value = func_get_arg(1);
                $key = $this->create_key();
                $this->where[$key] = $value;
                $this->query .= " WHERE ".$column."=:".$key;
            }
        }else{
            throw new InvalidInputException("Invalid input, please check the syntax");
        }
        return $this;
    }
    public function join($type,$table){
        $this->query .= "INNER JOIN $table";
        return $this;
    }
    public function and(){
        $this->query .= " AND ";
        return;
    }
    public function like(){
        $this->query .= "";
    }
    public function execute(){
        $s = $this->instance->prepare($this->query);
        if(count($this->where) > 0){
            foreach(array_keys($this->where) as $bindkey){
                $s->bindParam(":".$bindkey,$this->where[$bindkey]);
            }
        }
        $s->execute();
        return $this->getResults($s);
    }

    public function getResults($s){
        if(count($this->columns) > 1){
            foreach($s->fetchAll()[0] as $result){
                $output = [];
                if(in_array($result, $this->columns)){
                    array_push($output, $result);
                }
            }
        }else{
            if($this->columns[0] == "*"){
                return $s->fetchAll();
            }else{
                $output = $s->fetchAll()[0][$this->columns[0]];
            }

        }
        return $output;
    }
    private function clear_cache(){
        $this->$like    = array();
        $this->$from    = array();
        $this->$where   = array();
        $this->$column  = array();
    }

    private function create_key(){
        return md5(microtime().rand());
    }

}

?>