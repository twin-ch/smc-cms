<?php

namespace models;

use db\mysqli as db;
use base\helpers\Comments as Comments;
use base\model as Model;

class Main_Model  extends Model
{

/** 
* Список страниц
* @access public
* @param int $id_parent
* @return bool|array
*/      
    public static function getListPages($id_parent)
    {
        $res = db::query("SELECT `id`, `id_parent`, `title`
                              FROM `". IRB_CONFIG_DBPREFIX ."pages`
                                WHERE `id_parent` = ".(int)$id_parent ."
                                  ORDER BY `id` " 
                        );
     
        return db::prepareResult($res);
    }
    
/**
* Конкретная страница
* @access public
* @param int $id
* @return bool|array
*/    
    public static function getPageContent($id)
    {    
        $res = db::query("SELECT *
                              FROM `". IRB_CONFIG_DBPREFIX ."pages`
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

    
    
    
    
    
    
    
    
