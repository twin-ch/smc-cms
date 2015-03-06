<?php

namespace debug;

use db\db as db;

class Debug_Mysqli
{
    public static function prepareError($file, $line, $sql = '', $error = '')
    { 
        $output =  '<div class="irb_mess">'
                 .  '<strong>MySQL error: <br />'
                 .  'in: </strong>'. $file 
                 .  '<strong> on line: </strong>'. $line
                 . '<br />'
                 . htmlSpecialChars($error)
                 . '</div>'; 
      
        if(!empty($sql))
        {  
            $output .= self::_prepareSql($sql, $error);
        }  
        
        return $output;
    } 
    
    public static function prepareTest($file, $line, $sql, $error)
    { 
        $output =  '<div class="irb_mess">'
                 .  '<strong>Query: <br />'
                 .  'in: </strong>'. $file 
                 .  '<strong> on line: </strong>'. $line
                 . '</div>'; 
        
        $output .= self::_prepareSql($sql);
        
        if(empty($error))
        {
            $start = microtime(true);
            mysqli_query(db::getLink, $sql);
            $end   = microtime(true);
            $output .= '<div style="color:black; background:#F5EDB1;padding:5px">
                           <strong>Query time: </strong>'. sprintf("%01.4f", $end - $start) .' s
                           <br>
                           <strong>Explain:</strong>
                       </div>';
            
            $res = mysqli_query(db::getLink, "EXPLAIN ". $sql);
            
            if(is_object($res))
            {
                $explain = mysqli_fetch_assoc($res);
                $output .= '<table class="irb_explain" width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr style="color:##B2B2B2">
                                <th>id</th>
                                <th>select_type</th>
                                <th>table</th>        
                                <th>type</th>        
                                <th>possible_keys</th>        
                                <th>key</th>
                                <th>key_len</th>
                                <th>ref</th>        
                                <th>rows</th>        
                                <th>Extra</th>
                            </tr>    
                            <tr>
                                <td>'. $explain['id'] .'</td>
                                <td>'. $explain['select_type'] .'</td>        
                                <td>'. $explain['table'] .'</td>
                                <td>'. $explain['type'] .'</td>        
                                <td>'. $explain['possible_keys'] .'</td>        
                                <td>'. $explain['key'] .'</td>        
                                <td>'. $explain['key_len'] .'</td>        
                                <td>'. $explain['ref'] .'</td>        
                                <td>'. $explain['rows'] .'</td>
                                <td>'. $explain['Extra'] .'</td>
                            </tr>    
                        </table>'; 
            }
        }
        
        return $output;
    } 
    
    protected static function _prepareSql($sql, $error = '')
    { 
        $sql   = htmlSpecialChars($sql);
        $error = htmlSpecialChars($error); 
        $out   = array('', '');
        
        if(!empty($error))
        {
            preg_match("#'(.+?)'#is", $error, $out);
            
            if(!empty($out[1]))
                $sql = str_replace($out[1], '<b style="color:red">'. $out[1] .'</b>', $sql);
        }
        
        $cnt  = substr_count($sql, "\r") + 1;
        $nums = array_fill(1, $cnt, true);
      
        return    '<div class="irb_listing">'
                .    '<div class="irb_num">'
                .        '<code>'. implode("<br>", array_keys($nums)) .'</code>' 
                .    '</div>'
                .    '<div class="irb_code">'
                .        '<code><span style="color:#990099">'
                .        nl2br($sql)
                .        '</span></code>'
                .    '</div>'                              
                .    '<div class="clear"></div>'
                . '</div>';
     
    }     

}