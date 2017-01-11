<?php
use lib\SimpelSQL;

require_once("autoloader.php");

$sql = new SimpelSQL();
$table = "table";
$sql->query("SELECT * FROM :table",$table);
?>