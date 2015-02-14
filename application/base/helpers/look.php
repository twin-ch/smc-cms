<?php 

namespace base\helpers;

use db\mysqli as db;

class Look
{
    protected static $_userdata;
    
 /** 
* Получаем данные пользователя 
* @access public
* @param int $id
* @return array
*/ 
    public static function getUserData($id)
    {
        if(empty(self::$_userdata))
        {
            self::$_userdata = self::_getUserDataById($id);
        }
        
        return self::$_userdata;
    }
    
/** 
* Проверка роли юзера 
* @access public
* @param string $access
* @return bool
*/ 
    public static function checkRole($access)
    {    
        self::$_userdata = self::getUserData(@$_SESSION['userdata']['id']);
        
        if(!empty(self::$_userdata[0]))
            return (bool)($access === self::$_userdata[0]['role']);
        else
            return false;
    }
    
/** 
* Запрос данных юзера 
* @access protected
* @param int $id
* @return array
*/     
    protected static function _getUserDataById($id)
    {
        $res = db::query("SELECT * FROM `". IRB_CONFIG_DBPREFIX ."users`
                           WHERE `id` = ".(int)$id
                        );
        
        return db::prepareResult($res);
    }     
}





















