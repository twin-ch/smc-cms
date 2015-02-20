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
* @param int $pag_num
* @param int $id_ans
* @return void
*/    
    public static function createListPages($id_category, $pag_num, $id_ans)
    {   
        $category = Main_Model::getCategoryName($id_category);
        self::$tpl->assign('category', htmlChars($category));
        $result   = Main_Model::getListPages($id_category);                
        parent::_createRows('list', $result, look::checkRole('trusted'));        
        
        if(look::checkRole('trusted'))
            self::$tpl->setBlock('add_page');  
     
        self::$tpl->setBlock('list_pages');
        
        $link      = array('main', 'category', $id_category);
        $id_parent = $id_category;        
        $data = compact('link', 'id_parent', 'pag_num', 'id_ans');
        parent::_createCommentsFor('category', $data);
        parent::_createTreeCategory();
    }      
    
/**
* Конкретная страница
* @access public
* @param int $id_category
* @param int $id_page
* @param int $pag_num
* @param int $id_ans
* @return void
*/    
    public static function createPageContent($id_category, $id_page, $pag_num, $id_ans)
    { 
        $category = Main_Model::getCategoryName($id_category);
        self::$tpl->assign('category', htmlChars($category));
        $result   = Main_Model::getPageContent($id_page);
        
        if(!empty($result))
            self::$tpl->assign(htmlChars($result))->setBlock('page_content');
        else
            self::$tpl->setBlock('page_empty');
            
        $link      = array('main', 'category', $id_category, 'page', $id_page);
        $id_parent = $id_category;        
        $data = compact('link', 'id_parent', 'pag_num', 'id_ans');
        parent::_createCommentsFor('page', $data);
        parent::_createTreeCategory();
    }
}   











