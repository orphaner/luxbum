<?php


class imagetoolkit
{

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


   /**-----------------------------------------------------------------------**/
   /** Fonctions de créations d'images */
   /**-----------------------------------------------------------------------**/

   /**
    *
    * @access private
    */
   function imageCreateFromType ($file, $imageType) {

      switch ($imageType) {
         case 1 :
            $imhandler = imageCreateFromGIF ($file);
            break;
         case 2 :
            $imhandler = imageCreateFromJPEG ($file);
            break;
         case 3 :
            $imhandler = imageCreateFromPNG ($file);
            break;
      }

      return $imhandler;
   }

   /**
    *
    * @access private
    */
   function imageWriteFromType ($image, $filename, $quality, $imageType) {

      switch ($imageType){
         case 1 :
            imageGIF ($image, $filename);
            break;

         case 2 :
            imageJPEG ($image, $filename, $quality);
            break;

         case 3 :
            imagePNG ($image, $filename);
            break;
      }
   }

   /**
    *
    */
   function createThumb ($img_src, $img_dest, $dst_w="85", $dst_h="85") {

      // Lit les dimensions de l'image
      $size = GetImageSize ($img_src);
      $src_w = $size[0];
      $src_h = $size[1];
      $imageType = $size[2];

      // Teste les dimensions tenant dans la zone
      $test_h = round (($dst_w / $src_w) * $src_h);
      $test_w = round (($dst_h / $src_h) * $src_w);

      // Si Height final non précisé (0)
      if (!$dst_h) {
         $dst_h = $test_h;
      }

      // Sinon si Width final non précisé (0)
      elseif (!$dst_w) {
         $dst_w = $test_w;
      }

      // Sinon teste quel redimensionnement tient dans la zone
      elseif ($test_h>$dst_h) {
         $dst_w = $test_w;
      }
      else {
         $dst_h = $test_h;
      }

      // La vignette existe ?
      $test = (file_exists ($img_dest));

      // L'original a été modifié ?
      if ($test) {
         $test = (filemtime ($img_dest)>filemtime ($img_src));
      }

      // Les dimensions de la vignette sont correctes ?
      if ($test) {
         $size2 = GetImageSize ($img_dest);
         $test = ($size2[0] == $dst_w);
         $test = ($size2[1] == $dst_h);
      }

      // Créer la vignette ?
      if (!$test) {

         // Copie dedans l'image initiale redimensionnée
         $src_im = imageToolkit::ImageCreateFromType ($img_src, $imageType);


         /* GD 2 */
         if (imagetoolkit::gdVersion() == 2) {

            // Crée une image vierge aux bonnes dimensions
            $dst_im = ImageCreateTrueColor ($dst_w, $dst_h);

//             $text = 'Nico CopyRight';
//             $font = '_fonts/arial.ttf';
//             imagettftext ($src_im, 20, 0, 11, 21, $color_bg_alpha_text, $font, $text);

            ImageCopyResampled ($dst_im, $src_im, 0, 0, 0, 0, $dst_w, $dst_h, $src_w, $src_h);

            if (IMAGE_BORDER_PIXEL > 0) {
               $pixels = IMAGE_BORDER_PIXEL;
               $nuanceMax = IMAGE_BORDER_MAX_NUANCE;
               $thumb_bg_color = imagetoolkit::hexToDecColor (IMAGE_BORDER_HEX_COLOR);
               for ($i = 0 ; $i < $pixels ; $i++) {
                  $nuance = (int)(($nuanceMax/$pixels)*$i);
                  $color = imagecolorallocatealpha ($dst_im, $thumb_bg_color[0], $thumb_bg_color[1], $thumb_bg_color[2], $nuance);
                  imagerectangle ($dst_im, $i, $i, $dst_w - ($i+1), $dst_h - ($i+1), $color);
               }
            }
         }

         /* GD 1 */
         else {
            // Crée une image vierge aux bonnes dimensions
            $dst_im = ImageCreate ($dst_w, $dst_h);
            imagecopyresized ($dst_im, $src_im, 0, 0, 0, 0, $dst_w, $dst_h, $src_w, $src_h);
         }

         // Sauve la nouvelle image
//          ob_start ();
         imagetoolkit::ImageWriteFromType ($dst_im, $img_dest, 95, $imageType);
//          $content = ob_get_contents ();
//          ob_end_clean ();
         @chmod ($img_dest, 0777);
//          files::writeFile ($img_dest, $content);

         // Détruis les tampons
         ImageDestroy ($dst_im);
         ImageDestroy ($src_im);
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
}


?>