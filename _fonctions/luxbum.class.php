<?php


//==============================================================================
// Classe luxBum
//==============================================================================

class luxBum {

   /**-----------------------------------------------------------------------**/
   /* Fonctions utilitaires */
   /**-----------------------------------------------------------------------**/

   /**
    * Affiche une taille en octets sous une belle forme
    */
   function niceSize ($size) {
      if ($size >= 1073741824) {
         $size = (round ($size / 1073741824 * 100) / 100).' Go';
      }

      elseif ($size >= 1048576) {
         $size = (round ($size / 1048576 * 100) / 100).' Mo';
      }

      elseif ($size >= 1024) {
         $size = (round ($size / 1024 * 100) / 100).' Ko';
      }

      else {
         $size = $size.' octets';
      }

      if ($size == 0) {
         $size = '0 Ko';
      } 
      //sprintf('%01.2f', $size/$mb)

      return $size;
   }


   /**
    * 
    */
   function niceName ($name) {
      $pattern = array ('_', '-');
      $repl = array (' ', ' ');
      return str_replace ($pattern, $repl, $name);
   }


   /**-----------------------------------------------------------------------**/
   /* Fonctions pr les noms de répertoires */
   /**-----------------------------------------------------------------------**/

   /**
    * Retourne le chemin complet des vignettes du dossier $dir de photos
    * Avec un / final
    */
   function getThumbPath ($dir) {
      return luxbum::getFsPath ($dir) . THUMB_DIR;
   }

   /**
    * Retourne le chemin complet des prévisualisation du dossier $dir de photos
    * Avec un / final
    */
   function getPreviewPath ($dir) {
      return luxbum::getFsPath ($dir) . PREVIEW_DIR;
   }

   /**
    * Retourne le chemin complet vers le dossier des commentaires
    * Avec un / final
    */
   function getCommentPath($dir) {
      return luxbum::getFsPath ($dir) . COMMENT_DIR;
   }

   /**
    * Retourne le chemin complet vers le fichier des commentaires
    * Avec un / final
    */
   function getCommentFilePath($dir, $photo) {
      return luxbum::getCommentPath($dir) . $photo . '.txt';
   }

   /**
    * Retourne le chemin complet du dossier $dir de photos
    * Avec un / final
    */
   function getFsPath ($dir, $subdir='') {
      if ($subdir == '') {
         return PHOTOS_DIR.files::addTailSlash($dir);
      }
      else {
         return PHOTOS_DIR.files::addTailSlash($dir).files::addTailSlash($subdir);
      }
   }

   /**-----------------------------------------------------------------------**/
   /* Fonctions pr les noms des images */
   /**-----------------------------------------------------------------------**/

   /**
    * Retourne le chemin de l'image $img du dossier $dir d'images
    */
   function getImage ($dir, $img) {
      return luxbum::getFsPath ($dir) . $img;
   }

   /**
    * Retourne le chemin de l'image vignette $img du dossier $dir d'images
    */
   function getThumbImage ($dir, $img, $w, $h) {
      return luxbum::getThumbPath ($dir) . $w . '_' . $h . $img;
   }

   /**
    * Retourne le chemin de l'image prévisualisation $img du dossier $dir d'images
    */
   function getPreviewImage ($dir, $img, $w, $h) {
      return luxbum::getPreviewPath ($dir) . $w . '_' . $h . $img;
   }
}


?>