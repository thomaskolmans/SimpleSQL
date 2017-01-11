<?php

return array(
    /* Functions that can cause security issues */
    "table_drop" => false,
    "db_create"  => false,
    
    "PDO_errors" => true,  /* Only use this in development, it's smart to turn it off when not developing. */
    "SimpelSQL_errors" => true
    );
?>