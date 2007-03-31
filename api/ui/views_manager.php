<?php

class ManagerCommonUI {

   function purgeGlobals() {
      $_GLOBALS['_LB_message'] = '';
   }

   function checkAuth(){
   }
  }

class ManagerCommonView extends ManagerCommonUI {
   var $template;


   function action($match) {
      $this->purgeGlobals();
      $this->checkAuth();
      $this->initTemplate();
      $this->run($match);


      $res =& $GLOBALS['_LB_render']['res'];
      $affpage =& $GLOBALS['LB_render']['affpage']; 

      header('Content-Type: text/html; charset=UTF-8');
      include (TEMPLATE_MANAGER_DIR.$this->template.'.php');
      return 200;
   }

}



class ManagerCommonAction {
   var $toView;

   function action($match) {
      $this->purgeGlobals();
      $this->checkAuth();
      $this->setToView();
      $this->run($match);

      $view = new $this->toView;
      $view->action($match);
   }
}

/**
 * @package ui
 */
class ManagerIndexView extends ManagerCommonView {
   function run($match) {

      $dir = '';

      if (count($match) == 1) { 
         $dir = '';
      }
      else if (count($match) == 2) {
         $dir = files::removeTailSlash($match[1]);
      }
      else {
         echo "erreur";
      }


      $GLOBALS['_LB_render']['res'] = '';
      $res =& $GLOBALS['_LB_render']['res']; 
      $res = new  luxBumIndex ($dir);
      $res->addAllGallery ();
   }


   function initTemplate() {
      $this->template = 'index';
      $GLOBALS['LB']['title'] = __('Gallery management');
   }
}

/**
 * @package ui
 */
class ManagerGalleryView extends ManagerCommonView { 
   function run($match) {

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


      $GLOBALS['LB_render']['affpage'] = new Paginator($currentPage, $res->getIntRowCount(), LIMIT_THUMB_PAGE, MAX_NAVIGATION_ELEMENTS);


      $affpage =& $GLOBALS['LB_render']['affpage'];


   }


   function initTemplate() {
      $this->template = 'gallery';
      $GLOBALS['LB']['title'] = __('Gallery management');
   }
}


/**
 * @package ui
 */
class ManagerLoginView extends ManagerCommonView {
   function run($match) {
   }

   function initTemplate() {
      $this->template = 'login';
      $GLOBALS['LB']['title'] = __('Login to the luxbum manager');
   }
}


/**
 * @package ui
 */
class ManagerLogoutAction extends ManagerCommonAction {
   function run($match) {
   }

   function setToView() {
      $this->toView = 'ManagerLoginView';
   }
}






/**
 * @package ui
 */
class ManagerView extends ManagerCommonView {
   function run($match) {
   }

   function initTemplate() {
      $this->template = '';
      $GLOBALS['LB']['title'] = __('');
   }
}
?>