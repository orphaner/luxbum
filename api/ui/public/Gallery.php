<?php

/**
 * @package ui
 */
class ui_public_Gallery extends ui_CommonView {
   
   /**
    * Check access list
    * 
    * @return boolean
    */
   function checkACL() {
      return true;
   }
   
   /**
    * 
    * @param Pluf_HTTP_Request $request
    * @param array $match
    */
   public function view($request, $match) {
      if (count($match) == 3) {
         $dir = files::addTailSlash($match[1]);
         $defaultImage = $match[2];
      }
      else {
         echo "erreur";
      }
      
      
      // Check if the gallery is private
	  $this->checkFile($dir, $defaultImage);
      $this->checkPrivate($dir);

      $gallery = new luxBumGallery($dir);
      $gallery->createOrMajDescriptionFile();

      $imgIndex = $gallery->getImageIndex($defaultImage);
      $gallery->setDefaultIndex($imgIndex);
      $currentPage = (int)($imgIndex / LIMIT_THUMB_PAGE)+1;
      $gallery->setStartOfPage(($currentPage * LIMIT_THUMB_PAGE) - LIMIT_THUMB_PAGE);
      $gallery->setEndOfPage((($currentPage) * LIMIT_THUMB_PAGE) );
      $gallery->reset();
      
      $cFile = $gallery->getDefault();
      

      $pages = new Paginator($gallery, $currentPage, $gallery->getTotalCount(), LIMIT_THUMB_PAGE, MAX_NAVIGATION_ELEMENTS);
      
	  if (Pluf::f('show_comment')) {
	     // Add comment form valid
	     $ctPost = new Commentaire();
	     $ctPost = lbPostAction::comment($request, $ctPost, $cFile);
	  }
	  else {
	     $ctPost = null; 
	  }

      $context = new Pluf_Template_Context(array('gallery'  => $gallery, 
                                                 'pages'    => $pages,
                                                 'ctPost'   => $ctPost,
                                                 'cFile'    => $cFile,
                                                 'cfg'      => $GLOBALS['_PX_config']));
      
      
      $tmpl = new Pluf_Template('gallery.html');
      return new Pluf_HTTP_Response($tmpl->render($context));
   }
   
   /**
    * 
    * @param Pluf_HTTP_Request $request
    * @param array $match
    */
   public function selection($request, $match) {
      if (count($match) == 3) {
         //$dir = $match[1];
         $defaultImage = $match[2];
      }
      else {
         echo "erreur";
      }
      
      $GLOBALS['isSelection'] = true;
      $gallery = new LuxbumSelectionGallery(Selection::getInstance());
      
      if ($gallery->getTotalCount() == 0) {
         return new Pluf_HTTP_Response_Redirect(Pluf::f('url_base'));
      }

      $imgIndex = $gallery->getImageIndex($defaultImage);
      if ($imgIndex == -1) {
         $imgIndex = 0;
      }
      $gallery->setDefaultIndex($imgIndex);
      $currentPage = (int)($imgIndex / LIMIT_THUMB_PAGE)+1;
      $gallery->setStartOfPage(($currentPage * LIMIT_THUMB_PAGE) - LIMIT_THUMB_PAGE);
      $gallery->setEndOfPage((($currentPage) * LIMIT_THUMB_PAGE) );
      $gallery->reset();
      

      $pages = new Paginator($gallery, $currentPage, $gallery->getTotalCount(), LIMIT_THUMB_PAGE, MAX_NAVIGATION_ELEMENTS);


      // Add comment form valid
      $ctPost = new Commentaire();
      lbPostAction::comment($request, $ctPost, $img);

      $context = new Pluf_Template_Context(array('gallery' => $gallery, 
                                                 'isSelection' => true,     
                                                 'pages'   => $pages,
                                                 'cFile'   => $gallery->getDefault(),
                                                 'cfg'     => $GLOBALS['_PX_config']));
      
      
      $tmpl = new Pluf_Template('gallery.html');
      return new Pluf_HTTP_Response($tmpl->render($context));
   }
}
?>