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
define ('PASS_FILE', 'pass.php');

define ('TEMPLATE_DIR', 'templates/');
define ('TEMPLATE', 'photoblog');

include (TEMPLATE_DIR.TEMPLATE.'/css/themes_css.php');
include (TEMPLATE_DIR.TEMPLATE.'/conf_'.TEMPLATE.'.php');


//------------------------------------------------------------------------------
// Includes
//------------------------------------------------------------------------------
include (CONF_DIR.'config.php');


//------------------------------------------------------------------------------
// Fonctions
//------------------------------------------------------------------------------

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
    * Vérifie si le dossier est correct
    */
   function isDir ($dir) {
      if (!verif::dir ($dir)) {
         exit ('nom de dossier incorrect !!');
      }
      else if (!is_dir (luxbum::getFsPath ($dir))) {
         exit ('dossier incorrect !!');
      }
   }

   /**
    * Vérifie si un couple dossier/image est correct.
    * Exit si erreur !
    * @param String dir Dossier de l'image
    * @param String $file Nom de l'image
    */
   function isImage ($dir, $file) {
      verif::isDir ($dir);
      if (!verif::photo ($dir, $file)) {
         exit ('nom de la photo incorrect !!');
      }
   }
}
?>