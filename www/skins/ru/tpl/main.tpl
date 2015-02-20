
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
       <a href="<?php echo href('admin', 'add', 'page', library\irb_url::iniGET('b')); ?>"> Добавить страницу</a>
       <br /><br />
    <!--// add_page end  -->      
    <!--// list_rows -->    
        <?php echo $num; ?>. 
        <a href="<?php echo href('main', 'page', $id, $id_parent);?>"><?php echo $title; ?></a>
        <?php if(base\helpers\look::checkRole('trusted')){ ?>
            <a href="<?php echo href('admin', 'edit', 'page', $id, $id_parent); ?>"><img src="/skins/ru/images/edit.png" border="0" /></a>
            <a href="<?php echo href('admin', 'delete', 'page', $id, $id_parent); ?>"
               onclick="return confirm('Удалить страницу и комментарии к ней?')"><img src="/skins/ru/images/delete.png" border="0" /></a>
        <?php } ?>
        <br />
    <!--// list_rows end -->
    <!--// list_empty -->
        Нет страниц
    <!--// list_empty end -->
<!--// list_pages end # Конец списка страниц -->


<!--// page_content # Контент страницы -->
    <h3><?php echo $category; ?></h3>
    <a href="<?php echo href('main', 'category', library\irb_url::iniGET('c')) ?>">Вернуться к списку</a>
    <h4><?php echo $title; ?></h4>
    <?php echo $text; ?>
<!--// page_content end  -->

<!--// page_empty -->
    Нет такой страницы
<!--// page_empty end -->


<!--// comments_block # Комментарии -->
    <?php include '/comment/comment.tpl'; ?>
<!--// comments_block end # Конец комментариев -->

            </div>
        </div>
   

















