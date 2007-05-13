<?php

class CommonGallery extends SortableRecordset {
   /**
    * Enter description here...
    *
    * @access private
    * @var unknown_type
    */
   var $name;
   var $preview;
   
   var $flvCount = 0;
   var $imageCount = 0;
   var $totalCount = 0;
   
   var $flvSize = 0;
   var $imageSize = 0;
   var $totalSize = 0;

   
   /**
    * Set the gallery name
    * @param string $name Gallery name
    */
   function setName ($name) {
      $this->name = $name;
   }

   /**
    * Return the gallery name
    * @return String GAllery name
    */
   function getName () {
      return $this->name;
   }

   /**
    * Retourne le "beau" nom de la galerie
    * @return String Beau nom de la galerie
    */
   function getNiceName () {
      return luxbum::niceName ($this->name);
   }

   /**
    * Retourne l'image par d�faut de la galerie
    * @return String Chemin vers l'image par d�faut de la galerie
    */
   function getPreview () {
      return $this->preview;
   }

   /**
    * Retourne la taille en octets des photos de la galerie
    * @return int Taille en octets des photos de la galerie
    */
   function getImageSize () {
      return $this->imageSize;
   }

   /**
    * Retourne la taille en octets des photos de la galerie
    * @return int Taille en octets des photos de la galerie
    */
   function getFlvSize () {
      return $this->flvSize;
   }

   /**
    * Retourne la taille en octets des photos de la galerie
    * @return int Taille en octets des photos de la galerie
    */
   function getTotalSize () {
      return $this->totalSize;
   }

   /**
    * Retourne la taille affichable des photos de la galerie
    * @return int Taille affichable des photos de la galerie
    */
   function getImageNiceSize () {
      return luxbum::niceSize ($this->imageSize);
   }

   /**
    * Retourne la taille affichable des photos de la galerie
    * @return int Taille affichable des photos de la galerie
    */
   function getFlvNiceSize () {
      return luxbum::niceSize ($this->flvSize);
   }

   /**
    * Retourne la taille affichable des photos de la galerie
    * @return int Taille affichable des photos de la galerie
    */
   function getTotalNiceSize () {
      return luxbum::niceSize ($this->totalSize);
   }

   /**
    * Retourne le nombre de photos de la galerie
    * @return int Nombre de photos de la galerie
    */
   function getImageCount () {
      return $this->imageCount;
   }

   /**
    * Retourne le nombre de vidéos de la galerie
    * @return int Nombre de vidéos de la galerie
    */
   function getFlvCount () {
      return $this->flvCount;
   }

   /**
    * Retourne le nombre de vidéos de la galerie
    * @return int Nombre de vidéos de la galerie
    */
   function getTotalCount () {
      return $this->totalCount;
   }

   /**
    * Affecte la position de la galerie dans l'index
    * @param String $sortPosition Position de la galerie dans l'index
    */
   function setSortPosition ($sortPosition) {
      $this->sortPosition = $sortPosition;
   }
   
   /**
    * Retourne la position de la galerie dans l'index
    * @return String Position de la galerie dans l'index
    */
   function getSortPosition () {
      return $this->sortPosition;
   }
   
   /**
    * Retourne la position d'une image $imgName dans la galerie
    * @param String $imgName l'image dont on cherche la position
    * @return la position de cette image dans la galerie
    */
   function getImageIndex ($imgName) {
      $index = 0;
      $trouve = false;

      $this->reset();
      while (!$trouve && list (,$img) = each ($this->arrayList)) {
         $name = $img->getFile();
         if ($name == $imgName) {
            $trouve = true;
         }
         else {
            $index++;
         }
      }
      $this->reset();
      if (!$trouve) {
         return -1;
      }
      return $index;
   }
    
}

?>