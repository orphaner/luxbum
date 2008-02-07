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
    * @return Pluf_Template_Context
    */
   public function index($request, $match) {
      $dir = '';
      $index = true;
      return $this->common($dir, $index);
   }
   
   /**
    * 
    * @param Pluf_HTTP_Request $request
    * @param array $match
    * @return Pluf_Template_Context
    */
   public function folder($request, $match) {
      $dir = files::removeTailSlash($match[1]);
      $index = false;
      return $this->common($dir, $index);
   }
   
   /**
    * Common function that display an index or folder page
    *
    * @param String $dir
    * @param boolean $index
    * @return Pluf_Template_Context
    */
   private function common($dir, $index) {
      
      // Check if the gallery is private
      $this->checkDir($dir);
      $this->checkPrivate($dir);

      $galleries = new luxBumIndex($dir);
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