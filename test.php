<?php
use lib\SimpelSQL;
use lib\Database\SQL;
require_once("autoloader.php");

$sql = new SimpelSQL("primary");

echo $sql->select("name","application",array("version" => "0.2"));

?>

