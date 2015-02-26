<?php 

use library\IRB_URL as IRB_URL;

/**
* Инициализация POST.
* @access public
* @param string $val
* @param string $default
* @return string 
*/      
    function iniPOST($key, $default = '')
    {
        return (isset($_POST[$key]) && $_POST[$key] !== '') 
               ? $_POST[$key] : $default;
    }

/**
* Формирование ссылок.
* @access public
* @param array $arg
* @return string 
*/      
    function href()   
    { 
        $arg = func_get_args();
        $arg = is_array($arg[0]) ? $arg[0] : $arg;
        return IRB_URL::createHref($arg);
    }
    
/**   
* Активация ссылок 
* @access public
* @param string $return
* @param string|array $param
* @param boll|string|int $default
* @return string
*/ 
    function activeLink($param, $return, $default = false)
    {
        $value = IRB_URL::iniGET($param);
     
        if($default && $value === '')
            return 'class="active"';
     
        if(is_array($return) && in_array($value, $return))
            return 'class="active"';
        
        return (IRB_URL::iniGET($param) === $return) ? 'class="active"' : NULL;
    }  
    
/**
* Перенаправление
* @access public
* @param array $arg
* @return void
*/     
    function reDirect()
    {    
        $arg = func_get_args();
        $arg = is_array($arg[0]) ? $arg[0] : $arg;
        
        if(!empty($arg))                          
            header('location: '. href($arg));
        else
            header('location: '. str_replace("/index.php", "", $_SERVER['HTTP_REFERER']));
        
        exit();
    }  
    
/**
 * Сохраняет данные для передачи между страницами
 * @param string $name
 * @param string $text
 */
    function setFlashData($name, $value)
    {
        if (empty($_SESSION['flashData']))
            $_SESSION['flashData'] = array();
     
        $_SESSION['flashData'][$name] = $value;
    }
    
/**
 * Возвращает данные, сохранённые для передачи между страницами
 * @param string $name
 * @param mixed $default
 * @return string
 */
    function getFlashData($name, $default = '')
    {
        if(empty($_SESSION['flashData'][$name]))
            return $default;
     
        $data = $_SESSION['flashData'][$name];    
        unset($_SESSION['flashData'][$name]);
     
        return $data;
    } 
    
/**
* Flash-редирект 
* @access public
* @param string $info
* @param array $data
* @param bool $default
* @return void
*/
    function redirectFlash($param, $info = true)
    {
        if($info === false)
            setFlashData('info', getLanguage('CHANGE_DATA'));
        else
            setFlashData('info', $info);
        
        redirect($param);
    }     

/** 
* Обработка переменных для вывода в поток
* @access public
* @param string|array $data
* @return string|array
*/                                                     
    function htmlChars($data)    
    {    
        if(is_array($data))             
            $data = array_map('htmlChars', $data);  
        else               
            $data = htmlspecialchars($data);    
                                 
        return $data; 
    }
    
/**   
* Разбор системных сообщений 
* @access public
* @param string $info
* @param string $separator
* @return string
*/ 
    function prepareInfo($info = '', $separator = '<br>')
    {
        if(is_array($info))
            return implode($separator, $info);
        elseif(!empty($info))
            return $info;
        else
            return '&nbsp;';
    }     
   
/**
* Поддержка мультиязычности (строка).
* @access public
* @param string $message
* @param string $key
* @return string 
*/      
    function getLanguage($message, $key = '')
    {
        $class = 'base\language\\'. IRB_LANGUAGE;
        $message = $class::translate($message);
        
        if(is_array($message) && !empty($key))
            return $message[$key];
        else
            return $message;
    }
    
/**   
* Генерация 404 
* @access public
* @return void
*/ 
    function create404()
    {
        if(true === IRB_CONFIG_DEBUG)
            trigger_error('Not found', E_USER_ERROR); 
       
        header("HTTP/1.1 404 Not Found");     
        include IRB_DIR_TEMPLATE .'/404.html';
        exit();
    } 






































