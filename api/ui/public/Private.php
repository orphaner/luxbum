<?php

/**
 * @package ui
 */
class ui_public_Private extends ui_CommonView {
   
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
   function action($request, $match) {

      $dir = files::removeTailSlash($match[1]);
      
	  $this->checkDir($dir);


      $res = new luxBumIndex($dir);
      $res->addAllGallery();

      // if the login is OK
      $privatePost =  new PassPost();
      if (lbPostAction::login($request, $privatePost, $dir)) {
         if ($res->isSelfGallery()) {
            header('Location: '.link::gallery($res->getSelfGallery()->f()->getDir(), $res->getSelfGallery()->f()->getFile()));
            exit();
         }
         else {
            header('Location: '.link::subGallery($dir));
         }
      }

      $context = new Pluf_Template_Context(array('privatePost' => $privatePost,
                                                 'galleries'   => $res,
                                                 'cfg'         => $GLOBALS['_PX_config']));
      
      
      $tmpl = new Pluf_Template('private.html');
      return new Pluf_HTTP_Response($tmpl->render($context));
   }
}
?>