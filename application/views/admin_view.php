<?php

namespace views;

use models\Admin_Model as Admin_Model;
use base\view as View;

class Admin_View extends View
{

/**
* Новая категория 
* @access public
* @param string $cat_name
* @return void
*/    
    public static function createAddCategory($cat_name)
    {
        self::$tpl->assign('cat_name', htmlChars($cat_name));
        self::$tpl->setBlock('new_category');
        parent::_createTreeCategory();
    }
    
/**
* Редактирование категории 
* @access public
* @param int $id_category
* @param string $sub_name
* @return void
*/    
    public static function createEditCategory($id_category, $sub_name)
    {
        $category = Admin_Model::getCategoryName($id_category);
        self::$tpl->assign('cat_name', htmlChars($category));
        self::$tpl->assign('sub_name', htmlChars($sub_name));
        self::$tpl->setBlock('edit_category');
        parent::_createTreeCategory();
    } 
    
/**
* Новая страница  
* @access public
* @param int $id_category
* @return void
*/    
    public static function createAddPage($id_category)
    {
        $category = Admin_Model::getCategoryName($id_category);
        self::$tpl->assign('category', htmlChars($category));
        self::$tpl->setBlock('new_page');     
        self::$tpl->setBlock('add_page');        
        parent::_createTreeCategory();
    }  
    
   
    
/**
* Новая страница  
* @access public
* @param int $id_category
* @param int $id_sheet
* @return void
*/    
    public function createEditPage($id_category, $id_sheet)
    {  
        $category = Admin_Model::getCategoryName($id_category);
        self::$tpl->assign('category', htmlChars($category));
        $result   = Admin_Model::getPageContent($id_sheet);
        self::$tpl->assign(htmlChars($result));
        self::$tpl->setBlock('text_page');    
        self::$tpl->setBlock('add_page');        
        parent::_createTreeCategory();
    }  
}   

























