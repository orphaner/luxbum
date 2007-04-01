<?php


include('common.php');
$_SESSION['manager'] = true;


include('api/ui/views_manager.php');
include('api/ui/link_manager.php');
include('api/ui/lib.backend.php');


// Registers views
$dispatcher->registerController('ManagerIndexView', '#^/$#i');
$dispatcher->registerController('ManagerIndexView', '#^/galleries/folder/$#i');
$dispatcher->registerController('ManagerIndexView', '#^/galleries/folder/(.*)/$#i');
$dispatcher->registerController('ManagerIndexView', '#^/galleries/$#i');
$dispatcher->registerController('ManagerGalleryView', '#^/gallery/(.*)/$#i');
$dispatcher->registerController('ManagerCommentsView', '#^/comments/$#i');
$dispatcher->registerController('ManagerToolsView', '#^/tools/$#i');
$dispatcher->registerController('ManagerLoginView', '#^/login/$#i');
$dispatcher->registerController('ManagerLogoutAction', '#^/logout/$#i');

// Actions on the index page
$dispatcher->registerController('IndexAddGalleryAction', '#^/action/index/addgallery/(.*)$#i');
$dispatcher->registerController('IndexPurgeCacheAction', '#^/action/index/sort/(.*)$#i');
$dispatcher->registerController('IndexPurgeCacheAction', '#^/action/index/purgecache/(.*)$#i');
$dispatcher->registerController('IndexGenCacheGalleryAction', '#^/action/index/gencache/(.*)$#i');
$dispatcher->registerController('IndexGalleryRenameAction', '#^/action/index/rename/(.*)$#i');
$dispatcher->registerController('IndexGalleryDeleteAction', '#^/action/index/delete/(.*)$#i');

// Actions on the gallery page
$dispatcher->registerController('GalleryMoveAction', '#^/action/gallery/move/(.*)$#i');
$dispatcher->registerController('GallerySortAction', '#^/action/gallery/sort/(.*)$#i');
$dispatcher->registerController('GalleryPurgeCacheAction', '#^/action/gallery/purgecache/(.*)$#i');
$dispatcher->registerController('GalleryGenCacheAction', '#^/action/gallery/gencache/(.*)$#i');

// Call the right action or view
$dispatcher->Launch($_SERVER['QUERY_STRING']);

?>