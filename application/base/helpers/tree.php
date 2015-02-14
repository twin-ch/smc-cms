<?php

namespace base\helpers;

use base\helpers\look as look;

class Tree
{
    protected static $_rows,
                     $_shift, 
                     $_shift_cnt = 0, 
                     $_max_nest, 
                     $_result = ''; 

/** 
* Дерево категорий 
* @access public
* @param array $_result
* @return void
*/    
    public static function prepareCategory($_result)
    {
        $i = 0;
        $arr = array();
        
        foreach($_result as $row)
        {   
            self::$_rows[$i]['id']        = $row['id'];
            self::$_rows[$i]['id_parent'] = $row['id_parent'];
            self::$_rows[$i]['rows']      = look::checkRole('trusted') ? 
                                          ' <a href="'. href('admin', 'edit', 'category', $row['id']) .'">'
                                        . '<img src="'. src('edit.png').'" border="0" /></a> '
                                        . ' <a href="'. href('admin', 'delete', 'category', $row['id']) .'">'
                                        . '<img src="'. src('delete.png').'" border="0" '
                                        . ' onclick="return confirm(\''. getLanguage('DELETE') .'\')"/></a> ' 
                                        : '';
                                  
            self::$_rows[$i]['rows']     .= '<a href="'. href('main', 'category', $row['id']) .'">'
                                        . htmlChars($row['name'])
                                        . '</a>';
            
            ++$i; 
        }
        
        return self::_createTree(15); 
    } 
    
/** 
* Метод формирования дерева 
* @access protected 
* @param int $_shift //Сдвиг вправо в пикселях 
* @param int $_max_nest // Максимальная вложенность 
* @return string 
*/       
    protected static function _createTree($_shift, $_max_nest = 10) 
    { 
        self::$_shift    = $_shift; 
        self::$_max_nest = $_max_nest; 
        $data = self::_sortArray(); 
        self::_recursiveTree($data, $parent = 0, $_shift = 0); 
        return self::$_result; 
    }     

/** 
* Рекурсивный метод обхода массива 
* @access private 
* @param array $data 
* @param int $parent 
* @param int $_shift 
* @return void 
*/       
    private static function _recursiveTree($data, $parent, $_shift) 
    { 
        $arr   = $data[$parent]; 
        $cnt   = count($arr); 
        $style = ''; 
       
        if(!empty($_shift) && ++self::$_shift_cnt < self::$_max_nest) 
            $style = ' style="padding-left:'. $_shift .'px;"'; 
          
        for($i = 0; $i < $cnt; $i++) 
        { 
            self::$_result .= "<div". $style .">\n"; 
            self::$_result .= $arr[$i]['rows']; 
             
            if(isset($data[$arr[$i]['id']]))  
                self::_recursiveTree($data, $arr[$i]['id'], self::$_shift); 
                 
            self::$_result .= "</div>\n"; 
        } 
    }     

/** 
* Метод формирования массива дерева 
* @access private 
* @return array 
*/       
    private static function _sortArray() 
    { 
        $cnt = count(self::$_rows); 
      
        for ($i = 0; $i < $cnt; ++$i) 
            $arr[self::$_rows[$i]['id_parent']][] = self::$_rows[$i]; 
         
        return $arr; 
    } 
}