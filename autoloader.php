<?php
    if(session_id() == '') {
        session_start();
    }
    spl_autoload_register(function($class){
        $new = str_replace("\\","/",$class);
        if(file_exists(__DIR__.'/'. $new . '.php')){
            require_once (__DIR__.'/'. $new . '.php'); 
            return true; 
        }
    }); 


?>