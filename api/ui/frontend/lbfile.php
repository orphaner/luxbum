<?php

class lbfile {


   /*------------------------------------------------------------------------------
    Informations on the file and it's position in the recordset
    -----------------------------------------------------------------------------*/
   /**
    *
    * @access public
    * @return boolean <code>true</code> if the current image is the last. <code>false otherwise</code>
    */
   function isLast() {
      $res = $GLOBALS['_LB_render']['res'];
      return $res->isLast();
   }

   /**
    *
    * @access public
    * @return boolean <code>true</code> if the current image is the last. <code>false otherwise</code>
    */
   function isFirst() {
      $res = $GLOBALS['_LB_render']['res'];
      return $res->isFirst();
   }

   /**
    *
    * @access public
    *
    * @param boolean return Type of return : true return result as a string, false (default) print in stdout
    */
   function dateDescription($s, $return = false) {
      if (isset($GLOBALS['_LB_render']['img'])) {
         $img = $GLOBALS['_LB_render']['img'];
      }
      else {
         $img = $GLOBALS['_LB_render']['res']->f();
      }
      $img->findDescription();
      $result = utf8_encode($img->getDateDesc());
      $result = sprintf($s, $result);
      if ($return) return $result;
      echo $result;
   }

   /**
    *
    * @access public
    * @param boolean return Type of return : true return result as a string, false (default) print in stdout
    * @return int
    */
   function commentCount($return = false) {
      $result =  $GLOBALS['_LB_render']['img']->getNbComment();
      if ($return) return $result;
      echo $result;
   }
   

   /**
    *
    * @access public
    * @param boolean return Type of return : true return result as a string, false (default) print in stdout
    * @return String
    */
   function date($return = false) {
      
   }
   
   /**
    *
    * @access public
    * @param boolean return Type of return : true return result as a string, false (default) print in stdout
    * @return String
    */
   function description($return = false) {
      
   }
   
   /**
    *
    * @access public
    * @param boolean return Type of return : true return result as a string, false (default) print in stdout
    * @return String
    */
   function dateUpload($return = false) {
      
   }
   
   /**
    *
    * @access public
    * @param boolean return Type of return : true return result as a string, false (default) print in stdout
    * @return String
    */
   function name($return = false) {
      
   }
   
   /**
    *
    * @access public
    * @param boolean return Type of return : true return result as a string, false (default) print in stdout
    * @return String
    */
   function size($return = false) {
      
   }
   
   /**
    *
    * @access public
    * @param boolean return Type of return : true return result as a string, false (default) print in stdout
    * @return String
    */
   function niceSize($return = false) {
      
   }
   
   /*------------------------------------------------------------------------------
    Links / back-forward in vignette pages
    -----------------------------------------------------------------------------*/

   /**
    *
    *
    * @access public
    * @param string $s
    * @param boolean return Type of return : true return result as a string, false (default) print in stdout
    */
   function vignettePrev($s, $return = false) {
      $res = $GLOBALS['_LB_render']['res'];
      if ($res->isFirst()) {
         $result =  '&nbsp;';
      }
      else {
         $dir = $res->getDir();
         $res->move($res->getDefaultIndex());
         $res->movePrev();
         $img = $res->f();
         $result = sprintf('<a href="%s">%s</a>', link::fileType($img), $s);
      }
      if ($return) return $result;
      echo $result;
   }

   /**
    *
    *
    * @access public
    * @param string $s
    * @param boolean return Type of return : true return result as a string, false (default) print in stdout
    */
   function vignetteNext($s, $return = false) {
      $res = $GLOBALS['_LB_render']['res'];
      if ($res->isLast()) {
         $result =  '&nbsp;';
      }
      else {
         $dir = $res->getDir();
         $res->move($res->getDefaultIndex());
         $res->moveNext();
         $img = $res->f();
         $result = sprintf('<a href="%s">%s</a>', link::fileType($img), $s);
      }
      if ($return) return $result;
      echo $result;
   }

   /**
    *
    *
    * @access public
    * @param string $s
    * @param boolean return Type of return : true return result as a string, false (default) print in stdout
    */
   function vignettePrevLink($s, $return = false) {
      $res = $GLOBALS['_LB_render']['res'];
      if ($res->isFirst()) {
         $result =  '';
      }
      else {
         $dir = $res->getDir();
         $res->move($res->getDefaultIndex());
         $res->movePrev();
         $img = $res->f();
         $result = link::fileType($img);
      }
      if ($return) return $result;
      echo $result;
   }

   /**
    *
    * @access public
    * @param string $s
    * @param boolean return Type of return : true return result as a string, false (default) print in stdout
    */
   function vignetteNextLink($s, $return = false) {
      $res = $GLOBALS['_LB_render']['res'];
      if ($res->isLast()) {
         $result =  '';
      }
      else {
         $dir = $res->getDir();
         $res->move($res->getDefaultIndex());
         $res->moveNext();
         $img = $res->f();
         $result = link::fileType($img);
      }
      if ($return) return $result;
      echo $result;
   }
   
   /*------------------------------------------------------------------------------
    Links / back-forward in display pages
    -----------------------------------------------------------------------------*/
   /**
    *
    *
    * @access public
    * @param string $s
    * @param string $first='&nbsp;'
    * @param boolean return Type of return : true return result as a string, false (default) print in stdout
    */
   function affichagePrev($s, $first='&nbsp;', $return = false) {
      $res = $GLOBALS['_LB_render']['res'];
      if ($res->isFirst()) {
         $result =  $first;
      }
      else {
         $dir = $res->getDir();
         $res->move($res->getDefaultIndex());
         $res->movePrev();
         $img = $res->f();
         $result = sprintf('<a href="%s">%s</a>', link::affichage($dir, $img->getFile()), $s);
      }
      if ($return) return $result;
      echo $result;
   }

   /**
    *
    *
    * @access public
    * @param string $s
    * @param string $last = '&nbsp;'
    * @param boolean return Type of return : true return result as a string, false (default) print in stdout
    */
   function affichageNext($s, $last = '&nbsp;', $return = false) {
      $res = $GLOBALS['_LB_render']['res'];
      if ($res->isLast()) {
         $result =  $last;
      }
      else {
         $dir = $res->getDir();
         $res->move($res->getDefaultIndex());
         $res->moveNext();
         $img = $res->f();
         $result = sprintf('<a href="%s">%s</a>', link::affichage($dir, $img->getFile()), $s);
      }
      if ($return) return $result;
      echo $result;
   }

   /**
    *
    *
    * @access public
    * @param boolean return Type of return : true return result as a string, false (default) print in stdout
    */
   function affichagePrevLink($return = false) {
      $res = $GLOBALS['_LB_render']['res'];
      if ($res->isFirst()) {
         $result =  '';
      }
      else {
         $dir = $res->getDir();
         $res->move($res->getDefaultIndex());
         $res->movePrev();
         $img = $res->f();
         $result = link::affichage($dir, $img->getFile());
      }
      if ($return) return $result;
      echo $result;
   }

   /**
    *
    *
    * @access public
    * @param boolean return Type of return : true return result as a string, false (default) print in stdout
    */
   function affichageNextLink($return = false) {
      $res = $GLOBALS['_LB_render']['res'];
      if ($res->isLast()) {
         $result =  '';
      }
      else {
         $dir = $res->getDir();
         $res->move($res->getDefaultIndex());
         $res->moveNext();
         $img = $res->f();
         $result = link::affichage($dir, $img->getFile());
      }
      if ($return) return $result;
      echo $result;
   }
}
?>