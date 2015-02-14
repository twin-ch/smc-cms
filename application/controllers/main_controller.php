<?php 

namespace controllers;

use base\helpers\validator as Validator;
use models\Main_Model as Main_Model;
use views\Main_View as Main_View;

class Main_Controller
{

/**     
* Метод запуска контроллера 
* @access public 
* @param array $get
* @return void   
*/
    public static function run($get)
    {   
        Main_View::template('/main'); 
        
        $method = 'action'. $get['a'];
        self::$method($get);
        
        Main_View::createInfo(getFlashData('info'));        
        Main_View::run();
    }
    
/**     
* Акшен списка страниц категории 
* @access public 
* @param array $get
* @return void   
*/
    public static function actionCategory($get)
    {
        $cid = $get['b'];
        $num = $get['c'];
        self::_addComment('category', $cid);
        Main_View::createListPages($cid, $num);
    } 

/**     
* Акшен выбранной страницы 
* @access public  
* @param array $get
* @return void   
*/
    public static function actionPage($get)
    {
        $pid = $get['b'];
        $cid = $get['c'];
        $num = $get['d'];
        self::_addComment('page', $pid);
        Main_View::createPageContent($cid, $pid, $num);
    }    
    
/**     
* Акшен главной страницы (дефолтно) 
* @access public
* @return void   
*/
    public static function __callStatic($name, $arg)
    {
        Main_View::createStaticPage('main');
    }
    
/**     
* Добавляем комментарии 
* @access public 
* @param string $owner
* @param int $id
* @return void   
*/
    protected static function _addComment($owner, $id)
    {
        if(!empty($_POST))
        {
            $author  = iniPOST('author');
            $comment = iniPOST('comment');
         
            if(false === ($info = Validator::validationComment($author, $comment)))
                $info = Main_Model::addComment($owner, $id, $author, $comment);
            
            redirectFlash($info);
        }
    }    
    
}















