<?php

///////////////////////////////////////////////////////////////
//                  СИСТЕМНЫЕ НАСТРОЙКИ   
///////////////////////////////////////////////////////////////
/**
* Выбор языка
* @access public
* @param string $lang
* @return void 
*/
    function setLanguage($lang = IRB_CONFIG_LANGUAGE)
    {
        define('IRB_LANGUAGE',  $lang);
    }  

    setLanguage();
    
    define('IRB_ROOT', dirname(__DIR__));   
    define('IRB_APPLICATION_ROOT', IRB_ROOT .'/'. IRB_CONFIG_APPLICATION);
    define('IRB_CORE_ROOT',        IRB_ROOT .'/'. IRB_CONFIG_CORE);
    define('IRB_DIR_TEMPLATE',     IRB_ROOT .'/skins/'. IRB_LANGUAGE . '/tpl');
    define('IRB_HOST', 'http://'.  $_SERVER['HTTP_HOST']);

    if(true === IRB_CONFIG_DEBUG)
    {
        include IRB_CORE_ROOT .'/debug/debugger.php';
    }     
    
    include IRB_CORE_ROOT .'/library/irb_default.php';
    include IRB_CORE_ROOT .'/db/'. IRB_CONFIG_SQL_DRIVER .'.php';
/**
* Автозагрузка классов
* @access public
* @param string $classname
* @return void 
*/
    function __autoload($classname)
    {
        $classname = str_replace('\\', '/', $classname);
        $files[] = IRB_CORE_ROOT .'/'. strtolower($classname);
        $files[] = IRB_APPLICATION_ROOT .'/'. strtolower($classname);
       
        foreach ($files as $file)
        {
            if(file_exists($file .'.php'))
            {
                include_once $file .'.php';
                break;
            }  
        }   
    } 

  
    


    
    
    
    
    
    
    