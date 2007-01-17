<?php


  /**
   * @package inc
   */

class ImageToolkitGD extends ImageToolkit
{

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
    * Remplit les champs principaux de la classe.
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
         $this->typeMime = $size['mime'];
      }
   }

   /**
    *
    * @access static
    */
   static function getImageDimensions($path) {
      if (is_file ($path)) {
         $size = GetImageSize ($path);
         return sprintf ('width="%s" height="%s"', $size[0], $size[1]);
      }
      return '';
   }

   /**
    * Crée l'image redimentionnée. Il faut préalablement avoir apellé la méthode
    * setDestSize(...)
    * @param String $img_dest Chemin où il faut écrire l'image redimensionnée.
    */
   function createThumb ($img_dest) {
      $this->imageDest = $img_dest;

      // Créer la vignette ?
      if ($this->canResize ()) {

         // Copie dedans l'image initiale redimensionnée
         $srcHandler = $this->ImageCreateFromType ($this->image, $this->imageType);


         /* GD 2 */
         if ($this->gdVersion() == 2) {

            // Crée une image vierge aux bonnes dimensions
            $destHandler = ImageCreateTrueColor ($this->imageDestWidth, $this->imageDestHeight);
            ImageCopyResampled ($destHandler, $srcHandler, 0, 0, $this->decalW, $this->decalH, $this->imageDestWidth, $this->imageDestHeight, $this->cropW, $this->cropH);
         }

         /* GD 1 */
         else {

            // Crée une image vierge aux bonnes dimensions
            $destHandler = ImageCreate ($this->imageDestWidth, $this->imageDestHeight);
            imagecopyresized ($destHandler, $srcHandler, 0, 0, $this->decalW, $this->decalH, $this->imageDestWidth, $this->imageDestHeight, $this->cropW, $this->cropH);
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
    * Retourne la version exacte de GD
    * @return Version exacte de GD
    */
   static function gdVersionExact ()  {
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
   static function gdVersion ($user_ver = 0) {

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