<?php

  /**
   * @package inc
   */
class verif {
   
   /**
    * Vérifie si le format du nom du dossier est correct
    * @param String dir Dossier de l'image
    */
   function dir ($dir) {
//       if (! ereg ("^[A-Za-z0-9_/]+$", $dir)) {
//          return false;
//       }
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