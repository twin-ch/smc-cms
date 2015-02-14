<?php

namespace base\helpers;

use db\mysqli as db;
use base\Model as Model;

class Comments
{
    protected static $_table = 'comments';
    protected static $_page_menu;
    
/** 
* Лента комментариев 
* @access public
* @param string $owner
* @param array $link
* @param int $id_parent
* @param int $num
* @return bool|array
*/  
    public static function read($owner, $link, $id_parent, $num)
    {
        if(true !== IRB_COMMENTS_JOIN)
        {
            self::$_table .= '_'. $owner;
            $cond = '';
        }
        else
            $cond = "\nAND `owner` = '". db::escape($owner) ."'";
        
        Model::setPaginatorLink($link);
        Model::setPaginatorConditions($cond);
        $result = Model::getPagination(self::$_table, $id_parent, $num);
        self::$_page_menu = Model::getPaginatorMenu();
        return $result; 
    }
    
/**
* Постраничка: генерация меню.
* @access public
* @return string 
*/ 
    public static function paginator()
    {
        return self::$_page_menu;
    }     
    
/** 
* Добавляем комментарий
* @access public
* @param array $data
* @return bool|string
*/      
    
    public static function add($data)
    {
        if(true !== IRB_COMMENTS_JOIN)
        {
            self::$_table .= '_'. $data['owner'];
            unset($data['owner']);
        }
     
        return Model::insertInto(self::$_table, $data);
    }

/** 
* Редактируем комментарий
* @access public
*/    
    public static function edit()
    {
       // В ТЗ задачи редактировать комменты не стояло
    }
    
/** 
* Удаляем комментарий
* @param string $owner
* @access public
*/  
    public static function delete($owner, $ids)
    {
        if(true !== IRB_COMMENTS_JOIN)
        {
            self::$_table .= '_'. $owner;            
            $condition    = '';
        }
        else
            $condition = "\n`owner` = '". db::escape($owner) ."' AND ";
     
        $condition .= "\n`id_parent` IN (". implode(',', db::intval($ids)) .")";
        
        return Model::deleteFrom(self::$_table, $condition);
    }
    
}


















