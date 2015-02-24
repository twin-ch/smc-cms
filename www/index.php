<?php
/** 
* @TODO To clean in release 
*/
    $start = microtime(true);
////////////////////////////////////////


    header("Content-Type: text/html; charset=utf-8"); 
    error_reporting(E_ALL & ~E_STRICT);      
    session_start();
session_destroy();    
    include __DIR__ .'/config/config.php';  
    include __DIR__ .'/config/system.php'; 
    
    Router::run();   
   

/** 
* @TODO To clean in release 
*/
    echo '<br /><br /><br />';
    echo 'Время генерации страницы: '. sprintf("%01.4f", microtime(true) - $start) .'<br />';
    echo 'Количество подключенных файлов: '. count(get_included_files()) .'<br />';
    echo 'Количество запросов: '. db\mysqli::$count .'<br />';

   
dbg(get_included_files());
    
/////////////////////////////////////////////    
    




















    
    