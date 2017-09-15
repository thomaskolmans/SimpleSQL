<?php
require_once("autoloader.php");

$sql = new lib\SimpleSQL();

var_dump($sql->select("date","post_agenda",["id" => 1]));
echo "<br>";
var_dump($sql->select("*","post_agenda",["id" => 1]));