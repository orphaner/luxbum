<?php

/**
 * @package process
 */
class processFactory {
   static function imageToolkit($imagePath, $imageDriver='gd') {
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