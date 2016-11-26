<?php
namespace lib\Database;

use \PDO;
use Exception\InvalidInputException;

class Connection{

    public  $connection = null;

    private $host; 
    private $port;
    private $dbname;
    private $username;
    private $password;

    public function __construct($host,$dbname,$username,$password,$port ="4454"){

        $this->host         = $host;
        $this->dbname       = $dbname;
        $this->username     = $username;
        $this->password     = $password;
        $this->port         = $port;

    }

    public function open(){
        try{
            $this->connection = new PDO("mysql:host=".$this->host.";dbname=".$this->dbname."",$this->username,$this->password);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);            
        }catch(Exception $e){
            return false;
        }
        return $this->connection;
    }

    public function close(){
        return $this->connection = null;
    }

    public function is($state){
        switch($state){
            case "open":
                if($this->connection != null){
                    return true;
                }
                return false;
            break;
            case "closed":
                if($this->connection == null){
                    return true;
                }  
                return false;
            break;
            default:
                new InvalidInputException($state);
            break; 
        }
    }

    public function isOpen(){
        return $this->is("open");
    }

    public function isClosed(){
        return $this->is("clossed");
    }

?>