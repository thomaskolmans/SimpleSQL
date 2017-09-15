<?php

namespace lib\Database;

use lib\SimpleSQL;
use lib\Database\Connection;
use lib\Exception\InvalidInputException;

class SQL{

    private $config;
    private $settings;

    public $connection;
    static $instance;

    public $base;

    public function __construct($con = "primary"){
        $parent = $this->get_calling_class();
        if($parent = "lib\SimpleSQl"){
            $this->config = SimpleSQl::$data;
        }else{
            $this->config = SimpleSQl::getConfig($con);
        }
        if(!isset($this->connection)){
            $c = new Connection($this->config['host'],$this->config['databasename'],$this->config['username'],$this->config['password']);
            if($c->isClosed()){
                $c->open();
            } 
            $this->connection = $c->connection;
        }
    }

    private static function getInstance($con = "primary"){
        if(!isset(self::$instance)){
            self::$instance = new SQL($con);
        }
        return self::$instance;
    }
    public function get_calling_class() {
        $trace = debug_backtrace();
        $class = $trace[1]['class'];
        for ( $i=1; $i<count( $trace ); $i++ ) {
            if ( isset( $trace[$i] ) && isset ( $trace[$i]['class']) )
                 if ( $class != $trace[$i]['class'] )
                     return $trace[$i]['class'];
        }
        return null;
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

    public static function select($column,$table,$whereequals = array(),$limit = null){
        $sql = self::getInstance();
        $sql = self::getInstance();
        if(is_array($column)){
            $querycolumn = implode(", ","`".$column."`");
        }elseif($column == "*"){
            $querycolumn = "*";
        }else{
            $querycolumn = "`".$column ."`";
        }
        $query = $sql->connection->prepare("SELECT ".$querycolumn." FROM ".$table."".$sql->where($whereequals));
        $sql->bind($query,$whereequals)->execute();
        $fetch  = $query->fetchAll();
        if(!empty($fetch)){
            $output = [];
            if(count($fetch) > 1 || $column == "*"){
                foreach($fetch as $result){
                    $result = array_filter($result,function($var){
                        return !is_int($var);
                    },ARRAY_FILTER_USE_KEY);
                    array_push($output,$result);
                }
            }else{
                return $fetch[0][0];
            }
            return $output;
        }else{
            return null;
        }
    }

    public static function update($column,$table,$whereequals,$to){
        $sql = self::getInstance()->connection;
        $values = array();
        if(is_array($column)){
            $squery = "UPDATE ".$table." SET";
            $columnnumber = 0;
            foreach($column as $col){
                $key    = $this->create_key();
                $squery  .= " SET ".$col."=:".$key;
                $columnnumber++;
            }
            $squery .= $sql->where($whereequals);
            $query = $sql->prepare($squery);
        }else{
            $values[":value"] = $to;
            $query = $sql->prepare("UPDATE ".$table." SET ".$column."=:value".$sql->where($whereequals));
        }
        foreach(array_keys($values) as $columnkey){
            $query->bindParam($columnkey,$values[$columnkey]);  
        }
        $sql->bind($query,$whereequals)->execute();
    }

    public static function delete($table,$whereequals){
        if(func_get_args() > 2){
            $whereequals = func_get_args();
            unset($whereequals[0]);
            $whereequals = array_values($whereequals);
        }

        $query = self::getInstance()->connection->prepare("DELETE FROM ".$table."".$sql->where($whereequals));

        $sql->bind($query,$whereequals)->execute();
    }

    public static function drop($table){
        if(SimpleSQl::getSettings("table_drop")){
            $query = "DROP TABLE :table";
            $query = self::getInstance()->connection->prepare($query);
            $query->bindParam(":table",$table);
            $query->execute();
        }else{
            return false;
        }
    }
    public static function database($name){
        $sql = self::getInstance();
        $query = $sql->connection->prepare("CREATE DATABASE ".$name);
        $query->execute();
    }
    public static function create($table,array $values,$primarykey){
        $valuestring = "";
        $size = sizeof($values);
        foreach($values as $key => $value){
            $valuestring .= $key." ".$value.",";
        }
        $valuestring .= "PRIMARY KEY(".$primarykey.")";
        $query = self::getInstance()->connection->prepare("CREATE TABLE ".$table." (".$valuestring.") ");
        $query->execute();
    }

    public static function insert($table,$values){

        if(func_get_args() > 2){
            $values = func_get_args();
            unset($values[0]);
            $values = array_values($values);
        }
        $stringvalues = "";
        for($i = 0; $i < sizeof($values[0]); $i++){
            if($i <  sizeof($values[0]) - 1){
                $stringvalues .= ":".$i.",";
            }else{
                $stringvalues .= ":".$i;
            }
        }
        $query = self::getInstance()->connection->prepare("INSERT INTO ".$table." VALUES(".$stringvalues.")");
        for($i = 0; $i < sizeof($values[0]); $i++){
            $query->bindParam(":".$i,$values[0][$i]);
        }
        $query->execute(); 
    }
    public static function exists($table,$whereequals){
        $sql = self::getInstance()->connection;
        if(count($whereequals) > 0){
            $query = $sql->prepare("SELECT * FROM ".$table."".self::getInstance()->where($whereequals));
            self::getInstance()->bind($query,$whereequals)->execute();
            if($query->rowCount() > 0){
                return true;
            }else{
                return false;
            }
        }else{
            $query = $sql->prepare("SHOW TABLES LIKE '".$table."'");          
            $query->execute();
            if($query->rowCount() > 0){
                return true;
            }else{
                return false;
            }
        }
    }

    public static function create_index(){
        $instance = self::getInstance();
    }

    public static function create_unique_index(){
        $instance = self::getInstance();
    }

    public static function count($table,$whereequals){
        $query = self::getInstance()->connection->prepare("SELECT * FROM ".$table."".self::getInstance()->where($whereequals));
        self::getInstance()->bind($query,$whereequals)->execute();
        return $query->rowCount();
    }
    public static function show_tables($database = ""){
        $sql = self::getInstance()->connection;
        $query = $sql->prepare('show tables from nytrix');
        $query->execute();
        $fetch = $query->fetchAll();
        return $fetch;
    }
    public static function show_databases(){
        $sql = self::getInstance()->connection;
        $query = $sql->prepare("show database");
    }

    public static function avg(){
        $instance = self::getInstance();
    }

    public static function max(){

    }
    private function create_key(){
        return md5(microtime().rand());
    }
}

?>
