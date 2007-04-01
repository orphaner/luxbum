<?php

if (!is_file('conf/config.php')) {
   exit('<a href="install.php">please install luxbum</a>');
}

include('common.php');
$_SESSION['manager'] = true;

$dispatcher->registerController('IndexView', '#^/$#i');
$dispatcher->registerController('IndexView', '#^/folder/(.*)/$#i');
$dispatcher->registerController('ImageView', '#^/image/('.files::removeTailSlash(THUMB_DIR).'|'.files::removeTailSlash(PREVIEW_DIR).'|index|full)/(.+)/(.+)$#i');
$dispatcher->registerController('PrivateView', '#^/private/(.*)/$#i');
$dispatcher->registerController('PrivateView', '#^/private/(.*)/(.*)$#i');
$dispatcher->registerController('VignetteView', '#^/album/(.*)/$#i');
$dispatcher->registerController('VignetteView', '#^/album/(.*)/(.*)$#i');
$dispatcher->registerController('AffichageView', '#^/photo/(.*)/(.*)$#i');
$dispatcher->registerController('InfosMetaView', '#^/meta/(.*)/(.*)$#i');
$dispatcher->registerController('CommentaireView', '#^/comments/(.*)/(.*)$#i');
$dispatcher->registerController('SlideShowView', '#^/slide\-show/(.*)$#i');
$dispatcher->registerController('SlideShowView', '#^/slide\-show/(.*)/([0-9]+)$#i');

$dispatcher->Launch($_SERVER['QUERY_STRING']);

?>