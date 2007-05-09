<?php

/**
 * @package ui
 */
class ManagerGalleryView extends ManagerCommonView { 
   function run() {

      if (count($this->match) == 3) { 
         $dir = $this->match[1];
         $defaultImage = $this->match[2];
      }
      else if (count($this->match) == 2) {
         $dir = $this->match[1];
         $currentPage = 1;
      }
      else {
         echo "erreur";
      }

      $GLOBALS['_LB_render']['res'] = luxBumGallery::getInstance($dir);
      $res =& $GLOBALS['_LB_render']['res']; 


      $galleryCount = $res->getTotalCount ();
      
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


   }


   function initTemplate() {
      $this->template = 'gallery';
      $GLOBALS['LB']['title'] = __('Gallery management');
   }
}

/**
 * Set the gallery sort
 *
 */
class GallerySortAction extends ManagerCommonAction {
   function setViews() {
      $this->setViewSuccess('ManagerGalleryView');
      $this->setViewError('ManagerGalleryView');
   }

   function run() {
   }
}

/**
 * Purge the gallery cache
 *
 */
class GalleryPurgeCacheAction extends ManagerCommonAction {
   function setViews() {
      $this->setViewSuccess('ManagerGalleryView');
      $this->setViewError('ManagerGalleryView');
   }

   function run() {
   }
}

/**
 * Generate the gallery cache
 *
 */
class GalleryGenCacheAction extends ManagerCommonAction {
   function setViews() {
      $this->setViewSuccess('ManagerGalleryView');
      $this->setViewError('ManagerGalleryView');
   }

   function run() {
   }
}

?>