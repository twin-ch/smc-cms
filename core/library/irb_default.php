<?php 

/** 
* Установка дефолтного GET. 
* @access public 
* @return array  
*/   
    function setArrayGET() 
    { 
        static $get; 
     
        if(empty($get)) 
        { 
            $keys   = range('a', 'z'); 
            $values = array_fill(0, 26, ''); 
            $get    = array_combine($keys, $values); 
            $get    = array_merge(array('page' => 'main'), $get); 
        } 
     
        return $get; 
    }  
 
/**  
* Разбор GET параметров.  
* @access public  
* @return array   
*/  
    function prepareGET()  
    { 
        $get = setArrayGET();  
       
        if(true === IRB_CONFIG_REWRITE && !empty($_GET['route']))   
        {   
            $param = explode('/', trim($_GET['route'], '/'));   
            $i = 0;   
         
            foreach($get as $var => $val)  
            {   
                if(!empty($param[$i]))   
                   $get[$var] = $param[$i];  
                 
                $i++;  
            }  
        }  
        elseif(!empty($_GET))   
        {   
            foreach($get as $var => $val)   
                if(!empty($_GET[$var]))   
                   $get[$var] = $_GET[$var];       
        }  
     
        return $get;  
    }   
    
/**  
* Инициализация GET.  
* @access public  
* @param string $key  
* @param string $default  
* @return string   
*/        
    function iniGET($key, $default = '')  
    { 
        $get = prepareGET();   
        return (!empty($get[$key])) ? $get[$key] : $default;  
    }
    
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
* @param array $param
* @return string 
*/      
    function href()   
    { 
        $arg  = func_get_args();
        $get  = array_keys(setArrayGET());    
        $host = IRB_HOST;
        $href = '';   
        $i    = 0;
        
        if(defined('IRB_ADMIN'))              
            $host .= '/admin/'; 
       
        if(is_array($arg[0]))  
            $arg = $arg[0];
     
        foreach($arg as $val)
        {        
            if(true === IRB_CONFIG_REWRITE)    
              $href .= '/'. $val; 
            elseif(!empty($val))   
              $href .= '&'. $get[$i++] .'='. $val;  
        }
        
        if(true === IRB_CONFIG_REWRITE)   
            return $host . $href; 
        else   
            return $host .'?'. trim($href, '&');          
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
        $value = iniGET($param);
     
        if($default && $value === '')
            return 'class="active"';
     
        if(is_array($return) && in_array($value, $return))
            return 'class="active"';
        
        return (iniGET($param) === $return) ? 'class="active"' : NULL;
    }  
    
    
/**
* Перенаправление
* @access public
* @param array $param
* @return void
*/     
    function reDirect()
    {    
        $param = func_get_args();
        
        if(is_array($param[0]))
           $param = array_shift($param);
           
        if(!empty($param))                          
            header('location: '. href($param));
        else
            header('location: '. str_replace("/index.php", "", $_SERVER['HTTP_REFERER']));
        
        exit();
    }  
 
/**
 * Пути к картинкам
 * @param string $name
 * @param string $text
 */
    function src($pic)
    {
        return '/skins/'. IRB_LANGUAGE .'/images/'. $pic;
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
* @return void|array|string
*/
    function redirectFlash()
    {
        $args = func_get_args();
        $info = array_shift($args);
        
        if(empty($info) || is_int($info))
        {
            setFlashData('info', getLanguage('CHANGE_DATA'));
            redirect($args);
        }
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
       header("HTTP/1.1 404 Not Found");     
       include IRB_DIR_TEMPLATE .'/404.html';
       exit();
    } 






































