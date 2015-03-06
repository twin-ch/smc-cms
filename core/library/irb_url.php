<?php

namespace library;

class IRB_URL
{

/**
* Диапазон ключей GET параметров 
* @var $range_get
* @access public
*/  
    public static $range_get = array('a', 'g');

    protected static $_host,
                     $_get,    
                     $_mode = true;

/**
* Установка вида ссылок.
* @access public
* @param bool $mode
* @return void 
*/      
    public static function setRewrite($mode = true)   
    { 
        self::$_mode = $mode;
    }    
    
/**
* Установка абсолютных ссылок.
* @access public
* @param array $host
* @return void
*/      
    public static function setHost($host)   
    { 
        self::$_host = $host;
    }    

/**  
* Инициализация GET.  
* @access public  
* @param string $key  
* @param string $default  
* @return string   
*/        
    public static function iniGET($key, $default = '')  
    { 
        $get = self::prepareGET();   
        return (!empty($get[$key])) ? $get[$key] : $default;  
    } 
    
/**  
* Подсчет активных параметров.  
* @access public    
* @return string   
*/        
    public static function countParam()  
    { 
        self::$_get = self::_getArray();
      
        return count(self::_clearLast(self::$_get)); 
    }    

/**  
* Разбор GET параметров.  
* @access public  
* @return array   
*/  
    public static function prepareGET()  
    { 
        self::$_get = self::_createDefault();  
       
        if(self::$_mode && !empty($_GET['route']))   
        {   
            $param = explode('/', trim($_GET['route'], '/'));   
            $i = 0;   
         
            foreach(self::$_get as $var => $val)  
            {   
                if(!empty($param[$i]))   
                   self::$_get[$var] = $param[$i];  
             
                $i++;  
            }  
        }  
        elseif(!empty($_GET))   
        {   
            foreach(self::$_get as $var => $val)   
                if(!empty($_GET[$var]))   
                   self::$_get[$var] = $_GET[$var];       
        }  
     
        return self::$_get; 
    } 

/**
* Формирование URL из параметров.
* @access public
* @param array $arg
* @return array 
*/      
    public static function createHref()
    {
        $arg = func_get_args();    
        $arg = is_array($arg[0]) ? $arg[0] : $arg;
        
        if(empty($arg))        
            $arg = self::getParam();
     
        return self::_createURL($arg);
    } 
    
/**
* Получаем активные GET параметры в виде массива.
* @access public
* @return array 
*/      
    public static function getParam()
    {
        $get = self::_clearLast(self::$_get);
        return array_values($get);
    }   
    
/**
* Установка параметра.
* @access public
* @param string $param
* @param string $value
* @return array 
*/      
    public static function addParam($param, $value)   
    {
        self::$_get = self::_getArray();
        self::$_get[$param] = $value;
        return self::_clearLast(self::$_get);
    }
    
/**
* Синглетончик.
* @access public
* @param string $param
* @param string $value
* @return void 
*/      
    protected static function _getArray()   
    {
        if(empty(self::$_get))
            self::$get = self::prepareGET();
       
        return self::$_get;    
    } 
    
/**
* Удаляем последние парамеры.
* @access public
* @param int $offset 
* @param bool $type
* @return string 
*/      
    public static function deleteParam($offset = 0, $type = false)   
    {     
        if(empty(self::$_get))
            self::$_get = self::prepareGET();
       
        $act = self::_clearLast(self::$_get);
        $arr = !empty($offset) ? array_slice($act, 0, -$offset, true) : $act;
        $arr = self::_clearLast($arr); 
        return ($type) ? $arr : self::_createURL($arr);
    } 
    
/**
* Формирование URL.
* @access protected
* @param array $get
* @return string 
*/      
    protected static function _createURL($get)   
    {     
        $href = '';   
        $i = 0;
        
        foreach($get as $val)
        {        
            if(self::$_mode)    
              $href .= '/'. $val; 
            elseif(!empty($val))   
              $href .= '&'. $get[$i++] .'='. $val;  
        }
        
        if(self::$_mode)   
            return self::$_host . $href; 
        else   
            return self::$_host .'?'. trim($href, '&');          
    }
    
/**
* Очистка массива от последних пустых значений.
* @access public
* @param array $keys
* @return array 
*/      
    protected static function _clearLast($keys)
    {
        $keys = array_reverse($keys);
        
        foreach($keys as $key => $value)
        {
            if(!empty($value))
                break;
            
            unset($keys[$key]);    
        }
        
        return array_reverse($keys);
    } 
    
/** 
* Установка дефолтного GET. 
* @access public 
* @return array  
*/   
    protected static function _createDefault() 
    { 
        static $get; 
     
        if(empty($get)) 
        { 
            $keys   = range(self::$range_get[0], self::$range_get[1]); 
            $values = array_fill(0, count($keys), 0); 
            $get    = array_combine($keys, $values); 
            $get    = array_merge(array('page' => 'main'), $get); 
        } 
     
        return $get; 
    }  
}