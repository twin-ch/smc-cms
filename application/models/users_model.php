<?php

namespace models;

use db\mysqli as db;
use base\helpers\Comments as Comments;
use base\model as Model;

class Users_Model extends Model
{

/** 
* Список юзеров
* @access public
* @return bool|array
*/      
    public static function getUsersList()
    {
        $res = db::query("SELECT *
                              FROM `". IRB_CONFIG_DBPREFIX ."users`
                                ORDER BY `id` "
                        );
        
        return db::prepareResult($res);
    }
    
/**
* Конкретный юзер
* @access public
* @param int $id
* @return bool|array
*/    
    public static function getUserData($id)
    {    
        $res = db::query("SELECT *
                              FROM `". IRB_CONFIG_DBPREFIX ."users`
                                WHERE `id` = ".(int)$id ."
                                  ORDER BY `id` "
                         );
        
        $result = db::prepareResult($res);
        return !empty($result[0]) ? $result[0] : create404();
    }

/** 
* Добавляем комментарий 
* @access public
* @param string $owner
* @param int $id_parent
* @param string $author
* @param string $comment
* @return bool|string
*/  
    public static function addComment($owner, $id_parent, $author, $comment)
    {
        $data = array('owner'      => $owner,
                      'id_parent'  => $id_parent,
                      'author'     => $author, 
                      'text'       => $comment,
                    );
      
        return Comments::add($data);
    }       
} 

    
    
    
    
    
    
    
    
