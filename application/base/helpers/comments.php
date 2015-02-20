<?php

namespace base\helpers;

use db\mysqli as db;
use base\Model as Model;

class Comments
{
    protected static $_table       = 'comments';
    protected static $_table_users = 'users';
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
    public static function readTape($owner, $link, $id_parent, $num)
    {    
        if(true !== IRB_COMMENTS_JOIN)
        {
            self::$_table .= '_'. $owner;
            $cond = '\nAND `id_shift` = 0';
        }
        else
            $cond = "\nAND `owner` = '". db::escape($owner);
     
        Model::setPaginatorLink($link);
        Model::setPaginatorConditions($cond);
        $result = Model::getPagination(self::$_table, $id_parent, $num);
        self::$_page_menu = Model::getPaginatorMenu();
        return $result;
    }
    
/** 
* Дерево комментариев 
* @access public
* @param string $owner
* @param array $link
* @param int $id_parent
* @param int $num
* @return bool|array
*/  
    public static function readTree($owner, $link, $id_parent, $num)
    {
        if(true !== IRB_COMMENTS_JOIN)
        {
            self::$_table .= '_'. $owner;
            $cond = '\nAND `id_shift` = 0';
        }
        else
            $cond = "\nAND `owner` = '". db::escape($owner) ."'\nAND `id_top` = 0";
        
        Model::setPaginatorLink($link);
        Model::setPaginatorConditions($cond);
        $result = Model::getPagination(self::$_table, $id_parent, $num);
        self::$_page_menu = Model::getPaginatorMenu();
        
        foreach($result as $row)
            $ids[] = (int)$row['id'];
     
        if(empty($ids))
            return false;
        
        $res = db::query("SELECT *
                            FROM `". IRB_CONFIG_DBPREFIX . self::$_table ."`
                            WHERE (`id`       IN (". implode(',', $ids) .")
                                OR `id_top`   IN (". implode(',', $ids) ."))"
                             );
        
        return  db::prepareResult($res);
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
* Достаем имя собеседника (на чей комментарий отвечаем)
* @access public
* @param array $data
* @return bool|string
*/      
    
    public static function getCollocutor($id)
    {
        $res = db::query("SELECT `author`
                            FROM `". IRB_CONFIG_DBPREFIX . self::$_table ."`
                            WHERE `id` = ".(int)$id 
                           );
        $row = db::prepareResult($res); 
        return empty($row) ? false : $row[0]['author'];
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
        
        $res = db::query("SELECT `id_top`
                            FROM `". IRB_CONFIG_DBPREFIX . self::$_table ."`
                            WHERE `id` = ".(int)$data['id_shift'] 
                           );
        
        $row = db::prepareResult($res);
        $data['id_top'] = !empty($row[0]['id_top']) ? $row[0]['id_top'] : $data['id_shift'];
        $id  = Model::insertInto(self::$_table, $data);
        return empty($id);
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


















