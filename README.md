# SimpelSQL
_A php library that makes SQL simpel._


# Installation
We recommend using `composoer` to install our library. 
```sh
composer require nytrix/simpelsql
```
You can also download the library and install it manually. 

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

# License 

SimpelSQL is under the `MIT` license, read it [here](https://github.com/thomaskolmans/SimpelSQL/blob/master/LICENSE)



