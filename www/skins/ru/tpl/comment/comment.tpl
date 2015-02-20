
    <br />
    <br />
    <br />
    <br />
    <h4>Комментарии</h4>

    <div style="text-align:center"><?php echo $page_menu; ?></div>
    <form action="<?php echo href('admin', 'delete', 'comment'); ?>" method="post">    
<!--// comments_rows --> 
        <?php echo $comments; ?>
<!--// comments_rows end -->
    </form>
<!--// comments_empty -->
        Нет комментариев
<!--// comments_empty end -->
    <form action="" method="post">    
    <div style="text-align:center"><?php echo $page_menu; ?></div>
    <a name="answer"></a>
    <h4>Добавить комментарий</h4>
<!--// answer -->
<strong style="color:red">Вы отвечаете на комментарий <?php echo $collocutor; ?></strong>
<br />
<!--// answer end -->
    Имя<br />
        <input name="author" type="text" value="<?php echo $author; ?>" /><br />
    Текст<br />    
        <textarea name="comment" cols="50" rows="10"><?php echo $comment; ?></textarea><br />  
        <input type="submit" value="Отправить" />
    </form>















