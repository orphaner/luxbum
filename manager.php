<?php


include('common.php');


include('api/ui/views_manager.php');
include('api/ui/link_manager.php');
include('api/ui/lib.backend.php');

$dispatcher->registerController('ManagerIndexView', '#^/$#i');
$dispatcher->registerController('ManagerIndexView', '#^/galleries/folder/(.*)/$#i');
$dispatcher->registerController('ManagerIndexView', '#^/galleries/$#i');
$dispatcher->registerController('ManagerGalleryView', '#^/gallery/(.*)/$#i');
$dispatcher->registerController('ManagerCommentsView', '#^/comments/$#i');
$dispatcher->registerController('ManagerToolsView', '#^/tools/$#i');
$dispatcher->registerController('ManagerLoginView', '#^/login/$#i');
$dispatcher->registerController('ManagerLogoutAction', '#^/logout/$#i');

$dispatcher->Launch($_SERVER['QUERY_STRING']);

?>