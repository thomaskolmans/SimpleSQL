<?php
// load the autoloader and use SimpleSQl, and create a SimpleSQL instance; that's all you need!
use lib\SimpleSQl;
require_once("../autoloader.php");

$simpleSQL = new SimpleSQL();

$simpleSQL->create("TABLE_NAME", [
	"test_column" => "varchar(255)"
], "test_column");