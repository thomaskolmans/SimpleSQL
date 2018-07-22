<?php

return array(
    /* Functions that can cause security issues */
    "table_drop" => true,
    "db_create"  => false,
   	"table_create" => false,

    /* Only use this in development, should be turned of in production. */
    "PDO_errors" => true,  
    "SimpleSQl_errors" => true,

    /* Migration settings*/
    "migration" => true,
    "migration_clean" => false
    );
?>