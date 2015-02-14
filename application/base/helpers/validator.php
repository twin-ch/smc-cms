<?php

namespace base\helpers;

class Validator
{

/** 
* Валидация категории
* @access public
* @param string $name
* @return string|bool
*/  
    public static function validationCategory($name)
    {
        return empty($name) ? getLanguage('EMPTY_CATEGORY_NAME') : false;
    } 

 
/** 
* Валидация страницы
* @access public
* @param string $title
* @param string $text
* @return string|bool
*/  
    public static function validationPage($title, $text)
    {
        $error = array();
     
        if(empty($title))
            $error[] = getLanguage('EMPTY_TITLE');       
     
        if(empty($text))
            $error[] = getLanguage('EMPTY_TEXT'); 
      
        return !empty($error) ? implode('<br>', $error) : false;
    } 
    
/** 
* Валидация комментариев
* @access public
* @param string $author
* @param string $comment
* @return string|bool
*/  
    public static function validationComment($author, $comment)
    {
        $error = array();
     
        if(false !== $author && empty($author))
            $error[] = getLanguage('EMPTY_NAME');
        elseif(mb_strlen($author, 'utf-8') > 20)
            $error[] = getLanguage('LONG_NAME');
            
        if(empty($comment))
            $error[] = getLanguage('EMPTY_TEXT'); 
      
        return !empty($error) ? implode('<br>', $error) : false;
    } 
} 

    
    
    
    
    
    
    
    
