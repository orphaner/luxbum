<?php

/**
 * @package ui
 */
class ui_public_Private {
   /**
    * 
    * @param Pluf_HTTP_Request $request
    * @param array $match
    */
   function action($request, $match) {

      $dir = files::removeTailSlash($match[1]);


      $res = new luxBumIndex($dir);
      $res->addAllGallery();

      // if the login is OK
      $privatePost =  new PassPost();
      if (lbPostAction::login($request, $privatePost, $dir)) {
         if ($res->isSelfGallery()) {
            header('Location: '.link::vignette($dir));
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