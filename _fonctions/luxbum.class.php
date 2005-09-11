<?php

include(FONCTIONS_DIR.'class/luxbumgallery.class.php');
include(FONCTIONS_DIR.'class/luxbumgallerylist.class.php');
include(FONCTIONS_DIR.'class/luxbumimage.class.php');
include(FONCTIONS_DIR.'class/luxbumindex.class.php');
include(FONCTIONS_DIR.'class/imagetoolkit.class.php');
include(FONCTIONS_DIR.'class/files.php');


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
      $pattern = array ('_');
      $repl = array (' ');
      return str_replace ($pattern, $repl, $name);
   }


   /**-----------------------------------------------------------------------**/
   /* Fonctions pr les noms de r�pertoires */
   /**-----------------------------------------------------------------------**/

   /**
    * 
    */
   function protectDir ($dir) {

      // petite secu pour eviter d'aller dans les reps inf�rieurs
      while (is_string (strstr ($dir, '..'))) {
         $dir = str_replace ('..', '.', $dir);
      }

      // encore une ptite secu pour �viter de faire mumuse avec les ./././sys par exemple
      while (is_string (strstr ($dir, './'))) {
         $dir = str_replace ('./', '', $dir);
      }
      return $dir;
   }

   /**
    * Retourne le chemin complet du dossier $dir de photos
    * Avec un / final
    */
   function getDirPath ($dir) {
      return PHOTOS_DIR. (files::addTailSlash ($dir));
   }

   /**
    * Retourne le chemin complet des vignettes du dossier $dir de photos
    * Avec un / final
    */
   function getThumbPath ($dir) {
      return $this->getDirPath ($dir) . THUMB_DIR;
   }

   /**
    * Retourne le chemin complet des pr�visualisation du dossier $dir de photos
    * Avec un / final
    */
   function getPreviewPath ($dir) {
      return $this->getDirPath ($dir) . PREVIEW_DIR;
   }


   /**-----------------------------------------------------------------------**/
   /* Fonctions pr les noms des images */
   /**-----------------------------------------------------------------------**/

   /**
    * Retourne le chemin de l'image $img du dossier $dir d'images
    */
   function getImage ($dir, $img) {
      return luxbum::getDirPath ($dir) . $img;
   }

   /**
    * Retourne le chemin de l'image vignette $img du dossier $dir d'images
    */
   function getThumbImage ($dir, $img, $w, $h) {
      return $this->getThumbPath ($dir) . $w . '_' . $h . $img;
   }

   /**
    * Retourne le chemin de l'image pr�visualisation $img du dossier $dir d'images
    */
   function getPreviewImage ($dir, $img) {
      return $this->getPreviewPath ($dir) . $img;
   }
}


?>