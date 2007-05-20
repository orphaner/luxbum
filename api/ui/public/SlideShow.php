<?php

/**
 * @package ui
 */
class ui_public_SlideShow {
   /**
    * 
    * @param Pluf_HTTP_Request $request
    * @param array $match
    */
   function action($request, $match) {
      $dir = $match[1];

      // Check if the gallery is private
      if (PrivateManager::isLockedStatic($dir)) {
         return PrivateView::action($match);
      }

      $GLOBALS['LB']['dir'] = $dir;
      $GLOBALS['LB']['title'] =  ' - '.NOM_GALERIE;

      $GLOBALS['_LB_render']['res'] = luxBumGallery::getInstance($dir);
      $res =& $GLOBALS['_LB_render']['res'];

      // Add comment form valid
      lbPostAction::comment();

      header('Content-Type: text/html; charset=UTF-8');
      include (TEMPLATE_DIR.TEMPLATE.'/slideshow.php');
      return 200;

   }
}
?>