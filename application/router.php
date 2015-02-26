<?php

use library\IRB_URL as IRB_URL;

class Router
{

/**     
* Метод запуска приложения 
* @access public  
* @return void   
*/ 

    public static function run()
    { 
        IRB_URL::setRewrite(IRB_CONFIG_REWRITE);
        IRB_URL::setHost(IRB_HOST);
        
        $routes = IRB_URL::iniGET('page', 'empty');
        $routes = preg_replace('~[^a-z0-9_]~', '', $routes); 
     
        if(__METHOD__ !== __CLASS__ .'::'. $routes)
            self::$routes(IRB_URL::prepareGET());
        else
            create404();
    }
    
/**     
* Подхватываем контроллер (метод перегрузки)
* @access public  
* @param string $name, $arg
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

