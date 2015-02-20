<?php 

namespace controllers;

use models\Users_Model as Users_Model;
use views\Users_View as Users_View;

class Users_Controller
{

/**     
* Метод запуска контроллера 
* @access public
* @param array $get
* @return void   
*/
    public static function run($get)
    {   
        Users_View::template('/users'); 
        
        $method = 'action'. $get['a'];
        self::$method($get);
        
        Users_View::createInfo(getFlashData('info'));        
        Users_View::run();
    }

/**     
* Акшен конкретного юзера 
* @access public
* @param array $get
* @return void   
*/
    public static function actionUser($get)
    {
        $id      = $get['b'];
        $pag_num = $get['c'];
        $id_ans  = $get['d'];
        $info    = '';
        
        if(!empty($_POST))
        {
            $author  = iniPOST('author');
            $comment = iniPOST('comment');        
          
            $info = Users_Model::addComment('user', $id, $author, $comment, $id_ans);
        }
     
        Users_View::createUser($id, $pag_num, $id_ans);
    }
    
/**     
* Акшен списка юзеров 
* @access public
* @return void   
*/
    public static function __callStatic($name, $get)
    {
        $pag_num = $get[0]['a'];
        $id_ans  = $get[0]['b'];
        $info    = '';
        
        if(!empty($_POST))
        {
            $author  = iniPOST('author');
            $comment = iniPOST('comment');        
          
            $info = Users_Model::addComment('users', 1, $author, $comment, $id_ans);
        }
     
        Users_View::createUsersList($pag_num, $id_ans);
    } 
}

















