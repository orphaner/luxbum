<?php

class lbct {
   
   /*------------------------------------------------------------------------------
    COMMENTS
    -----------------------------------------------------------------------------*/
   /**
    *
    * @access private
    * @param boolean return Type of return : true return result as a string, false (default) print in stdout
    */
   function ctPost($field, $return = false) {
      $field = 'get'.ucfirst($field);
      lbFactory::ctPost();
      $result = $GLOBALS['_LB_render']['ctPost']->$field();
      $result = unprotege_input($result);
      if ($return) return $result;
      echo $result;
   }
   
   /**
    *
    * @access public
    * @param boolean return Type of return : true return result as a string, false (default) print in stdout
    */
   function ctPostAuthor($return = false) {
      return lbct::ctPost('author', $return);
   }

   /**
    *
    * @access public
    * @param boolean return Type of return : true return result as a string, false (default) print in stdout
    */
   function ctPostEmail($return = false) {
      return lbct::ctPost('email', $return);
   }

   /**
    *
    * @access public
    * @param boolean return Type of return : true return result as a string, false (default) print in stdout
    */
   function ctPostWebsite($return = false) {
      return lbct::ctPost('website', $return);
   }

   /**
    *
    * @access public
    * @param boolean return Type of return : true return result as a string, false (default) print in stdout
    */
   function ctPostContent($return = false) {
      return lbct::ctPost('content', $return);
   }

   /**
    *
    * @access public
    * @param string $key
    * @param boolean return Type of return : true return result as a string, false (default) print in stdout
    */
   function ctPostError($key, $s='<span class="error">%s</span>', $return = false) {
      lbFactory::ctPost();
      $result = $GLOBALS['_LB_render']['ctPost']->getError($key);
      if (trim($result) != '') {
         $result = sprintf($s, $result);
      }
      if ($return) return $result;
      echo $result;
   }


   /**
    *
    * @access public
    * @param boolean return Type of return : true return result as a string, false (default) print in stdout
    */
   function ctAuthor($s='%s', $return = false) {
      lbFactory::comment();
      $ct = $GLOBALS['_LB_render']['ct']->f();
      $result = sprintf($s, unprotege_input($ct->getAuthor()));
      if ($return) return $result;
      echo $result;
   }

   /**
    *
    * @access public
    * @param boolean return Type of return : true return result as a string, false (default) print in stdout
    */
   function ctEmail($s='%s', $return = false) {
      lbFactory::comment();
      $ct = $GLOBALS['_LB_render']['ct']->f();
      $email = $ct->getEmail();
      $result = '';
      if (strlen($email) > 0) {
         $result = sprintf($s, unprotege_input($email));
      }
      if ($return) return $result;
      echo $result;
   }

   /**
    *
    * @access public
    * @param boolean return Type of return : true return result as a string, false (default) print in stdout
    */
   function ctWebsite($s='%s', $return = false) {
      lbFactory::comment();
      $ct = $GLOBALS['_LB_render']['ct']->f();
      $website = $ct->getWebsite();
      $result = '';
      if (strlen($website) > 0) {
         $result = sprintf($s, unprotege_input($website));
      }
      if ($return) return $result;
      echo $result;
   }

   /**
    *
    *
    * @param boolean return Type of return : true return result as a string, false (default) print in stdout
    */
   function ctContent($s='%s', $return = false) {
      lbFactory::comment();
      $ct = $GLOBALS['_LB_render']['ct']->f();
      $result = sprintf($s, unprotege_input($ct->getContent()));
      if ($return) return $result;
      echo $result;
   }

   /**
    * @access public
    */
   function ctFormAction() {
      echo '<input type="hidden" name="action" id="action" value="ct"/>';
   }

   /**
    * @access public
    * @return boolean
    */
   function ctHasNext() {
      lbFactory::comment();
      $ct = $GLOBALS['_LB_render']['ct'];
      return !$ct->EOF();
   }

   /**
    *
    * @access public
    * @return boolean
    */
   function ctMoveNext() {
      lbFactory::comment();
      $ct =& $GLOBALS['_LB_render']['ct'];
      $ct->moveNext();
   }
}

?>