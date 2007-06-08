<?php
$GLOBALS['_PX_starttime'] = microtime(true);

if (!file_exists('conf/config.php')) {
   exit('<a href="install.php">please install luxbum</a>');
}

include('common.php');
$_SESSION['manager'] = false;



Pluf::loadConfig('conf/config.php');
include (TEMPLATE_DIR.Pluf::f('template').'/conf_'.Pluf::f('template').'.php');


/**
 *
 * Classe de réponses aux actions des formulaires.
 * L'action est lancée seulement si le formulaire est effectivement validé.
 */
class lbPostAction {

   /**
    *
    * @param Pluf_HTTP_Request $request
    * @param Commentaire $comment
    * @param CommonFile $file
    * @return Commentaire
    */
   function comment($request, $comment, $file) {
      if (count($request->POST) > 0 && isset($request->POST['action']) && $request->POST['action'] === 'ct') {
         $comment->fillFromPost($request);

         if ($comment->isValidForm()) {
            $comment->fillInfos();
            $file->saveComment($comment);
            $comment = new Commentaire();
         }
      }
      return $comment;
   }

   /**
    *
    * @param Pluf_HTTP_Request $request
    * @param Commentaire $comment
    * @param PassPost $$privatePost
    * @return Commentaire
    */
   function login($request, $privatePost, $dir) {
      if (count($request->POST) > 0 && isset($request->POST['action']) && $request->POST['action'] === 'private') {
         $privatePost->fillFromPost();
         $privateManager =& PrivateManager::getInstance();
         if ($privateManager->unlockDir($dir, $privatePost)) {
            return true;
         }
         return false;
      }
   }
}

// Index views
$dispatcher->registerController('ui_public_Index', 'index', '#^/$#i');
$dispatcher->registerController('ui_public_Index', 'index', '#^/folder/(.*)/$#i');

// View files (image & flv)
$dispatcher->registerController('ui_public_FileDownload', 'image', '#^/image/(vignette|apercu|index|full)/(.+)/(.+)$#i');
$dispatcher->registerController('ui_public_FileDownload', 'flv', '#^/flvdl/(.+)/(.+\.flv)$#i');

// Selection
$dispatcher->registerController('ui_public_Selection', 'select', '#^/select/(.*)/(.*)$#i');
$dispatcher->registerController('ui_public_Selection', 'unselect', '#^/unselect/(.*)/(.*)$#i');
$dispatcher->registerController('ui_public_Selection', 'selectall', '#^/selectall/(.*)/$#i');
$dispatcher->registerController('ui_public_Selection', 'unselectall', '#^/unselectall/(.*)/$#i');
$dispatcher->registerController('ui_public_Selection', 'deleteSelection', '#^/deleteselection/$#i');
$dispatcher->registerController('ui_public_Selection', 'downloadSelection', '#^/downloadselection/$#i');
$dispatcher->registerController('ui_public_Gallery', 'selection', '#^/selectiong/(.*)/(.*)$#i');
$dispatcher->registerController('ui_public_Display', 'selection', '#^/selectiond/(.*)/(.*)$#i');

// Private views
$dispatcher->registerController('ui_public_Private', 'action', '#^/private/(.*)/$#i');
$dispatcher->registerController('ui_public_Private', 'action', '#^/private/(.*)/(.*)$#i');

// Gallery views
$dispatcher->registerController('ui_public_Gallery', 'view', '#^/gallery/(.*)/$#i');
$dispatcher->registerController('ui_public_Gallery', 'view', '#^/gallery/(.*)/(.*)$#i');
$dispatcher->registerController('ui_public_Gallery', 'view', '#^/gallery/(.+)/(.+\.flv)$#i');

// File display view
$dispatcher->registerController('ui_public_Display', 'view', '#^/display/(.*)/(.*)$#i');

// Image meta informations views
$dispatcher->registerController('ui_public_InfosMeta', 'action', '#^/meta/(.*)/(.*)$#i');

// File comments
$dispatcher->registerController('ui_public_Commentaire', 'action', '#^/comments/(.*)/(.*)$#i');

// Image gallery slideshow
$dispatcher->registerController('ui_public_SlideShow', 'action', '#^/slide\-show/(.*)/(.*)$#i');


// Select the correct view and display it
$dispatcher->Launch($_SERVER['QUERY_STRING']);


?>