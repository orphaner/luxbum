<?php

class linkmanager extends link {

   function prefix() {
      return URL_BASE.'manager.php?/';
   }

   function galleryIndex() {
      return  linkmanager::prefix();
   }

   /*------------------------------------------------------------------------------
    Links to the manager differents pages
    -----------------------------------------------------------------------------*/
   function subGalleryIndex($dir) {
      if ($dir == '') {
         return linkmanager::galleryIndex();
      }
      $dir = link::encode($dir);
      $dir = files::addTailSlash($dir);
      return  linkmanager::prefix().'galleries/folder/'.$dir;
   }

   function manageGallery($dir) {
      $dir = link::encode($dir);
      $dir = files::addTailSlash($dir);
      return  linkmanager::prefix().'gallery/'.$dir;
   }

   /*------------------------------------------------------------------------------
    Links to actions on the index page
    -----------------------------------------------------------------------------*/
   function actionIndexSort($dir) {
      $dir = link::encode($dir);
      return linkmanager::prefix().'action/index/sort/';
   }

   function actionIndexAddGallery($dir) {
      $dir = link::encode($dir);
      return linkmanager::prefix().'action/index/addgallery/'.$dir;
   }

   function actionIndexGencache($dir) {
      $dir = link::encode($dir);
      return linkmanager::prefix().'action/index/gencache/';
   }

   function actionIndexPurgecache($dir) {
      $dir = link::encode($dir);
      return linkmanager::prefix().'action/index/purgecache/';
   }
   
   function actionIndexGalleryRename($dir) {
      $dir = link::encode($dir);
      return linkmanager::prefix().'action/index/rename/'.$dir;
   }

   function actionIndexGalleryDelete($dir) {
      $dir = link::encode($dir);
      return linkmanager::prefix().'action/index/delete/'.$dir;
   }


   /*------------------------------------------------------------------------------
    Links to actions on the gallery manage page
    -----------------------------------------------------------------------------*/
   function actionGallerySort($dir) {
      $dir = link::encode($dir);
      return linkmanager::prefix().'action/gallery/sort/'.$dir;
   }

   function actionGalleryPurgecache($dir) {
      $dir = link::encode($dir);
      return linkmanager::prefix().'action/gallery/purgecache/'.$dir;
   }

   function actionGalleryGencache($dir) {
      $dir = link::encode($dir);
      return linkmanager::prefix().'action/gallery/gencache/'.$dir;
   }

}

?>