<?php

namespace lib\Database;

use lib\SimpelSQL;
use lib\Database\Connection;
use lib\Exception\InvalidInputException;

class SQL{

    private $config;

    public $connection;
    static $instance;

    public function __construct($con = "primary", $connection = null){
        $this->config = SimpelSQL::getConfig($con);
        if(!isset($this->connection)){
            $c = new Connection($this->config['host'],$this->config['databasename'],$this->config['username'],$this->config['password']);
            if($c->isClosed()){
                $c->open();
            } 
            $this->connection = $c->connection;
        }
    }

    private static function getInstance(){
        if(!isset(self::$instance)){
            self::$instance = new SQL();
        }
        return self::$instance;
    }

    public function getConnection(){
        return $this->connection;
    }

    public function where($whereequals){
        $wherestring = "";
        if(is_array($whereequals)){
            $size = sizeof($whereequals);
            for($i = 0; $i < $size; $i++){             
                if($i == 0){
                    $wherestring .= " WHERE ".array_keys($whereequals)[$i]."=:".array_keys($whereequals)[$i];
                }else{
                    $wherestring .= " AND ".array_keys($whereequals)[$i]."=:".array_keys($whereequals)[$i];
                }
            }
            return $wherestring;
        }else{
            throw new InvalidInputException($whereequals);
        }
    }

    public function bind($query,$whereequals){
        $size = sizeof($whereequals);
        for($it = 0; $it < $size; $it++){
            $query->bindParam(":".array_keys($whereequals)[$it],$whereequals[array_keys($whereequals)[$it]]);
        }
        return $query;
    }

    public static function select($table,$column,$whereequals = array()){
        $sql = self::getInstance();
        $query = $sql->connection->prepare("SELECT * FROM ".$table."".$sql->where($whereequals));
        $sql->bind($query,$whereequals)->execute();
        $fetch  = $query->fetchAll();
        if(count(func_get_args()) >= 3){
            $result = func_get_arg(3);
        }else{
            $result = 0;
        }
        if(!empty($fetch)){
            if($column != null && $column != "*"){
                return $fetch[$result][$column];
            }elseif($result != "all"){
                return $fetch[$result];
            }else{
                return $fetch;
            }   
        }else{
            return null;
        }
    }

    public static function update($table,$column,$whereequals,$to){
        $query = $this->connection->prepare("UPDATE ".$table." SET ".$column."=:value".$sql->where($whereequals));
        $query->bindParam(":value",$to);
        $sql->bind($query,$whereequals)->execute();
    }

    public static function create($table,$values,$primarykey){
        $valuestring = "";
        $size = sizeof($values);
        foreach($values as $key => $value){
            $valuestring .= $key." ".$value.",";
        }
        $valuestring .= "primary key (".$primarykey.")";
        $query = self::getInstance()->connection->prepare("CREATE TABLE ".$tablename." (".$valuestring.") ");
        $query->execute();
    }

    public static function insert($table,$values){
        if(func_get_args() > 2){
            $values = func_get_args();
        }
        $stringvalues = "";
        for($i = 0; $i < sizeof($values); $i++){
            if($i <  sizeof($values) - 1){
                $stringvalues .= ":".$i.",";
            }else{
               $stringvalues .= ":".$i;
            }
        }
        $query = self::getInstance()->connection->prepare("INSERT INTO ".$table." VALUES(".$stringvalues.")");
        for($i = 0; $i < sizeof($values); $i++){
            $query->bindParam(":".$i,$values[$i]);
        }
        $query->execute(); 
    }
}

?>