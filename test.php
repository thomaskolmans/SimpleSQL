<?php
use lib\SimpelSQL;
use lib\Database\SQL;
require_once("autoloader.php");

$sql = new SimpelSQL("primary");

echo $sql->query()->select("name")->from("application")->where("name","Nytrix")->execute();

?>