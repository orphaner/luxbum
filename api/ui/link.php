<?php

  /**
   * @package ui
   */
class link {
   function encode($s) {
      $s = rawurlencode($s);
      $s = str_replace ('%2F', '/', $s);
      return $s;
   }

   function prefix () {
      return (USE_REWRITE == 'on') ? URL_BASE : URL_BASE.'?/';
   }

   function thumb ($dir, $img) {
      $dir = link::encode($dir);
      $img = link::encode($img);
      return  link::prefix().'image/'.THUMB_DIR.$dir.'/'.$img;
   }
   
   function preview ($dir, $img) {
      $dir = link::encode($dir);
      $img = link::encode($img);
      return  link::prefix().'image/'.PREVIEW_DIR.$dir.'/'.$img;
   }
   
   function index ($dir, $img) {
      $dir = link::encode($dir);
      $img = link::encode($img);
      return  link::prefix().'image/index/'.$dir.'/'.$img;
   }
   
   function full ($dir, $img) {
      $dir = link::encode($dir);
      $img = link::encode($img);
      return  link::prefix().'image/full/'.$dir.'/'.$img;
   }

   function photo($path) {
      $path = link::encode($path);
      return URL_BASE.$path;
   }
   
   // Le lien pour les pages de vignettes
   function vignette ($dir, $img = '') {
      $dir = link::encode($dir);
      $img = link::encode($img);
      if ($img == '') {
         return link::prefix().'album/'.$dir.'/';
      }
      else {
         return link::prefix().'album/'.$dir.'/'.$img;
      }
   }
   
   
   // Le lien pour les pages de slideshow
   function slideshow ($dir, $start='') {
      $dir = link::encode($dir);
      if ($start == '') {
         return link::prefix().'slide-show/'.$dir;
      }
      else {
         return link::prefix().'slide-show/'.$dir.'/'.$start;
      }
   }

   function privateGallery($dir) {
      $dir = link::encode($dir);
      return link::prefix().'private/'.$dir.'/';
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
      $dir = link::encode($dir);
      $dir = files::addTailSlash($dir);
      return  link::prefix().'folder/'.$dir;
   }

   function commentaire($dir, $img) {
      $dir = link::encode($dir);
      $img = link::encode($img);
      return  link::prefix().'comments/'.$dir.'/'.$img;
   }

   function meta($dir, $img) {
      $dir = link::encode($dir);
      $img = link::encode($img);
      return  link::prefix().'meta/'.$dir.'/'.$img;
   }
   function affichage($dir, $img) {
      $dir = link::encode($dir);
      $img = link::encode($img);
      return  link::prefix().'photo/'.$dir.'/'.$img;
   }
}


?>