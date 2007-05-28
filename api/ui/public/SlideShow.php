<?php

/**
 * @package ui
 */
class ui_public_SlideShow extends ui_CommonView {
   
   /**
    * Check access list
    * 
    * @return boolean
    */
   function checkACL() {
      return Pluf::f('show_slideshow');
   }
   
   /**
    * 
    * @param Pluf_HTTP_Request $request
    * @param array $match
    */
   function action($request, $match) {
      $dir = $match[1];
      
      // Check if the gallery is private
	  $this->checkDir($dir);
      $this->checkPrivate($dir);

      $GLOBALS['LB']['dir'] = $dir;
      $GLOBALS['LB']['title'] =  ' - '.NOM_GALERIE;

      $GLOBALS['_LB_render']['res'] = new luxBumGallery($dir);
      $res =& $GLOBALS['_LB_render']['res'];

      // Add comment form valid
      lbPostAction::comment();

      header('Content-Type: text/html; charset=UTF-8');
      include (TEMPLATE_DIR.TEMPLATE.'/slideshow.php');
      return 200;

   }
}
?>