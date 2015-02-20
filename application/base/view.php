<?php

namespace base;

use library\IRB_Template as Template;
use library\IRB_Tree as Tree;
use library\IRB_URL as URL;
use base\helpers\Look as look;
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
        self::$tpl = new Template($template); 
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
            $category = self::_createRowsTree('/category/category_row', 'category_css', $result);
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
* @param int $pag_num
* @param string $key_ans
* @return void
*/    
    protected static function _createCommentsFor($owner, $data)
    { 
        $result    = Comments::readTree($owner, 
                                        $data['link'], 
                                        $data['id_parent'], 
                                        $data['pag_num']
                                        );
     
        $page_menu = Comments::paginator();
        self::$tpl->assign('page_menu', $page_menu); 
        
        if(!empty($result))
        {  
            $comments = self::_createRowsTree('/comment/comment_row', 
                                              'comment_css', 
                                              $result,
                                              $data['pag_num'],
                                              $data['id_ans']
                                              );
            
            self::$tpl->assign('comments', $comments);
            self::$tpl->setBlock('comments_rows');  
        }
        else
            self::$tpl->setBlock('comments_empty');
        
        if(!empty($data['id_ans']))
        {
            if(false !== ($collocutor = Comments::getCollocutor($data['id_ans'])))
            {
                self::$tpl->assign('collocutor', htmlChars($collocutor));
                self::$tpl->setBlock('answer');
            }
        }
        
        $return_data = array('author'  => iniPOST('author'),
                             'comment' => iniPOST('comment')
                             );
        
        self::$tpl->assign(htmlChars($return_data));
        self::$tpl->setBlock('comments_block');
    } 

/**
* Генерация простых рядов. 
* @access protected
* @param int $block
* @param array $result
* @return string 
*/ 
    protected static function _createRows($block, $result)
    {
        if(!empty($result))
        {
            $i = 0;
          
            foreach($result as $row)
            {
                $row['num'] = ++$i;
                self::$tpl->assign(htmlChars($row));
                self::$tpl->setBlock($block .'_rows');
            }
        }
        else
            self::$tpl->setBlock($block .'_empty');
    }  
    
/**
* Генерация рядов co вложенностью. 
* @access protected
* @param string $templ
* @param string $css
* @param array $result
* @param array $pag_num
* @param int $id_ans
* @return string 
*/ 
    protected static function _createRowsTree($templ, $css, $result, $pag_num = '', $id_ans = '')
    {
        $i = 0;
        $rows = array();
        $tpl  = new Template($templ);
     
        foreach($result as $row)
        { 
            $count  = URL::countParam();
            $sss    = empty($pag_num) ? 2 : 1;
            $offset = empty($id_ans)  ? $count + $sss : $count;
            $row['link1'] = URL::addParam(array($row['id']), $offset); 
           
            $tpl->assign(htmlChars($row));            
         
            $rows[$i]['rows']      = $tpl->parseTpl();
            $rows[$i]['id']        = $row['id'];
            $rows[$i]['id_shift']  = $row['id_shift'];
            ++$i;  
        }
        
        Tree::setting($css);
        return Tree::prepare($rows); 
    }     
    
}   























