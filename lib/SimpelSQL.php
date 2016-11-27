<?php

namespace lib;

use lib\Database\Query;
use lib\Database\Connection;
use lib\Database\SQL;

class SimpelSQL extends SQL{

    private $connetion;

    public function __construct($con = "primary"){
        $data = self::getConfig($con);
        $connection = new Connection($data["host"],$data["databasename"],$data["username"],$data["password"]);
        parent::__construct($con,$connection);
    }
    
    public function query(){
        new Query();
    }
    public static function getConfig($item,$key = false){
        $config = include("config.php");
        foreach($config as $keys => $value){
            if($keys == $item){
                if($key){
                    return $keys;
                }
                return $value;
            }
        }
    }
}

?>