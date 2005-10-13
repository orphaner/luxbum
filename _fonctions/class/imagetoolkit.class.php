<?php


class imagetoolkit
{

   /**-----------------------------------------------------------------------**/
   /** Fonctions de créations d'images */
   /**-----------------------------------------------------------------------**/

   var $image;
   var $imageWidth;
   var $imageHeight;
   var $imageType;

   var $imagDest;
   var $imageDestWidth;
   var $imageDestHeight;

   /**
    *
    */
   function imagetoolkit ($image) {
      $this->image = $image;
      $this->setSrcInfos ();
   }

   /**
    *
    */
   function getImageDestWidth () {
      return $this->imageDestWidth;
   }

   /**
    *
    */
   function getImageDestHeight () {
      return $this->imageDestHeight;
   }

   /**
    *
    * @access private
    */
   function setSrcInfos () {

      if ($this->image != "") {         
         // Lit les dimensions de l'image
         $size = GetImageSize ($this->image);
         $this->imageWidth = $size[0];
         $this->imageHeight = $size[1];
         $this->imageType = $size[2];
      }
   }

   /**
    *
    * @access private
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
            $imhandler = imageCreateFromPNG ($this->image);
            break;
      }

      return $imhandler;
   }

   /**
    *
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
            break;
      }
   }

   /**
    *
    */
   function setDestSize ($dst_w, $dst_h) {
      $this->imageDestWidth = $dst_w;
      $this->imageDestHeight = $dst_h;

      // Teste les dimensions tenant dans la zone
      $test_h = round (($this->imageDestWidth / $this->imageWidth) * $this->imageHeight);
      $test_w = round (($this->imageDestHeight / $this->imageHeight) * $this->imageWidth);

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
    *
    * @access private
    */
   function canResize () {

      // La vignette existe ?
      $test = (file_exists ($this->imageDest));

      // L'original a été modifié ?
      if ($test) {
         $test = (filemtime ($this->imageDest)>filemtime ($this->image));
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
   function createThumb ($img_dest) {
      $this->imageDest = $img_dest;

//       echo $this->imageDestWidth.' => '.$this->imageDestHeight.'<br />';

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