<?php

namespace views;

use base\helpers\look as look;
use models\Main_Model as Main_Model;
use base\view as View;

class Main_View extends View
{
  
/**
* Список страниц категории 
* @access public
* @param int $id_category
* @param int $num
* @return void
*/    
    public static function createListPages($id_category, $num)
    {   
        $category = Main_Model::getCategoryName($id_category);
        self::$tpl->assign('category', htmlChars($category));
        $result   = Main_Model::getListPages($id_category);                
        parent::_createRows('list', $result, look::checkRole('trusted'));        
        
        if(look::checkRole('trusted'))
            self::$tpl->setBlock('add_page');  
     
        self::$tpl->setBlock('list_pages');
        
        $link = array('main', 'category', $id_category);
        parent::_createCommentsFor('category', $link, $id_category, $num);
        parent::_createTreeCategory();
    }      
    
/**
* Конкретная страница
* @access public
* @param int $id_category
* @param int $id_page
* @param int $num
* @return void
*/    
    public static function createPageContent($id_category, $id_page, $num)
    { 
        $category = Main_Model::getCategoryName($id_category);
        self::$tpl->assign('category', htmlChars($category));
        $result   = Main_Model::getPageContent($id_page);
        
        if(!empty($result))
            self::$tpl->assign(htmlChars($result))->setBlock('page_content');
        else
            self::$tpl->setBlock('page_empty');
            
        $link = array('main', 'category', $id_category, 'page', $id_page);
        parent::_createCommentsFor('page', $link, $id_page, $num);
        parent::_createTreeCategory();
    }
}   











