<?php

/**
 * @package ui
 */
class ManagerIndexView extends ManagerCommonView {
   function run() {

      $dir = '';

      if (count($this->match) == 1) {
         $dir = '';
      }
      else if (count($this->match) == 2) {
         $dir = files::removeTailSlash($this->match[1]);
      }
      else {
         echo "erreur";
      }


      if (!isset($GLOBALS['_LB_render']['res'])) {
         $res =& $GLOBALS['_LB_render']['res'];
         $res = new luxBumIndex($dir);
         $res->addAllGallery(0);
      }
   }


   function initTemplate() {
      $this->template = 'index';
      $GLOBALS['LB']['title'] = __('Gallery management');
   }
}

/**
 * Add a gallery on the specified gallery
 *
 */
class IndexAddGalleryAction extends ManagerCommonAction {
   function setViews() {
      $this->setViewSuccess(linkmanager::subGalleryIndex($this->match[1]));
      $this->setViewError(linkmanager::subGalleryIndex($this->match[1]));
   }

   function run() {
      $_SESSION[''] = 'not yet';
   }
}

/**
 * Sort the specified gallery
 *
 */
class IndexSortGalleryAction extends ManagerCommonAction {
   function setViews() {
      $this->setViewSuccess(linkmanager::subGalleryIndex($this->match[1]));
      $this->setViewError(linkmanager::subGalleryIndex($this->match[1]));
   }

   function run() {
   }
}

/**
 * Purge the cache of the specified gallery
 *
 */
class IndexPurgeCacheAction extends ManagerCommonAction {
   function setViews() {
      $this->setViewSuccess(linkmanager::subGalleryIndex($this->match[1]));
      $this->setViewError(linkmanager::subGalleryIndex($this->match[1]));
   }

   function run() {
   }
}

/**
 * Generate the cache of the specified gallery
 *
 */
class IndexGenCacheGalleryAction extends ManagerCommonAction {
   function setViews() {
      $this->setViewSuccess(linkmanager::subGalleryIndex($this->match[1]));
      $this->setViewError(linkmanager::subGalleryIndex($this->match[1]));
   }

   function run() {
   }
}

/**
 * Rename a gallery
 *
 */
class IndexGalleryRenameAction extends ManagerCommonAction {
   function setViews() {
      $this->setViewSuccess(linkmanager::subGalleryIndex($this->match[1]));
      $this->setViewError(linkmanager::subGalleryIndex($this->match[1]));
   }

   function run() {
   }
}

/**
 * Delete a gallery
 *
 */
class IndexGalleryDeleteAction extends ManagerCommonAction {
   function setViews() {
      $this->setViewSuccess(linkmanager::subGalleryIndex($this->match[1]));
      $this->setViewError(linkmanager::subGalleryIndex($this->match[1]));
   }

   function run() {
   }
}

?>