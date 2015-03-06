<?php  

class Config  
{  
/**        
* @access protected 
* @var array $_data    
*/
    protected static $_data = array();
    
/**       
* Читаем конфигу   
* @access protected 
* @return array    
*/   
    protected static function _getConfig()  
    {// Обращаемся за данными только один раз
        if(empty(self::$_data))
            self::$_data = include IRB_ROOT .'/config/local.php';
       
        return self::$_data;
    }
    
/**       
* Получаем данные конфигурации   
* @access public 
* @param string $config
* @return array    
*/   
    public static function get($config)  
    {// возвращаем нужную настройку
        self::_getConfig(); 
        return self::$_data[$config]; 
    }  
}  