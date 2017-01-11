<?php
if(!isset($_POST["name"])){
    select("*");
}else{
    select($_POST["name"]);
}
function select(){
     $query .= 'SELECT ' . (func_num_args() == 0 ? '*' : '`' . implode("`, `", func_get_args()) . '`').' FROM table ';
}