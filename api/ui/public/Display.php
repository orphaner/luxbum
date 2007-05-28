<?php

/**
 * @package ui
 */
class ui_public_Display extends ui_CommonView {
   
   private $gallery;
   private $dir;
   private $file;
   

   /**
    *
    * @param Pluf_HTTP_Request $request
    * @param array $match
    */
   function view($request, $match) {
      $this->dir = files::addTailSlash($match[1]);
      $this->file = $match[2];

      // Check if the gallery is private
	  $this->checkFile($this->dir, $this->file);
      $this->checkPrivate($this->dir);

      
      $GLOBALS['isSelection'] = false;
      $this->gallery = new luxBumGallery($this->dir);
      $imgIndex = $this->gallery->getImageIndex($this->file);
      $this->gallery->setDefaultIndex($imgIndex);
      
      return $this->common();
   }
   
   /**
    *
    * @param Pluf_HTTP_Request $request
    * @param array $match
    */
   function selection($request, $match) {
      $this->dir = files::addTailSlash($match[1]);
      $this->file = $match[2];

      // Check if the gallery is private
	  $this->checkFile($this->dir, $this->file);
      $this->checkPrivate($this->dir);

   
      $GLOBALS['isSelection'] = true;
      $this->gallery = new LuxbumSelectionGallery(Selection::getInstance());
      
      if ($this->gallery->getTotalCount() == 0) {
         return new Pluf_HTTP_Response_Redirect(Pluf::f('url_base'));
      }
      
      $imgIndex = $this->gallery->getImageIndex($this->file);
      if ($imgIndex == -1) {
         $imgIndex = 0;
         $this->dir = $this->gallery->f()->getDir();
         $this->file = $this->gallery->f()->getFile();
      }
      $this->gallery->setDefaultIndex($imgIndex);
      
      return $this->common();
   }

   private function common() {
      if (files::isPhotoFile($this->dir, $this->file)) {
	      $cFile = new luxBumImage($this->dir, $this->file);
	      if (!Pluf::f('show_meta')) {
	         $metas = null;
	      }
	      else {
		     $cFile->metaInit();
		     $metas = $cFile->getMeta();
	      }
      }
      else if (files::isFlvFile($this->dir, $this->file)) {
         $cFile = new LuxbumFlv($this->dir, $this->file);
         $metas = null;
      }
            
	  if (Pluf::f('show_comment')) {
	     // Add comment form valid
	     $ctPost = new Commentaire();
	     $ctPost = lbPostAction::comment($request, $ctPost, $cFile);
	     $comments = $cFile->lazyLoadComments();
	  }
	  else {
	     $ctPost = null; 
	     $comments = null;
	  }

      $context = new Pluf_Template_Context(array('gallery'     => $this->gallery,
											     'metas'       => $metas,
											     'isSelection' => $GLOBALS['isSelection'],
											     'comments'    => $comments,
                                                 'ctPost'      => $ctPost,
                                                 'cFile'       => $cFile,
                                                 'cfg'         => $GLOBALS['_PX_config']));

      $tmpl = new Pluf_Template('display.html');
      return new Pluf_HTTP_Response($tmpl->render($context));
   }
}

?>