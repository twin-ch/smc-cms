<?php 

namespace controllers;

use base\helpers\validator as Validator;
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
        $id  = $get['b'];
        $num = $get['c'];
        self::_addComment('user', $id);
        Users_View::createUser($id, $num);
    }
    
/**     
* Акшен списка юзеров 
* @access public
* @param array $get
* @return void   
*/
    public static function __callStatic($name, $get)
    {
        $num = $get[0]['a'];
        self::_addComment('users', 0);
        Users_View::createUsersList($num);
    }

/**     
* Добавляем комментарии 
* @access public 
* @param string $owner
* @param int $id
* @return void   
*/
    protected static function _addComment($owner, $id = '')
    {
        if(!empty($_POST))
        {
            $author  = iniPOST('author');
            $comment = iniPOST('comment');
         
            if(false === ($info = Validator::validationComment($author, $comment)))
                $info = Users_Model::addComment($owner, $id, $author, $comment);
            
            redirectFlash($info);
        }
    } 
}

















