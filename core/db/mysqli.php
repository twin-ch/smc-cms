<?php

namespace db;

use debug\DBdebug as debug;

class Mysqli
{
    public static $link;  
    public static $count = 0; 
      
/**
* Экранирование апострофов в литеральных константах.
* @access public
* @param mix $data
* @return string|array 
*/   
    public static function escape($data)   
    {  
        self::_connect();
        if(is_array($data))
            $data = array_map("self::escape", $data);
        else              
            $data = mysqli_real_escape_string(self::$link, $data);
        
        return $data;
    }  
    
/**
* Приведение к числовому типу.
* @access public
* @param mix $data
* @return string|array 
*/   
    public static function intval($data)   
    {  
        if(is_array($data))
            $data = array_map("self::intval", $data);
        else              
            $data = intval($data);
        
        return $data;
    }
    
/**
* Произвольный запрос MySQL.
* @access public
* @param string $sql
* @param bool $test
* @return resours|void
*/     
    public static function query($sql, $test = false) 
    {
        self::_connect();
        self::$count++;
       
        $result = mysqli_query(self::$link, $sql); 
        $error  =  mysqli_error(self::$link);
        
        if(true === IRB_CONFIG_DEBUG) 
        {                
            $trace =  debug_backtrace();
            
            if($result === false)
            {
                throw new \Exception(debug::prepareError($trace[0]['file'], $trace[0]['line'], $sql, $error), 
                                     E_USER_ERROR);
            }
            elseif($test)
            {
                throw new \Exception(debug::prepareTest($trace[0]['file'], $trace[0]['line'], $sql, $error), 
                                     28);
            }   
        } 
        
        return $result; 
    }
    
/**
* Разбор результата.
* @access public
* @param resourse $res
* @return array 
*/   
    public static function prepareResult($res)
    {    
        $result = array();
     
        while($row = mysqli_fetch_assoc($res))
            $result[] = $row;
     
        return $result;    
    } 
    
/**
* Коннект.
* @access protected
* @return void 
*/      
    protected static function _connect()
    {
        if(empty(self::$link))
        {
            self::$link = @mysqli_connect(IRB_CONFIG_DBSERVER, 
                                         IRB_CONFIG_DBUSER, 
                                         IRB_CONFIG_DBPASSWORD, 
                                         IRB_CONFIG_DATABASE
                                         ) ;
         
            if(mysqli_connect_errno())
            {
                trigger_error(debug::prepareError(mysqli_connect_error(), __FILE__, __LINE__),
                              E_USER_ERROR);
            }
          
            mysqli_set_charset(self::$link, 'utf8');
        }  
    }
}



    
    
    
    
    