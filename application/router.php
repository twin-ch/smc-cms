<?php

class Router
{

/**     
* Метод запуска приложения 
* @access public  
* @return void   
*/ 

    public static function run()
    { 
        $page = iniGET('page', 'empty');
        $name = preg_replace('~[^a-z0-9_]~', '', $page);
     
        if(__METHOD__ !== __CLASS__ . '::' . $name)
            self::$name(prepareGET());
        else
            create404();
    }
    
/**     
* Подхватываем контроллер (метод перегрузки)
* @access public  
* $param string $name, $arg
* @return void   
*/    
    public static function __callStatic($name, $arg)
    {
        $controller = 'controllers\\'. $name .'_controller';
        
        if(class_exists($controller))
            $controller::run($arg[0]);
        else
            create404();
    }
}