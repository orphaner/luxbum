<?php

class lbprivate {
   /*------------------------------------------------------------------------------
    PRIVATE GALLERY
    -----------------------------------------------------------------------------*/
   function privateFormAction() {
      echo '<input type="hidden" name="action" id="action" value="private"/>';
   }
   function privatePostError($key, $s='<span class="error">%s</span>', $return = false) {
      lbFactory::privatePost();
      $result = $GLOBALS['_LB_render']['privatePost']->getError($key);
      if (trim($result) != '') {
         $result = sprintf($s, $result);
      }
      if ($return) return $result;
      echo $result;
   }
   function privatePostLogin($return = false) {
      lbFactory::privatePost();
      $result = $GLOBALS['_LB_render']['privatePost']->getLogin();
      $result = unprotege_input($result);
      if ($return) return $result;
      echo $result;
   }
   function privatePostPassword($return = false) {
      lbFactory::privatePost();
      $result = $GLOBALS['_LB_render']['privatePost']->getPassword();
      $result = unprotege_input($result);
      if ($return) return $result;
      echo $result;
   }
}

?>