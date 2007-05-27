<?php

/**
 * @package ui
 */
abstract class ui_CommonView {

   /**
    * Check access list
    *
    * @return boolean
    */
   public function checkACL() {
      return true;
   }

   /**
    * Enter description here...
    *
    * @param string $dir
    */
   public function checkPrivate($dir) {
      if (PrivateManager::isLockedStatic($dir)) {
         throw new Pluf_HTTP_PrivateException($dir);
      }
   }
   
   /**
    * Enter description here...
    *
    * @param string $dir
    */
   public function checkDir($dir) {
      $dir = files::addTailSlash($dir);
      if (!is_dir(luxBum::getFsPath($dir))) {
         throw new Pluf_HTTP_FileSystemException(sprintf(__('The gallery %s doesn\'t exists.'), $dir));
      }
   }
   
   /**
    * Enter description here...
    *
    * @param string $file
    */
   public function checkFile($dir, $file) {
      $dir = files::addTailSlash($dir);
      $this->checkDir($dir);
      if (!file_exists(luxBum::getFilePath($dir, $file))) {
         throw new Pluf_HTTP_FileSystemException(sprintf(__('The file %s doesn\'t exists.'), $dir . $file));
      }
   }
   
   /**
    * Default view contructor useful for basics verifications
    *
    */
   public function __construct() {
      // TODO: put here some basic verifications: tmp & photos directories exists and writeable ; install.php ; conf OK !
   }
}


?>