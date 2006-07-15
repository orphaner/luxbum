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
define ('ORDER_FILE', 'ordre.txt');
define ('DEFAULT_INDEX_FILE', 'defaut.txt');
define ('ALLOWED_FORMAT', 'jpg|jpeg|png|gif');


//------------------------------------------------------------------------------
// Includes
//------------------------------------------------------------------------------
include (CONF_DIR.'config.php');
include (CONF_DIR.'themes_css.php');
include (LIB_DIR.'ModeliXe.php');


//------------------------------------------------------------------------------
// Variables pour les différents templates
//------------------------------------------------------------------------------
switch (TEMPLATE_THEME) {
   case '2COL':
      define ('LIMIT_THUMB_PAGE', 6);
      define ('NB_COL', 2);
      define ('IMG_W', 125);
      define ('IMG_H', 125);
      define ('VIGNETTE_STYLE', 'vignette2col');
      break;

   default:
      define ('LIMIT_THUMB_PAGE', 12);
      define ('NB_COL', 3);
      define ('IMG_W', 85);
      define ('IMG_H', 85);
      define ('VIGNETTE_STYLE', 'vignette3col');
      break;
}


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

class verif {
   
   /**
    * Vérifie si le format du nom du dossier est correct
    * @param String dir Dossier de l'image
    */
   function dir ($dir) {
      if (! ereg ("^[A-Za-z0-9_/]+$", $dir)) {
         return false;
      }
      return true;
   }
   
   /**
    * Vérifie si l'image existe
    * @param String dir Dossier de l'image
    * @param String $img Nom de l'image
    */
   function photo ($dir, $img) {
      if (!is_file (luxbum::getImage ($dir, $img))) {
         return false;
      }
      return true;
   }

   /**
    * Vérifie si un couple dossier/image est correct.
    * Exit si erreur !
    * @param String dir Dossier de l'image
    * @param String $file Nom de l'image
    */
   function isImage ($dir, $file) {
      $lux = new Luxbum();
      $lux->setPhotoDir($dir);
      if (!verif::dir ($dir)) {
         exit ('nom de dossier incorrect !!');
      }
      else if (!is_dir ($lux->getDirPath ($dir))) {
         exit ('dossier incorrect !!');
      }
      else if (!verif::photo ($dir, $file)) {
         exit ('nom de la photo incorrect !!');
      }
   }
}
?>