<?php

class IndexView {
   function action ($match) {
      IndexView::initTemplate();
      // HookInitTemplate

      if (count($match) == 1) { 
         $photoDir = '';
      }
      else if (count($match) == 2) {
         $photoDir = files::removeTailSlash($match[1]);
         verif::isDir($photoDir);
      }
      else {
         echo "erreur";
      }

      $GLOBALS['_LB_render']['res'] = '';
      $res =& $GLOBALS['_LB_render']['res']; 
      $res = new  luxBumIndex ($photoDir);
      $res->addAllGallery ();


      include (TEMPLATE_DIR.TEMPLATE.'/index.php');
      return 200;
   }

   function initTemplate() {
      $GLOBALS['LB']['title'] = NOM_GALERIE;
   }
}


class VignetteView {
   function action ($match) {
      VignetteView::initTemplate();

      if (count($match) == 3) { 
         $dir = $match[1];
         $defaultImage = $match[2];
      }
      else if (count($match) == 2) {
         $dir = $match[1];
         $currentPage = 1;
      }
      else {
         echo "erreur";
      }
      verif::isDir ($dir);

      // Vérif que la page est bonne
      $GLOBALS['_LB_render']['res'] = new luxBumGallery($dir);
      $res =& $GLOBALS['_LB_render']['res']; 
      $res->addAllImages ();
      $galleryCount = $res->getCount ();

      
      $niceDir = ucfirst (luxBum::niceName ($res->getName()));
      $GLOBALS['LB']['title'] =  $niceDir.' - '.NOM_GALERIE;
      $GLOBALS['LB']['niceDir'] = $niceDir;
      $res->createOrMajDescriptionFile ();
      $res->getDescriptions ();

      if (isset($defaultImage)) {
         $imgIndex = $res->getImageIndex($defaultImage);
         $res->setDefaultIndex($imgIndex);
         $currentPage = (int)($imgIndex / LIMIT_THUMB_PAGE)+1;
      }
      else {
         $res->setDefaultIndex(($currentPage * LIMIT_THUMB_PAGE) - LIMIT_THUMB_PAGE);
      }
      $res->setStartOfPage(($currentPage * LIMIT_THUMB_PAGE) - LIMIT_THUMB_PAGE);
      $res->setEndOfPage((($currentPage) * LIMIT_THUMB_PAGE) );
      $res->reset();
      
      $GLOBALS['LB']['currentPage'] = $currentPage;
      $GLOBALS['_LB_render']['img'] = $res->getDefault();

      include (TEMPLATE_DIR.TEMPLATE.'/vignette.php');
      return 200;
   }

   function initTemplate() {
   }
}


class CommentaireView {
   function action ($match) {
      $dir = $match[1];
      $photo = $match[2];
      verif::photo($dir, $photo);
      $GLOBALS['_LB_render']['img'] = new luxBumImage ($dir, $photo);
      $GLOBALS['_LB_render']['img']->exifInit ();

      include (TEMPLATE_DIR.TEMPLATE.'/comment.php');
      return 200;
   }
}


class AffichageView {
   function action ($match) {
      $dir = $match[1];
      $photo = $match[2];
      verif::photo($dir, $photo);
      
      $GLOBALS['_LB_render']['img'] = new luxBumImage ($dir, $photo);
      $GLOBALS['_LB_render']['img']->exifInit();

      $GLOBALS['_LB_render']['res'] = new luxBumGallery($dir);
      $res =& $GLOBALS['_LB_render']['res']; 
      $res->addAllImages ();
      $imgIndex = $res->getImageIndex($photo);
      $res->setDefaultIndex($imgIndex);

      $GLOBALS['LB']['title'] =  ' - '.NOM_GALERIE;
      
      include (TEMPLATE_DIR.TEMPLATE.'/affichage.php');
      return 200;
   }
}


class InfosExifView {
   function action ($match) {
      $dir = $match[1];
      $photo = $match[2];
      verif::photo($dir, $photo);
      $GLOBALS['_LB_render']['img'] = new luxBumImage ($dir, $photo);
      $GLOBALS['_LB_render']['img']->exifInit ();
      $GLOBALS['LB']['title'] =  ' - '.NOM_GALERIE;

      include (TEMPLATE_DIR.TEMPLATE.'/exif.php');
      return 200;
   }
}

class SlideShowView {
   function action ($match) {
      $dir = $match[1];
      verif::isDir($dir);
      $GLOBALS['LB']['dir'] = $dir;
      $GLOBALS['LB']['title'] =  ' - '.NOM_GALERIE;

      include (TEMPLATE_DIR.TEMPLATE.'/slideshow.php');
      return 200;

   }
}
?>