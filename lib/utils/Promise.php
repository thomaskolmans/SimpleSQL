<?php

namespace lib\utils;

use Closure;
use ReflectionFunction;

class Promise{

    /**
     * Promise function
     * @var Clsoure
     */
    private $promise;

    /**
     * List of callables
     * @var array
     */
    private $callables = [];
    private $callable


    /**
     * Create a new promise
     * @param Closure $promise [description]
     */    
    public function __construct(Closure $promise){
        $this->promise = $promise;

        $r = function(){
            $this->callable = 
        };

        $rs = [];

        for($i = 0; $i < count($this->getClosureParameters($promise)); $i++){
            $rs[] = $r;
        }

        call_user_func($this->promise,...array_values($rs));
    }
    /**
     * When resolved, then is executed, so everyting within the closure gets then executed
     * @param  Closure $response [description]
     * @return [type]            [description]
     */
    public function then(Closure $response){
        if($this->resolve !== null){
            $this->callable[] = $response(...array_values($this->resolve));
        }
        return $this;
    }
    /**
     * When rejected, you can catch the error here
     * @param  Closure $error [description]
     * @return Promise promise object
     */
    public function catch(Closure $error){
        if($this->reject !== null){
            $this->callable[] = $error(...array_values($this->resolve));
        }
        return $this;
    }  
    /**
     * Always executed when called
     * @param  Closure $always [description]
     * @return Promise promise object
     */
    public function always(Closure $always){
        call_user_func($always,...array_values($this->getClosureParameters($always)));
        return $this;
    }
    /**
     * Get parameters of closure
     * @param  Closure $closure full closure
     * @return String[] Parameters of closure
     */
    private function getClosureParameters(Closure $closure){
        $reflection = new ReflectionFunction($closure);
        return $reflection->getParameters();
    }
}
?>