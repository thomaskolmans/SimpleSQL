<?php
// load the autoloader and use SimpleSQl, and create a SimpleSQL instance; that's all you need!
use lib\SimpleSQl;
require_once("../autoloader.php");

$simpleSQL = new SimpleSQL();

/**

You can also start a secondary connection to another database, you can define the settings in the config.php

In the config there has been a connection defined named "secondary", you can create that instance like here below.
**/

$secondary = new SimpleSQL("secondary");