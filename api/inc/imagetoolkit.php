<?php

ini_set ('memory_limit', '32M'); 

/**
 * @package inc
 * @abstract
 */
class ImageToolkit
{

   /**-----------------------------------------------------------------------**/
   /** Fonctions de créations d'images */
   /**-----------------------------------------------------------------------**/

   var $image;
   var $imageWidth;
   var $imageHeight;
   var $imageType;
   var $typeMime = '';

   var $imagDest;
   var $imageDestWidth;
   var $imageDestHeight;

   /**
    * Constructeur par défaut
    * @param String $image Chemin complet vers l'image
    */
   function ImageToolkit ($image) {
      $this->image = $image;
      $this->setSrcInfos ();
   }

   /**
    * Retourne la largeur finale de l'image redimensionnée.
    * @return int Largeur finale de l'image redimensionnée.
    */
   function getImageDestWidth () {
      return $this->imageDestWidth;
   }

   /**
    * Retourne la hauteur finale de l'image redimensionnée.
    * @return int Hauteur finale de l'image redimensionnée.
    */
   function getImageDestHeight () {
      return $this->imageDestHeight;
   }
   
   /**
    * Retourne le type Mime de l'image
    * @return String Type mime de l'image
    */
   function getTypeMime () {
      return $this->typeMime;
   }

   /**
    * Fixe une taille finale maximale de redimensionnement.
    * @param Int $dst_w Largeur maximale (px ou %)
    * @param int $dst_h Hauteur maximale (px ou %)
    * @param string $mode Crop mode (force, crop, ratio)
    * @param boolean $expand Allow resize of image
   */
   function setDestSize ($dst_w, $dst_h, $mode='ratio', $expand=true) {
      $this->mode = $mode;

      $imgWidth = $this->imageWidth;
      $imgHeight = $this->imageHeight;
                
      if (strpos($dst_w, '%', 0)) {
         $dst_w = $imgWidth*$dst_w/100;
      }
      if (strpos($dst_h, '%', 0)) {
         $dst_h = $imgHeight*$dst_h/100;
      }
                
      $ratio = $imgWidth/$imgHeight;
                
      // guess resize ($_w et $_h)
      if ($mode=='ratio') {
         $_w=99999;
         if ($dst_h > 0) {
            $_h = $dst_h;
            $_w = $_h*$ratio;
         }
         if ($dst_w > 0 && $_w > $dst_w) {
            $_w = $dst_w;
            $_h = $_w/$ratio;
         }
                        
         if (!$expand && $_w > $imgWidth) {
            $_w = $imgWidth;
            $_h = $imgHeight;
         }
      }
      else {
         // crop source image
         $_w = $dst_w;
         $_h = $dst_h;
      }
                
      if ($mode == 'force') {
         if ($dst_w > 0) {
            $_w = $dst_w;
         }
         else {
            $_w = $dst_h*$ratio;
         }
                        
         if ($dst_h > 0) {
            $_h = $dst_h;
         }
         else {
            $_h = $dst_w/$ratio;
         }
                        
         if (!$expand && $_w > $imgWidth){
            $_w = $imgWidth;
            $_h = $imgHeight;
         }
                        
         $cropW = $imgWidth;
         $cropH = $imgHeight;
         $decalW = 0;
         $decalH = 0;
      }
      else {
         // guess real viewport of image
         $innerRatio = $_w/$_h;
         if ($ratio >= $innerRatio) {
            $cropH = $imgHeight;
            $cropW = $imgHeight*$innerRatio;
            $decalH = 0;
            $decalW = ($imgWidth-$cropW)/2;
         }
         else {
            $cropW = $imgWidth;
            $cropH = $imgWidth/$innerRatio;
            $decalW = 0;
            $decalH = ($imgHeight-$cropH)/2;
         }
      }
      $this->imageDestWidth = $_w;
      $this->imageDestHeight = $_h;
      $this->decalW = $decalW;
      $this->decalH = $decalH;
      $this->cropW = $cropW;
      $this->cropH = $cropH;
   }

   /**
    * Est ce qu'il faut redimentionner une image ?
    * @access private
    * @return boolean
    */
   function canResize () {

      // La vignette existe ?
      if (!file_exists ($this->imageDest)) {
         return true;
      }

      // L'original a été modifié ?
      if (filemtime($this->imageDest) > filemtime ($this->image)) {
         return true;
      }

      return false;
   }
   

   /**
    *
    */
   function destBiggerThanFrom() {
      return ($this->imageDestWidth == $this->imageWidth &&
              $this->imageDestHeight == $this->imageHeight);
   }

   /**
    * Convert a color defined in hexvalues to corresponding dezimal values
    *
    * @access public
    * @param string $hex color value in hexformat (e.g. 'FF0000')
    * @return array associative array with color values in dezimal format (fields: 0->'red', 1->'green', 2->'blue')
    */
   function hexToDecColor ($hex) {
      $length = strlen($hex);
      $color[] = hexdec(substr($hex, $length - 6, 2));
      $color[] = hexdec(substr($hex, $length - 4, 2));
      $color[] = hexdec(substr($hex, $length - 2, 2));
      return $color;
   }


   /**
    * @abstract
    * @access private
    */
   function setSrcInfos () {
   }
   /**
    * @abstract
    */
   function getImageDimensions($path) {
   }
   /**
    * @abstract
    */
   function createThumb ($img_dest) {
   }
}


?>