<?php

namespace base;

use db\mysqli as db;
use library\IRB_Paginator as pgn;

class Model 
{
    protected static $_page_menu,
                     $_link_param = array();
/** 
* Статическая страница
* @access public
* @param string $page
* @return array
*/      
    public static function getStaticPage($page)
    {
        $page = preg_replace('~[^a-z0-9_]~', '', $page);
        $path = IRB_ROOT .'/static/'. $page .'.txt';
     
        if(false === ($result = @file_get_contents($path)))
            trigger_error('No file with the content on: '. $path, E_USER_WARNING);
        
        return $result;
    }

/** 
* Название категории
* @access public
* @param int $id
* @return string|void
*/      
    public static function getCategoryName($id)
    {
        $res = db::query("SELECT `name`
                              FROM `". IRB_CONFIG_DBPREFIX ."pages_category`
                                WHERE `id` = ".(int)$id
                        );
        $result = db::prepareResult($res);
        return !empty($result[0]) ? $result[0]['name'] : create404();
    }
    
/** 
* Дерево категорий 
* @access public
* @return string|bool
*/    
    public static function getTreeCategory()
    { 
        $res = db::query("SELECT *
                            FROM `". IRB_CONFIG_DBPREFIX ."pages_category`"
                         );
        
        return db::prepareResult($res);
    } 
  
/** 
* Запрос с постраничкой
* @access public
* @param string $table
* @param int $id_parent
* @param int $num
* @param bool $test
* @return bool|array
*/  
    public static function getPagination($table, $id_parent, $num, $test = false)
    {
        pgn::setLimitParam($num, IRB_CONFIG_ROWS);
        $where = !empty($id_parent) ? "WHERE `id_parent` = ".(int)$id_parent : '';
     
        $res = pgn::countQuery("SELECT *
                                  FROM `". IRB_CONFIG_DBPREFIX . $table ."`
                                    ". $where ."
                                      ORDER BY `id` DESC ",
                              $test);
      
        self::$_page_menu = pgn::createMenu(self::$_link_param);
     
        return db::prepareResult($res); 
    }
    
/**
* Постраничка: установка параметров ссылки.
* @access public
* @param array $param
* @return array 
*/      
    public static function setPaginatorLink($param = array())
    {
        return self::$_link_param = $param;
    }
    
/**
* Постраничка: генерация меню.
* @access public
* @return string 
*/ 
    public static function getPaginatorMenu()
    {
        return self::$_page_menu;
    } 
    
/** 
* Добавляем запись
* @access public
* @param string $table
* @param array $data
* @param bool $test
* @return bool|string
*/  
    public static function insertInto($table, $data, $test = false)
    {
        $res = db::query("INSERT INTO `". IRB_CONFIG_DBPREFIX . $table ."`
                            SET ". self::_prepareData($data), 
                        $test);
        
        $id = mysqli_insert_id(db::$link);
        return empty($id) ? getLanguage('FATAL_ERROR') : $id;
    }
    
/** 
* Редактируем запись
* @access public
* @param string $table
* @param int $id
* @param array $set
* @param bool $test
* @return bool|string
*/  
    public static function update($table, $id, $set, $test = false)
    {
        $res = db::query("UPDATE `". IRB_CONFIG_DBPREFIX . $table ."`
                            SET ". self::_prepareData($set) ."
                            WHERE `id` = ".(int)$id, 
                        $test);
     
        return !(bool)mysqli_affected_rows(db::$link) ? getLanguage('FATAL_ERROR') : false;
    }
    
/** 
* Удаляем запись
* @access public
* @param string $table
* @param array $cond
* @param bool $test
* @return bool|string
*/  
    public static function deleteFrom($table, $cond, $test = false)
    {
        $res = db::query("DELETE FROM `". IRB_CONFIG_DBPREFIX . $table ."`
                            WHERE ". $cond,
                        $test);
     
        return !(bool)mysqli_insert_id(db::$link) ? getLanguage('FATAL_ERROR') : false;
    } 

/**
* Подготовка данных
* @access protected
* @param array $data
* @return string 
*/        
    public static function _prepareData($data)
    { 
        $fields = array();
       
        if(!empty($data))
        {
            foreach($data as $field => $value)
            {
                $fields[] = "`". $field ."` = ". ((is_string($value) || is_float($value)) 
                          ? "'". db::escape($value) ."'" : (int)$value);
            }
        }
     
        return implode(",\n", $fields);
    }     
    
} 

    
    
    
    
    
    
    
    
