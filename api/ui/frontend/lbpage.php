<?php

class lbpage {
   
   /*------------------------------------------------------------------------------
    PAGINATOR
    -----------------------------------------------------------------------------*/

   /**
    *
    *
    * @param boolean return Type of return : true return result as a string, false (default) print in stdout
    */
   function resPosition($return = false) {
      $result = $GLOBALS['_LB_render']['res']->getIntIndex();
      $result ++;
      if ($return) return $result;
      echo $result;
   }


   /**
    *
    *
    * @param boolean return Type of return : true return result as a string, false (default) print in stdout
    */
   function resTotal($return = false) {
      $result = $GLOBALS['_LB_render']['res']->getIntRowCount();
      if ($return) return $result;
      echo $result;
   }
   
   /**
    *
    *
    * @param boolean return Type of return : true return result as a string, false (default) print in stdout
    */
   function paginatorCurrentPage($return = false) {
      $result = $GLOBALS['_LB_render']['affpage']->getCurrentPage();
      if ($return) return $result;
      echo $result;
   }

   /**
    *
    *
    * @param boolean return Type of return : true return result as a string, false (default) print in stdout
    */
   function paginatorTotalPages($return = false) {
      $result = $GLOBALS['_LB_render']['affpage']->getTotalPages();
      if ($return) return $result;
      echo $result;
   }

   /**
    *
    *
    * @param boolean return Type of return : true return result as a string, false (default) print in stdout
    */
   function paginatorLinkVignette($return = false) {
      $affpage = $GLOBALS['_LB_render']['affpage']->f();
      $res = $GLOBALS['_LB_render']['res'];

      $dir = $res->getDir();
      $res->move($affpage[1]);
      $img = $res->f();
      $result = link::fileType($img);
      if ($return) return $result;
      echo $result;
   }

   /**
    *
    *
    * @param boolean return Type of return : true return result as a string, false (default) print in stdout
    */
   function paginatorLinkAffichage($return = false) {
      $affpage = $GLOBALS['_LB_render']['affpage']->f();
      $res = $GLOBALS['_LB_render']['res'];

      $dir = $res->getDir();
      $res->move($affpage[1]);
      $img = $res->f();
      $result = link::affichage($dir, $img->getFile());
      if ($return) return $result;
      echo $result;
   }

   /**
    *
    *
    * @param boolean return Type of return : true return result as a string, false (default) print in stdout
    */
   function paginatorElementText($return = false) {
      $affpage = $GLOBALS['_LB_render']['affpage']->f();
      $result = $affpage[0];
      if ($return) return $result;
      echo $result;
   }

   /**
    *
    *
    * @param boolean return Type of return : true return result as a string, false (default) print in stdout
    */
   function paginatorElementImage($return = false) {
      $affpage = $GLOBALS['_LB_render']['affpage']->f();
      $res = $GLOBALS['_LB_render']['res'];

      if ($affpage[0][0] == '&') {
         $result = $affpage[0];
      }
      else {
         $res->move($affpage[1]);
         $img = $res->f();

         $path = $img->getThumbLink();
         $dimensions = $img->getThumbResizeSize();
         $result = sprintf ('<img src="%s" %s alt=""/>', $path, $dimensions);
      }
      if ($return) return $result;
      echo $result;
   }

   /**
    *
    *
    * @param boolean return Type of return : true return result as a string, false (default) print in stdout
    */
   function paginatorAltClass($return = false) {
      $affpage = $GLOBALS['_LB_render']['affpage']->f();
      $paginatorPage = $affpage[2];
      if ($paginatorPage == $GLOBALS['_LB_render']['affpage']->getCurrentPage()) {
         $result = 'alt2';
      }
      else {
         $result = 'alt1';
      }
      if ($return) return $result;
      echo $result;
   }

   /**
    *
    *
    */
   function paginatorIsCurrent() {
      $affpage = $GLOBALS['_LB_render']['affpage']->f();
      $paginatorPage = $affpage[2];
      return ($paginatorPage == $GLOBALS['_LB_render']['affpage']->getCurrentPage());
   }

   /**
    *
    */
   function isFirstPage() {
      return ($GLOBALS['_LB_render']['affpage']->getCurrentPage() == 1);
   }

   /**
    *
    */
   function isLastPage() {
      $affpage = $GLOBALS['_LB_render']['affpage'];
      $paginatorPage = $affpage->getCurrentPage();
      return ($paginatorPage == $affpage->getTotalPages());
   }

   
   
   
   
   
   
   /**
    *
    *
    * @param boolean return Type of return : true return result as a string, false (default) print in stdout
    */
   function vignettePrevPageLink($return = false) {
      $affpage = $GLOBALS['_LB_render']['affpage'];
      $res = $GLOBALS['_LB_render']['res'];

      $dir = $res->getDir();
      $res->move($affpage->prevPage());
      $img = $res->f();
      $result = link::fileType($img);
      if ($return) return $result;
      echo $result;
   }

   /**
    *
    *
    * @param boolean return Type of return : true return result as a string, false (default) print in stdout
    */
   function vignetteNextPageLink($return = false) {
      $affpage = $GLOBALS['_LB_render']['affpage'];
      $res = $GLOBALS['_LB_render']['res'];

      $dir = $res->getDir();
      $res->move($affpage->nextPage());
      $img = $res->f();
      $result = link::fileType($img);
      if ($return) return $result;
      echo $result;
   }

   /**
    *
    *
    * @param string $s
    * @param boolean return Type of return : true return result as a string, false (default) print in stdout
    */
   function vignettePrevPage($s, $return = false) {
      $affpage = $GLOBALS['_LB_render']['affpage'];
      $res = $GLOBALS['_LB_render']['res'];

      $dir = $res->getDir();
      $res->move($affpage->prevPage());
      $img = $res->f();
      $result = sprintf('<a href="%s">%s</a>', link::fileType($img), $s);
      if ($return) return $result;
      echo $result;
   }

   /**
    *
    *
    * @param string $s
    * @param boolean return Type of return : true return result as a string, false (default) print in stdout
    */
   function vignetteNextPage($s, $return = false) {
      $affpage = $GLOBALS['_LB_render']['affpage'];
      $res = $GLOBALS['_LB_render']['res'];

      $dir = $res->getDir();
      $res->move($affpage->nextPage());
      $img = $res->f();
      $result = sprintf('<a href="%s">%s</a>', link::fileType($img), $s);
      if ($return) return $result;
      echo $result;
   }
}

?>