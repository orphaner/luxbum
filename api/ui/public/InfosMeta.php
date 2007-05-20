<?php


class ui_public_InfosMeta {
   /**
    * 
    * @param Pluf_HTTP_Request $request
    * @param array $match
    */
   function action($request, $match) {
      $dir = files::addTailSlash($match[1]);
      $photo = $match[2];

      // Check if the gallery is private
      if (PrivateManager::isLockedStatic($dir)) {
         return PrivateView::action($match);
      }

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