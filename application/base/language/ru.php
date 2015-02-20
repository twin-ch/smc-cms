<?php

namespace base\language;

class Ru
{
    protected static $lang = array( 
                                    'FATAL_ERROR'           => 'Сбой системы, попробуйте позже.', 
                                    'CHANGE_DATA'           => 'Данные успешно изменены',
                                    'EMPTY_NAME'            => 'Нет имени',
                                    'LONG_NAME'             => 'Имя не должно превышать 20 символов',
                                    'ADD_COMMETNT'          => 'Коментарий добавлен',    
                                    'EMPTY_TITLE'           => 'Нет заголовка',
                                    'EMPTY_TEXT'            => 'Нет текста',
                                    'EMPTY_CATEGORY'        => 'Не выбрана категория',
                                    'EMPTY_CATEGORY_NAME'   => 'Нет названия категории',
                                    'DELETE_CATEGORY'       => 'Удалить категорию и все связанные с ней подкатегории, страницы и комментарии?',
                                    'DELETE_COMMENT'        => 'Удалить комментарий и все вложенные комментарии?',
                             ); 


    public static function translate($phrase)
    {
        if(empty(self::$lang[$phrase]))
            trigger_error('Phrase <em>'. $phrase .'</em> not found', E_USER_WARNING);
        
        return self::$lang[$phrase];
    }
}
    
 