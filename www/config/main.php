<?php

///////////////////////////////////////////////////////////////
//                  ГЛОБАЛЬНЫЕ НАСТРОЙКИ
///////////////////////////////////////////////////////////////
// Режим отладки
    define('IRB_CONFIG_DEBUG',        true);    
    define('IRB_CONFIG_EXCEPTION',    true);

// Включает ЧПУ (вид ссылок)  
    define('IRB_CONFIG_REWRITE',      true);

// Дефолтный язык приложения   
    define('IRB_CONFIG_LANGUAGE',     'ru');
 
// Путь до приложения относительно корня сайта  
    define('IRB_CONFIG_APPLICATION',  '../application');
    
// Путь до ядра относительно корня сайта  
    define('IRB_CONFIG_CORE',         '../core');
    
// Хранение комментариев в общей таблице
    define('IRB_COMMENTS_JOIN',        true);

///////////////////////////////////////////////////////////////
//                  НАСТРОЙКИ MYSQL
///////////////////////////////////////////////////////////////    
/**
* Префикс таблиц БД.
* Сервер БД. 
* Пользователь БД
* Пароль БД
* Название базы
* Драйвер SQL
*/   
    define('IRB_CONFIG_DBPREFIX',       'irb_'); 
    define('IRB_CONFIG_DBSERVER',       'localhost');
    define('IRB_CONFIG_DBUSER',         'root');     
    define('IRB_CONFIG_DBPASSWORD',     '');
    define('IRB_CONFIG_DATABASE',       'cms');
    define('IRB_CONFIG_SQL_DRIVER',     'mysqli');   
    
    
