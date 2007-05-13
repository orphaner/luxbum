<?php

if (!is_file('conf/config.php')) {
   exit('<a href="install.php">please install luxbum</a>');
}

include('common.php');
$_SESSION['manager'] = false;

$dispatcher->registerController('IndexView', '#^/$#i');
$dispatcher->registerController('IndexView', '#^/folder/(.*)/$#i');


$dispatcher->registerController('SelectView', '#^/select/(.*)/(.*)$#i');
$dispatcher->registerController('UnselectView', '#^/unselect/(.*)/(.*)$#i');
$dispatcher->registerController('SelectionVignetteView', '#^/selectionv/(.*)/(.*)$#i');
$dispatcher->registerController('SelectionAffichageView', '#^/selectiona/(.*)/(.*)$#i');

$dispatcher->registerController('FlvDlView', '#^/flv/(.+)/(.+\.flv)$#i');

$dispatcher->registerController('FlvView', '#^/file/(.+)/(.+\.flv)$#i');

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