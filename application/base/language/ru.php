<?php

namespace base\language;

class Ru
{
    protected static $lang = array( 
                                    'FATAL_ERROR'           => 'Сбой системы', 
                                    'CHANGE_DATA'           => 'Данные успешно изменены',
                                    'EMPTY_NAME'            => 'Нет имени',
                                    'LONG_NAMEs'            => 'Имя не должно превышать 20 символов',
                                    'EMPTY_TITLE'           => 'Нет заголовка',
                                    'EMPTY_TEXT'            => 'Нет текста',
                                    'EMPTY_CATEGORY'        => 'Не выбрана категория',
                                    'EMPTY_CATEGORY_NAME'   => 'Нет названия категории',
                                    'DELETE'                => 'Удалить категорию и все связанные с ней подкатегории, страницы и комментарии?'
                             ); 


    public static function translate($phrase)
    {
        if(empty(self::$lang[$phrase]))
            trigger_error('Phrase <em>'. $phrase .'</em> not found', E_USER_WARNING);
        
        return self::$lang[$phrase];
    }
}
    
 