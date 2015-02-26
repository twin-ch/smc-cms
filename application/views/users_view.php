<?php

namespace views;

use models\Users_Model as Users_Model;
use base\View as View;

class Users_View extends View
{
    
/**
* Список юзеров 
* @access public
* @param int $pag_num
* @return void
*/       
    public static function createUsersList($pag_num, $id_ans)
    {   
        $result = Users_Model::getUsersList();
        self::_createRows('users', htmlChars($result));
        self::$tpl->setBlock('list_users');
        $link      = array('users');
        $id_parent = null;
        $data = compact('link', 'id_parent', 'pag_num', 'id_ans');
        self::_createCommentsFor('users', $data);
        parent::_createTreeCategory();
    } 
    
/**
* Конкретный юзер
* @access public
* @param int $pag_num
* @param int $id_ans
* @return void
*/       
    public static function createUser($id, $pag_num, $id_ans)
    {    
        $result = Users_Model::getUserData($id);
       
        if(!empty($result))
        { 
            self::$tpl->assign($result);
            $link = array('users', 'user', $id);
            $id_parent = $id;
            $data = compact('link', 'id_parent', 'pag_num', 'id_ans');
            parent::_createCommentsFor('user', $data);
        }
        else
            self::$tpl->setBlock('user_empty');
     
        self::$tpl->setBlock('user');
        parent::_createTreeCategory();
    }    
}   











