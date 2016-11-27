<?php

use lib\SimpelSQL;
use lib\Database\SQL;
require_once("autoloader.php");

$sql = new SimpelSQL();
$sql::insert("application","example","0.2","live");

?>