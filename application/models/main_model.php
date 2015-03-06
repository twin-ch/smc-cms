<?php

namespace models;

use db\db as db;
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
     
        return db::fetchArray($res);
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
        
        $result = db::fetchRow($res);
        return !empty($result) ? $result : create404();
    }  
   
   
} 

    
    
    
    
    
    
    
    
