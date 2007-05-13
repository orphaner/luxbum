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
   var $viewPage = 'vignette';

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

      $this->setRes($dir);
      $res =& $GLOBALS['_LB_render']['res'];

      $niceDir = ucfirst (luxBum::niceName ($res->getName()));
      $GLOBALS['LB']['title'] =  $niceDir.' - '.NOM_GALERIE;
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
      include (TEMPLATE_DIR.TEMPLATE.'/'.$this->viewPage.'.php');
      return 200;
   }
   
   function setRes($dir) {
      $GLOBALS['_LB_render']['res'] = luxBumGallery::getInstance($dir);
   }
}


/**
 * @package ui
 */
class FlvView extends VignetteView {
   var $viewPage = 'flv';
}

class SelectionVignetteView extends VignetteView {
   
   function setRes() {
      $GLOBALS['_LB_render']['res'] = luxBumGallery::getInstance($dir);
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

class SelectView {
   function action ($match) {
      $dir = $match[1];
      $file = $match[2];

      $selection = Selection::getInstance();
      $selection->addFile($dir, $file);
      Selection::saveSelection($selection);
      
      $redirect = link::vignette($dir, $file);
      header('Location: '.$redirect);
   }
}

class UnselectView {
   function action ($match) {
      $dir = $match[1];
      $file = $match[2];

      $selection = Selection::getInstance();
      $selection->removeFile($dir, $file);
      Selection::saveSelection($selection);
      
      $redirect = link::vignette($dir, $file);
      header('Location: '.$redirect);
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
         $newfile = $luxAff->generateThumb(VIGNETTE_THUMB_W, VIGNETTE_THUMB_H);
      }
      else if ($type == 'index') {
         $newfile = $luxAff->generateThumb(INDEX_THUMB_W, INDEX_THUMB_H);
      }
      else if ($type == 'full') {
         $newfile = $luxAff->getImagePath();
      }
      else {
         $newfile = $luxAff->generatePreview(PREVIEW_W, PREVIEW_H);
      }

      if (headers_sent($file,$lineno) ) {
         die ("fuck header");
      }

      header('Content-Encoding: none');
      header('Content-Type: '.$luxAff->getTypeMime());
      header('Cache-Control: maxage=3600');
      header('Pragma: public');
      header('Last-Modified: ' + HTTP::getHttpDate(filemtime($newfile)));
      header('Expires: ' . HTTP::getHttpDate(time() + 3600));
      
      if ($fp = fopen($newfile, 'rb')) {
         while (!feof($fp)) {
            print fread($fp, 4096);
         }
      }
      @fclose ($fp);

      return 200;
   }
}

/**
 * @package ui
 */
class FlvDlView {
   function action ($match) {
      $dir = $match[1];
      $file = $match[2];

      // Check if the gallery is private
      if (PrivateManager::isLockedStatic($dir)) {
         return PrivateView::action($match);
      }

      if (headers_sent($ffff,$lineno) ) {
         die ("fuck header");
      }

      $luxAff = new LuxbumFlv($dir, $file);
      
      header('Content-Encoding: none');
      header('Content-Type: '.$luxAff->getTypeMime());
      header('Cache-Control: maxage=3600');
      header('Pragma: public');
      header('Last-Modified: ' + HTTP::getHttpDate(filemtime(luxbum::getFilePath($dir, $file))));
      header('Expires: ' . HTTP::getHttpDate(time() + 3600));
      
      
      if ($fp = fopen(luxbum::getFilePath($dir, $file), 'rb')) {
         while (!feof($fp)) {
            print fread($fp, 4096);
         }
      }
      @fclose ($fp);

      return 200;
   }
}

/**
 * 
 */
class HTTP {
   /**
    * Return a date and time string that is conformant to RFC 2616
    * @see http://www.w3.org/Protocols/rfc2616/rfc2616-sec3.html#sec3.3
    *
    * @param int $time the unix timestamp of the date we want to return,
    *                empty if we want the current time
    * @return string a date-string conformant to the RFC 2616
    */
   function getHttpDate($time='') {
      if ($time == '') {
         $time = time();
      }
      /* Use fixed list of weekdays and months, so we don't have to fiddle with locale stuff */
      $months = array('01' => 'Jan', '02' => 'Feb', '03' => 'Mar',
		      '04' => 'Apr', '05' => 'May', '06' => 'Jun',
		      '07' => 'Jul', '08' => 'Aug', '09' => 'Sep',
		      '10' => 'Oct', '11' => 'Nov', '12' => 'Dec');
      $weekdays = array('1' => 'Mon', '2' => 'Tue', '3' => 'Wed',
		      '4' => 'Thu', '5' => 'Fri', '6' => 'Sat',
		      '0' => 'Sun');
      $dow = $weekdays[gmstrftime('%w', $time)];
      $month = $months[gmstrftime('%m', $time)];
      $out = gmstrftime('%%s, %d %%s %Y %H:%M:%S GMT', $time);
      return sprintf($out, $dow, $month);
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