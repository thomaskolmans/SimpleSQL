<?php
namespace lib\sql;

class RawQuery {
    
    public $query, $values;

    public function __construct($query){
        $this->values = $query;
        $this->query = array_shift($this->values);
        $this->decode();
    }

    public function decode(){
        $this->findPrepared();
    }

    public function findPrepared(){
        preg_match_all("/(?=:).+?(?= )/",$this->query,$prepared);
        var_dump($prepared);
    }
}