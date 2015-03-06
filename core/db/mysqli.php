<?php

namespace db;

use debug\Debug_Mysqli as debug;

class DB
{

    public static $count = 0;
    protected static $_link;  
    
/**
* Коннект.
* @access protected
* @return void 
*/      
    protected static function _connect()
    {
        if(empty(self::$_link))
        {
            self::$_link = @mysqli_connect(IRB_CONFIG_DBSERVER, 
                                           IRB_CONFIG_DBUSER, 
                                           IRB_CONFIG_DBPASSWORD, 
                                           IRB_CONFIG_DATABASE
                                           ) ;
         
            if(mysqli_connect_errno())
            {
                trigger_error(debug::prepareError(mysqli_connect_error(), __FILE__, __LINE__),
                              E_USER_ERROR);
            }
          
            mysqli_set_charset(self::$_link, 'utf8');
        }  
    }
    
/**
* Получаем ссылку на объект mysqli.
* @access public
* @param mix $data
* @return string|array 
*/   
    public static function getLink()   
    {  
        self::_connect();
        return self::$_link;
    }    

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
            $data = array_map('self::escape', $data);
        else              
            $data = mysqli_real_escape_string(self::$_link, $data);
        
        return $data;
    } 
    
/**
* Разбор массива в строку с обработкой целочисленных значений.
* @access public
* @param array  $data
* @return string 
*/   
    public static function implodeInt($data)   
    {
        return implode(',', array_map('intval', $data));
    }
    
/**
* Разбор массива в строку с обработкой строковых значений.
* @access public
* @param array  $data
* @return string
*/   
    public static function implodeStr($data)   
    {
        return "'". implode("','", self::escape($data)) ."'";
    }
    
/**
* Произвольный запрос MySQL.
* @access public
* @param string $sql
* @param bool $test
* @return resource
*/     
    public static function query($sql, $test = false) 
    {
        self::_connect();
        self::$count++;
        $result = mysqli_query(self::$_link, $sql); 
        $error  = mysqli_error(self::$_link);
        
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
* Получение ряда запроса в виде массива.
* @access public
* @param object $res
* @return array
*/     
    public static function fetchRow($res) 
    {// Проверяем результат
        if(!is_object($res))
            return false;
     
        return mysqli_fetch_assoc($res);
    }    
    
    
/**
* Получение результата запроса в виде массива.
* @access public
* @param object $res
* @return array
*/     
    public static function fetchArray($res) 
    {
        if(!is_object($res))
            return false;
     
        $result = array();
     
        while($row = mysqli_fetch_assoc($res))
            $result[] = $row;
     
        return $result;
    } 
    
/**
* Получает число строк, затронутых предыдущей операцией .
* @access public
* @return array
*/     
    public static function affectedRows() 
    {
        return mysqli_affected_rows(self::$_link);
    } 
    
/**
* Возвращает идентификатор, используемый в последнем запросе.
* @access public
* @return array
*/     
    public static function insertId() 
    {
        return mysqli_insert_id(self::$_link);
    }
}




















