<?php

class ImageToolkitImagick extends imagetoolkit {
   function createThumb($img_dest) {
      exec('convert -size '.$this->imageDestWidth.'x'.$this->imageDestHeight.' '.$this->image .' -geometry '.$this->imageDestWidth.'x'.$this->imageDestHeight.' '.$img_dest);
   }
  }

?>