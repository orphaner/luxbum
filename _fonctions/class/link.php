<?php

class link {
   function prefix () {
      return (USE_REWRITE == 'on') ? URL_BASE : URL_BASE.'?/';
   }

   function thumb ($dir, $img) {
      if (USE_REWRITE == 'on') {
         $prefix = URL_BASE.'image/';
      }
      else {
         $prefix = URL_BASE.'image.php?';
      }
      return $prefix.THUMB_DIR.'/'.$dir.'/'.$img;
   }
   
   function preview ($dir, $img) {
      if (USE_REWRITE == 'on') {
         $prefix = URL_BASE.'image/';
      }
      else {
         $prefix = URL_BASE.'image.php?';
      }
      return $prefix.PREVIEW_DIR.'/'.$dir.'/'.$img;
   }
   
   function index ($dir, $img) {
      if (USE_REWRITE == 'on') {
         $prefix = URL_BASE.'image/';
      }
      else {
         $prefix = URL_BASE.'image.php?';
      }
      return $prefix.'index/'.$dir.'/'.$img;
   }
   
   // Le lien pour les pages de vignettes
   function vignette ($dir, $img = '') {
      if ($img == '') {
         return link::prefix()."vignette/$dir.html";
      }
      else {
         return link::prefix()."vignettei/$dir/$img.html";
      }
   }
   
//    // Le lien pour les pages des aperçus
//    function apercu ($dir, $image, $page) {
//       $page--;
//       return link::prefix().'affichage-'.$page.'-'.$dir.'-'.$image.'.html';
//    }
   
   // Le lien pour les pages de slideshow
   function slideshow ($dir, $start='') {
      if ($start == '') {
         return link::prefix().'slide-show/'.$dir.'.html';
      }
      else {
         return link::prefix().'slide-show/'.$dir.'/'.$start.'.html';
      }
   }
   
//    // Lien pour voir la sélection
//    function selection ($page) {
//       return link::prefix()."selection_list-$page.html";
//    }
   
//    // Lien pour sélectionner une photo
//    function apercu_select ($dir, $image, $page) {
//       $page--;
//       return link::prefix().'select-'.$page.'-'.$dir.'-'.$image.'.html';
//    }
   
//    // Lien pour désélectionner une photo
//    function apercu_unselect ($dir, $image, $page) {
//       $page--;
//       return link::prefix().'unselect-'.$page.'-'.$dir.'-'.$image.'.html';
//    }
 
   // Lien pour une sous galerie
   function subGallery($dir) {
      return  link::prefix().'ssgal/'.$dir.'.html';
   }

   function commentaire($dir, $img) {
      return  link::prefix().'commentaires/'.$dir.'/'.$img.'.html';
   }

   function exif($dir, $img) {
      return  link::prefix().'exif/'.$dir.'/'.$img.'.html';
   }
   function affichage($dir, $img) {
      return  link::prefix().'affichage/'.$dir.'/'.$img.'.html';
   }
}


?>