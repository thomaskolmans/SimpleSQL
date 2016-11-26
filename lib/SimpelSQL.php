<?php
namespace lib;

use Database\Query;
use Database\Connection;

class SimpelSQL{

    private $connetion;

    public function __construct(){
        $connection = new Connection($this->getConfig("connection"))
    }   
    public function query(){
        new Query();
    }
    public function getConfig($item,$key = false){
        $config = include_once("../config.php");
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