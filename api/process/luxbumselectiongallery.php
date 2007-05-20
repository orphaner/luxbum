<?php

/**
 *
 */
class LuxbumSelectionGallery extends CommonGallery {

   /**
    * @param Selection $selection
    */
   function LuxbumSelectionGallery($selection) {
      parent::__construct();
      $this->name = __('My selection');

      $array = $selection->toArray();
      for ($i = 0 ; $i < $selection->getCount() ; $i++) {
         $dir = files::addTailSlash($array[$i]['dir']);
         $file = $array[$i]['file'];

         if (files::isPhotoFile($dir, $file)) {
            $object = new luxBumImage ($dir, $file);
            $theFile = luxbum::getFilePath($dir, $file);
            $this->imageSize += filesize ($theFile);
            $this->imageCount++;
            $this->addToList($object);
         }
         else if (files::isFlvFile($dir, $file)) {
            $object = new LuxbumFlv($dir, $file);
            $theFile = luxbum::getFilePath($dir, $file);
            $this->flvSize += filesize ($theFile);
            $this->flvCount++;
            $this->addToList($object);
         }
      }
      $this->totalSize = $this->flvSize + $this->imageSize;
      $this->totalCount = $this->flvCount + $this->imageCount;
   }
   
   /**
    * @return string
    */
   function getDir() {
      return '';
   }

   /**-----------------------------------------------------------------------**/
   /** Private galleries */
   /**-----------------------------------------------------------------------**/
   /**
    * Retourne si la galerie est privée ou non
    * @return Boolean Galerie privée ou non (true / false)
    */
   function isPrivate() {
      return false;
   }

   /**
    * Retourne si la galerie est la première galerie privée ou non dans l'arborescence
    * @return Boolean Galerie privée ou non (true / false)
    */
   function isPrivateExact() {
      return false;
   }

   /**
    *
    */
   function isUnlocked() {
      return true;
   }


   /**-----------------------------------------------------------------------**/
   /** Sub galleries */
   /**-----------------------------------------------------------------------**/
   /**
    *
    */
   function isSubGallery ($paramNotUsed) {
      return false;
   }

   /**
    *
    */
   function hasSubGallery() {
      return false;
   }


   /**-----------------------------------------------------------------------**/
   /** Default image */
   /**-----------------------------------------------------------------------**/
   /**
    * Search for the default image
    * @access private
    */
   protected function _completeDefaultImage () {
      while (!$this->EOF && $this->f()->getType() != TYPE_IMAGE_FILE) {
         $this->next();
      }
      $file = $this->f();
      $this->dir = $file->getDir();
      $this->preview = $file->getFile();
   }
   
   /**-----------------------------------------------------------------------**/
   /** UI Functions */
   /**-----------------------------------------------------------------------**/
   /**
    * @return string the url link to delete the selection
    */
   function getLinkDelete() {
      return link::deleteSelection();
   }
   
   /**
    * @return string the url link to download the selection
    */
   function getLinkDownload() {
      return  link::downloadSelection();
   }
   
   /**
    * @return string the link url to consult the selection gallery
    */
   function getLinkConsult() {
      $this->moveStart();
      $file = $this->f();
      return link::selection($file->getDir(), $file->getFile());
   }
}

?>