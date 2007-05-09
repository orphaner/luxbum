<?php

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

   /*------------------------------------------------------------------------------
    Functions to display the index page
    -----------------------------------------------------------------------------*/

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
    * @param boolean return Type of return : true return result as a string, false (default) print in stdout
    */
   function galleryNiceName($current = true, $return = false) {
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
   function galleryName($current = true, $return = false) {
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
   function galleryImageSize($current = true, $return = false) {
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
   function galleryFlvSize($current = true, $return = false) {
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
   function gallerySize($current = true, $return = false) {
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
   function galleryImageNiceSize($current = true, $return = false) {
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
   function galleryFlvNiceSize($current = true, $return = false) {
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
   function galleryNiceSize($current = true, $return = false) {
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
   function galleryImageCount($current = true, $return = false) {
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
   function galleryFlvCount($current = true, $return = false) {
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
   function galleryCount($current = true, $return = false) {
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
   function galleryLinkPrivate($s, $text, $return = false) {
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
   function galleryLinkSubGallery($s, $text, $return = false) {
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
   function galleryLinkConsult($s, $text, $return = false) {
      if (!lb::hasElements()) {
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
   function galleryLinkSlideshow($s, $text, $return = false) {
      if (SHOW_SLIDESHOW == 'off') {
         return ;
      }
      if (!lb::hasImage()) {
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
         $img = lb::colorThemePath(true).'/images/folder_locked.png';
      }
      
      // Sub gallery image
      else if ($res->hasSubGallery() && $res->getTotalCount() == 0) {
         $link = link::subGallery($res->getDir());
         $img = lb::colorThemePath(true).'/images/folder_image.png';
      }
      
      // Video gallery
      else if ($res->getImageCount() == 0 && $res->getFlvCount() > 0) {
         $img2 = $res->f();
         $link = link::fileFlv($img2->getDir(), $img2->getFile());
         $img = lb::colorThemePath(true).'/images/folder_video.png';
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

   /**
    *
    *
    * @param boolean return Type of return : true return result as a string, false (default) print in stdout
    */
   function pageStyle ($return = false) {
      global $themes_css;
      if (!array_key_exists (TEMPLATE_THEME, $themes_css)) {
         $default = DEFAULT_CSS;
      }
      else {
         $default = TEMPLATE_THEME;
      }

      $result = '';
      $result .= sprintf('<link rel="stylesheet" href="%s" title="%s" type="text/css"/>',
      TEMPLATE_DIR. TEMPLATE.'/themes/'.$default.'/'.$default.'.css', '');
      while (list ($theme, $title) = each ($themes_css)) {
         if ($theme != $default) {
            $result .= sprintf('<link rel="alternate stylesheet" href="%s" title="%s" type="text/css"/>',
            TEMPLATE_DIR. TEMPLATE.'/themes/'.$theme.'/'.$theme.'.css', $title);
         }
         else {
            $result .= sprintf('<link rel="stylesheet" href="%s" title="%s" type="text/css"/>',
            TEMPLATE_DIR. TEMPLATE.'/themes/'.$default.'/'.$default.'.css', $title);
         }
      }
      if ($return) return $result;
      echo $result;
   }

   /**
    *
    *
    * @param boolean return Type of return : true return result as a string, false (default) print in stdout
    */
   function colorThemePath ($return = false) {
      $result = TEMPLATE_DIR. TEMPLATE.'/themes/'.TEMPLATE_THEME;
      if ($return) return $result;
      echo $result;
   }

   /**
    *
    *
    * @param boolean return Type of return : true return result as a string, false (default) print in stdout
    */
   function getVideoPlayer($return = false) {
      $result = TEMPLATE_COMMON_DIR.'/flash/video/'.$GLOBALS['video_player'][TEMPLATE_THEME].'.swf';
      if ($return) return $result;
      echo $result;
   }
   
   /**
    *
    *
    * @param boolean return Type of return : true return result as a string, false (default) print in stdout
    */
   function flashPlayerBgcolor($return = false) {
      $result = $GLOBALS['video_player_bgcolor'][TEMPLATE_THEME];
      if ($return) return $result;
      echo $result;
   }

   /**
    *
    *
    * @param boolean return Type of return : true return result as a string, false (default) print in stdout
    */
   function favicon($return = false) {
      if (is_file(PHOTOS_DIR.'favicon.ico')) {
         $favicon = PHOTOS_DIR.'favicon.ico';
         $result = sprintf('<link rel="shortcut icon" href="%s"/>', $favicon);
         if ($return) return $result;
         echo $result;
      }
   }

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
   function photoDescription($s, $return = false) {
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
   function commentCount($return = false) {
      $result =  $GLOBALS['_LB_render']['img']->getNbComment();
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



   /*------------------------------------------------------------------------------
    OPTIONS STATUS
    -----------------------------------------------------------------------------*/

   /**
    *
    *
    */
   function metaEnabled() {
      return (SHOW_META == 'on');
   }

   /**
    *
    *
    */
   function commentsEnabled() {
      return (SHOW_COMMENTAIRE == 'on');
   }

   /**
    *
    *
    */
   function slideshowEnabled() {
      return (SHOW_SLIDESHOW == 'on');
   }

   /**
    *
    *
    */
   function selectionEnabled() {
      return (SHOW_SELECTION == 'on');
   }

   /**
    *
    *
    */
   function slideshowFadingEnabled() {
      return (SLIDESHOW_FADING == 'on');
   }


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
      if (lb::slideshowFadingEnabled()) {
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
      if (lb::slideshowFadingEnabled()) {
         $result = 'checked';
      }
      else {
         $result = '';
      }
      if ($return) return $result;
      echo $result;
   }

   //    /**
   //     *
   //     *
   //     * @param boolean return Type of return : true return result as a string, false (default) print in stdout
   //     */
   //    function slideshowPhotoList($return = false) {
   //       $list = new luxBumGallery($GLOBALS['LB']['dir']);
   //       $list->addAllImages ();
   //       $list->reset();
   //       $i = 0;
   //       $result = '';
   //       while (!$list->EOF()) {
   //          $img = $list->f();
   //          $result .= 'photosURL['.$i.'] = "'.$img->getPreviewLink().'";'."\n";
   //          $list->moveNext();
   //          $i++;
   //       }
   //       if ($return) return $result;
   //       echo $result;
   //    }



   /*------------------------------------------------------------------------------
    PAGINATOR
    -----------------------------------------------------------------------------*/

   /**
    *
    *
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
    *
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

   /**
    *
    */
   function isLast() {
      $res = $GLOBALS['_LB_render']['res'];
      return $res->isLast();
   }

   /**
    *
    */
   function isFirst() {
      $res = $GLOBALS['_LB_render']['res'];
      return $res->isFirst();
   }

   /**
    *
    *
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
    * @param string $s
    * @param string $first='&nbsp;'
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
    * @param string $s
    * @param string $last = '&nbsp;'
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


   /*------------------------------------------------------------------------------
    PAGINATOR
    -----------------------------------------------------------------------------*/

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


   /*------------------------------------------------------------------------------
    COMMENTS
    -----------------------------------------------------------------------------*/
   /**
    *
    *
    * @param boolean return Type of return : true return result as a string, false (default) print in stdout
    */
   function ctPostAuthor($return = false) {
      lbFactory::ctPost();
      $result = $GLOBALS['_LB_render']['ctPost']->getAuthor();
      $result = unprotege_input($result);
      if ($return) return $result;
      echo $result;
   }

   /**
    *
    *
    * @param boolean return Type of return : true return result as a string, false (default) print in stdout
    */
   function ctPostEmail($return = false) {
      lbFactory::ctPost();
      $result = $GLOBALS['_LB_render']['ctPost']->getEmail();
      $result = unprotege_input($result);
      if ($return) return $result;
      echo $result;
   }

   /**
    *
    *
    * @param boolean return Type of return : true return result as a string, false (default) print in stdout
    */
   function ctPostWebsite($return = false) {
      lbFactory::ctPost();
      $result = $GLOBALS['_LB_render']['ctPost']->getWebsite();
      $result = unprotege_input($result);
      if ($return) return $result;
      echo $result;
   }

   /**
    *
    *
    * @param boolean return Type of return : true return result as a string, false (default) print in stdout
    */
   function ctPostContent($return = false) {
      lbFactory::ctPost();
      $result = $GLOBALS['_LB_render']['ctPost']->getContent();
      $result = unprotege_input($result);
      $result = unprotege_input($result);
      if ($return) return $result;
      echo $result;
   }

   /**
    *
    *
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
    *
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
    *
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
    *
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
    *
    */
   function ctFormAction() {
      echo '<input type="hidden" name="action" id="action" value="ct"/>';
   }

   /**
    *
    */
   function ctHasNext() {
      lbFactory::comment();
      $ct = $GLOBALS['_LB_render']['ct'];
      return !$ct->EOF();
   }

   /**
    *
    */
   function ctMoveNext() {
      lbFactory::comment();
      $ct =& $GLOBALS['_LB_render']['ct'];
      $ct->moveNext();
   }

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