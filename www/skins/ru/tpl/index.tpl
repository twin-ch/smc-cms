<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN">
<html>
<head>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type" /> 
<title><?php echo $title; ?></title>
    <meta name="keywords" content="<?php echo $keywords; ?>" />  
    <meta name="description" content="<?php echo $description; ?>" />
  <link rel="stylesheet" type="text/css" href="/skins/ru/css/style.css">
</head>
<body>
  <div id="header">
    <div id="header_inner" class="fixed">
      <div id="logo">
<img src="http://pharm-forum.ru/smiles/21f9fce.gif" border="0" /><h1>SMC-CMS</h1><br />
 Super Mega Classy Content Management System 
      </div>
      <div id="menu">
        <ul id="userMenu">
          <li><a href="<?php echo href('main'); ?>" <?php echo activeLink('page', array('main', 'admin'), true); ?>>Главная</a></li>        
          <li><a href="<?php echo href('users'); ?>"<?php echo activeLink('page', 'users'); ?>>Пользователи</a></li>
          <li><a href="<?php echo href('enter'); ?>"<?php echo activeLink('page', 'enter'); ?>>Вход</a></li>
        </ul>
      </div>
    </div>
  </div>

    <div id="main_inner" class="fixed">
<!--// content -->
<!--// content end -->
        <div id="secondaryContent_2columns">
            <div id="columnC_2columns">
<?php include '/tree_category.tpl'; ?> 
            </div>
        </div>
      <div class="clear"></div> 
    </div>

  <div id="footer" class="fixed">
  <?php echo date('Y')?> twin &copy;
  </div>
</body>
</html>