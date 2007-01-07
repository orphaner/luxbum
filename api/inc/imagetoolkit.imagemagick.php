<?php

  /**
   * @package inc
   */
class ImageToolkitImageMagick extends ImageToolkit {
   function createThumb($img_dest) {
      $this->imageDest = $img_dest;
      
      // Créer la vignette ?
      if ($this->canResize ()) {

         $cmd = 'convert -size %dx%d %s -geometry %dx%d %s';
         $cmd = sprintf($cmd, $this->imageDestWidth, $this->imageDestHeight, 
                        escapeshellarg($this->image), 
                        $this->imageDestWidth, $this->imageDestHeight, 
                        escapeshellarg($img_dest));
         exec($cmd);
      }
   }


   /**
    * Remplit les champs principaux de la classe.
    * 
    * @access private
    */
   function setSrcInfos () {
      if ($this->image != "") {

         $cmd ='identify -format %w:%h:%m ' . escapeshellarg($this->image);
         exec($cmd, $res, $exit);

         if ($exit == 0) {
            $size  = explode(':', $res[0]);
            $this->imageWidth = $size[0];
            $this->imageHeight = $size[1];
            $this->imageType = $size[2];
            $this->typeMime = files::getMimeType($this->image);
         }
      }
   }

   /**
    *
    * @access static
    */
   static function getImageDimensions($path) {
      if (is_file ($path)) {
         $cmd ='identify -format %w:%h:%m ' . escapeshellarg($this->image);
         exec($cmd, $res, $exit);

         if ($exit == 0) {
            $size  = explode(':', $res[0]);
            return sprintf ('width="%s" height="%s"', $size[0], $size[1]);
         }
      }
      return '';
   }
  }

?>