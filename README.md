# SimpleSQL
_A php library that makes SQL simpel._ Build your applications much faster with *simple* and fast communication to your database. 

# Why?
SimpleSQL is a very lightweight library that will speed up your proccess of creating, selecting, updating and all your other SQL queries. It's a very easy syntax and has also multiple ways of doing your queries, there is a basic set of SQL functions that will work with the documented parameters, but then you can also *build* your own query through our building structure. 

# Installation
We recommend using `composor` to install our library. Look at how to install composor [here](https://getcomposer.org/)
```sh
composer require nytrix/simplesql
```
You can also download the library and install it manually. Download button will be added when the first official release is there.

# Features

- Common SQL queries in easy functions with understandable paramters. 
- SQL query builder, so you can make any query with just PHP functions
- Database backup that you can pass it on in other connections
- Multi-driver possibilities
- Mutliple connections available, and easy to communicate between those. 

# Usage
_Examples of SQL queries, simply in PHP._

**Create instance**<br>
Create your SimpleSQL instance, this will be used in the examples below. 
```php
<?php
    use lib\SimpleSQL;
    $sql = new SimpleSQL; 
?>
```
# Functions

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

**Create**
```php
$sql->create("table",array("id int auto_increment",name varchar(255)),"id");
```

**Exists**
```php
$sql->exists("table",array("where" => "equals"));
```

Full documentation you can find [**here**](https://github.com/thomaskolmans/SimpelSQL/blob/master/docs/README.md)

# License 

SimpelSQL is under the `MIT` license, read it [here](https://github.com/thomaskolmans/SimpelSQL/blob/master/LICENSE)



