# SimpleSQL
_A php library that makes SQL simpel._

# Why SimpleSQL?

SimpleSQL is a very lightweight library that will speed up your proccess of creating, selecting, updating and all your other SQL queries. It's a very easy syntax and has also multiple ways of doing your queries, there is a basic set of SQL functions that will work with the documented parameters, but then you can also *build* your own query through our building structure. 

# Installation
We recommend using `composor` to install our library. Look at how to install composor [here](https://getcomposer.org/)
```sh
composer require nytrix/simplesql
```
You can also download the library and install it manually. Download button will be added when the first official release is there.

# Usage

Examples of SQL queries, made simple in PHP. 

**Create instance**
```php
<?php
    use lib\SimpleSQL;
    $sql = new SimpleSQL; 
?>
```
This is the variable used for the other functions

**Select**

```php
$sql->select("table","column",array("where" => "equals"));
```

**Update**

```php
$sql->update("table","column",array("where" => "equals"),"to");
```

**Insert**
```php
$sql->insert("table",array("value1","value2","value3"));
```

Full documentation you can find [here](https://github.com/thomaskolmans/SimpelSQL/blob/master/docs/README.md)

# License 

SimpelSQL is under the `MIT` license, read it [here](https://github.com/thomaskolmans/SimpelSQL/blob/master/LICENSE)



