<?php


class link {
   function prefix () {
      return (USE_REWRITE == 'on') ? '' : '?/';
   }
   
   // Le lien pour les pages de vignettes
   function vignette ($dir, $img = '') {
      if ($img == '') {
         return link::prefix()."vignette-$dir.html";
      }
      else {
         return link::prefix()."vignette-$dir-$img.html";
      }
   }
   
   // Le lien pour les pages des aperçus
   function apercu ($dir, $image, $page) {
      $page--;
      return link::prefix().'affichage-'.$page.'-'.$dir.'-'.$image.'.html';
   }
   
   // Le lien pour les pages de slideshow
   function slideshow ($dir, $start='') {
      if ($start == '') {
         return link::prefix().'slideshow-'.$dir.'.html';
      }
      else {
         return link::prefix().'slideshow-'.$dir.'-'.$start.'.html';
      }
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
 
   // Lien pour une sous galerie
   function subGallery($dir) {
      return  link::prefix().'ssgal-'.$dir.'.html';
   }

   function commentaire($dir, $img) {
      return  link::prefix().'commentaires-'.$dir.'-'.$img.'.html';
   }

   function exif($dir, $img) {
      return  link::prefix().'exif-'.$dir.'-'.$img.'.html';
   }
   function affichage($dir, $img) {
      return  link::prefix().'affichage-'.$dir.'-'.$img.'.html';
   }
}


?>