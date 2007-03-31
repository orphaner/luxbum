<?php



  /**
   * @package ui
   */
class lbm extends lb {

   /**
    *
    *
    * @param boolean return Type of return : true return result as a string, false (default) print in stdout
    */
   function pageStyle ($return = false) {
      $result = '';
      $result .= sprintf('<link rel="stylesheet" href="%s" title="%s" type="text/css"/>',
                         URL_BASE.TEMPLATE_MANAGER_DIR.'manager.css', 'Manager Template');

      if ($return) return $result;
      echo $result;
   }

   function headerMessage($return = false) {
      if (!isset($_GLOBALS['_LB_message'])) {
         $result = '';
      }
      else {
         $result = $_GLOBALS['_LB_message'];
      }
      if ($return) return $result;
      echo $result;
   }

   function imgSrc($img, $return = false) {
      $result = URL_BASE.'manager_templates/images/'.$img;
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
      $link =  linkmanager::subGallery($res->getDir());
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
   function linkManageGallery($s='%s', $text, $return = false) {
      $res = $GLOBALS['_LB_render']['res']->f();
      $link =  linkmanager::manageGallery($res->getDir());
      $link = sprintf('<a href="%s">%s</a>', $link, $text);
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
      $link = linkmanager::manageGallery($res->getDir());

      // Sub gallery image
      if ($res->hasSubGallery() &&  $res->getCount() == 0) {
         $img = lbm::imgSrc('folder_image.png', true);
      }
      // default gallery image
      else {
      }

      $result = sprintf('<a href="%s"><img src="%s" alt=""/></a>', $link, $img);
      if ($return) return $result;
      echo $result;
   }


  }


?>