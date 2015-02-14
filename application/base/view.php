<?php

namespace base;

use library\IRB_Template as IRB_Template;
use base\helpers\Look as look;
use base\helpers\Tree as Tree;
use base\helpers\Comments as Comments;
use base\model as Model;

class View 
{
    protected static $tpl;
    
/**
* Установка шаблона
* @access public
* @param string $template
* @return void
*/
    public static function template($template)
    {
        self::$tpl = new IRB_Template($template); 
    }
    
/**
* Вывод сообщения 
* @access public
* @param string $info
* @return void
*/    
    public static function createInfo($info)
    {  
        if(empty($info))
            $info = getFlashData('info');
        
        if(!empty($info))
            self::$tpl->assign('info', $info)->setBlock('info');
    }
    
/**
* Рендер
* @access public
* @return void 
*/ 
    public static function run()
    { 
        self::$tpl->extendsTpl('/index', 'content')->display();
    } 

/**
* Статические страницы 
* @access public
* @param string $page
* @return void
*/    
    public static function createStaticPage($page = 'main')
    {   
        $result = Model::getStaticPage($page);
        self::$tpl->assign($page, $result)->setBlock($page);
        self::_createTreeCategory();
    }      
    
/** 
* Дерево категорий 
* @access protected
* @return void
*/    
    protected static function _createTreeCategory()
    {
        if(look::checkRole('trusted'))
            self::$tpl->setBlock('category_add');
     
        $result = Model::getTreeCategory();
       
        if(!empty($result))
        {
            $category = Tree::prepareCategory($result); 
            self::$tpl->assign('category', $category);
            self::$tpl->setBlock('category_rows');             
        }
        else
            self::$tpl->setBlock('category_empty');
     
        self::$tpl->setBlock('category_tree');
    }     
    
/**
* Комментарии
* @access protected
* @param string $owner
* @param array $link
* @param int $id_parent
* @param int $num
* @return void
*/    
    protected static function _createCommentsFor($owner, $link, $id_parent, $num)
    {  
        $result    = Comments::read($owner, $link, $id_parent, $num);
        $page_menu = Comments::paginator();
        self::$tpl->assign('page_menu', $page_menu);        
        self::_createRows('comments', $result);
        
        $return_data = array('author'  => iniPOST('author'),
                             'comment' => iniPOST('comment')
                             );
        self::$tpl->assign(htmlChars($return_data));
        self::$tpl->setBlock('comments_block');
    } 

/**
* Генерация рядов. 
* @access protected
* @param int $block
* @param array $result
* @param bool $admin
* @return string 
*/ 
    protected static function _createRows($block, $result, $admin = false)
    {
        if(!empty($result))
        {
            $i = 0;
          
            foreach($result as $row)
            {
                $row['num'] = ++$i;
                self::$tpl->assign(htmlChars($row));
                
                if($admin)
                    self::$tpl->setBlock('admin');
                
                self::$tpl->setBlock($block .'_rows');
                
                if($admin)
                    self::$tpl->clearBlock('admin');
            }
        }
        else
            self::$tpl->setBlock($block .'_empty');
    }  
}   











