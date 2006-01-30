<?php

//@start upd dark 1.2
session_start();
//@end upd dark 1.2

  //------------------------------------------------------------------------------
  // Includes
  //------------------------------------------------------------------------------
include ('common.php');


//------------------------------------------------------------------------------
// Constantes
//------------------------------------------------------------------------------
define ('MX_TEMPLATE_PATH', STRUCTURE_DIR);      //Précise le répertoire de template par défaut.


//------------------------------------------------------------------------------
// Fonction utilitaires
//------------------------------------------------------------------------------
// Pr définir le titre des pages (un peu concon)
function definir_titre (&$page, $titre_page) {
   $page->MxText('titre_page', $titre_page);
}


$prefix_rewrite = (USE_REWRITE == 'on') ? '' : '?/';

// Le lien pour les pages de vignettes
function lien_vignette ($page, $dir) {
   global $prefix_rewrite;
   return $prefix_rewrite."vignette-$page-$dir.html";
}

// Le lien pour les pages de vignettes
function lien_vignette_redirect ($page, $dir, $img) {
   global $prefix_rewrite;
   return $prefix_rewrite."vignette-$page-$dir-$img.html";
}

// Le lien pour les pages des aperçus
function lien_apercu ($dir, $image, $page) {
   global $prefix_rewrite;
   $page--;
   return $prefix_rewrite.'affichage-'.$page.'-'.$dir.'-'.$image.'.html';
}

// Le lien pour les pages de slideshow
function lien_slideshow ($page) {
   global $prefix_rewrite;
   return $prefix_rewrite."slideshow-$page.html";
}

// Lien pour voir la sélection
function lien_selection ($page) {
   global $prefix_rewrite;
   return $prefix_rewrite."selection_list-$page.html";
}

// Lien pour sélectionner une photo
function lien_apercu_select ($dir, $image, $page) {
   global $prefix_rewrite;
   $page--;
   return $prefix_rewrite.'select-'.$page.'-'.$dir.'-'.$image.'.html';
}

// Lien pour désélectionner une photo
function lien_apercu_unselect ($dir, $image, $page) {
   global $prefix_rewrite;
   $page--;
   return $prefix_rewrite.'unselect-'.$page.'-'.$dir.'-'.$image.'.html';
}


function remplir_style (&$page) {
   global $themes_css;
   if (!array_key_exists (COLOR_THEME, $themes_css)) {
      $default = 'light';
   } 
   else {
      $default = COLOR_THEME;
   }

   while (list ($theme, $title) = each ($themes_css)) {
      if ($theme == $default) {
         $rel = 'stylesheet';
      } 
      else {
         $rel = 'alternate stylesheet';
      }

      $page->MxAttribut ('stylesheet.rel', $rel);
      $page->MxAttribut ('stylesheet.href', STYLE_DIR.'style_'.$theme.'.css');
      $page->MxAttribut ('stylesheet.title', $title);
      $page->MxBloc ('stylesheet', 'loop');
   }
}


//------------------------------------------------------------------------------
// Variables pour les différents templates
//------------------------------------------------------------------------------

switch (TEMPLATE_THEME) {
   case '2COL':
      define ('LIMIT_THUMB_PAGE', 6);
      define ('NB_COL', 2);
      define ('IMG_W', 125);
      define ('IMG_H', 125);
      $template = 'vignette_2col.mxt';
      break;

   default:
      define ('LIMIT_THUMB_PAGE', 12);
      define ('NB_COL', 3);
      define ('IMG_W', 85);
      define ('IMG_H', 85);
      $template = 'vignette_3col.mxt';
      break;
}


//------------------------------------------------------------------------------
// Quelle page inclure ?
//------------------------------------------------------------------------------

$p = '';

if (USE_REWRITE == 'off') {
   if (isset ($_SERVER['QUERY_STRING'])) {
      if (isset ($_GET['p']) && ($_GET['p'] == 'infos_exif' || $_GET['p'] == 'comment'
      || $_GET['p'] == 'slideshow' || $_GET['p'] == 'selection_list' || $_GET['p'] == 'dl_selection')) {
         $p = $_GET['p'];
      }
      else if (ereg ('^/(.*)-(.*)-(.*)-(.*)\.html$', $_SERVER['QUERY_STRING'], $argv) ||
               ereg ('^/(.*)-(.*)-(.*)\.html$', $_SERVER['QUERY_STRING'], $argv) ||
               ereg ('^/(.*)-(.*)\.html$', $_SERVER['QUERY_STRING'], $argv)) {
         $p = $argv[1];
      }
   }
}
else {
   if (isset ($_GET['p'])) {
      $p = $_GET['p'];
   }
}


switch ($p) {
//    case '404':
//       include (FONCTIONS_DIR.'error404.php');
//       break;
   case 'vignette':
      include (FONCTIONS_DIR.'vignette.php');
      break;
   case 'affichage':
      include (FONCTIONS_DIR.'affichage.php');
      break;
   case 'infos_exif':
      include (FONCTIONS_DIR.'infos_exif.php');
      break;
   case 'comment':
      include (FONCTIONS_DIR.'commentaire.php');
      break;
   case 'slideshow':
      include (FONCTIONS_DIR.'slideshow.php');
      break;
/**upd dark 1 **/
   case 'select': 
   case 'unselect':
      include (FONCTIONS_DIR.'select.php');
      break;
   case 'selection_list':
      include (FONCTIONS_DIR.'selection_list.php');
      break;
   case 'dl_selection':
      include (FONCTIONS_DIR.'dl_selection.php');
      break;
/** fin upd dark1 **/
   default:
      include (FONCTIONS_DIR.'index.php');
      break;
}

?>