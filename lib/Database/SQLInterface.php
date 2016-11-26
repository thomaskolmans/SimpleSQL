<?php
namespace lib\database;

interface SQLInterface{

    public function __construct();
    public static function getInstance();

    private function where();
}

?>