<?php
namespace lib\Database;

use lib\SimpleSQl;
use lib\Database\DatabaseGranualty;

class Database{

    private $sql;
    private $databases = [];

    public function __construct(){
        $this->sql = new SimpleSQl("primary");
        if(func_get_args() !== null){
            foreach(func_get_args() as $database){
                array_push($this->databases,$database);
            }
        }
    }

    public function backup($connection = "primary",int $granualty = DatabaseGranualty::TABLES){
        (string) $output = "";
        $tables = $this->sql->show_tables();
        switch($granualty){
            case 0:
                $database = $this->sql->show_databases();
            break;
            case 1:
                
            break;
            case 2:

            break;
        }
    }

    public function create_backup_file($data,$dir = "",$name = "backup"){
        return file_put_contents($dir."/".$name.".sql", $data);
    }
}