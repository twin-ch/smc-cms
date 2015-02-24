<?php
namespace controllers;
//exit('Упс... Админка на хостинге недоступна. <a href="'. $_SERVER['HTTP_REFERER'] .'">Вернуться</a>.');


/**
Временная админка, только побаловаться. 
В ТЗ про админку не упоминали
*/



use base\helpers\Look as look;
use base\helpers\Validator as Validator;
use models\Admin_Model as Admin_Model;
use views\Admin_View as Admin_View;

class Admin_Controller
{
/**     
* Метод запуска контроллера 
* @access public  
* @param array $get
* @return void   
*/
    public static function run($get)
    {
        if(!look::checkRole('trusted'))
            create404();
       
        Admin_View::template('/admin'); 
        
        $action = $get['a'];
        $object = $get['b'];
        $method = $action . $object;
        self::$method($get);
        Admin_View::createInfo(getFlashData('info'));
        Admin_View::run();
    }
    
/**     
* Новая категория 
* @access public  
* @param array $get
* @return void   
*/
    public static function addCategory($get)
    {
        $cat_name = iniPOST('cat_name');
        $cid = '';
        
        if(!empty($_POST))
        { 
            if(false === ($info = Validator::validationCategory($cat_name)))
            {
                $info = $cid = Admin_Model::addCategory($cat_name);
                
                if(is_int($cid))
                {
                    $redirect = array('admin', 'edit', 'category', $cid); 
                    redirectFlash($redirect, false);
                }
            }
            
            Admin_View::createInfo($info);
        }
        
        Admin_View::createAddCategory($cat_name);
    }
    
/**     
* Редактируем категорию или доавляем подкатегорию
* @access public  
* @param array $get
* @return void   
*/
    public static function editCategory($get)
    { 
        $cid = $get['c'];
        $cat_name = iniPOST('cat_name');
        $sub_name = iniPOST('sub_name');     
     
        if(!empty($_POST))
        {          
            if(!empty($_POST['edit'])) 
            {
                if(false === ($info = Validator::validationCategory($cat_name)))
                    $info = Admin_Model::editCategory($cid, $cat_name);
            }
            elseif(!empty($_POST['add']))
            {
                if(false === ($info = Validator::validationCategory($sub_name)))
                    $info = $cid = Admin_Model::addCategory($sub_name, $cid); 
            }
            
            if(empty($info) || is_int($cid))
            {
                $redirect = array('admin', 'edit', 'category', $cid);
                redirectFlash($redirect, false);
            }
            
            Admin_View::createInfo($info);
        }
        
        Admin_View::createEditCategory($cid, $cat_name, $sub_name);
    }
    
/**     
* Удаляем категорию 
* @access public  
* @param array $get
* @return void   
*/
    public static function deleteCategory($get)
    { 
        $cid  = $get['c'];
        
        if(false === ($info = Admin_Model::deleteCategory($cid)))
        {
            $redirect = array('admin', 'add', 'category');
            redirectFlash($redirect, false);
        }
        
        redirectFlash('', false);
    }
    
/**     
* Новая страница 
* @access public 
* @param array $get
* @return void   
*/
    public static function addPage($get)
    {
        $cid   = $get['c'];
        $title = iniPOST('title');
        $text  = iniPOST('text');
     
        if(!empty($_POST))
        { 
            if(false === ($info = Validator::validationPage($title, $text)))
                $info = $pid = Admin_Model::addPage($cid, $title, $text);           
            
            if(empty($info) || is_int($pid))
            {
                $redirect = array('admin', 'edit', 'page', $pid, $cid);    
                redirectFlash($redirect, false);
            }
        }
        
        Admin_View::createAddPage($cid); 
    }    

/**     
* Редактируем страницу 
* @access public  
* @param array $get
* @return void   
*/
    public static function editPage($get)
    { 
        $pid = $get['c'];
        $cid = $get['d'];
        
        if(!empty($_POST))
        {
            $title = iniPOST('title');
            $text  = iniPOST('text');
            
            if(false === ($info = Validator::validationPage($title, $text)))
                $info = Admin_Model::editPage($pid, $title, $text);
          
            if(empty($info) || is_int($pid))
            {
                $redirect = array('admin', 'edit', 'page', $pid, $cid);    
                redirectFlash($redirect, false);
            }
        }
     
        Admin_View::createEditPage($cid, $pid);    
    }    
    
/**     
* Удаляем комментрий
* @access public  
* @param array $get
* @return void   
*/
    public static function deleteComment($get)
    {  
        $id  = iniPOST('delete');
        
        if(false === Admin_Model::deleteComment(key($id)))
            redirectFlash('', false);
    }  
    
/**     
* Удаляем страницу 
* @access public  
* @param array $get
* @return void   
*/
    public static function deletePage($get)
    {  
        $pid  = $get['c'];
        $cid  = $get['d'];
        
        if(false === Admin_Model::deletePage($pid))
        {
            $redirect = array('main', 'category', $cid);
            redirectFlash($redirect, true);
        }
    }      
    
/**     
* 404 
* @access public  
* @return void   
*/
    public static function __callStatic($name, $arg)
    { 
        create404(); 
    }
}
















