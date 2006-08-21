<?php

function lbIndexLink() {
   return URL_BASE.INDEX_FILE;
  }


  /**
   *
   *
   * @param boolean return Type of return : true return result as a string, false (default) print in stdout
   */
function lbPageTitle($return = false) {
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
function lbGalleryH1($return = false) {
   $result = $GLOBALS['LB']['title'];
   if ($return) return $result;
   echo $result;
}


/**
 *
 *
 * @param boolean return Type of return : true return result as a string, false (default) print in stdout
 */
function lbGalleryNiceName($return = false) {
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
function lbGalleryName($return = false) {
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
function lbGallerySize($return = false) {
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
function lbGalleryNiceSize($return = false) {
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
function lbGalleryNbPhotos($return = false) {
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
function lbHasPhotos() {
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
function lbGalleryLinkPrivate($s, $text, $return = false) {
   $res = $GLOBALS['_LB_render']['res']->f();
   if (!$res->isPrivate()) {
      return ;
   }
   $result =  lien_sous_galerie($res->getName());
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
function lbGalleryLinkSubGallery($s, $text, $return = false) {
   $res = $GLOBALS['_LB_render']['res']->f();
   if (!$res->hasSubGallery()) {
      return ;
   }
   $link =  link::subGallery($res->getName());
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
function lbGalleryLinkConsult($s, $text, $return = false) {
   if (!lbHasPhotos()) {
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
function lbMenuNav($s, $elt, $sep = '&#187;', $return = false) {
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
function lbDefaultImage($return = false) {
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
function lbGalleryLinkSlideshow($s, $text, $return = false) {
   if (SHOW_SLIDESHOW == 'off') {
      return ;
   }
   if (!lbHasPhotos()) {
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
function lbPageStyle ($return = false) {
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
function lbColorThemePath ($return = false) {
   $result = TEMPLATE_DIR. TEMPLATE.'/themes/'.DEFAULT_CSS;
   if ($return) return $result;
   echo $result;
}

/**
 *
 *
 * @param boolean return Type of return : true return result as a string, false (default) print in stdout
 */
function lbFavicon($return = false) {
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
function lbResPosition($return = false) {
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
function lbResTotal($return = false) {
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
function lbVignetteStyle($return = false) {
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
function lbLinkVignette($return = false) {
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
function lbLinkAffichage($return = false) {
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
function lbPathPhoto($return = false) {
   $result = $GLOBALS['_LB_render']['img']->getImagePath();
   if ($return) return $result;
   echo $result;
}

/**
 *
 *
 * @param boolean return Type of return : true return result as a string, false (default) print in stdout
 */
function lbDisplayVignette($return = false) {
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
function lbDisplayApercu($return = false) {
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
function lbPhotoDescription($return = false) {
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
function lbLinkExif($return = false) {
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
function lbLinkComment($return = false) {
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
function lbCommentCount($return = false) {
   $result =  $GLOBALS['_LB_render']['img']->getNbComment();
   if ($return) return $result;
   echo $result;
}

/**
 *
 *
 * @param boolean return Type of return : true return result as a string, false (default) print in stdout
 */
function lbImageName($return = false) {
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
function lbExifExposureTime($return = false) {
   $result = $GLOBALS['_LB_render']['img']->getExifExposureTime ();
   if ($return) return $result;
   echo $result;
}

/**
 *
 *
 * @param boolean return Type of return : true return result as a string, false (default) print in stdout
 */
function lbExifAperture($return = false) {
   $result = $GLOBALS['_LB_render']['img']->getExifAperture ();
   if ($return) return $result;
   echo $result;
}

/**
 *
 *
 * @param boolean return Type of return : true return result as a string, false (default) print in stdout
 */
function lbExifFocalLength($return = false) {
   $result = $GLOBALS['_LB_render']['img']->getExifFocalLength ();
   if ($return) return $result;
   echo $result;
}

/**
 *
 *
 * @param boolean return Type of return : true return result as a string, false (default) print in stdout
 */
function lbExifCameraMaker($return = false) {
   $result = $GLOBALS['_LB_render']['img']->getExifCameraMaker ();
   if ($return) return $result;
   echo $result;
}

/**
 *
 *
 * @param boolean return Type of return : true return result as a string, false (default) print in stdout
 */
function lbExifCameraModel($return = false) {
   $result = $GLOBALS['_LB_render']['img']->getExifCameraModel ();
   if ($return) return $result;
   echo $result;
}

/**
 *
 *
 * @param boolean return Type of return : true return result as a string, false (default) print in stdout
 */
function lbExifISO($return = false) {
   $result = $GLOBALS['_LB_render']['img']->getExifISO ();
   if ($return) return $result;
   echo $result;
}

/**
 *
 *
 * @param boolean return Type of return : true return result as a string, false (default) print in stdout
 */
function lbExifCaptureDate($return = false) {
   $result = $GLOBALS['_LB_render']['img']->getExifCaptureDate ();
   if ($return) return $result;
   echo $result;
}

/**
 *
 *
 * @param boolean return Type of return : true return result as a string, false (default) print in stdout
 */
function lbExifFlash($return = false) {
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
function lbExifEnabled() {
   return (SHOW_EXIF == 'on');
}

/**
 *
 *
 */
function lbCommentsEnabled() {
   return (SHOW_COMMENTAIRE == 'on');
}

/**
 *
 *
 */
function lbSlideshowEnabled() {
   return (SHOW_SLIDESHOW == 'on');
}

/**
 *
 *
 */
function lbSelectionEnabled() {
   return (SHOW_SELECTION == 'on');
}

/**
 *
 *
 */
function lbSlideshowFadingEnabled() {
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
function lbPhotoDir($return = false) {
   $result = PHOTOS_DIR;
   if ($return) return $result;
   echo $result;
}

/**
 *
 *
 * @param boolean return Type of return : true return result as a string, false (default) print in stdout
 */
function lbSlideshowTime($return = false) {
   $result = SLIDESHOW_TIME;
   if ($return) return $result;
   echo $result;
}

/**
 *
 *
 * @param boolean return Type of return : true return result as a string, false (default) print in stdout
 */
function lbSlideshowDir($return = false) {
   $result = $GLOBALS['LB']['dir'];
   if ($return) return $result;
   echo $result;
}

/**
 *
 *
 * @param boolean return Type of return : true return result as a string, false (default) print in stdout
 */
function lbSlideshowFadingText($return = false) {
   if (lbSlideshowFadingEnabled()) {
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
function lbSlideshowFadingCheckbox($return = false) {
   if (lbSlideshowFadingEnabled()) {
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
function lbSlideshowPhotoList($return = false) {
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
function lbVignettePrev($s, $return = false) {
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
function lbVignetteNext($s, $return = false) {
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
function lbVignettePrevLink($s, $return = false) {
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
function lbVignetteNextLink($s, $return = false) {
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
function lbIsLast() {
   $res = $GLOBALS['_LB_render']['res'];
   return $res->isLast();
}

/**
 *
 */
function lbIsFirst() {
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
function lbAffichagePrev($s, $first='&nbsp;', $return = false) {
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
function lbAffichageNext($s, $last = '&nbsp;', $return = false) {
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
function lbAffichagePrevLink($return = false) {
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
function lbAffichageNextLink($return = false) {
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
function lbPaginatorCurrentPage($return = false) {
   $result = $GLOBALS['LB_render']['affpage']->getCurrentPage();
   if ($return) return $result;
   echo $result;
}

/**
 *
 *
 * @param boolean return Type of return : true return result as a string, false (default) print in stdout
 */
function lbPaginatorTotalPages($return = false) {
   $result = $GLOBALS['LB_render']['affpage']->getTotalPages();
   if ($return) return $result;
   echo $result;
}

/**
 *
 *
 * @param boolean return Type of return : true return result as a string, false (default) print in stdout
 */
function lbPaginatorLinkVignette($return = false) {
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
function lbPaginatorLinkAffichage($return = false) {
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
function lbPaginatorElementText($return = false) {
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
function lbPaginatorElementImage($return = false) {
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
function lbPaginatorAltClass($return = false) {
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
function lbPaginatorIsCurrent() {
   $affpage = $GLOBALS['LB_render']['affpage']->f();
   $paginatorPage = $affpage[2];
   return ($paginatorPage == $GLOBALS['LB_render']['affpage']->getCurrentPage());
}

/**
 *
 */
function lbIsFirstPage() {
   return ($GLOBALS['LB_render']['affpage']->getCurrentPage() == 1);
}

/**
 *
 */
function lbIsLastPage() {
   $affpage = $GLOBALS['LB_render']['affpage'];
   $paginatorPage = $affpage->getCurrentPage();
   return ($paginatorPage == $affpage->getTotalPages());
}

/**
 *
 *
 * @param boolean return Type of return : true return result as a string, false (default) print in stdout
 */
function lbVignettePrevPageLink($return = false) {
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
function lbVignetteNextPageLink($return = false) {
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
function lbVignettePrevPage($s, $return = false) {
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
function lbVignetteNextPage($s, $return = false) {
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
?>