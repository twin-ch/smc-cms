<?php

namespace models;

use db\mysqli as db;
use base\helpers\Comments as Comments;
use base\Model as Model;


class Admin_Model extends Model
{
    protected static $_id_cat = array();
    
/** 
* Новая категория
* @access public
* @param string $name
* @param int $id_parent
* @return bool|int
*/      
    public static function addCategory($name, $id_parent = 0)
    {
        $data = array('name' => $name);
        
        if(!empty($id_parent))
            $data['id_parent'] = $id_parent;
            
        return parent::insertInto('pages_category', $data);
    } 
    
/** 
* Редактируем категорию
* @access public
* @param int $id
* @param string $name
* @return bool|int
*/      
    public static function editCategory($id, $name)
    {
        $set = array('name' => $name);
        return parent::update('pages_category', $id, $set);
    } 
    
/** 
* Удаляем категорию
* @access public
* @param int $id
* @return bool|string
*/      
    public static function deleteCategory($id)
    {
        $id_pages = array();
        
        db::query('START TRANSACTION');
     
        $res = db::query("SELECT `id`, `id_parent`
                           FROM `". IRB_CONFIG_DBPREFIX ."pages_category`"
                           );
     
        while($row = mysqli_fetch_assoc($res))
            $ids[$row['id']] = $row['id_parent'];
        
        self::_setTreeCategory($ids, $id); 
        self::$_id_cat[] = $id;
        
        $res = db::query("SELECT `id` 
                           FROM `". IRB_CONFIG_DBPREFIX ."pages`
                           WHERE `id_parent` IN (". implode(',', db::intval(self::$_id_cat)) .")"
                           );
     
        while($row = mysqli_fetch_assoc($res))
            $id_pages[] = $row['id']; 
     
        $cond = "`id` IN (". implode(',', db::intval(self::$_id_cat)) .")";
        
        if(false === parent::deleteFrom('pages_category', $cond))
            return self::_rollback();
        
        if(!empty($id_pages))    
        {
            if(false === parent::deleteFrom('pages', $cond))
                return self::_rollback();
            
            if(false === Comments::delete('page', $id_pages))
                return self::_rollback();
        }
        
        if(false === Comments::delete('category', self::$_id_cat))
            return self::_rollback();
     
        db::query('COMMIT');
        return false;   
    } 
    
/** 
* Просмотр страницы
* @access public
* @param int $id
* @return string|void
*/      
    public static function getPageContent($id)
    {
        $res = db::query("SELECT *
                              FROM `". IRB_CONFIG_DBPREFIX ."pages`
                                WHERE `id` = ".(int)$id
                        );
        
        $result = db::prepareResult($res);
        return !empty($result[0]) ? $result[0] : create404();
    }   
    
/**  
* Добавляем страницу
* @access public
* @param int $id_parent
* @param string $title
* @param string $text
* @return bool|array
*/      
    public static function addPage($id_parent, $title, $text)
    {
        $data = array('id_parent' => $id_parent, 
                      'title'     => $title, 
                      'text'      => $text
                      );
        
        return parent::insertInto('pages', $data);
    }
    
/** 
* Редактируем страницу
* @access public
* @param int $id
* @param string $title
* @param string $text
* @return bool|array
*/      
    public static function editPage($id, $title, $text)
    {
        $set = array('title' => $title, 
                     'text'  => $text
                     );
        
        return parent::update('pages', $id, $set);
    }
    
/** 
* Удаляем страницу
* @access public
* @param int $id
* @return void
*/      
    public static function deletePage($id)
    {
        db::query('START TRANSACTION'); 
        
        if(false === Comments::delete('page', array($id)))
            return getLanguage('FATAL_ERROR');
        
        $cond = '`id` ='.(int)$id;
        
        if(false === parent::deleteFrom('pages', $cond))
            return self::_rollback();
        
        db::query('COMMIT');
        return false;
    } 
    
/** 
* Рекурсивно достаем все подкатегории
* @access protected
* @param array $data
* @param int $parent
* @return void
*/      
    protected static function _setTreeCategory($data, $parent)
    {
        $arr = array();
        
        foreach($data as $id => $id_parent)
        {
            if($id_parent == 0)
                continue;
         
            if($id_parent != $parent && $id != $parent)
                continue;
            
            if(empty(self::$_id_cat[$id]))
                $arr[$id] = $id_parent;    
        }
      
        foreach($arr as $id => $id_parent)
        {  
            self::$_id_cat[$id] = $id;
            self::_setTreeCategory($data, $id);
        }
    } 
    
/** 
* Возврат транзакции
* @access protected
* @return string
*/      
    protected static function _rollback()
    {
        db::query('ROLLBACK');
        return getLanguage('FATAL_ERROR');
    }
} 

    
    
    
    
    
    
    
    
