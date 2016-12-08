<?php
namespace lib\Database;

class Database{
    
    public function __construct(){

    }

    public function backup(){
        create_backup_file();
    }

    public function create_backup_file($name = "backup"){
        $handle = fopen($name.".sql","w");
        return $handle;
    }
}