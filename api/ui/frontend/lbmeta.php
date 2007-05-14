<?php

class lbmeta {
   /*------------------------------------------------------------------------------
    META DATA
    -----------------------------------------------------------------------------*/

   /**
    *
    *
    * @param boolean return Type of return : true return result as a string, false (default) print in stdout
    */
   function metaExists($return = false) {
      $result = $GLOBALS['_LB_render']['img']->hasMeta();
      if ($return) return $result;
      echo $result;
   }

   function getMetaName($return = false) {
      $meta = $GLOBALS['_LB_render']['meta']->f();
      $result = $meta->name;
      if ($return) return $result;
      echo $result;
   }

   function getMetaValue($return = false) {
      $meta = $GLOBALS['_LB_render']['meta']->f();
      $result = $meta->value;
      if ($return) return $result;
      echo $result;
   }
    
}

?>