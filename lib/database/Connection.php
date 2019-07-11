<?php
namespace lib\Database;

use \PDO;
use lib\SimpleSQl;
use lib\exception\InvalidInputException;

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
            if(SimpleSQl::pdoErrors()){
                $this->connection->setAttribute(PDO::ATTR_EMULATE_PREPARES, false );
                $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
            }
        }catch(Exception $e){
            if(SimpleSQl::simpleSqlErrors()){
                throw new ConnectionExcpetion("Unable to connect, database authentication failed.");
            }
        }     
        return $this->connection;
    }

    public function close(){
        return $this->connection = null;
    }

    public function is($state){
        switch($state){
            case "open":
                return $this->connection != null;
            break;
            case "closed":
                return $this->connection == null;
            break;
            default:
                if(SimpleSQl::simpleSqlErrors()){
                    throw new InvalidInputException($state);
                }
            break; 
        }
    }

    public function isOpen(){
        return $this->is("open");
    }

    public function isClosed(){
        return $this->is("closed");
    }

    public function getConnection(){
        return $this->connection;
    }
}
?>