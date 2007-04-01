<?php

class linkmanager extends link {

   function prefix() {
      return URL_BASE.'manager.php?/';
   }

   function subGallery($dir) {
      $dir = link::encode($dir);
      $dir = files::addTailSlash($dir);
      return  linkmanager::prefix().'galleries/folder/'.$dir;
   }

   function manageGallery($dir) {
      $dir = link::encode($dir);
      $dir = files::addTailSlash($dir);
      return  linkmanager::prefix().'gallery/'.$dir;
   }

   function actionGalleryRename($dir) {
      $dir = link::encode($dir);
      return linkmanager::prefix().'action/gallery/rename/'.$dir;
   }

   function actionGalleryMove($dir) {
      $dir = link::encode($dir);
      return linkmanager::prefix().'action/gallery/move/'.$dir;
   }

   function actionGalleryDelete($dir) {
      $dir = link::encode($dir);
      return linkmanager::prefix().'action/gallery/delete/'.$dir;
   }

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