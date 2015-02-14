<!--// category_tree # Дерево категорий -->           
    <h4>Список категорий</h4>
    <!--// category_add -->    
    <a href="<?php echo href('admin', 'add', 'category')?>">Добавить категорию</a>
    <br /><br />
    <!--// category_add end --> 
    
    <!--// category_rows -->
        <?php echo $category; ?>
    <!--// category_rows end -->
    
    <!--// category_empty -->
        Нет категорий
    <!--// category_empty end -->
<!--// category_tree end # Конец деревa категорий -->