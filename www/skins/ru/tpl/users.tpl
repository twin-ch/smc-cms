
        <div id="primaryContent_2columns">
            <div id="columnA_2columns">
            
<!--// info # Сообщения -->
    <div id="info"><?php echo $info; ?></div>
<!--// info end  -->

<!--// list_users # Список юзеров -->
    <h4>Список юзеров</h4>
    <!--// users_rows -->
        <?php echo $num; ?>. 
        <a href="<?php echo href('users', 'user', $id); ?>"><?php echo $login; ?></a> (<?php echo $role; ?>)
        <br />
    <!--// users_rows end -->
    <!--// users_empty -->
        Нет юзеров
    <!--// users_empty end -->
<!--// list_users end # Конец списка юзеров -->


<!--// user # Конкретный юзер -->
    <a href="<?php echo href('users') ?>">Вернуться к списку</a>
    <h3><?php echo $login; ?> </h3>
    (<?php echo $role; ?>)
<!--// user end  -->

<!--// user_empty -->
    Нет такого юзера
<!--// user_empty end  # Кирдык юзеру  -->


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















