<?php

class lbgal {


   /**
    *
    *
    * @param boolean return Type of return : true return result as a string, false (default) print in stdout
    */
   function niceName($current = true, $return = false) {
      $res = $GLOBALS['_LB_render']['res'];
      if ($current) $res = $res->f();
      $result = $res->getNiceName();
      if ($return) return $result;
      echo $result;
   }

   /**
    *
    *
    * @param boolean return Type of return : true return result as a string, false (default) print in stdout
    */
   function name($current = true, $return = false) {
      $res = $GLOBALS['_LB_render']['res'];
      if ($current) $res = $res->f();
      $res = $GLOBALS['_LB_render']['res']->f();
      $result = $res->getName();
      if ($return) return $result;
      echo $result;
   }

   
   /**
    *
    *
    * @param boolean return Type of return : true return result as a string, false (default) print in stdout
    */
   function imageSize($current = true, $return = false) {
      $res = $GLOBALS['_LB_render']['res'];
      if ($current) $res = $res->f();
      $result = $res->getImageSize();
      if ($return) return $result;
      echo $result;
   }

   /**
    *
    *
    * @param boolean return Type of return : true return result as a string, false (default) print in stdout
    */
   function flvSize($current = true, $return = false) {
      $res = $GLOBALS['_LB_render']['res'];
      if ($current) $res = $res->f();
      $result = $res->getFlvSize();
      if ($return) return $result;
      echo $result;
   }

   /**
    *
    *
    * @param boolean return Type of return : true return result as a string, false (default) print in stdout
    */
   function size($current = true, $return = false) {
      $res = $GLOBALS['_LB_render']['res'];
      if ($current) $res = $res->f();
      $result = $res->getTotalSize();
      if ($return) return $result;
      echo $result;
   }

   
   /**
    *
    *
    * @param boolean return Type of return : true return result as a string, false (default) print in stdout
    */
   function imageNiceSize($current = true, $return = false) {
      $res = $GLOBALS['_LB_render']['res'];
      if ($current) $res = $res->f();
      $result = $res->getImageNiceSize();
      if ($return) return $result;
      echo $result;
   }

   /**
    *
    *
    * @param boolean return Type of return : true return result as a string, false (default) print in stdout
    */
   function flvNiceSize($current = true, $return = false) {
      $res = $GLOBALS['_LB_render']['res'];
      if ($current) $res = $res->f();
      $result = $res->getFlvNiceSize();
      if ($return) return $result;
      echo $result;
   }

   /**
    *
    *
    * @param boolean return Type of return : true return result as a string, false (default) print in stdout
    */
   function niceSize($current = true, $return = false) {
      $res = $GLOBALS['_LB_render']['res'];
      if ($current) $res = $res->f();
      $result = $res->getTotalNiceSize();
      if ($return) return $result;
      echo $result;
   }
   

   /**
    *
    *
    * @param boolean return Type of return : true return result as a string, false (default) print in stdout
    */
   function imageCount($current = true, $return = false) {
      $res = $GLOBALS['_LB_render']['res'];
      if ($current) $res = $res->f();
      $result = $res->getImageCount();
      if ($return) return $result;
      echo $result;
   }

   /**
    *
    *
    * @param boolean return Type of return : true return result as a string, false (default) print in stdout
    */
   function flvCount($current = true, $return = false) {
      $res = $GLOBALS['_LB_render']['res'];
      if ($current) $res = $res->f();
      $result = $res->getFlvCount();
      if ($return) return $result;
      echo $result;
   }

   /**
    *
    *
    * @param boolean return Type of return : true return result as a string, false (default) print in stdout
    */
   function count($current = true, $return = false) {
      $res = $GLOBALS['_LB_render']['res'];
      if ($current) $res = $res->f();
      $result = $res->getTotalCount();
      if ($return) return $result;
      echo $result;
   }
   

   /**
    *
    *
    * @param boolean return Type of return : true return result as a string, false (default) print in stdout
    */
   function hasImage($current = true) {
      $res = $GLOBALS['_LB_render']['res'];
      if ($current) $res = $res->f();
      return ($res->getImageCount() > 0);
   }

   /**
    *
    *
    * @param boolean return Type of return : true return result as a string, false (default) print in stdout
    */
   function hasFlv($current = true) {
      $res = $GLOBALS['_LB_render']['res'];
      if ($current) $res = $res->f();
      return ($res->getFlvCount() > 0);
   }

   /**
    *
    *
    * @param boolean return Type of return : true return result as a string, false (default) print in stdout
    */
   function hasElements($current = true) {
      $res = $GLOBALS['_LB_render']['res'];
      if ($current) $res = $res->f();
      return ($res->getTotalCount() > 0);
   }

   
   /**
    * Check if the current gallery is private and locked
    * @return true if the current gallery is private, false otherwise
    */
   function isPrivateAndLocked($current = true) {
      $res = $GLOBALS['_LB_render']['res'];
      if ($current) $res = $res->f();
      return $res->isPrivate() && !$res->isUnlocked();
   }

   /**
    * Check if the current gallery is private
    * @return true if the current gallery is private, false otherwise
    */
   function isPrivate($current = true) {
      $res = $GLOBALS['_LB_render']['res'];
      if ($current) $res = $res->f();
      return $res->isPrivate();
   }

   /**
    * Check if the current gallery is private
    * @return true if the current gallery is private, false otherwise
    */
   function isPrivateExact($current = true) {
      $res = $GLOBALS['_LB_render']['res'];
      if ($current) $res = $res->f();
      return $res->isPrivateExact();
   }

   /**
    *
    *
    * @param string $s
    * @param string $text
    * @param boolean return Type of return : true return result as a string, false (default) print in stdout
    */
   function linkPrivate($s, $text, $return = false) {
      $res = $GLOBALS['_LB_render']['res']->f();
      if (!$res->isPrivate()) {
         return ;
      }
      $link =  link::privateGallery($res->getDir());
      $link = sprintf('<a href="%s">%s</a>', $link, $text);
      $result = sprintf($s, $link);
      if ($return) return $result;
      echo $result;
   }

   /**
    *
    *
    * @param string $s
    * @param string $text
    * @param boolean return Type of return : true return result as a string, false (default) print in stdout
    */
   function linkSubGallery($s, $text, $return = false) {
      $res = $GLOBALS['_LB_render']['res']->f();
      if (!$res->hasSubGallery()) {
         return ;
      }
      $link =  link::subGallery($res->getDir());
      $link = sprintf('<a href="%s">%s</a>', $link, $text);
      $result = sprintf($s, $link);
      if ($return) return $result;
      echo $result;
   }

   /**
    *
    *
    * @param string $s
    * @param string $text
    * @param boolean return Type of return : true return result as a string, false (default) print in stdout
    */
   function linkConsult($s, $text, $return = false) {
      if (!lbgal::hasElements()) {
         return ;
      }

      $gallery = $GLOBALS['_LB_render']['res']->f();
      $file = $gallery->f();
      $l = link::fileType($file);
      $link = sprintf('<a href="%s">%s</a>', $l, $text);
      $result = sprintf($s, $link);
      if ($return) return $result;
      echo $result;
   }

   /**
    *
    *
    * @param string $s
    * @param string $text
    * @param boolean return Type of return : true return result as a string, false (default) print in stdout
    */
   function linkSlideshow($s, $text, $return = false) {
      if (SHOW_SLIDESHOW == 'off') {
         return ;
      }
      if (!lbgal::hasImage()) {
         return ;
      }
      $img = $GLOBALS['_LB_render']['res']->f();
      $dir = $img->getDir();
      $link = sprintf('<a href="javascript:void(0)" onclick="window.open(\'%s\',\'Diaporama\',\'width=670,height=505\');">%s</a>', link::slideshow($dir), $text);
      $result = sprintf($s, $link);
      if ($return) return $result;
      echo $result;
   }

   /**
    *
    *
    * @param boolean return Type of return : true return result as a string, false (default) print in stdout
    */
   function defaultImage($return = false) {
      $res = $GLOBALS['_LB_render']['res']->f();
      $img = $res->getIndexLink();

      // private gallery image
      if ($res->isPrivate() && !$res->isUnlocked()) {
         $link = link::privateGallery($res->getDir());
         $img = lbconf::colorThemePath(true).'/images/folder_locked.png';
      }

      // Sub gallery image
      else if ($res->hasSubGallery() && $res->getTotalCount() == 0) {
         $link = link::subGallery($res->getDir());
         $img = lbconf::colorThemePath(true).'/images/folder_image.png';
      }

      // Video gallery
      else if ($res->getImageCount() == 0 && $res->getFlvCount() > 0) {
         $img2 = $res->f();
         $link = link::fileFlv($img2->getDir(), $img2->getFile());
         $img = lbconf::colorThemePath(true).'/images/folder_video.png';
      }

      // default gallery image
      else {
         $file = $res->f();
         $link = link::fileType($file);
      }

      $result = sprintf('<a href="%s"><img src="%s" alt=""/></a>', $link, $img);
      if ($return) return $result;
      echo $result;
   }
}

?>