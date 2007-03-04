<?php

  /**
   * @package inc
   */
class ImageToolkitImageMagick extends ImageToolkit {
   var $forceResizeCmd = false;

   /**
    *
    */
   function createThumb($img_dest) {
      $this->imageDest = $img_dest;
      
      // Créer la vignette ?
      if ($this->canResize ()) {

         // Crop method
         if ($this->mode == 'crop') {
            $cmd = 'convert -gravity center -crop %dx%d+%d+%d +repage %s %s';
            $cmd = sprintf($cmd, 
                           $this->cropW, $this->cropH,
                           $this->decalW, $this->decalH,
                           escapeshellarg($this->image), 
                           escapeshellarg($img_dest));
            exec($cmd);

            $cmd = 'convert -resize %dx%d -geometry %dx%d %s %s';
            $cmd = sprintf($cmd, 
                           $this->imageDestWidth, $this->imageDestHeight,
                           $this->imageDestWidth, $this->imageDestHeight,
                           escapeshellarg($img_dest),
                           escapeshellarg($img_dest));
            exec($cmd);
         }
         // Other methods
         else {
            $cmd = 'convert -resize %dx%d -geometry %dx%d %s %s';
            $cmd = sprintf($cmd, 
                           $this->imageDestWidth, $this->imageDestHeight,
                           $this->imageDestWidth, $this->imageDestHeight,
                           escapeshellarg($this->image), 
                           escapeshellarg($img_dest));
            exec($cmd);
         }
      }
   }


   /**
    * Remplit les champs principaux de la classe.
    * 
    * @access private
    */
   function setSrcInfos () {
      if ($this->image != "") {

         if (function_exists('GetImageSize') && !$this->forceResizeCmd) {
            $exit = 0;
            $size = GetImageSize ($this->image);
         }
         else {
            $cmd ='identify -format %w:%h:%m ' . escapeshellarg($this->image);
            exec($cmd, $res, $exit);
            $size  = explode(':', $res[0]);
         }

         if ($exit == 0) {
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
   function getImageDimensions($path) {
      if (is_file ($path)) {
         $cmd ='identify -format %w:%h ' . escapeshellarg($this->image);
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