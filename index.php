<?php
$startTimedede = microtime();
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
// Précise le répertoire de template par défaut.
define ('MX_TEMPLATE_PATH', STRUCTURE_DIR);


//------------------------------------------------------------------------------
// Fonction utilitaires
//------------------------------------------------------------------------------
// Pr définir le titre des pages (un peu concon)
function definir_titre (&$page, $titre_page) {
   $page->MxText('titre_page', $titre_page);
}
/*
class link {   
   function prefix () {
      return (USE_REWRITE == 'on') ? '' : '?/';
   }
   
   // Le lien pour les pages de vignettes
   function vignette ($page, $dir) {
      return link::prefix()."vignette-$page-$dir.html";
   }
   
   // Le lien pour les pages de vignettes
   function vignette_redirect ($page, $dir, $img) {
      return link::prefix()."vignette-$page-$dir-$img.html";
   }
   
   // Le lien pour les pages des aperçus
   function apercu ($dir, $image, $page) {
      $page--;
      return link::prefix().'affichage-'.$page.'-'.$dir.'-'.$image.'.html';
   }
   
   // Le lien pour les pages de slideshow
   function slideshow ($page) {
      return link::prefix()."slideshow-$page.html";
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
}*/

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

function lien_sous_galerie($dir) {
   global $prefix_rewrite;
   return $prefix_rewrite.'ssgal-'.$dir.'.html';
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
   
   if (is_file(PHOTOS_DIR.'favicon.ico')) {
      $page->MxAttribut('favicon.favicon', PHOTOS_DIR.'favicon.ico');
   }
   else {
      $page->MxBloc('favicon', 'delete');
   }
}


//------------------------------------------------------------------------------
// Quelle page inclure ?
//------------------------------------------------------------------------------

$p = '';
if (isset ($_GET['p'])) {
   $p = $_GET['p'];
}
else if (isset ($_SERVER['QUERY_STRING'])) {
   $argv = split("[/-]", $_SERVER['QUERY_STRING']);
   if (isset ($argv[1])) {
      $p = $argv[1];
   }
}


switch ($p) {
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

echo "\n\n".'mem: '.(int)(memory_get_usage()/1024).' ko';
echo ' - exec time (sec): '.(microtime() - $startTimedede);
?>