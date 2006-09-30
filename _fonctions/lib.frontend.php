<?php

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
   function galleryNiceName($return = false) {
      $img = $GLOBALS['_LB_render']['res']->f();
      $result = $img->getNiceName();
      if ($return) return $result;
      echo $result;
   }

   /**
    *
    *
    * @param boolean return Type of return : true return result as a string, false (default) print in stdout
    */
   function galleryName($return = false) {
      $img = $GLOBALS['_LB_render']['res']->f();
      $result = $img->getName();
      if ($return) return $result;
      echo $result;
   }

   /**
    *
    *
    * @param boolean return Type of return : true return result as a string, false (default) print in stdout
    */
   function gallerySize($return = false) {
      $img = $GLOBALS['_LB_render']['res']->f();
      $result = $img->getSize();
      if ($return) return $result;
      echo $result;
   }

   /**
    *
    *
    * @param boolean return Type of return : true return result as a string, false (default) print in stdout
    */
   function galleryNiceSize($return = false) {
      $img = $GLOBALS['_LB_render']['res']->f();
      $result = $img->getNiceSize();
      if ($return) return $result;
      echo $result;
   }

   /**
    *
    *
    * @param boolean return Type of return : true return result as a string, false (default) print in stdout
    */
   function galleryNbPhotos($return = false) {
      $img = $GLOBALS['_LB_render']['res']->f();
      $result = $img->getCount();
      if ($return) return $result;
      echo $result;
   }

   /**
    *
    *
    * @param boolean return Type of return : true return result as a string, false (default) print in stdout
    */
   function hasPhotos() {
      $img = $GLOBALS['_LB_render']['res']->f();
      return ($img->getCount() > 0);
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
      $result =  lien_sous_galerie($res->getDir());
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
      if (!lb::hasPhotos()) {
         return ;
      }

      $img = $GLOBALS['_LB_render']['res']->f();
      $l =  link::vignette ($img->getDir());
      $link = sprintf('<a href="%s">%s</a>', $l, $text);
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
            $link = link::vignette($concat);
//         $result .= sprintf($elt,  sprintf($sep.' %s', $name));
         }
         else {
            $concat .= $list[$i].'/';
            $link = link::subGallery($concat);
         }
         $result .= sprintf($elt,
                            sprintf($sep.' <a href="%s">%s</a>',
                                    $link, $name)
            );
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
      if ($res->isPrivate()) {
         $link = 'private';
      }
      else if ($res->hasSubGallery() &&  $res->getCount() == 0) {
         $link = link::subGallery($res->getName());
      }
      else {
         $link = link::vignette ($res->getDir());
      }
      $result = sprintf('<a href="%s"><img src="%s" alt=""/></a>', $link, $img);
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
      if (!lb::hasPhotos()) {
         return ;
      }
      $img = $GLOBALS['_LB_render']['res']->f();
      $dir = $img->getDir();
      $link = sprintf('<a href="#" onclick="window.open(\'%s\',\'Diaporama\',\'width=700,height=545,scrollbars=yes,resizable=yes\');">%s</a>', link::slideshow($dir), $text);
      $result = sprintf($s, $link);
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
      if (!array_key_exists (COLOR_THEME, $themes_css)) {
         $default = DEFAULT_CSS;
      }
      else {
         $default = COLOR_THEME;
      }

      $result = '';
      $result .= sprintf('<link rel="stylesheet" href="%s" title="%s" type="text/css"/>',
                         TEMPLATE_DIR. TEMPLATE.'/themes/'.$default.'/'.$default.'.css', '$title');
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
      $result = TEMPLATE_DIR. TEMPLATE.'/themes/'.DEFAULT_CSS;
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
      $dir = $img->getImageDir();
      $name = $img->getImageName();
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
      $result = link::vignette($GLOBALS['_LB_render']['res']->getDir(),
                               $img->getImageName());
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
                                $img->getImageName());
      if ($return) return $result;
      echo $result;
   }

   /**
    *
    *
    * @param boolean return Type of return : true return result as a string, false (default) print in stdout
    */
   function pathPhoto($return = false) {
      $result = $GLOBALS['_LB_render']['img']->getImagePath();
      if ($return) return $result;
      echo $result;
   }

   /**
    *
    *
    * @param boolean return Type of return : true return result as a string, false (default) print in stdout
    */
   function displayVignette($return = false) {
      $img = $GLOBALS['_LB_render']['res']->f();
      $path = $img->getThumbLink();
      $dimensions = $img->getThumbResizeSize();
      $result = sprintf ('<img src="%s" %s alt=""/>', $path, $dimensions);
      if ($return) return $result;
      echo $result;
   }

   /**
    *
    *
    * @param boolean return Type of return : true return result as a string, false (default) print in stdout
    */
   function displayApercu($return = false) {
      $img = $GLOBALS['_LB_render']['img'];
      $path = $img->getPreviewLink();
      $dimensions = $img->getPreviewResizeSize();
      $result = sprintf ('<img src="%s" %s alt=""/>', $path, $dimensions);
      if ($return) return $result;
      echo $result;
   }

   /**
    *
    *
    * @param boolean return Type of return : true return result as a string, false (default) print in stdout
    */
   function photoDescription($return = false) {
      $img = $GLOBALS['_LB_render']['img'];
      $img->findDescription();
      $result = $img->getDateDesc();
      if ($return) return $result;
      echo $result;
   }

   /**
    *
    *
    * @param boolean return Type of return : true return result as a string, false (default) print in stdout
    */
   function linkExif($return = false) {
      $img =  $GLOBALS['_LB_render']['img'];
      $result = link::exif($img->getImageDir(), $img->getImageName());
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
      $result = link::commentaire($img->getImageDir(), $img->getImageName());
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
      $result =  $GLOBALS['_LB_render']['img']->getImageName();
      if ($return) return $result;
      echo $result;
   }

/*------------------------------------------------------------------------------
 EXIF DATA
 -----------------------------------------------------------------------------*/

   /**
    *
    *
    * @param boolean return Type of return : true return result as a string, false (default) print in stdout
    */
   function exifExposureTime($return = false) {
      $result = $GLOBALS['_LB_render']['img']->getExifExposureTime ();
      if ($return) return $result;
      echo $result;
   }

   /**
    *
    *
    * @param boolean return Type of return : true return result as a string, false (default) print in stdout
    */
   function exifAperture($return = false) {
      $result = $GLOBALS['_LB_render']['img']->getExifAperture ();
      if ($return) return $result;
      echo $result;
   }

   /**
    *
    *
    * @param boolean return Type of return : true return result as a string, false (default) print in stdout
    */
   function exifFocalLength($return = false) {
      $result = $GLOBALS['_LB_render']['img']->getExifFocalLength ();
      if ($return) return $result;
      echo $result;
   }

   /**
    *
    *
    * @param boolean return Type of return : true return result as a string, false (default) print in stdout
    */
   function exifCameraMaker($return = false) {
      $result = $GLOBALS['_LB_render']['img']->getExifCameraMaker ();
      if ($return) return $result;
      echo $result;
   }

   /**
    *
    *
    * @param boolean return Type of return : true return result as a string, false (default) print in stdout
    */
   function exifCameraModel($return = false) {
      $result = $GLOBALS['_LB_render']['img']->getExifCameraModel ();
      if ($return) return $result;
      echo $result;
   }

   /**
    *
    *
    * @param boolean return Type of return : true return result as a string, false (default) print in stdout
    */
   function exifISO($return = false) {
      $result = $GLOBALS['_LB_render']['img']->getExifISO ();
      if ($return) return $result;
      echo $result;
   }

   /**
    *
    *
    * @param boolean return Type of return : true return result as a string, false (default) print in stdout
    */
   function exifCaptureDate($return = false) {
      $result = $GLOBALS['_LB_render']['img']->getExifCaptureDate ();
      if ($return) return $result;
      echo $result;
   }

   /**
    *
    *
    * @param boolean return Type of return : true return result as a string, false (default) print in stdout
    */
   function exifFlash($return = false) {
      $result = $GLOBALS['_LB_render']['img']->getExifFlash ();
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
   function exifEnabled() {
      return (SHOW_EXIF == 'on');
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
      if (lb::lideshowFadingEnabled()) {
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
      if (lb::lideshowFadingEnabled()) {
         $result = 'checked';
      }
      else {
         $result = '';
      }
      if ($return) return $result;
      echo $result;
   }

   /**
    *
    *
    * @param boolean return Type of return : true return result as a string, false (default) print in stdout
    */
   function slideshowPhotoList($return = false) {
      $list = new luxBumGallery($GLOBALS['LB']['dir']);
      $list->addAllImages ();
      $list->reset();
      $i = 0;
      $result = '';
      while (!$list->EOF()) {
         $img = $list->f();
         $result .= 'photosURL['.$i.'] = "'.$img->getPreviewLink().'";'."\n";
         $list->moveNext();
         $i++;
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
         $result = sprintf('<a href="%s">%s</a>', link::vignette($dir, $img->getImageName()), $s);
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
         $result = sprintf('<a href="%s">%s</a>', link::vignette($dir, $img->getImageName()), $s);
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
         $result = link::vignette($dir, $img->getImageName());
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
         $result = link::vignette($dir, $img->getImageName());
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
         $result = sprintf('<a href="%s">%s</a>', link::affichage($dir, $img->getImageName()), $s);
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
         $result = sprintf('<a href="%s">%s</a>', link::affichage($dir, $img->getImageName()), $s);
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
         $result = link::affichage($dir, $img->getImageName());
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
         $result = link::affichage($dir, $img->getImageName());
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
      $result = $GLOBALS['LB_render']['affpage']->getCurrentPage();
      if ($return) return $result;
      echo $result;
   }

   /**
    *
    *
    * @param boolean return Type of return : true return result as a string, false (default) print in stdout
    */
   function paginatorTotalPages($return = false) {
      $result = $GLOBALS['LB_render']['affpage']->getTotalPages();
      if ($return) return $result;
      echo $result;
   }

   /**
    *
    *
    * @param boolean return Type of return : true return result as a string, false (default) print in stdout
    */
   function paginatorLinkVignette($return = false) {
      $affpage = $GLOBALS['LB_render']['affpage']->f();
      $res = $GLOBALS['_LB_render']['res'];

      $dir = $res->getDir();
      $res->move($affpage[1]);
      $img = $res->f();
      $result = link::vignette($dir, $img->getImageName());
      if ($return) return $result;
      echo $result;
   }

   /**
    *
    *
    * @param boolean return Type of return : true return result as a string, false (default) print in stdout
    */
   function paginatorLinkAffichage($return = false) {
      $affpage = $GLOBALS['LB_render']['affpage']->f();
      $res = $GLOBALS['_LB_render']['res'];

      $dir = $res->getDir();
      $res->move($affpage[1]);
      $img = $res->f();
      $result = link::affichage($dir, $img->getImageName());
      if ($return) return $result;
      echo $result;
   }

   /**
    *
    *
    * @param boolean return Type of return : true return result as a string, false (default) print in stdout
    */
   function paginatorElementText($return = false) {
      $affpage = $GLOBALS['LB_render']['affpage']->f();
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
      $affpage = $GLOBALS['LB_render']['affpage']->f();
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
      $affpage = $GLOBALS['LB_render']['affpage']->f();
      $paginatorPage = $affpage[2];
      if ($paginatorPage == $GLOBALS['LB_render']['affpage']->getCurrentPage()) {
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
      $affpage = $GLOBALS['LB_render']['affpage']->f();
      $paginatorPage = $affpage[2];
      return ($paginatorPage == $GLOBALS['LB_render']['affpage']->getCurrentPage());
   }

   /**
    *
    */
   function isFirstPage() {
      return ($GLOBALS['LB_render']['affpage']->getCurrentPage() == 1);
   }

   /**
    *
    */
   function isLastPage() {
      $affpage = $GLOBALS['LB_render']['affpage'];
      $paginatorPage = $affpage->getCurrentPage();
      return ($paginatorPage == $affpage->getTotalPages());
   }

   /**
    *
    *
    * @param boolean return Type of return : true return result as a string, false (default) print in stdout
    */
   function vignettePrevPageLink($return = false) {
      $affpage = $GLOBALS['LB_render']['affpage'];
      $res = $GLOBALS['_LB_render']['res'];

      $dir = $res->getDir();
      $res->move($affpage->prevPage());
      $img = $res->f();
      $result = link::vignette($dir, $img->getImageName());
      if ($return) return $result;
      echo $result;
   }

   /**
    *
    *
    * @param boolean return Type of return : true return result as a string, false (default) print in stdout
    */
   function vignetteNextPageLink($return = false) {
      $affpage = $GLOBALS['LB_render']['affpage'];
      $res = $GLOBALS['_LB_render']['res'];

      $dir = $res->getDir();
      $res->move($affpage->nextPage());
      $img = $res->f();
      $result = link::vignette($dir, $img->getImageName());
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
      $affpage = $GLOBALS['LB_render']['affpage'];
      $res = $GLOBALS['_LB_render']['res'];

      $dir = $res->getDir();
      $res->move($affpage->prevPage());
      $img = $res->f();
      $result = sprintf('<a href="%s">%s</a>', link::vignette($dir, $img->getImageName()), $s);
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
      $affpage = $GLOBALS['LB_render']['affpage'];
      $res = $GLOBALS['_LB_render']['res'];

      $dir = $res->getDir();
      $res->move($affpage->nextPage());
      $img = $res->f();
      print_r($res);
      $result = sprintf('<a href="%s">%s</a>', link::vignette($dir, $img->getImageName()), $s);
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
   function ctPostError($key, $return = false) {
      lbFactory::ctPost();
      $result = $GLOBALS['_LB_render']['ctPost']->getError($key);
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
      $ct = $GLOBALS['_LB_render']['ct'];
      $ct->moveNext();
   }
  }

class lbFactory {
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