<?php

/**
 * @package ui
 */
class ui_public_Index extends ui_CommonView {
   
   public function checkPrivate() {
      return false;
   }
   
   /**
    * 
    * @param Pluf_HTTP_Request $request
    * @param array $match
    */
   public function index($request, $match) {

      if (count($match) == 1) {
         $dir = '';
         $index = true;
      }
      else if (count($match) == 2) {
         $dir = files::removeTailSlash($match[1]);
         $index = false;
      }
      else {
         throw new Exception("Unable to find the selected gallery");
      }

      // Check if the gallery is private
      if (PrivateManager::isLockedStatic($dir)) {
         return PrivateView::action($match);
      }

      $galleries = new luxBumIndex ($dir);
      $galleries->addAllGallery ();
      
      $selection = new LuxbumSelectionGallery(Selection::getInstance());
      //var_dump($selection);

      $context = new Pluf_Template_Context(array('galleries' => $galleries, 
                                                 'selection' => $selection,
                                                 'index'     => $index,
                                                 'cfg' => $GLOBALS['_PX_config']));
      
      
      $tmpl = new Pluf_Template('index.html');
      return new Pluf_HTTP_Response($tmpl->render($context));
   }
}
?>