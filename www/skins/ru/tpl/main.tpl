
        <div id="primaryContent_2columns">
            <div id="columnA_2columns">
            
<!--// info # Сообщения -->
    <div id="info"><?php echo $info; ?></div>
<!--// info end  -->

<!--// main # Главная страница -->
    <?php echo $main; ?>. 
<!--// main end -->    

<!--// list_pages # Список страниц -->
    <h3><?php echo $category; ?></h3>
    <h4>Список страниц</h4>
    <!--// add_page -->
       <a href="<?php echo href('admin', 'add', 'page', iniGET('b')); ?>"> Добавить страницу</a>
       <br /><br />
    <!--// add_page end  -->      
    <!--// list_rows -->    
        <?php echo $num; ?>. 
        <a href="<?php echo href('main', 'page', $id, $id_parent);?>"><?php echo $title; ?></a>
        <!--// admin -->
            <a href="<?php echo href('admin', 'edit', 'page', $id, $id_parent); ?>"><img src="/skins/ru/images/edit.png" border="0" /></a>
            <a href="<?php echo href('admin', 'delete', 'page', $id, $id_parent); ?>"
               onclick="return confirm('Удалить страницу и комментарии к ней?')"><img src="/skins/ru/images/delete.png" border="0" /></a>
        <!--// admin end -->
        <br />
    <!--// list_rows end -->
    <!--// list_empty -->
        Нет страниц
    <!--// list_empty end -->
<!--// list_pages end # Конец списка страниц -->


<!--// page_content # Контент страницы -->
    <h3><?php echo $category; ?></h3>
    <a href="<?php echo href('main', 'category', iniGET('c')) ?>">Вернуться к списку</a>
    <h4><?php echo $title; ?></h4>
    <?php echo $text; ?>
<!--// page_content end  -->

<!--// page_empty -->
    Нет такой страницы
<!--// page_empty end -->


<!--// comments_block # Комментарии -->
<br />
<br />
<br />
<br />
    <h4>Комментарии</h4>
    <div style="text-align:center"><?php echo $page_menu; ?></div>
    <!--// comments_rows --> 
    <div class="comments">
        <h5><?php echo $author; ?></h5>
        <hr width="400px" align="left"/>
            <?php echo $text; ?>
    </div>
    <!--// comments_rows end -->
    <!--// comments_empty -->
        Нет комментариев
    <!--// comments_empty end -->
    <div style="text-align:center"><?php echo $page_menu; ?></div>
    <h4>Добавить комментарий</h4>
<form action="" method="post">
Имя<br />
    <input name="author" type="text" value="<?php echo $author; ?>" /><br />
Текст<br />    
    <textarea name="comment" cols="50" rows="10"><?php echo $comment; ?></textarea><br />
    <input name="ok" type="submit" value="Отправить" />
</form>
<!--// comments_block end # Конец комментариев -->

            </div>
        </div>
   

















