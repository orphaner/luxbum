<?php

/**
 * @package ui
 */
class link {
   function encode($s) {
      $s = rawurlencode($s);
      $s = str_replace('%2F', '/', $s);
      return $s;
   }

   function prefix() {
      return (Pluf::f('use_rewrite')) ? Pluf::f('url_base') : Pluf::f('url_index').'?/';
   }

   function thumb($dir, $img) {
      $dir = link::encode($dir);
      $img = link::encode($img);
      return  link::prefix().'image/vignette/'.$dir.$img;
   }

   function preview($dir, $img) {
      $dir = link::encode($dir);
      $img = link::encode($img);
      return  link::prefix().'image/apercu/'.$dir.$img;
   }

   function index($dir, $img) {
      $dir = link::encode($dir);
      $img = link::encode($img);
      return  link::prefix().'image/index/'.$dir.$img;
   }

   function full($dir, $img) {
      $dir = link::encode($dir);
      $img = link::encode($img);
      return  link::prefix().'image/full/'.$dir.$img;
   }

   function photo($path) {
      $path = link::encode($path);
      return URL_BASE.$path;
   }

   // Le lien pour les pages de vignettes
   function gallery($dir, $file) {
      if ($GLOBALS['isSelection']) {
         return link::selection($dir, $file);
      }
      $dir = link::encode($dir);
      $file = link::encode($file);
      return link::prefix().'gallery/'.$dir.$file;
   }
   
   // Le lien pour les pages de video flv
   function fileFlvDL($dir, $img = '') {
      $dir = link::encode($dir);
      $img = link::encode($img);
      if($img == '') {
         return link::prefix().'flvdl/'.$dir;
      }
      else {
         return link::prefix().'flvdl/'.$dir.$img;
      }
   }

   // Le lien pour les pages de slideshow
   function slideshow($dir, $file) {
      $dir = link::encode($dir);
      $file = link::encode($file);
      return link::prefix().'slide-show/'.$dir.$file;
   }

   function privateGallery($dir) {
      $dir = link::encode($dir);
      return link::prefix().'private/'.$dir;
   }
    
   function select($dir, $file) {
      $dir = link::encode($dir);
      $file = link::encode($file);
      return link::prefix().'select/'.$dir.$file;
   }
    
   function unselect($dir, $file) {
      $dir = link::encode($dir);
      $file = link::encode($file);
      return link::prefix().'unselect/'.$dir.$file;
   }
    
   function selectall($dir) {
      $dir = link::encode($dir);
      return link::prefix().'selectall/'.$dir;
   }
    
   function unselectall($dir) {
      $dir = link::encode($dir);
      return link::prefix().'unselectall/'.$dir;
   }
    
   function selection($dir, $file) {
      $dir = link::encode($dir);
      $file = link::encode($file);
      return link::prefix().'selectiong/'.$dir.$file;
   }
   
   function deleteSelection() {
      return link::prefix().'deleteselection/';
   }
   
   function downloadSelection() {
      return link::prefix().'downloadselection/';
   }

   // Lien pour une sous galerie
   function subGallery($dir) {
      $dir = link::encode($dir);
      $dir = files::addTailSlash($dir);
      return  link::prefix().'folder/'.$dir;
   }

   function commentaire($dir, $img) {
      $dir = link::encode($dir);
      $img = link::encode($img);
      return  link::prefix().'comments/'.$dir.$img;
   }

   function meta($dir, $img) {
      $dir = link::encode($dir);
      $img = link::encode($img);
      return  link::prefix().'meta/'.$dir.$img;
   }
   function display($dir, $img) {
      $dir = link::encode($dir);
      $img = link::encode($img);
      if ($GLOBALS['isSelection']) {
         return  link::prefix().'selectiond/'.$dir.$img;
      }
      else {
         return  link::prefix().'display/'.$dir.$img;
      }
   }
}


?>