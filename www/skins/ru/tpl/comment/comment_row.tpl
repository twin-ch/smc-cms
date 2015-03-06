        <div class="comment_main">
        <?php if(base\helpers\look::checkRole('trusted')){ ?>
        <img src="" border="0" />
        <input name="delete[<?php echo $id;?>]" type="submit" class="delete" value="" 
        onclick="return confirm(' <?php echo getLanguage('DELETE_COMMENT'); ?>')"/>
            <br />
        <?php } ?>
            <h5><?php echo $author; ?></h5>
            <hr width="400px" align="left"/>
                <?php echo $text; ?>
                 
                <div style="text-align:right"> <a href="<?php echo $link_ans .'#answer'; ?>">Ответить</a></div>
        </div>
  
