<?php 

namespace controllers;

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
        $cid     = $get['b'];
        $pag_num = $get['c'];
        $id_ans  = $get['d'];
        $info    = '';

        if(!empty($_POST))
        {
            $author  = iniPOST('author');
            $comment = iniPOST('comment');        
          
            $info = Main_Model::addComment('category', $cid, $author, $comment, $id_ans);
        }
        
        Main_View::createInfo($info);
        Main_View::createListPages($cid, $pag_num, $id_ans);
    } 

/**     
* Акшен выбранной страницы 
* @access public  
* @param array $get
* @return void   
*/
    public static function actionPage($get)
    {
        $pid     = $get['b'];
        $cid     = $get['c'];
        $pag_num = $get['d'];
        $id_ans  = $get['e'];
        $info    = '';
     
        if(!empty($_POST))
        {  
            $author  = iniPOST('author');
            $comment = iniPOST('comment');        
          
            $info = Main_Model::addComment('page', $pid, $author, $comment, $id_ans);
        }
        
        Main_View::createInfo($info);
        Main_View::createPageContent($cid, $pid, $pag_num, $id_ans);
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
    
}















