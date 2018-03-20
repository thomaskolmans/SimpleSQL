<?php
namespace lib\SQL;

use lib\Exception\InvalidInputException;
use lib\Database\Connection;

class Query{

    public $instance;
    
    public $query = "";
    public $like    = array();
    public $from    = array();
    public $where   = array();
    public $and     = array();
    public $or      = array();
    public $columns = array();
    public $tables  = array();

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
    public function __clone(){
        $this->instance = clone $this->instance;
    }
    public function select(){
         $this->query .= 'SELECT ' . (func_num_args() == 0 ? '*' : '`' . implode("`, `", func_get_args()) . '`');
         foreach(func_get_args() as $column){
            array_push($this->columns, $column);
         }
         return $this;
    }
    public function delete(){
        $this->query .= 'DELETE ' . (func_num_args() == 0 ? '*' : '`' . implode("`, `", func_get_args()) . '`');
         foreach(func_get_args() as $column){
            array_push($this->columns, $column);
         }
         return $this;
    }
    public function update($table){
        $this->query .= 'UPDATE '.$table.'';
        return $this;
    }
    public function to(){
        $this->query .= ' SET ' . (func_num_args() == 0 ? '*' : '`' . implode("`, `", func_get_args()) . '`');
    }
    public function insert($table,...$values){
        $this->query .= 'INSERT INTO `'.$table.'` VALUES('.implode("`,`",$values. '`').')';
        return $this;
    }
    public function create($table,$values,$primarykey = null){
        $size = sizeof($values);
        $valuestring = "";
        $primary = false;
        foreach($values as $key => $value){
            $valuestring .= "`".$key."` ".$value.",";
            if(strpos(strtolower($value), 'primary key' ) !== false )
                $primary = true;
        }
        if(!$primary)
            $valuestring .= "PRIMARY KEY(".$primarykey.")";

        $this->query .= "CREATE TABLE `".$table."` (".$valuestring.") ";
        return $this;
    }
    public function createFromTable($newtablename,$columns,$table,$whereequals){

    }
    public function select_desctinct(){
         $this->query .= 'SELECT DISTINCT ' . (func_num_args() == 0 ? '*' : '`' . implode("`, `", func_get_args()) . '`');
         foreach(func_get_args() as $column){
            array_push($this->columns, $column);
         }
         return $this;
    }
    public function from(){
        $this->query .=' FROM `'.implode("`,`",func_get_args()). '`'; 
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
                    $column = array_keys($args[0])[0];
                    $value  = func_get_arg(0)[$column]; 
                    $key = $this->create_key();
                    $this->where[$key] = $value;
                    $this->query .= " WHERE `".$column."`=:".$key;
                }
            }elseif(count($args) == 2){
                $column = func_get_arg(0);
                $value = func_get_arg(1);
                $key = $this->create_key();
                $this->where[$key] = $value;
                $this->query .= " WHERE `".$column."`=:".$key;
            }
        }else{
            throw new InvalidInputException("Invalid input, please check the syntax");
        }
        return $this;
    }
    public function join(){
        $args = $this->join_arguments(func_get_args());
        $this->query .= " INNER JOIN $args[0] ON ".$args[1]."=".$args[2];
        return $this;
    }
    private function join_arguments(){
        $args = func_get_args()[0];
        $id1 = "";
        $id2 = "";
        $table = "";
        if(count($args) < 2){
            throw new InvalidInputException("join functions need between 2 or 4 arguments");
        }
        if(count($args) == 2){
            if(is_array($args[0]) && is_array($args[1])){
                $arr2 = $args[1];
                $arr1 = $args[0];
                $table = $arr1[0];
                $id1 = $arr2[0].".".$arr2[1];
                $id2 = $arr1[0].".".$arr1[1];
            }else{
                throw new InvalidInputException("the 2 arguments need to be arrays");
            }
        }
        if(count($args) == 3){
            $table = $args[0];
            $id1 = $args[1];
            $id2 = $args[2];
        }
        return array($table,$id1,$id2);
    }
    public function inner_join(){
        return $this->join(func_get_args());
    }
    public function left_join(){
        $args = $this->join_arguments(func_get_args());
        $this->query .= " LEFT JOIN $args[0] ON ".$args[1]."=".$args[2];
        return $this;
    }
    public function right_join(){
        $args = $this->join_arguments(func_get_args());
        $this->query .= " RIGHT JOIN $args[0] ON ".$args[1]."=".$args[2];
        return $this;
    }
    public function full_join(){
        $args = $this->join_arguments(func_get_args());
        $this->query .= " FULL OUTER JOIN $args[0] ON ".$args[1]."=".$args[2];
        return $this;
    }
    public function and(){
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
                    $this->and[$key] = $value;
                    $this->query .= " AND `".$column."`=:".$key;
                }
            }elseif(count($args) == 2){
                $column = func_get_arg(0);
                $value = func_get_arg(1);
                $key = $this->create_key();
                $this->and[$key] = $value;
                $this->query .= " AND `".$column."`=:".$key;
            }
        }else{
            throw new InvalidInputException("Invalid input, please check the syntax");
        }
        return $this;
    }
    public function or(){
        $args = func_get_args();
            if(count($args) == 1){
                if(is_array($args[0])){
                    if(count($args[0]) > 1){
                        throw new InvalidInputException("Array can only contain 1 key and value.");
                    }
                    $column = array_keys($args(0))[0];
                    $value  = func_get_arg(0)[$column]; 
                    $key = $this->create_key();
                    $this->and[$key] = $value;
                    $this->query .= " OR `".$column."`=:".$key;
                }
            }
            if(count($args) > 2){
                $column = func_get_arg(0);
                $value = func_get_arg(1);
                $key = $this->create_key();
                $this->and[$key] = $value;
                $this->query .= " OR `".$column."`=:".$key;
            }
        return $this;
    }
    public function like(){
        $this->query .= "";
        return $this;
    }
    public function as(){
        $args = func_get_args();
        if(count($args) > 1){
            $this->query .= " AS ".implode("`, `", func_get_args());
        }else{
            throw new InvalidInputException("Aliases can only be 1, or must be defined for each column.");
        }
        return $this;
    }
    public function show(){
        $this->query .= ("SHOW ".implode(", ", func_get_args()) . '`');
        return $this;
    }
    public function orderBy($columns){
        $this->query .= " ORDER BY  `".implode("`, `",array_values(func_get_args()))."`";
        return $this;
    }
    public function desc(){
        $this->query .= " DESC ";
        return $this;
    }
    public function asc(){
        $this->query .= " ASC ";
        return $this;
    }
    public function limit($amount){
        $this->where["limit"] = $amount;
        $this->query .= " LIMIT :limit";
        return $this;
    }
    public function execute($destroy = true){
        $s = $this->instance->prepare($this->query);
        if(count($this->where) > 0){
            foreach(array_keys($this->where) as $bindkey){
                $s->bindParam(":".$bindkey,$this->where[$bindkey]);
            }
        }
        $s->execute();
        if($destroy){
            $this->destroy();
        }
        return $this->getResults($s);
    }
    public function destroy(){
        $this->clear_cache();
        $this->query = "";
        return $this->query;
    }
    public function getResults($s){
        return $s->fetchAll();
    }
    private function clear_cache(){
        $this->like    = array();
        $this->from    = array();
        $this->where   = array();
        $this->and     = array();
        $this->or      = array();
        $this->columns = array();
        $this->tables  = array();
    }

    private function create_key(){
        return md5(microtime().rand());
    }

}

?>