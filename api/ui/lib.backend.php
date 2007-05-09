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

   /**
    * Enter description here...
    *
    * @param unknown_type $return
    * @return unknown
    */
   function headerMessage($return = false) {
      if (!isset($_SESSION['_LB_message'])) {
         $result = '';
      }
      else {
         $result = $_SESSION['_LB_message'];
      }
      if ($return) return $result;
      echo $result;
   }

   /**
    * Enter description here...
    *
    * @param unknown_type $img
    * @param unknown_type $return
    * @return unknown
   */
   function imgSrc($img, $return = false) {
      $result = URL_BASE.'manager_templates/images/'.$img;
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
      if ($res->hasSubGallery() &&  $res->getTotalCount() == 0) {
         $img = lbm::imgSrc('folder_image.png', true);
      }
      // Empty gallery
      else if ($res->getTotalCount() == 0) {
         $img = lbm::imgSrc('folder_image.png', true);
      }

      $result = sprintf('<a href="%s"><img src="%s" alt=""/></a>', $link, $img);
      if ($return) return $result;
      echo $result;
   }

   /**
    *
    */
   function indexSortSelect($return = false) {
      $res = $GLOBALS['_LB_render']['res']->f();
      $sort = $res->getSortOrder();
      $list = array ('type=nom&order=asc'    => __('Name (asc)'),
      'type=nom&order=desc'   => __('Name (desc)'),
      'type=count&order=asc'  => __('Photo count (asc)'),
      'type=count&order=desc' => __('Photo count (desc)'),
      'type=size&order=asc'   => __('Size (asc)'),
      'type=size&order=desc'  => __('Size (desc)'),
      'type=manuel&order=asc' => __('Manual'));

      $result = '<select id="sortby" name="sortby">';
      while (list($k,$v) = each($list)) {
         $result .= '<option value="'.$k.'">'.$v.'</option>';
      }
      $result .= '</select>';

      if ($return) return $result;
      echo $result;
   }


   /*------------------------------------------------------------------------------
    Links to the manager differents pages
    -----------------------------------------------------------------------------*/
   /**
    * return the link to the page index or subgallery
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
      $link =  linkmanager::subGalleryIndex($res->getDir());
      $link = sprintf('<a href="%s">%s</a>', $link, $text);
      $result = sprintf($s, $link);
      if ($return) return $result;
      echo $result;
   }

   /**
    * return the link to the photo management page
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
    * Utility method to generate a link to the $action
    *
    * @access private
    * @param String $action
    * @param boolean $return Type of return : true return result as a string, false (default) print in stdout
    * @return string
    */
   function linkAction($action, $current = true, $return = false) {
      $res = $GLOBALS['_LB_render']['res'];
      if ($current) $res = $res->f();
      $dir =  $res->getDir();
      $result = linkmanager::$action($dir);
      if ($return) return $result;
      echo $result;
   }
    
   /*------------------------------------------------------------------------------
    Links to actions on the index page
    -----------------------------------------------------------------------------*/
   function linkActionIndexSort($return = false) {
      return lbm::linkAction('actionIndexSort', false, $return);
   }

   function linkActionIndexAddGallery($return = false) {
      return lbm::linkAction('actionIndexAddGallery', false, $return);
   }

   function linkActionIndexGencache($return = false) {
      return lbm::linkAction('actionIndexGencache', false, $return);
   }

   function linkActionIndexPurgecache($return = false) {
      return lbm::linkAction('actionIndexPurgecache', false, $return);
   }
   
   function linkActionIndexGalleryRename($return = false) {
      return lbm::linkAction('actionIndexGalleryRename', true, $return);
   }
   
   function linkActionIndexGalleryDelete($return = false) {
      return lbm::linkAction('actionIndexGalleryDelete', true, $return);
   }
   
   /*------------------------------------------------------------------------------
    Links to actions on the gallery manage page
    -----------------------------------------------------------------------------*/
   function linkActionGallerySort($return = false) {
      return lbm::linkAction('actionGallerySort', true, $return);
   }

   function linkActionGalleryPurgecache($return = false) {
      return lbm::linkAction('actionGalleryPurgecache', true, $return);
   }

   function linkActionGalleryGencache($return = false) {
      return lbm::linkAction('actionGalleryGencache', true, $return);
   }
}


?>