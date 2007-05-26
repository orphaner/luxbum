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
}


?>