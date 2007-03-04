<?php

/**
 * @package process
 */
class processFactory {
   function imageToolkit($imagePath, $imageDriver=IMAGE_GENERATION_DRIVER) {
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
}

?>