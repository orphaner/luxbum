<?php

/**
 * @package ui
 */
class ui_public_Commentaire extends ui_CommonView {
   
   /**
    * Check access list
    * 
    * @return boolean
    */
   function checkACL() {
      return Pluf::f('show_comment');
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
      $this->checkPrivate($dir);
	  $this->checkFile($dir);
	  
	  $img = new luxBumImage ($dir, $photo);
      $img->metaInit ();


      // Add comment form valid
      $ctPost = new Commentaire();
      $ctPost = lbPostAction::comment($request, $ctPost, $img);

      $comments = $img->lazyLoadComments();

      $context = new Pluf_Template_Context(array('comments' => $comments,
                                                 'ctPost'   => $ctPost,
                                                 'img'      => $img,
                                                 'cfg'      => $GLOBALS['_PX_config']));
      
      
      $tmpl = new Pluf_Template('commentaire.html');
      return new Pluf_HTTP_Response($tmpl->render($context));
   }
}

?>