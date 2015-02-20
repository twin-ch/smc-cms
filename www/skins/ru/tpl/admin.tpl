
        <div id="primaryContent_2columns">
            <div id="columnA_2columns">
            
<!--// info # Сообщения -->
    <div id="info"><?php echo $info; ?></div>
<!--// info end  -->


<!--// new_category # Категория -->
    <h4>Новая категория</h4> 
    <form action="" method="post">
        <input name="cat_name" type="text" size="80" value="<?php echo $cat_name;  ?>" />
        <input name="add" type="submit" value="Добавить" />
    </form>
<!--// new_category end # Конец Категория -->


<!--// edit_category -->
    <h4>Редактировать категорию</h4>
    <form action="" method="post">
        <input name="cat_name" type="text" size="80" value="<?php echo $cat_name;  ?>" />
        <input name="edit" type="submit" value="Изменить" />
        <h4>или добавить подкатегорию</h4>    
        <input name="sub_name" type="text" size="80" value="<?php echo $sub_name; ?>" />
        <input name="add" type="submit" value="Добавить" />
    </form>
<!--// edit_category end -->



<!--// add_page # Новая страница -->
    <h3><?php echo $category; ?></h3>
    <a href="<?php echo href('main', 'category', library\irb_url::iniGET('d')) ?>">Вернуться к списку</a>
    <!--// new_page -->
        <h4>Новая страница. Хотите создать?</h4>
    <!--// new_page end -->
    
    <!--// text_page -->
        <h4><?php echo $title; ?></h4> 
            <?php echo $text; ?>  
        <br />
        <br />        
    <!--// text_page end -->
    
    <form action="" method="post">
        <input name="title" type="text" size="80"value="<?php echo $title; ?>" />
        <textarea name="text" cols="80" rows="15"><?php echo $text; ?></textarea><br />
        <input name="ok" type="submit" value="Отправить" />
    </form>
<!--// add_page end # Конец Новая страница -->

<!--// no_access -->
    <h3>У Вас нет прав на редактирование.</h3>
<!--// no_access end  -->

            </div>
        </div>















