<?php

  /**
   * @package ui
   */
class PrivateView {
   function action ($match) {
      IndexView::initTemplate();

      $dir = files::removeTailSlash($match[1]);


      $GLOBALS['_LB_render']['res'] = '';
      $res =& $GLOBALS['_LB_render']['res']; 
      $res = new luxBumIndex($dir);
      $res->addAllGallery ();

      // if the login is OK
      if (lbPostAction::login($dir)) {
         if ($res->isSelfGallery()) {
            header('Location: '.link::vignette($dir));
         }
         else {
            header('Location: '.link::subGallery($dir));
         }
      }

      header('Content-Type: text/html; charset=UTF-8');
      include (TEMPLATE_DIR.TEMPLATE.'/private.php');
      return 200;
   }


   function initTemplate() {
      $GLOBALS['LB']['title'] = NOM_GALERIE;
   }
  }


/**
 * @package ui
 */
class IndexView {
   function action ($match) {
      IndexView::initTemplate();
      // HookInitTemplate

      if (count($match) == 1) { 
         $dir = '';
      }
      else if (count($match) == 2) {
         $dir = files::removeTailSlash($match[1]);
      }
      else {
         echo "erreur";
      }

      // Check if the gallery is private
      if (PrivateManager::isLockedStatic($dir)) {
         return PrivateView::action($match);
      }

      $GLOBALS['_LB_render']['res'] = '';
      $res =& $GLOBALS['_LB_render']['res']; 
      $res = new  luxBumIndex ($dir);
      $res->addAllGallery ();


      header('Content-Type: text/html; charset=UTF-8');
      include (TEMPLATE_DIR.TEMPLATE.'/index.php');
      return 200;
   }


   function initTemplate() {
      $GLOBALS['LB']['title'] = NOM_GALERIE;
   }
}


/**
 * @package ui
 */
class VignetteView {
   function action ($match) {

      if (count($match) == 3) { 
         $dir = $match[1];
         $defaultImage = $match[2];
      }
      else if (count($match) == 2) {
         $dir = $match[1];
         $currentPage = 1;
      }
      else {
         echo "erreur";
      }

      // Check if the gallery is private
      if (PrivateManager::isLockedStatic($dir)) {
         return PrivateView::action($match);
      }


      $GLOBALS['_LB_render']['res'] = luxBumGallery::getInstance($dir);
      $res =& $GLOBALS['_LB_render']['res']; 


      $galleryCount = $res->getCount ();
      
      $niceDir = ucfirst (luxBum::niceName ($res->getName()));
      $GLOBALS['LB']['title'] =  $niceDir.' - '.NOM_GALERIE;
      $GLOBALS['LB']['niceDir'] = $niceDir;
      $res->createOrMajDescriptionFile ();
      $res->getDescriptions ();

      if (isset($defaultImage)) {
         $imgIndex = $res->getImageIndex($defaultImage);
         $res->setDefaultIndex($imgIndex);
         $currentPage = (int)($imgIndex / LIMIT_THUMB_PAGE)+1;
      }
      else {
         $res->setDefaultIndex(($currentPage * LIMIT_THUMB_PAGE) - LIMIT_THUMB_PAGE);
      }
      $res->setStartOfPage(($currentPage * LIMIT_THUMB_PAGE) - LIMIT_THUMB_PAGE);
      $res->setEndOfPage((($currentPage) * LIMIT_THUMB_PAGE) );
      $res->reset();
      
      $GLOBALS['LB']['currentPage'] = $currentPage;
      $GLOBALS['_LB_render']['dir'] = $dir;
      $GLOBALS['_LB_render']['img'] = $res->getDefault();


      $GLOBALS['_LB_render']['affpage'] = new Paginator($currentPage, $res->getIntRowCount(), LIMIT_THUMB_PAGE, MAX_NAVIGATION_ELEMENTS);


      $affpage =& $GLOBALS['_LB_render']['affpage'];

      // Add comment form valid
      lbPostAction::comment();

      header('Content-Type: text/html; charset=UTF-8');
      include (TEMPLATE_DIR.TEMPLATE.'/vignette.php');
      return 200;
   }
}


/**
 * @package ui
 */
class CommentaireView {
   function action ($match) {
      $dir = $match[1];
      $photo = $match[2];

      // Check if the gallery is private
      if (PrivateManager::isLockedStatic($dir)) {
         return PrivateView::action($match);
      }

      verif::photo($dir, $photo);
      $GLOBALS['_LB_render']['img'] = new luxBumImage ($dir, $photo);
      $GLOBALS['_LB_render']['img']->metaInit ();
      $GLOBALS['LB']['title'] = NOM_GALERIE;



      $GLOBALS['_LB_render']['ctPost'] = new Commentaire();

      // Add comment form valid
      lbPostAction::comment();

      $GLOBALS['_LB_render']['ct'] = $GLOBALS['_LB_render']['img']->lazyLoadComments();
      //$ct =& $GLOBALS['_LB_render']['ct'];

      header('Content-Type: text/html; charset=UTF-8');
      include (TEMPLATE_DIR.TEMPLATE.'/commentaire.php');
      return 200;
   }
}


/**
 * @package ui
 */
class AffichageView {
   function action ($match) {
      $dir = $match[1];
      $photo = $match[2];

      // Check if the gallery is private
      if (PrivateManager::isLockedStatic($dir)) {
         return PrivateView::action($match);
      }

      verif::photo($dir, $photo);
      
      $GLOBALS['_LB_render']['img'] = new luxBumImage ($dir, $photo);
      $GLOBALS['_LB_render']['img']->metaInit();
      $GLOBALS['_LB_render']['meta'] = $GLOBALS['_LB_render']['img']->getMeta();
      $meta =& $GLOBALS['_LB_render']['meta']; 

      $GLOBALS['_LB_render']['res'] = new luxBumGallery($dir);
      $res =& $GLOBALS['_LB_render']['res']; 
      $imgIndex = $res->getImageIndex($photo);
      $res->setDefaultIndex($imgIndex);

      // Add comment form valid
      lbPostAction::comment();

      $GLOBALS['LB']['title'] =  ' - '.NOM_GALERIE;
      
      header('Content-Type: text/html; charset=UTF-8');
      include (TEMPLATE_DIR.TEMPLATE.'/affichage.php');
      return 200;
   }
}


/**
 * @package ui
 */
class InfosMetaView {
   function action ($match) {
      $dir = $match[1];
      $photo = $match[2];

      // Check if the gallery is private
      if (PrivateManager::isLockedStatic($dir)) {
         return PrivateView::action($match);
      }

      verif::photo($dir, $photo);
      $GLOBALS['_LB_render']['img'] = new luxBumImage ($dir, $photo);
      $GLOBALS['LB']['title'] =  ' - '.NOM_GALERIE;

      $GLOBALS['_LB_render']['img']->metaInit ();
      $GLOBALS['_LB_render']['meta'] = $GLOBALS['_LB_render']['img']->getMeta();
      $meta =& $GLOBALS['_LB_render']['meta']; 

      // Add comment form valid
      lbPostAction::comment();

      header('Content-Type: text/html; charset=UTF-8');
      include (TEMPLATE_DIR.TEMPLATE.'/meta.php');
      return 200;
   }
}

/**
 * @package ui
 */
class SlideShowView {
   function action ($match) {
      $dir = $match[1];

      // Check if the gallery is private
      if (PrivateManager::isLockedStatic($dir)) {
         return PrivateView::action($match);
      }

      $GLOBALS['LB']['dir'] = $dir;
      $GLOBALS['LB']['title'] =  ' - '.NOM_GALERIE;

      $GLOBALS['_LB_render']['res'] = luxBumGallery::getInstance($dir);
      $res =& $GLOBALS['_LB_render']['res']; 

      // Add comment form valid
      lbPostAction::comment();

      header('Content-Type: text/html; charset=UTF-8');
      include (TEMPLATE_DIR.TEMPLATE.'/slideshow.php');
      return 200;

   }
}

/**
 * @package ui
 */
class ImageView {
   function action ($match) {
      $type = $match[1];
      $dir = $match[2];
      $photo = $match[3];

      // Check if the gallery is private
      if (PrivateManager::isLockedStatic($dir)) {
         return PrivateView::action($match);
      }

      verif::isImage ($dir, $photo);

      $luxAff = new luxBumImage ($dir, $photo);
      if ($type == 'vignette') {
         $newfile = $luxAff->getAsThumb(VIGNETTE_THUMB_W, VIGNETTE_THUMB_H);
      }
      else if ($type == 'index') {
         $newfile = $luxAff->getAsThumb(INDEX_THUMB_W, INDEX_THUMB_H);
      }
      else if ($type == 'full') {
         $newfile = $luxAff->getImagePath();
      }
      else {
         $newfile = $luxAff->getAsPreview(PREVIEW_W, PREVIEW_H);
      }

      if (headers_sent($file,$lineno) ) {
         die ("fuck header");
      }

      header("Content-Encoding: none");
      header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
      header("Last-Modified: " . gmdate("D, d M Y H:i:s") . "GMT");
      header("Cache-Control: no-cache, must-revalidate");
      header("Pragma: no-cache");
      header('Content-Type: '.$luxAff->getTypeMime());

      if ($fp = fopen($newfile, 'rb')) {
         header("Content-Length: " . filesize($newfile));
         fpassthru($fp);
      }
      @fclose ($fp);
      
      return 200;
   }
}


/**
 *
 * Classe de r�ponses aux actions des formulaires.
 * L'action est lanc�e seulement si le formulaire est effectivement valid�.
 */
class lbPostAction {

   /**
    *
    */
   function comment() {
      if (count($_POST) > 0 && isset($_POST['action']) && $_POST['action'] === 'ct') {
         lbFactory::ctPost();
         $comment =& $GLOBALS['_LB_render']['ctPost'];
         $comment->fillFromPost();
         
         if ($comment->isValidForm()) {
            $comment->fillInfos();
            $GLOBALS['_LB_render']['img']->saveComment($comment);
            $comment = new Commentaire();
         }
      }
   }

   function login($dir) {
      if (count($_POST) > 0 && isset($_POST['action']) && $_POST['action'] === 'private') {
         lbFactory::privatePost();
         $private =&  $GLOBALS['_LB_render']['privatePost'];
         $private->fillFromPost();
         $privateManager =& PrivateManager::getInstance();
         if ($privateManager->unlockDir($dir, $private)) {
            return true;
         }
         return false;
      }
   }
}
?>