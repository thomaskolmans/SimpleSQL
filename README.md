# SimpelSQL
_A php library that makes SQL simpel._


# Installation
We recommend using `composor` to install our library. Look at how to install composor [here](https://getcomposer.org/)
```sh
composer require nytrix/simpelsql
```
You can also download the library and install it manually. Download button will be added when the first official release is there.

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



