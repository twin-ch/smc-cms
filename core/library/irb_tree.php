<?php

namespace library;

class IRB_Tree
{
    protected static $_rows, 
                     $_shift_cnt,
                     $_result,
                     $_css,
                     $_shift    = 15, // сдвиг в пикселях
                     $_max_nest = 10; // максимальная вложенность
                    
/** 
* Настройки 
* @access public
* @param string $css
* @param int $shift
* @param int $max_nest
* @return void
*/    
    public static function setting($css, $shift = 15, $max_nest = 10)
    {
        self::$_css      = $css;
        self::$_shift    = $shift; 
        self::$_max_nest = $max_nest; 
    } 
    
/** 
* Генерация дерева  
* @access public
* @param array $rows
* @return void
*/    
    public static function prepare($rows)
    {
        self::$_result = '';
        self::$_shift_cnt = 0;
        self::$_rows   = $rows;
        $data = self::_sort();
        self::_recursive($data, $parent = 0, $shift = 0);
        return self::$_result;  
    } 

/** 
* Рекурсивный метод обхода массива 
* @access protected 
* @param array $data 
* @param int $parent 
* @param int $_shift 
* @return void 
*/       
    protected static function _recursive($data, $parent, $shift) 
    {
        $arr   = $data[$parent];    
        $cnt   = count($arr);
     
        $style = ''; 
       
        if(!empty($shift) && ++self::$_shift_cnt < self::$_max_nest) 
            $style = ' style="padding-left:'. $shift .'px;"'; 
     
        for($i = 0; $i < $cnt; $i++)
        { 
            self::$_result .= "<div". $style ." class=\"". self::$_css ."\">\n"; 
            self::$_result .= $arr[$i]['rows']; 
         
            if(isset($data[$arr[$i]['id']]))  
                self::_recursive($data, $arr[$i]['id'], self::$_shift); 
         
            self::$_result .= "\t\t</div>\n"; 
        }
    }     

/** 
* Метод формирования массива дерева 
* @access protected 
* @return array 
*/       
    protected static function _sort() 
    { 
        $cnt = count(self::$_rows); 
      
        for ($i = 0; $i < $cnt; ++$i) 
            $arr[self::$_rows[$i]['id_shift']][] = self::$_rows[$i]; 
     
        return $arr; 
    } 
}