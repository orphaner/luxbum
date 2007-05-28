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
      $dir = files::addTailSlash($match[1]);
      $defaultImage = $match[2];
      
      // Check if the gallery is private
	  $this->checkFile($dir, $defaultImage);
      $this->checkPrivate($dir);

      $GLOBALS['LB']['dir'] = $dir;
      $GLOBALS['LB']['title'] =  ' - '.NOM_GALERIE;

      $gallery = new luxBumGallery($dir, TYPE_IMAGE_FILE);
      $imgIndex = $gallery->getImageIndex($defaultImage);
      $gallery->setDefaultIndex($imgIndex);
      

      $context = new Pluf_Template_Context(array('gallery'  => $gallery, 
                                                 'cfg'      => $GLOBALS['_PX_config']));
      
      $tmpl = new Pluf_Template('slideshow.html');
      return new Pluf_HTTP_Response($tmpl->render($context));
   }
}
?>