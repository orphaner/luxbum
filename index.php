<?php

  //------------------------------------------------------------------------------
  // Includes
  //------------------------------------------------------------------------------
include ('common.php');


//------------------------------------------------------------------------------
// Constantes
//------------------------------------------------------------------------------
define ('MX_TEMPLATE_PATH', STRUCTURE_DIR);      //Pr�cise le r�pertoire de template par d�faut.


//------------------------------------------------------------------------------
// Fonction utilitaires
//------------------------------------------------------------------------------
// Pr d�finir le titre des pages (un peu concon)
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

// Le lien pour les pages des aper�us
function lien_apercu ($dir, $image, $page) {
   global $prefix_rewrite;
   $page--;
   return $prefix_rewrite.'affichage-'.$page.'-'.$dir.'-'.$image.'.html';
}


function remplir_style (&$page) {
   $themes = array (
      'light' =>'Th�me Blanc', 
      'dark' =>'Th�me Noir'
      );

   if (!array_key_exists (COLOR_THEME, $themes)) {
      $default = 'light';
   } 
   else {
      $default = COLOR_THEME;
   }

   while (list ($theme, $title) = each ($themes)) {
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
// Variables pour les diff�rents templates
//------------------------------------------------------------------------------

switch (TEMPLATE_THEME) {
   case '2COL':
      define ('LIMIT_THUMB_PAGE', 6);
      define ('NB_COL', 2);
      define ('IMG_W', 300);
      define ('IMG_H', 300);
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
      if (ereg ('^/(.*)-(.*)-(.*)-(.*)\.html$', $_SERVER['QUERY_STRING'], $argv) ||
          ereg ('^/(.*)-(.*)-(.*)\.html$', $_SERVER['QUERY_STRING'], $argv)) {
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
   default:
      include (FONCTIONS_DIR.'index.php');
      break;
}

?>