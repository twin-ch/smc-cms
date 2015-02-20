 
<?php if(base\helpers\look::checkRole('trusted')){ ?>        

<a href="<?php echo href('admin', 'edit', 'category', $id); ?> ">
<img src="/skins/ru/images/edit.png" border="0" /></a>
<a href="<?php echo href('admin', 'delete', 'category', $id); ?> ">
<img src="/skins/ru/images/delete.png" border="0" onclick="return confirm(' <?php echo getLanguage('DELETE_CATEGORY'); ?>')"/></a> 

<?php } ?>

<a href="<?php echo  href('main', 'category', $id) ?>"><?php echo $name; ?></a>

