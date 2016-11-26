# SimpelSQL
_A php library that makes SQL simpel._


# Installation


# Usage
```php
<?php
    use lib\Database\SQL;
    
    SQL::getInstance()->create("users",array(
        "id" => "int",
        "username" => "varchar(255)",
        "password" => "varchar(255)",
     ),"id");
?>
```





**Copyright - 2016 Thomas Kolmans. All rights reserved.**
