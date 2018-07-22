<?php
// load the autoloader and use SimpleSQl and create a SimpleSQL instance.
use lib\SimpleSQl;

require_once("autoloader.php");

$simpleSQL = new SimpleSQL();
$simpleSQL->migration->clean();
$simpleSQL->migration->migrate();
?>