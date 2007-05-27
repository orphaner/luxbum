<?php

@ini_set('memory_limit', '32M'); 

/**
 * @package inc
 * @abstract
 */
abstract class ImageToolkit
{

   /**-----------------------------------------------------------------------**/
   /** Image generation functions */
   /**-----------------------------------------------------------------------**/

   protected $image;
   protected $imageWidth;
   protected $imageHeight;
   protected $imageType;
   protected $typeMime = '';

   protected $imagDest;
   protected $imageDestWidth;
   protected $imageDestHeight;
   protected $mode;
   
   /**
    * Factory to create new instances of the right driver
    * @param string $imagePath image path of the original image
    * @param string $imageDriver driver to use
    * @return ImageToolkit
    */
   static function factory($imagePath, $imageDriver='') {
      if ($imageDriver === '') {
         $imageDriver = Pluf::f('image_generation_driver');
      }
      switch($imageDriver) {
         case 'gd':
            return new ImageToolkitGD($imagePath);
            break;

         case 'imagemagick':
            return new ImageToolkitImageMagick($imagePath);
            break;

         case 'imagick':
            break;
      }
   }
   
   /**
    * Constructeur par d�faut
    * @param String $image Chemin complet vers l'image
    */
   public function __construct($image) {
      $this->image = $image;
      $this->setSrcInfos();
   }

   /**
    * Retourne la largeur finale de l'image redimensionn�e.
    * @return int Largeur finale de l'image redimensionn�e.
    */
   public function getImageDestWidth () {
      return $this->imageDestWidth;
   }

   /**
    * Retourne la hauteur finale de l'image redimensionn�e.
    * @return int Hauteur finale de l'image redimensionn�e.
    */
   public function getImageDestHeight () {
      return $this->imageDestHeight;
   }
   
   /**
    * Returns the mime type of the image
    * @return String Type mime type of the image
    */
   public function getTypeMime () {
      return $this->typeMime;
   }

   /**
    * Set a maximum resize size
    * @param Int $dst_w Largeur maximale (px ou %)
    * @param int $dst_h Hauteur maximale (px ou %)
    * @param string $mode Crop mode (force, crop, ratio)
    * @param boolean $expand Allow resize of image
   */
   public function setDestSize ($dst_w, $dst_h, $mode='ratio', $expand=true) {
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
    * Does the image need to be resized ?
    * @access private
    * @return boolean
    */
   protected function canResize () {

      // the thumb exists ?
      if (!file_exists ($this->imageDest)) {
         return true;
      }

      // The original has changed ?
      if (filemtime($this->imageDest) > filemtime ($this->image)) {
         return true;
      }

      return false;
   }
   

   /**
    *
    * @return boolean
    */
   function destBiggerThanFrom() {
      return ($this->imageDestWidth >= $this->imageWidth &&
              $this->imageDestHeight >= $this->imageHeight);
   }

   /**
    * Convert a color defined in hexvalues to corresponding dezimal values
    *
    * @access public
    * @param string $hex color value in hexformat (e.g. 'FF0000')
    * @return array associative array with color values in dezimal format (fields: 0->'red', 1->'green', 2->'blue')
    */
   public static function hexToDecColor ($hex) {
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
   abstract protected function setSrcInfos() ;
   
   /**
    * @abstract
    */
   abstract public function getImageDimensions($path);
   
   /**
    * @abstract
    */
   abstract public function createThumb ($img_dest) ;
   
   /**
    * @return boolean
    */
   abstract public static function isAvailable();
   
}


?>