<?php


/**
 * @package ui
 */
class ui_public_InfosMeta extends ui_CommonView {
   
   /**
    * Check access list
    * 
    * @return boolean
    */
   function checkACL() {
      return Pluf::f('show_meta');
   }
   
   /**
    * 
    * @param Pluf_HTTP_Request $request
    * @param array $match
    */
   function action($request, $match) {
      $dir = files::addTailSlash($match[1]);
      $photo = $match[2];

      // Check if the gallery is private
	  $this->checkFile($dir, $photo);
      $this->checkPrivate($dir);

      verif::photo($dir, $photo);
      $img = new luxBumImage ($dir, $photo);
      $img->metaInit();
      $metas = $img->getMeta();

      $context = new Pluf_Template_Context(array('metas'   => $metas,
                                                 'img'     => $img,
                                                 'cfg'     => $GLOBALS['_PX_config']));
      
      
      $tmpl = new Pluf_Template('meta.html');
      return new Pluf_HTTP_Response($tmpl->render($context));
   }
}
?>