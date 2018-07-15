<?php
namespace lib;

use lib\database\Database;
use lib\database\Connection;
use lib\sql\SimpleQuery;
use lib\sql\Simple;
use lib\sql\RawQuery;

class SimpleSQl extends Simple{

    private $connetion;

    public static $data;

    public function __construct($con = "primary"){
        $data = self::getConfig($con);
        self::$data = $data; 
        $c = new Connection($data["host"],$data["databasename"],$data["username"],$data["password"]);
        if($c->isClosed()){
            $this->connection = $c->open();
        }
        parent::__construct($con,$this->connection);
    }
    
    public function close(){
        return $this->getConnection()->close();
    }
    public function open(){
        return $this->getConnection()->open();
    }
    public function check(){
        return $this->getConnection()->isOpen();
    }
    public function query(){
        if(count(func_get_args()) > 0 ){
            return new RawQuery(func_get_args());
        }
        return new Query($this->connection);
    }
    
    public static function getConfig($item,$key = false){
        $config = include(__DIR__."/../config.php");
        foreach($config as $keys => $value){
            if($keys == $item){
                if($key){
                    return $keys;
                }
                return $value;
            }
        } 
    }

    public static function getSettings($item,$key = false){
        $config = include(__DIR__."/../settings.php");
        foreach($config as $keys => $value){
            if($keys == $item){
                if($key){
                    return $keys;
                }
                return $value;
            }
        }
    }
    public function backup(){
        $database = new Database;
        return $database->backup(self::$data);
    }
    public function setConnection($connection){
        $this->connection = $connection;
        return $this;
    }
    public function getConnection(){
        $this->connection;
    }
}

?>