<?php 

namespace controllers;

use views\Enter_View as Enter_View;

class Enter_Controller
{
/**     
* Метод запуска контроллера 
* @access public 
* @param array $get
* @return void   
*/
    public static function run($get)
    {
        
        if($get['a'] === 'all')
        {
            $_SESSION['userdata']['id'] = 1;
            redirect('main');
        }
        elseif($get['a'] == 'trusted')
        {
            $_SESSION['userdata']['id'] = 2;
            redirect('main');
        }
     
        Enter_View::template('/enter');
        Enter_View::createEnter();
        Enter_View::run();
    }
}
















