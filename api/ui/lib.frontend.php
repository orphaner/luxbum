<?php
include('frontend/lbconf.php');
include('frontend/lbct.php');
include('frontend/lbfile.php');
include('frontend/lbgal.php');
include('frontend/lbmeta.php');
include('frontend/lbpage.php');
include('frontend/lbprivate.php');
include('frontend/lbslide.php');

/**
 * @package ui
 */
class lb {
   function indexLink() {
      return URL_BASE.INDEX_FILE;
   }


   /**
    *
    *
    * @param boolean return Type of return : true return result as a string, false (default) print in stdout
    */
   function pageTitle($return = false) {
      echo  $GLOBALS['LB']['title'];
   }

   /**
    *
    *
    * @param boolean return Type of return : true return result as a string, false (default) print in stdout
    */
   function galleryH1($return = false) {
      $result = $GLOBALS['LB']['title'];
      if ($return) return $result;
      echo $result;
   }

   /**
    *
    *
    * @param string $s
    * @param string $sep = '&#187'
    * @param boolean return Type of return : true return result as a string, false (default) print in stdout
    */
   function menuNav($s, $elt, $sep = '&#187;', $return = false) {
      $res = $GLOBALS['_LB_render']['res'];

      $dir = files::removeTailSlash($res->getDir());
      $list = explode('/', $dir);
      $count = count($list);
      if ($list[0] === '') {
         return;
      }
      $result = '';
      $concat = '';
      for ($i = 0 ; $i < $count ; $i++) {
         $name = luxbum::niceName($list[$i]);
         if ($i == $count-1) {
            $concat .= $list[$i];
            $result .= sprintf($elt,  sprintf($sep.' %s', $name));
         }
         else {
            $concat .= $list[$i].'/';
            $link = link::subGallery($concat);
            $result .= sprintf($elt,sprintf($sep.' <a href="%s">%s</a>', $link, $name));
         }
      }
      $result = sprintf($s, $result);
      if ($return) return $result;
      echo $result;
   }

   /*------------------------------------------------------------------------------
    Functions to display the index page
    -----------------------------------------------------------------------------*/


   /*------------------------------------------------------------------------------

   -----------------------------------------------------------------------------*/

   /**
    *
    *
    * @param boolean return Type of return : true return result as a string, false (default) print in stdout
    */
   function vignetteStyle($return = false) {
      $img = $GLOBALS['_LB_render']['res']->f();
      $dir = $img->getDir();
      $name = $img->getFile();
      if (isSet($_SESSION['luxbum'][$dir][$name])) {
         $result = 'view_photo_selected';
      }
      else {
         $result = 'view_photo';
      }
      if ($return) return $result;
      echo $result;
   }

   /**
    *
    *
    * @param boolean return Type of return : true return result as a string, false (default) print in stdout
    */
   function linkVignette($return = false) {
      $img = $GLOBALS['_LB_render']['res']->f();
      $result = $img->getVignettePageUrl();
      if ($return) return $result;
      echo $result;
   }

   /**
    *
    *
    * @param boolean return Type of return : true return result as a string, false (default) print in stdout
    */
   function linkAffichage($return = false) {
      $img = $GLOBALS['_LB_render']['res']->f();
      $result = link::affichage($GLOBALS['_LB_render']['res']->getDir(),
      $img->getFile());
      if ($return) return $result;
      echo $result;
   }

   /**
    *
    *
    * @param boolean return Type of return : true return result as a string, false (default) print in stdout
    */
   function linkPhoto($return = false) {
      $img = $GLOBALS['_LB_render']['res']->f();
      if (isset($GLOBALS['_LB_render']['img'])) {
         $img = $GLOBALS['_LB_render']['img'];
      }
      else {
         $img = $GLOBALS['_LB_render']['res']->f();
      }
      $result = link::full($GLOBALS['_LB_render']['res']->getDir(),
      $img->getFile());
      if ($return) return $result;
      echo $result;
   }

   /**
    *
    *
    * @param boolean return Type of return : true return result as a string, false (default) print in stdout
    */
   function displayVignette($s='<img src="%s" %s alt=""/>', $return = false) {
      $img = $GLOBALS['_LB_render']['res']->f();
      $path = $img->getThumbUrl();
      $dimensions = $img->getThumbResizeSize();
      $result = sprintf ($s, $path, $dimensions);
      if ($return) return $result;
      echo $result;
   }

   /**
    *
    *
    * @param boolean return Type of return : true return result as a string, false (default) print in stdout
    */
   function displayApercu($s = '<img src="%s" %s alt=""/>', $return = false) {
      if (isset($GLOBALS['_LB_render']['img'])) {
         $img = $GLOBALS['_LB_render']['img'];
      }
      else {
         $img = $GLOBALS['_LB_render']['res']->f();
      }
      $path = $img->getPreviewUrl();
      $dimensions = $img->getPreviewResizeSize();
      $result = sprintf ($s, $path, $dimensions);
      if ($return) return $result;
      echo $result;
   }

   /**
    *
    *
    * @param boolean return Type of return : true return result as a string, false (default) print in stdout
    */
   function urlFlvFile($return = false) {
      $img = $GLOBALS['_LB_render']['img'];
      $result = $img->urlPath();
      if ($return) return $result;
      echo $result;
   }

   /**
    *
    *
    * @param boolean return Type of return : true return result as a string, false (default) print in stdout
    */
   function linkMeta($return = false) {
      $img =  $GLOBALS['_LB_render']['img'];
      $result = link::meta($img->getDir(), $img->getFile());
      if ($return) return $result;
      echo $result;
   }

   /**
    *
    *
    * @param boolean return Type of return : true return result as a string, false (default) print in stdout
    */
   function linkComment($return = false) {
      $img =  $GLOBALS['_LB_render']['img'];
      $result = link::commentaire($img->getDir(), $img->getFile());
      if ($return) return $result;
      echo $result;
   }

   /**
    *
    *
    * @param boolean return Type of return : true return result as a string, false (default) print in stdout
    */
   function imageName($return = false) {
      if (isset($GLOBALS['_LB_render']['img'])) {
         $img = $GLOBALS['_LB_render']['img'];
      }
      else {
         $img = $GLOBALS['_LB_render']['res']->f();
      }
      $result =  $img->getFile();
      $result = utf8_encode($result);
      if ($return) return $result;
      echo $result;
   }








   /*------------------------------------------------------------------------------
    SELECTION
    -----------------------------------------------------------------------------*/
   function selectLink($return = false) {
      if (isset($GLOBALS['_LB_render']['img'])) {
         $file = $GLOBALS['_LB_render']['img'];
      }
      else {
         $file = $GLOBALS['_LB_render']['res']->f();
      }

      $dir = $file->getDir();
      $file = $file->getFile();

      $selection = Selection::getInstance();
      if ($selection->exists($dir, $file)) {
         $label = __('Unselect file');
         $link = link::unselect($dir, $file);
      }
      else {
         $label = __('Select file');
         $link = link::select($dir, $file);
      }
      $result = sprintf('<a href="%s">%s</a>', $link, $label);
      if ($return) return $result;
      echo $result;
   }

}

/**
 *
 */
class lbFactory {
   function privatePost() {
      if (!isset($GLOBALS['_LB_render']['privatePost'])) {
         $GLOBALS['_LB_render']['privatePost'] = new PassPost();
      }
   }

   function comment() {
      if (!isset($GLOBALS['_LB_render']['ct'])) {
         $GLOBALS['_LB_render']['ct'] = $GLOBALS['_LB_render']['img']->lazyLoadComments();
      }
   }
   function ctPost() {
      if (!isset($GLOBALS['_LB_render']['ctPost'])) {
         $GLOBALS['_LB_render']['ctPost'] = new Commentaire();
      }
   }
}
?>