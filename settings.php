<?php

return array(
    /* Functions that can cause security issues */
    "table_drop" => false,
    "db_create"  => false,
    /* Only use this in development, it's smart to turn it off when not developing. */
    "PDO_errors" => true,  
    "SimpleSQl_errors" => true
    );
?>