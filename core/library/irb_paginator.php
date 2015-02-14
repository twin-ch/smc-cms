<?php

namespace library;

use db\mysqli as db;

class IRB_Paginator
{

    public static $table_count = 0;    
    public static $table_total = 0;
    public static $start_page  = 0;
    
    protected static $_num_page    = 1, 
                     $_num_rows    = 1,   
                     $_num_columns = 1,
                     $_link_param  = array();
/**
* Constructor
* @param int $page
* @param int $rows 
* @param int $columns 
*/    
    public static function setLimitParam($page = 1, $rows = 1, $columns = 1) 
    {
        if($rows > 1)
            self::$_num_page = (int)$page;    
     
        if($rows > 1)
            self::$_num_rows  = $rows;
     
        if($columns > 1)
            self::$_num_columns  = $columns; 
     
    } 
/**
* Method for working with data from an array
* @access public
* @param array $query    
* @return void
*/    
    public static function countFile($query) 
    {
        self::$table_count = count($query);
        
        $offset = self::$_num_rows * self::$_num_columns;
        $start  = self::$_num_page * $offset - $offset;  
        $res    = array_slice($query, $start, $offset);  
        self::_createLimit();
        
        return $res;
    }
    
    
/**
* Operates a cache of difficult inquiries
* @param string $query
* @access public
* @return void
*/
    public static function countQuery($query, $debug = '')
    {
       
        $query = str_replace("\n", " ", $query);
        $cntqu = preg_replace("#ORDER.*#is", " ", $query);
        preg_match("#FROM(.+)#i", $cntqu, $table);
     
        $res = db::query("SELECT COUNT(*) AS `cnt`
                           FROM ". $table[1]
                           );
        
        $data = db::prepareResult($res);
        self::$table_count = $data[0]['cnt']; 
        $res = db::query($query . self::_createLimit(), $debug);
       
        return $res;
    }
    
/**
* Operates a cache of difficult inquiries
* @param string $query
* @access public
* @return void
*/    
    public static function calcQuery($query, $debug = '')
    {
        $query = preg_replace('#SELECT#i', 'SELECT SQL_CALC_FOUND_ROWS ', $query);
        $start = (self::$_num_page > 1) ? self::$_num_page : 0;
     
        $res = db::query($query . '
                          LIMIT '. $start .', '. self::$_num_rows * self::$_num_columns,
                          $debug);
     
        $result = db::query('SELECT FOUND_ROWS()');
        $data   = db::prepareResult($result);
       
        self::$table_count = $data[0]['FOUND_ROWS()']; 
        self::_createLimit();
     
        return $res;
    }

/**
* Generates the navigation menu
* @access public
* @param string $param
* @return string
*/    
    public static function createMenu()
    { 
        self::$_link_param = func_get_args();
        
       
        $count = ceil(self::$table_total / self::$_num_rows / self::$_num_columns);
        $menu = "\n<!-- IRB_Paginator begin -->\n";
     
        if($count < 13)
        {          
            $i = 1;    
            $cnt = $count;
        }
        else
        {
            if(self::$_num_page > 10)
                $menu .= self::_createLink((self::$_num_page - 10), '-10&lt;', '_top');
                        
            if($count > 12)
            {    
                if(self::$_num_page == 7)
                    $menu .= self::_createLink(1, 1);
                elseif(self::$_num_page == 8)
                    $menu .= self::_createLink(1, 1) 
                          .  self::_createLink(2, 2);
                elseif(self::$_num_page > 7)
                    $menu .= self::_createLink(1, 1) 
                          .  self::_createLink(2, 2) 
                          .  self::_createLink(0, '...', '_top', false);
            }    
         
            if(self::$_num_page < 6)
            {  
                $i = 1;
                $cnt = 10;
            }
            elseif(self::$_num_page >= $count)
            { 
                $i = $count - 10; 
                $cnt = $count; 
            }
            else
            {   
                $i = self::$_num_page - 5;
                $cnt = $count;
            }
         
            if(self::$_num_page < 6) 
                $cnt = $i + 9;
            elseif($count - $i > 10)
                $cnt = $i + 10;
                        
        }        
     
        while($i <= $cnt)
        {
            if($i == self::$_num_page)
                $menu .= self::_createLink($i, $i, '_active', false);
            else
                $menu .= self::_createLink($i, $i);
                   
             $i++;
        }  
       
        if($count > 12)
        {    
            if(self::$_num_page < $count - 6)
                $menu .= self::_createLink(0, '...', '_top', false)
                      . self::_createLink(($count - 1), ($count - 1));
                   
            if(self::$_num_page < $count - 5)
                $menu .= self::_createLink($count, $count);
        }
     
       $end = (self::$_num_page  + 10 > $count) ? $count : self::$_num_page + 10;
     
       if(self::$_num_page < $count - 5 && $count - self::$_num_page >= 10)
           $menu .= self::_createLink($end, '&gt;+10', '_top');
     
        return $menu ."\n<!-- IRB_Paginator end -->\n";
    }
    
/**
* Calculates a position and prepares a limit for inquiry
* @access protected
* @return string
*/    
    protected static function _createLimit()
    { 
        self::$table_total = intval((self::$table_count - self::$_num_columns) / self::$_num_rows * self::$_num_columns) - 1;
     
        if(self::$_num_page < 1) 
            self::$_num_page = 1;
       
        if(empty(self::$table_total) || self::$table_total < self::$table_count)
            self::$table_total = self::$table_count;
     
        if(self::$_num_page > self::$table_total) 
            self::$_num_page = self::$table_total; 
     
        self::$start_page = self::$_num_page * self::$_num_rows * self::$_num_columns - self::$_num_rows * self::$_num_columns;
     
        if(self::$start_page < 0)
           self::$start_page = 0;
     
        return ' LIMIT '. self::$start_page .', '. self::$_num_rows * self::$_num_columns;
     
    }

/**
* Makes a hyperlink
* @param int $page
* @param string $link, $class
* @param bolean $active
* @access private
* @return string
*/      
    protected static function _createLink($page = 1, $link = '', $class = '', $active = true)
    {                   
        if(empty($link))
           $link = $page;
           
        if($active)
        {   
            $arg   = self::$_link_param[0];
            array_push($arg, $page);
            return "<span class=\"IRB_paginator". $class ."\">\n"
                 . "<a href=\"". href($arg) ."\" >". $link ."</a>\n</span>\n";
        }
        else
            return "<span class=\"IRB_paginator". $class ."\"> ". $link ." </span>\n";
    }

}











