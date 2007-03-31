<?php echo '<?xml version="1.0" encoding="UTF-8"?>';?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="MSSmartTagsPreventParsing" content="TRUE" />
    <title><?php lb::pageTitle(); ?></title>

    <?php lb::favicon();?>
    <?php lbm::pageStyle();?>
  </head>

  <body>
    <div id="header">
      <ul>
        <li mXattribut="class:class_galeries" id="menuGaleries"><a href="manager/galleries/"><?php ___('Galleries');?></a></li>
        <li mXattribut="class:class_commentaires" id="menuCommentaires"><a href="manager/comments/"><?php ___('Comments');?></a></li>
        <mx:bloc id="isadmin">
          <li mXattribut="class:class_parametres" id="menuParametres"><a href="manager/parameters/"><?php ___('Parameters');?></a></li>
        </mx:bloc id="isadmin">
        <li mXattribut="class:class_outils" id="menuOutils"><a href="manager/tools/"><?php ___('Tools');?></a></li>
        <li class="last" id="menuDeconnection"><a href="manager/logout/"><?php ___('Logout');?></a></li>
      </ul>
    </div>

    <div id="main">
