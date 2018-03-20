<?php
// load the autoloader and use SimpleSQl, and create a SimpleSQL instance; that's all you need!
use lib\SimpleSQl;
require_once("../autoloader.php");

$simpleSQL = new SimpleSQL();

/**
 *  Here we explain how you can select something from a database, easily in one line
 */

$simpleSQL->select("COLUMN","TABLE",["WHERE" => "EQUALS"]);

/**
*This is a very basic query, which will return an array of results. All function arguments are in the same order as queries would be. 
*Take for instance the example above, would be:
*
*SELECT "COLUMN" FROM  "TABLE" WHERE "key"=>"value"
*/

?>