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
    * @param Int $dst_w Largeur maximale
    * @param int $dst_h Hauteur maximale
    */
   function setDestSize ($dst_w, $dst_h) {
      $this->imageDestWidth = $dst_w;
      $this->imageDestHeight = $dst_h;

      // Teste les dimensions tenant dans la zone
      $test_h = round (($this->imageDestWidth / $this->imageWidth) * $this->imageHeight);
      $test_w = round (($this->imageDestHeight / $this->imageHeight) * $this->imageWidth);
      
      if ($this->imageWidth < $this->imageDestWidth && $this->imageHeight < $this->imageDestHeight) {
         $this->imageDestWidth = $this->imageWidth;
         $this->imageDestHeight = $this->imageHeight;
         return;
      }

      // Si Height final non précisé (0)
      if (!$this->imageDestHeight) {
         $this->imageDestHeight = $test_h;
      }

      // Sinon si Width final non précisé (0)
      elseif (!$this->imageDestWidth) {
         $this->imageDestWidth = $test_w;
      }

      // Sinon teste quel redimensionnement tient dans la zone
      elseif ($test_h>$this->imageDestHeight) {
         $this->imageDestWidth = $test_w;
      }
      else {
         $this->imageDestHeight = $test_h;
      }
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
   static function hexToDecColor ($hex) {
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
   static function getImageDimensions($path) {
   }
   /**
    * @abstract
    */
   function createThumb ($img_dest) {
   }
}


?>