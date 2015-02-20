
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
    <?php include '/comment/comment.tpl'; ?>
<!--// comments_block end # Конец комментариев -->

            </div>
        </div>















