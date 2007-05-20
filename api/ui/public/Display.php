<?php

/**
 * @package ui
 */
class ui_public_Display {
   /**
    *
    * @param Pluf_HTTP_Request $request
    * @param array $match
    */
   function view($request, $match) {
      $dir = files::addTailSlash($match[1]);
      $file = $match[2];

      // Check if the gallery is private
      if (PrivateManager::isLockedStatic($dir)) {
         return PrivateView::action($match);
      }

      verif::photo($dir, $file);

      $gallery = new luxBumGallery($dir);
      $imgIndex = $gallery->getImageIndex($file);
      $gallery->setDefaultIndex($imgIndex);

      if (files::isPhotoFile($dir, $file)) {
	      $cFile = new luxBumImage($dir, $file);
	      $cFile->metaInit();
	      $metas = $cFile->getMeta();
      }
      else if (files::isFlvFile($dir, $file)) {
         $cFile = new LuxbumFlv($dir, $file);
         $metas = null;
      }
      
      // Add comment form valid
      $ctPost = new Commentaire();
      $ctPost = lbPostAction::comment($request, $ctPost, $cFile);

      $comments = $cFile->lazyLoadComments();

      $context = new Pluf_Template_Context(array('gallery'  => $gallery,
											     'metas'    => $metas,
											     'comments' => $comments,
                                                 'ctPost'   => $ctPost,
                                                 'cFile'    => $cFile,
                                                 'cfg'      => $GLOBALS['_PX_config']));

      $tmpl = new Pluf_Template('display.html');
      return new Pluf_HTTP_Response($tmpl->render($context));
   }
}

?>