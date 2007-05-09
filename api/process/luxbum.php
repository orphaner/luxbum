<?php


//==============================================================================
// Classe luxBum
//==============================================================================

  /**
   * @package process
   */
class luxBum {

   /**-----------------------------------------------------------------------**/
   /* Fonctions utilitaires */
   /**-----------------------------------------------------------------------**/

   /**
    * Show a size in byte in a nice way
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
   /* Functions for directories name */
   /**-----------------------------------------------------------------------**/

   /**
    * Return the thumb full path of the directory $dir
    * With a trailing slash
    */
   function getThumbPath ($dir) {
      return luxbum::getFsPath ($dir) . THUMB_DIR;
   }

   /**
    * Retourne le chemin complet des pr�visualisation du dossier $dir de photos
    * With a trailing slash
    */
   function getPreviewPath ($dir) {
      return luxbum::getFsPath ($dir) . PREVIEW_DIR;
   }

   /**
    * Retourne le chemin complet vers le dossier des commentaires
    * With a trailing slash
    */
   function getCommentPath($dir) {
      return luxbum::getFsPath ($dir) . COMMENT_DIR;
   }

   /**
    * Retourne le chemin complet vers le fichier des commentaires
    * With a trailing slash
    */
   function getCommentFilePath($dir, $photo) {
      return luxbum::getCommentPath($dir) . $photo . '.txt';
   }

   /**
    * Retourne le chemin complet du dossier $dir de photos
    * With a trailing slash
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
    * Return the full file path
    */
   function getFilePath($dir, $img) {
      return luxbum::getFsPath ($dir) . $img;
   }

   /**
    * Retourne le chemin de l'image vignette $img du dossier $dir d'images
    */
   function getThumbImage ($dir, $img, $w, $h) {
      return luxbum::getThumbPath ($dir) . $w . '_' . $h . $img;
   }

   /**
    * Retourne le chemin de l'image pr�visualisation $img du dossier $dir d'images
    */
   function getPreviewImage ($dir, $img, $w, $h) {
      return luxbum::getPreviewPath ($dir) . $w . '_' . $h . $img;
   }
}


?>