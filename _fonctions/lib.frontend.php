<?php


class link {
   function prefix () {
      return (USE_REWRITE == 'on') ? '' : '?/';
   }
   
   // Le lien pour les pages de vignettes
   function vignette ($dir, $img = '') {
      if ($img == '') {
         return link::prefix()."vignette-$dir.html";
      }
      else {
         return link::prefix()."vignette-$dir-$img.html";
      }
   }
   
   // Le lien pour les pages des aperçus
   function apercu ($dir, $image, $page) {
      $page--;
      return link::prefix().'affichage-'.$page.'-'.$dir.'-'.$image.'.html';
   }
   
   // Le lien pour les pages de slideshow
   function slideshow ($dir, $start='') {
      if ($start == '') {
         return link::prefix().'slideshow-'.$dir.'.html';
      }
      else {
         return link::prefix().'slideshow-'.$dir.'-'.$start.'.html';
      }
   }
   
   // Lien pour voir la sélection
   function selection ($page) {
      return link::prefix()."selection_list-$page.html";
   }
   
   // Lien pour sélectionner une photo
   function apercu_select ($dir, $image, $page) {
      $page--;
      return link::prefix().'select-'.$page.'-'.$dir.'-'.$image.'.html';
   }
   
   // Lien pour désélectionner une photo
   function apercu_unselect ($dir, $image, $page) {
      $page--;
      return link::prefix().'unselect-'.$page.'-'.$dir.'-'.$image.'.html';
   }
 
   // Lien pour une sous galerie
   function subGallery($dir) {
      return  link::prefix().'ssgal-'.$dir.'.html';
   }

   function commentaire($dir, $img) {
      return  link::prefix().'commentaires-'.$dir.'-'.$img.'.html';
   }

   function exif($dir, $img) {
      return  link::prefix().'exif-'.$dir.'-'.$img.'.html';
   }
}



function lbPageTitle($return = false) {
   echo  $GLOBALS['LB']['title'];
}

/*------------------------------------------------------------------------------
 Functions to display the index page
 -----------------------------------------------------------------------------*/
function lbGalleryH1($return = false) {
   $result = $GLOBALS['_LB_render']['res']->fg('dir');
   if ($return) return $result;
   echo $result;
}

function lbGalleryNiceName($return = false) {
   $result = $GLOBALS['_LB_render']['res']->f()->getNiceName();
   if ($return) return $result;
   echo $result;
}
function lbGalleryName($return = false) {
   $result = $GLOBALS['_LB_render']['res']->f()->getName();
   if ($return) return $result;
   echo $result;
}
function lbGallerySize($return = false) {
   $result = $GLOBALS['_LB_render']['res']->f()->getSize();
   if ($return) return $result;
   echo $result;
}
function lbGalleryNiceSize($return = false) {
   $result = $GLOBALS['_LB_render']['res']->f()->getNiceSize();
   if ($return) return $result;
   echo $result;
}
function lbGalleryNbPhotos($return = false) {
   $result = $GLOBALS['_LB_render']['res']->f()->getCount();
   if ($return) return $result;
   echo $result;
}
function lbHasPhotos() {
   return ($GLOBALS['_LB_render']['res']->f()->getCount() > 0);
}
function lbGalleryLinkPrivate($s, $return = false) {
   $res =& $GLOBALS['_LB_render']['res']->f();
   if (!$res->isPrivate()) {
      return ;
   }
   $result =  lien_sous_galerie($res->getName());
   if ($return) return $result;
   echo $result;
}
function lbGalleryLinkSubGallery($s, $return = false) {
   $res =& $GLOBALS['_LB_render']['res']->f();
   if (!$res->hasSubGallery()) {
      return ;
   }
   $link =  link::subGallery($res->getName());
   $link = sprintf('<a href="%s">%s</a>', $link, _('Sous galeries'));
   $result = sprintf($s, $link);
   if ($return) return $result;
   echo $result;
}
function lbGalleryLinkConsult($s, $return = false) {
   if (!lbHasPhotos()) {
      return ;
   }
   $l =  link::vignette ($GLOBALS['_LB_render']['res']->f()->getDir());
   $link = sprintf('<a href="%s">%s</a>', $l, _('consulter'));
   $result = sprintf($s, $link);
   if ($return) return $result;
   echo $result;
}
function lbMenuNav($s, $elt, $return = false) {
   $res =& $GLOBALS['_LB_render']['res'];
   $list = explode('/',  $res->getDir());
   $count = count($list);
   if ($list[0] === '') {
      return;
   }
   $result = '';
   $concat = '';
   for ($i = 0 ; $i < $count ; $i++) {
      $concat .= $list[$i].'/';
      $link = link::subGallery($concat);
      $name = luxbum::niceName($list[$i]);
      if ($i == $count-1) {
         $result .= sprintf($elt,  sprintf('&#187; %s', $name));
      }
      else {
         $result .= sprintf($elt, 
                            sprintf('&#187; <a href="%s">%s</a>', 
                                    $link, $name)
            );
      }
   }
   $result = sprintf($s, $result);
   if ($return) return $result;
   echo $result;
}
function lbDefaultImage($return = false) {
   $res =& $GLOBALS['_LB_render']['res']->f();
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
   $result = sprintf('<a href="%s"><img src="%s"/></a>', $link, $img);
   if ($return) return $result;
   echo $result;
}
function lbGalleryLinkSlideshow($s, $return = false) {   
   if (SHOW_SLIDESHOW == 'off') {
      return ;
   }
   if (!lbHasPhotos()) {
      return ;
   }
   $dir =  $GLOBALS['_LB_render']['res']->f()->getDir();
   $link = sprintf('<a href="#" onclick="window.open(\'%s\',\'Diaporama\',\'width=700,height=545,scrollbars=yes,resizable=yes\');">%s</a>', link::slideshow($dir), _('Diaporama'));
   $result = sprintf($s, $link);
   if ($return) return $result;
   echo $result;
}

function lbPageStyle ($return = false) {
   global $themes_css;
   if (!array_key_exists (COLOR_THEME, $themes_css)) {
      $default = 'light';
   } 
   else {
      $default = COLOR_THEME;
   }

   $result = '';
   while (list ($theme, $title) = each ($themes_css)) {
      if ($theme == $default) {
         $rel = 'stylesheet';
      } 
      else {
         $rel = 'alternate stylesheet';
      }
      $result .= sprintf('<link rel="%s" href="%s" title="%s" type="text/css"/>',
                         $rel,  STYLE_DIR.'style_'.$theme.'.css', $title);
   }
   if ($return) return $result;
   echo $result;
}
function lbFavicon($return = false) {
   if (is_file(PHOTOS_DIR.'favicon.ico')) {
      $favicon = PHOTOS_DIR.'favicon.ico';
      $result = sprintf('<link rel="shortcut icon" mXattribut="%s"/>', $favicon);
      if ($return) return $result;
      echo $result;
   }
}
function lbResPosition($return = false) {
   $result = $GLOBALS['_LB_render']['res']->getIntIndex();
   $result ++;
   if ($return) return $result;
   echo $result;
}

function lbResTotal($return = false) {
   $result = $GLOBALS['_LB_render']['res']->getIntRowCount();
   if ($return) return $result;
   echo $result;
}
/*------------------------------------------------------------------------------
 
 -----------------------------------------------------------------------------*/
function lbVignetteStyle($return = false) {
   $img =& $GLOBALS['_LB_render']['res']->f();
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
function lbColStyle($return = false) {
   $result = VIGNETTE_STYLE;
   if ($return) return $result;
   echo $result;
}
function lbLinkVignette($return = false) {
   $result = link::vignette($GLOBALS['_LB_render']['res']->getDir(),
                            $GLOBALS['_LB_render']['res']->f()->getImageName());
   if ($return) return $result;
   echo $result;
}
function lbPathPhoto($return = false) {
   $result = $GLOBALS['_LB_render']['img']->getImagePath();
   if ($return) return $result;
   echo $result;
}
function lbDisplayVignette($return = false) {
   $img =& $GLOBALS['_LB_render']['res']->f();
   $path = $img->getThumbLink();
   $dimensions = $img->getThumbResizeSize();
   $result = sprintf ('<img src="%s" %s/>', $path, $dimensions);
   if ($return) return $result;
   echo $result;
}
function lbDisplayApercu($return = false) {
   $img =& $GLOBALS['_LB_render']['img'];
   $path = $img->getPreviewLink();
   $dimensions = $img->getPreviewResizeSize();
   $result = sprintf ('<img src="%s" %s/>', $path, $dimensions);
   if ($return) return $result;
   echo $result;
}
function lbPhotoDescription($return = false) {
   $img =& $GLOBALS['_LB_render']['img'];
   $img->findDescription();
   $result = $img->getDateDesc();
   if ($return) return $result;
   echo $result;
}
function lbImagePrev($return = false) {
   $res =& $GLOBALS['_LB_render']['res'];
   if ($res->isFirst()) {
      $result =  '&nbsp;';
   }
   else {
      $dir = $res->getDir();
      $res->move($res->getDefaultIndex());
      $res->movePrev();
      $img = $res->f()->getImageName();
      $result = sprintf('<a href="%s"><img src="_images/navig/back.gif" alt="back" border="0"/></a>', link::vignette($dir, $img));
   }
   if ($return) return $result;
   echo $result;
}
function lbImageNext($return = false) {
   $res =& $GLOBALS['_LB_render']['res'];
   if ($res->isLast()) {
      $result =  '&nbsp;';
   }
   else {
      $dir = $res->getDir();
      $res->move($res->getDefaultIndex());
      $res->moveNext();
      $img = $res->f()->getImageName();
      $result = sprintf('<a href="%s"><img src="_images/navig/forward.gif" alt="forward" border="0"/></a>', link::vignette($dir, $img));
   }
   if ($return) return $result;
   echo $result;
}
function lbLinkExif($return = false) {
   $img =&  $GLOBALS['_LB_render']['img'];
   $result = link::exif($img->getImageDir(), $img->getImageName());
   if ($return) return $result;
   echo $result;
}
function lbLinkComment($return = false) {
   $img =&  $GLOBALS['_LB_render']['img'];
   $result = link::commentaire($img->getImageDir(), $img->getImageName());
   if ($return) return $result;
   echo $result;
}
function lbCommentCount($return = false) {
   $result =  $GLOBALS['_LB_render']['img']->getNbComment();
   if ($return) return $result;
   echo $result;
}


/*------------------------------------------------------------------------------
 EXIF DATA
 -----------------------------------------------------------------------------*/
function lbExifExposureTime($return = false) {
   $result = $GLOBALS['_LB_render']['img']->getExifExposureTime ();
   if ($return) return $result;
   echo $result;
}
function lbExifAperture($return = false) {
   $result = $GLOBALS['_LB_render']['img']->getExifAperture ();
   if ($return) return $result;
   echo $result;
}
function lbExifFocalLength($return = false) {
   $result = $GLOBALS['_LB_render']['img']->getExifFocalLength ();
   if ($return) return $result;
   echo $result;
}
function lbExifCameraMaker($return = false) {
   $result = $GLOBALS['_LB_render']['img']->getExifCameraMaker ();
   if ($return) return $result;
   echo $result;
}
function lbExifCameraModel($return = false) {
   $result = $GLOBALS['_LB_render']['img']->getExifCameraModel ();
   if ($return) return $result;
   echo $result;
}
function lbExifISO($return = false) {
   $result = $GLOBALS['_LB_render']['img']->getExifISO ();
   if ($return) return $result;
   echo $result;
}
function lbExifCaptureDate($return = false) {
   $result = $GLOBALS['_LB_render']['img']->getExifCaptureDate ();
   if ($return) return $result;
   echo $result;
}
function lbExifFlash($return = false) {
   $result = $GLOBALS['_LB_render']['img']->getExifFlash ();
   if ($return) return $result;
   echo $result;
}



/*------------------------------------------------------------------------------
 OPTIONS STATUS
 -----------------------------------------------------------------------------*/
function lbExifEnabled() {
   return (SHOW_EXIF == 'on');
}
function lbCommentsEnabled() {
   return (SHOW_COMMENTAIRE == 'on');
}
function lbSlideshowEnabled() {
   return (SHOW_SLIDESHOW == 'on');
}
function lbSelectionEnabled() {
   return (SHOW_SELECTION == 'on');
}
function lbSlideshowFadingEnabled() {
   return (SLIDESHOW_FADING == 'on');
}


/*------------------------------------------------------------------------------
 SLIDESHOW
 -----------------------------------------------------------------------------*/
function lbPhotoDir($return = false) {
   $result = PHOTOS_DIR;
   if ($return) return $result;
   echo $result;
}
function lbSlideshowTime($return = false) {
   $result = SLIDESHOW_TIME;
   if ($return) return $result;
   echo $result;
}
function lbSlideshowDir($return = false) {
   $result = $GLOBALS['LB']['dir'];
   if ($return) return $result;
   echo $result;
}
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
function lbSlideshowPhotoList($return = false) {
   $list = new luxBumGallery($GLOBALS['LB']['dir']);
   $list->addAllImages ();
   $list->reset();
   $i = 0;
   $result = '';
   while (!$list->EOF()) {
      $result .= 'photosURL['.$i.'] = "'.$list->f()->getPreviewLink().'";'."\n";
      $list->moveNext();
      $i++;
   }
   if ($return) return $result;
   echo $result;
}

?>