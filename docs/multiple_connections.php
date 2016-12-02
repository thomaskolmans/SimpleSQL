<?php
use lib\SimpelSQL;

require_once("autoloader.php");

$sql = new SimpelSQL("secondary"); 

// You can add as many connections as you want, as long as they are in the configuration, under the name you want it to have, and the connecion data. 

?>