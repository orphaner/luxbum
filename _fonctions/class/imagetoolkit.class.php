<?php

ini_set ('memory_limit', '16M'); 

class imagetoolkit
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
   function imagetoolkit ($image) {
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
    * Remplit les champs principaux de la classe.
    * Utile ?
    * @access private
    */
   function setSrcInfos () {
      if ($this->image != "") {         
         // Lit les dimensions de l'image
         $size = GetImageSize ($this->image);
         $this->imageWidth = $size[0];
         $this->imageHeight = $size[1];
         $this->imageType = $size[2];
         $this->typeMime = $size['mime'];
      }
   }

   /**
    * Crée un handler de l'image suivant son type
    * @access private
    * @return Handler
    */
   function imageCreateFromType () {

      switch ($this->imageType) {
         case 1 :
            $imhandler = imageCreateFromGIF ($this->image);
            break;
         case 2 :
            $imhandler = imageCreateFromJPEG ($this->image);
            break;
         case 3 :
            // TODO: Trouver une meilleure façon de gérer ça :/
            $imhandler = imageCreateFromPNG ($this->image);
            imageinterlace ($imhandler,0);
            imagePNG ($imhandler, $this->image.'bis');
            ImageDestroy($imhandler);
            $imhandler = imageCreateFromPNG ($this->image.'bis');
            /*imageAlphaBlending($imhandler, true);
            imageSaveAlpha($imhandler, true);*/
            break;
      }

      return $imhandler;
   }

   /**
    * Ecrit l'image redimensionnée.
    * @access private
    */
   function imageWriteFromType ($image, $quality) {

      switch ($this->imageType){
         case 1 :
            imageGIF ($image, $this->imageDest);
            break;

         case 2 :
            imageJPEG ($image, $this->imageDest, $quality);
            break;

         case 3 :
            imagePNG ($image, $this->imageDest);
            files::deleteFile ($this->image.'bis');
            break;
      }
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
      $test = (file_exists ($this->imageDest));

      // L'original a été modifié ?
      if ($test) {
         $test = (filemtime($this->imageDest) > filemtime ($this->image));
      }
      
      // Les dimensions de la vignette sont correctes ?
      if ($test) {
         $size2 = GetImageSize ($this->imageDest);
         $test = ($size2[0] == $this->imageDestWidth);
         $test = ($size2[1] == $this->imageDestHeight);
      }

      return $test;
   }
   

   /**
    *
    */
   function destBiggerThanFrom() {
      return ($this->imageDestWidth == $this->imageWidth &&
         $this->imageDestHeight == $this->imageHeight);
   }

   /**
    * Crée l'image redimentionnée. Il faut préalablement avoir apellé la méthode
    * setDestSize(...)
    * @param String $img_dest Chemin où il faut écrire l'image redimensionnée.
    */
   function createThumb ($img_dest) {
      $this->imageDest = $img_dest;


      // Créer la vignette ?
      if (!$this->canResize ()) {

         // Copie dedans l'image initiale redimensionnée
         $srcHandler = $this->ImageCreateFromType ($this->image, $this->imageType);


         /* GD 2 */
         if ($this->gdVersion() == 2) {

            // Crée une image vierge aux bonnes dimensions
            $destHandler = ImageCreateTrueColor ($this->imageDestWidth, $this->imageDestHeight);
            ImageCopyResampled ($destHandler, $srcHandler, 0, 0, 0, 0, $this->imageDestWidth, $this->imageDestHeight, $this->imageWidth, $this->imageHeight);

            if (IMAGE_BORDER_PIXEL > 0) {
               $pixels = IMAGE_BORDER_PIXEL;
               $nuanceMax = IMAGE_BORDER_MAX_NUANCE;
               $thumbBgColor = $this->hexToDecColor (IMAGE_BORDER_HEX_COLOR);
               for ($i = 0 ; $i < $pixels ; $i++) {
                  $nuance = (int)(($nuanceMax/$pixels)*$i);
                  $color = imagecolorallocatealpha ($destHandler, $thumbBgColor[0], $thumbBgColor[1], $thumbBgColor[2], $nuance);
                  imagerectangle ($destHandler, $i, $i, $this->imageDestWidth - ($i+1), $this->imageDestHeight - ($i+1), $color);
               }
            }
         }

         /* GD 1 */
         else {
            // Crée une image vierge aux bonnes dimensions
            $destHandler = ImageCreate ($this->imageDestWidth, $this->imageDestHeight);
            imagecopyresized ($destHandler, $srcHandler, 0, 0, 0, 0, $this->imageDestWidth, $this->imageDestHeight, $this->imageWidth, $this->imageHeight);
         }

         // Sauve la nouvelle image
         $this->ImageWriteFromType ($destHandler, 95);
         @chmod ($this->imageDest, 0777);

         // Détruis les tampons
         ImageDestroy ($destHandler);
         ImageDestroy ($srcHandler);
      }
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
    * Retourne la version exacte de GD
    * @return Version exacte de GD
    */
   function gdVersionExact ()  {
      static $gd_version_number = null;
      if ($gd_version_number === null) {
         // Use output buffering to get results from phpinfo()
         // without disturbing the page we're in.  Output
         // buffering is "stackable" so we don't even have to
         // worry about previous or encompassing buffering.
         ob_start();
         phpinfo(8);
         $module_info = ob_get_contents();
         ob_end_clean();
         $matches = array();
         if (preg_match("/\bgd\s+version\b[^\d\n\r]+?([\d\.]+)/i",
                        $module_info,$matches)) {
            $gd_version_number = $matches[1];
         } else {
            $gd_version_number = 0;
         }
      }
      return $gd_version_number;
   }

   /**
    * Get which version of GD is installed, if any.
    * Returns the version (1 or 2) of the GD extension.
    * @return 1 ou 2 pour GD1 ou GD2
    */
   function gdVersion ($user_ver = 0) {

      if (! extension_loaded('gd')) {
         return;
      }

      static $gd_ver = 0;

      // Just accept the specified setting if it's 1.
      if ($user_ver == 1) {
         $gd_ver = 1;
         return 1;
      }

      // Use the static variable if function was called previously.
      if ($user_ver !=2 && $gd_ver > 0 ) {
         return $gd_ver;
      }

      // Use the gd_info() function if possible.
      if (function_exists('gd_info')) {
         $ver_info = gd_info();
         $match = array ();
         preg_match('/\d/', $ver_info['GD Version'], $match);
         $gd_ver = $match[0];
         return $match[0];
      }

      // If phpinfo() is disabled use a specified / fail-safe choice...
      if (preg_match('/phpinfo/', ini_get('disable_functions'))) {
         if ($user_ver == 2) {
            $gd_ver = 2;
            return 2;
         }
         else {
            $gd_ver = 1;
            return 1;
         }
      }

      // ...otherwise use phpinfo().
      ob_start();
      phpinfo(8);
      $info = ob_get_contents();
      ob_end_clean();
      $info = stristr($info, 'gd version');
      preg_match('/\d/', $info, $match);
      $gd_ver = $match[0];
      return $match[0];
   } // End gdVersion()

}


?>