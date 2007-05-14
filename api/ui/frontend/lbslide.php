<?php
class lbslide {

   /*------------------------------------------------------------------------------
    SLIDESHOW
    -----------------------------------------------------------------------------*/

   /**
    *
    *
    * @param boolean return Type of return : true return result as a string, false (default) print in stdout
    */
   function photoDir($return = false) {
      $result = PHOTOS_DIR;
      if ($return) return $result;
      echo $result;
   }

   /**
    *
    *
    * @param boolean return Type of return : true return result as a string, false (default) print in stdout
    */
   function slideshowTime($return = false) {
      $result = SLIDESHOW_TIME;
      if ($return) return $result;
      echo $result;
   }

   /**
    *
    *
    * @param boolean return Type of return : true return result as a string, false (default) print in stdout
    */
   function slideshowDir($return = false) {
      $result = $GLOBALS['LB']['dir'];
      if ($return) return $result;
      echo $result;
   }

   /**
    *
    *
    * @param boolean return Type of return : true return result as a string, false (default) print in stdout
    */
   function slideshowFadingText($return = false) {
      if (lbconf::slideshowFadingEnabled()) {
         $result = 'true';
      }
      else {
         $result = 'false';
      }
      if ($return) return $result;
      echo $result;
   }

   /**
    *
    *
    * @param boolean return Type of return : true return result as a string, false (default) print in stdout
    */
   function slideshowFadingCheckbox($return = false) {
      if (lbconf::slideshowFadingEnabled()) {
         $result = 'checked';
      }
      else {
         $result = '';
      }
      if ($return) return $result;
      echo $result;
   }
}
?>