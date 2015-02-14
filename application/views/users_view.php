<?php

namespace views;

use models\Users_Model as Users_Model;
use base\View as View;

class Users_View extends View
{
    
/**
* Список юзеров 
* @access public
* @param int num
* @return void
*/       
    public static function createUsersList($num)
    {   
        $result = Users_Model::getUsersList();
        self::_createRows('users', htmlChars($result));
        self::$tpl->setBlock('list_users');
        $link = array('users');
        self::_createCommentsFor('users', $link, null, $num);
        parent::_createTreeCategory();
    } 
    
/**
* Конкретный юзер
* @access public
* @param int id
* @return void
*/       
    public static function createUser($id, $num)
    {   
        $result = Users_Model::getUserData($id);
     
        if(!empty($result))
        { 
            self::$tpl->assign($result);
            $link = array('users', 'user', $id);
            parent::_createCommentsFor('user', $link, $id, $num);
        }
        else
            self::$tpl->setBlock('user_empty');
     
        self::$tpl->setBlock('user');
        parent::_createTreeCategory();
    }    
}   











