<?php

/** 
* @TODO To clean in release 
*/
    $start = microtime(true);
////////////////////////////////////////


    header("Content-Type: text/html; charset=utf-8"); 
    error_reporting(-1);      
    session_start();
    
    include __DIR__ .'/config/main.php';  
    include __DIR__ .'/config/system.php'; 
    
    Router::run();   
   

/** 
* @TODO To clean in release 
*/
    echo '<br /><br /><br />';
    echo 'Время генерации страницы: '. sprintf("%01.4f", microtime(true) - $start) .'<br />';
    echo 'Количество подключенных файлов: '. count(get_included_files()) .'<br />';
    echo 'Количество запросов: '. db\db::$count .'<br />';

   
dbg(get_included_files());
    
/////////////////////////////////////////////    
    




















    
    