<?php

namespace base;

use db\db as db;
use library\IRB_URL as URL;

use base\helpers\Comments as Comments;
use base\helpers\validator as Validator;

class Model 
{
    protected static $_cond,
                     $_page_menu,
                     $_link_param = array(),
                     $_order = " ASC ";
/** 
* Статическая страница
* @access public
* @param string $page
* @return array
*/      
    public static function getStaticPage($page)
    {
        $page = preg_replace('~[^a-z0-9_]~', '', $page);
        $path = IRB_ROOT .'/contents/'. $page .'.txt';
     
        if(false === ($result = @file_get_contents($path)))
            trigger_error('No file with the content on: '. $path, E_USER_WARNING);
        
        return $result;
    }

/** 
* Добавляем комментарий 
* @access public
* @param string $owner
* @param int $id_parent
* @param string $author
* @param string $comment
* @param int $id_ans
* @return void
*/  
    public static function addComment($owner, $id_parent, $author, $comment, $id_ans = 0)
    {
        if(false !== ($info = Validator::validationComment($author, $comment)))
            return $info;
        
        $data = array('owner'      => $owner,
                      'id_parent'  => $id_parent,
                      'id_shift'   => $id_ans,
                      'author'     => $author, 
                      'text'       => $comment,
                    );
      
        if(Comments::add($data))    
            return getLanguage('FATAL_ERROR');
        
        $redirect = !empty($id_ans) ? URL::deleteParam(1, true) : URL::getParam();         
        redirectFlash($redirect, getLanguage('ADD_COMMETNT'));
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
        $result = db::fetchRow($res);
        return !empty($result) ? $result['name'] : create404();
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
     
        return db::fetchArray($res);
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
        
        $id = db::insertId();
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
     
        return !(bool)db::affectedRows() ? getLanguage('FATAL_ERROR') : false;
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
     
        return !(bool)db::insertId() ? getLanguage('FATAL_ERROR') : false;
    } 

    
    
/**
* Подготовка данных
* @access protected
* @param array $data
* @return string 
*/        
    protected static function _prepareData($data)
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

    
    
    
    
    
    
    
    
