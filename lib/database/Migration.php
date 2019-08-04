<?php
namespace lib\database;

use lib\SimpleSQL;
use lib\exception\MigrationException;

class Migration{

    private $simplesql;
    private $migrations;

    private $version;
    private $description;
    private $type;
    private $file;

    public $message;

    public function __construct(SimpleSQL $simplesql){
        $this->simplesql = $simplesql;
    }

    public function findFiles(){
        $migration_dir = SimpleSQL::$root.'/migration';
        if (file_exists($migration_dir) && is_dir($migration_dir)) {
            return scandir($migration_dir);
        }else{
            throw new MigrationException("Migration folder does not exist");
        }
    }

    public function migrate(){
        $this->createMigrationTable();
        $this->getMigrations();
        $files = $this->findFiles();
        foreach($files as $file){
            if(file_exists(SimpleSQL::$root.'/migration/'.$file) && !is_dir($file) && $this->validFilename($file)){
                if($this->lastMigration()["version"] < $this->version){
                    $this->insertMigration(
                        $this->version,
                        $this->description,
                        $this->type,
                        $this->file,
                        $this->type === "PHP" ? $this->executePhpMigration($file) : $this->executeSqlMigration($file)
                    );
                }
            }
        }
    }

    public function clean(){
        $tables = $this->simplesql->show_tables();
        $this->simplesql->execute("SET GLOBAL FOREIGN_KEY_CHECKS=0;");
        foreach ($tables as $table) {
            $this->simplesql->drop($table[0]);
        }
        $this->simplesql->execute("SET GLOBAL FOREIGN_KEY_CHECKS=1;");
    }

    private function getMigrations(){
        if(!$this->simplesql->exists("simplesql_migration", [])){
            $this->migrations = $this->simplesql->select("*","simplesql_migration",[]);
        }
    }

    private function lastMigration(){
        if($this->migrations == null){
            return [
                "version" => 0
            ];
        }
        return end($this->migrations);
    }

    private function validFilename($filename){
        $file = pathinfo(SimpleSQL::$root.'/migration/'.$filename);
        $this->type = strtoupper($file["extension"]);
        $this->file = $file["filename"];
        if($filename[0] === "V" && strpos($file["filename"], '__') !== false){
            $parts = explode("__",$file["filename"]);
            if(count($parts) == 2){
                $this->version = substr($parts[0], 1);
                $this->description = $parts[1];
                return true;
            }

        }
        return false;
    }

    private function executeSqlMigration($file){
        try {
            $sql = file_get_contents(SimpleSQL::$root.'/migration/'.$file);
            $statements = explode(";", $sql);
            foreach($statements as $statement){
                if(!empty($statement)){
                    $this->simplesql->execute(trim($statement));
                }
            }
            return true;
        } catch(Exception $e){
            return false;
        }
    }
    
    private function executePhpMigration($file) {
        try {
            include(SimpleSQL::$root.'/migration/'.$file);
            return true;
        } catch (Exception $e){
            return false;
        }
    }

    private function insertMigration($version,$description,$type,$script,$success){
        $datetime = new \DateTime();
        $this->simplesql->insert("simplesql_migration",[0,$version,$description,$type,$script,$datetime->format("Y-m-d H:i:s"),$success]);
    }

    public function createMigrationTable(){
        if(!$this->simplesql->exists("simplesql_migration", [])){
            $this->simplesql->create("simplesql_migration",[
                "installed_rank" => "int auto_increment",
                "version" => "int",
                "description" => "varchar(255)",
                "type" => "varchar(255)",
                "script" => "varchar(255)",
                "installed_on" => "DateTime",
                "success" => "boolean"
            ],"installed_rank");
        }
    }
    
}

?>