<?php

use library\IRB_URL as URL;

class Router
{

/**     
* Метод запуска приложения 
* @access public  
* @return void   
*/ 

    public static function run()
    { 
        URL::setRewrite(IRB_CONFIG_REWRITE);
        URL::setHost(IRB_HOST);
        
        $page = URL::iniGET('page', 'empty');
        $name = preg_replace('~[^a-z0-9_]~', '', $page);
     
        if(__METHOD__ !== __CLASS__ .'::'. $name)
            self::$name(URL::prepareGET());
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

