<?php
$GLOBALS['_PX_starttime'] = microtime(true);

if (!is_file('conf/config.php')) {
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

$dispatcher->registerController('ui_public_Index', 'index', '#^/$#i');
$dispatcher->registerController('ui_public_Index', 'index', '#^/folder/(.*)/$#i');

$dispatcher->registerController('ui_public_FileDownload', 'image', '#^/image/(vignette|apercu|index|full)/(.+)/(.+)$#i');
$dispatcher->registerController('ui_public_FileDownload', 'flv', '#^/flvdl/(.+)/(.+\.flv)$#i');



$dispatcher->registerController('ui_public_Selection', 'select', '#^/select/(.*)/(.*)$#i');
$dispatcher->registerController('ui_public_Selection', 'unselect', '#^/unselect/(.*)/(.*)$#i');
$dispatcher->registerController('ui_public_Selection', 'deleteSelection', '#^/deleteselection/$#i');
$dispatcher->registerController('ui_public_Selection', 'downloadSelection', '#^/downloadselection/$#i');

$dispatcher->registerController('ui_public_Gallery', 'selection', '#^/selectiong/(.*)/(.*)$#i');
//$dispatcher->registerController('SelectionAffichageView', '#^/selectiona/(.*)/(.*)$#i');


$dispatcher->registerController('ui_public_Private', 'action', '#^/private/(.*)/$#i');
$dispatcher->registerController('ui_public_Private', 'action', '#^/private/(.*)/(.*)$#i');

$dispatcher->registerController('ui_public_Gallery', 'view', '#^/album/(.*)/$#i');
$dispatcher->registerController('ui_public_Gallery', 'view', '#^/album/(.*)/(.*)$#i');
$dispatcher->registerController('ui_public_Gallery', 'view', '#^/album/(.+)/(.+\.flv)$#i');

$dispatcher->registerController('ui_public_Display', 'view', '#^/photo/(.*)/(.*)$#i');

$dispatcher->registerController('ui_public_InfosMeta', 'action', '#^/meta/(.*)/(.*)$#i');

$dispatcher->registerController('ui_public_Commentaire', 'action', '#^/comments/(.*)/(.*)$#i');

$dispatcher->registerController('ui_public_SlideShow', 'action', '#^/slide\-show/(.*)$#i');
$dispatcher->registerController('ui_public_SlideShow', 'action', '#^/slide\-show/(.*)/([0-9]+)$#i');

$dispatcher->Launch($_SERVER['QUERY_STRING']);

?>