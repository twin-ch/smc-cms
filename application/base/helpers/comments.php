<?php

namespace base\helpers;

use db\db as db;
use base\Model as Model;
use base\helpers\Paginator as Paginator;

class Comments
{
    protected static $_table       = 'comments';
    protected static $_mod         = 'comments';
    protected static $_table_users = 'users';
    protected static $_page_menu;

/** 
* Лента комментариев 
* @access public
* @param string $owner
* @param array $link
* @param int $id_parent
* @param int $pag_num
* @return bool|array
*/  
    public static function readTape($owner, $link, $id_parent, $pag_num)
    {    
        if(true !== IRB_COMMENTS_JOIN)
        {
            self::$_table .= '_'. $owner;
            $cond = '\nAND `id_shift` = 0';
        }
        else
            $cond = "\nAND `owner` = '". db::escape($owner);
     
        $where = !empty($id_parent) ? " `id_parent` = ".(int)$id_parent : '1';
        
        return self::_getPagination($where, $cond, $pag_num);
    }
    
/** 
* Дерево комментариев 
* @access public
* @param string $owner
* @param array $link
* @param int $id_parent
* @param int $pag_num
* @return bool|array
*/  
    public static function readTree($owner, $link, $id_parent, $pag_num)
    {
        if(true !== IRB_COMMENTS_JOIN)
        {
            self::$_table .= '_'. $owner;
            $cond = '\nAND `id_shift` = 0';
        }
        else
            $cond = "\nAND `owner` = '". db::escape($owner) ."'\nAND `id_top` = 0";
         
        $where = !empty($id_parent) ? " `id_parent` = ".(int)$id_parent : '1';
        
        $result = self::_getPagination($where, $cond, $pag_num);
        
        foreach($result as $row)
            $ids[] = (int)$row['id'];
     
        if(empty($ids))
            return false;
        
        $res = db::query("SELECT *
                            FROM `". IRB_CONFIG_DBPREFIX . self::$_table ."`
                            WHERE (`id`       IN (". implode(',', $ids) .")
                                OR `id_top`   IN (". implode(',', $ids) ."))"
                             );
        
        return  db::fetchArray($res);
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
        $row = db::fetchRow($res); 
        return empty($row) ? false : $row['author'];
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
        
        $row = db::fetchRow($res);
        $data['id_top'] = !empty($row['id_top']) ? $row['id_top'] : $data['id_shift'];
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
* @access public
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
     
        $condition .= "\n`id_parent` IN (". db::implodeInt($ids) .")";
        
        return Model::deleteFrom(self::$_table, $condition);
    }
    
/** 
* Постраничка
* @access protected
* @param string $owner
* @access public
*/  
    protected static function _getPagination($where, $cond, $pag_num)
    {

        $config = \Config::get(self::$_mod);
        
        Paginator::setLimitParam($pag_num, $config['num_pag']);
        $res = Paginator::countQuery("SELECT *
                                       FROM `". IRB_CONFIG_DBPREFIX . self::$_table ."`
                                        WHERE ". $where . $cond ."
                                          ORDER BY `id` ASC"
                                    );    
            
        self::$_page_menu = Paginator::createMenu($config['key_pag']);
        return db::fetchArray($res);
    }    
    
    
}


















