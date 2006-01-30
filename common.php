<?php 

  // Au revoir les erreurs
error_reporting (E_ALL);

//------------------------------------------------------------------------------
// Constantes
//------------------------------------------------------------------------------
define ('FONCTIONS_DIR',       '_fonctions/');
define ('ADMIN_DIR',           '_fonctions_manager/');
define ('CONF_DIR',            '_conf/');
define ('LIB_DIR',             '_librairies/');
define ('STYLE_DIR',           '_styles/');
define ('STRUCTURE_DIR',       '_structure/');
define ('ADMIN_STRUCTURE_DIR', '_structure_manager/');
define ('INDEX_FILE',          'index.php');
define ('ADMIN_FILE',          'manager.php');

define ('PHOTOS_DIR', 'photos/');
define ('THUMB_DIR', 'vignette/');
define ('PREVIEW_DIR', 'apercu/');
define ('DESCRIPTION_FILE', 'description.txt');
define ('DEFAULT_INDEX_FILE', 'defaut.txt');
define ('ALLOWED_FORMAT', 'jpg|jpeg|png|gif');



//------------------------------------------------------------------------------
// Includes
//------------------------------------------------------------------------------
include (CONF_DIR.'config.php');
include (CONF_DIR.'themes_css.php');
include (LIB_DIR.'ModeliXe.php');


//------------------------------------------------------------------------------
// Fonctions
//------------------------------------------------------------------------------
function verif_dir ($dir) {
   if (! ereg ("^[A-Za-z0-9_]+$", $dir)) {
      return false;
   }
   return true;
}

function verif_photo ($dir, $img) {
   if (!is_file (luxbum::getImage ($dir, $img))) {
      return false;
   }
   return true;
}
?>