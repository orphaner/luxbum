<?php


/**
 * @package process
 */
class luxBumImage extends CommonFile
{
   var $thumbDir;
   var $previewDir;

   var $thumbToolkit = NULL;
   var $previewToolkit = NULL;
    
   var $imageMeta;


   /**
    * Default constructor
    * @param String $dir le nom de la galerie
    * @param String $img le nom de l'image
    */
   function luxBumImage($dir, $file) {
      $this->dir = files::addTailSlash($dir);
      $list = split('/', $dir);
      $this->name = $list[count($list) - 1];
      $this->type = TYPE_IMAGE_FILE;

      $this->file = $file;
      $this->thumbDir = luxbum::getThumbPath($this->dir);
      $this->previewDir = luxbum::getPreviewPath($this->dir);
      $this->setAllDescription ('', '');
   }

   /**
    * Retourne le dossier de l'image
    * @return String Dossier de l'image
    */
   function getImageDir () {
      return $this->dir;
   }

   /**
    * Retourne le chemin complet de l'image
    * @return String Chemin complet de l'image
    */
   function getImagePath () {
      return luxbum::getImage($this->dir, $this->file);
   }

   /**
    * Retourne le type mime de l'image
    * @return Type mime de l'image
    */
   function getTypeMime () {
      if ($this->thumbToolkit == null) {
         return '';
      }
      return $this->thumbToolkit->getTypeMime();
   }
    
   function getVignettePageUrl() {
      return link::vignette($this->dir, $this->file);
   }

   /**-----------------------------------------------------------------------**/
   /** Functions to create thumbs & previews */
   /**-----------------------------------------------------------------------**/
    
   /**
    * Return the thumb url. The url is the page who generates the thumb.
    * @return Thumb url
    */
   function getThumbUrl () {
      return link::thumb($this->dir, $this->file);
   }
    
   /**
    * Return the preview url. The url is the page who generates the preview.
    * @return Preview url
    */
   function getPreviewUrl () {
      return link::preview($this->dir, $this->file);
   }

   /**
    * Generate the thumb and returns the file path to it.
    * @return String File path to the generated thumb.
    */
   function generateThumb($dst_w = 85, $dst_h = 85, $mode) {
      $this->thumbToolkit = ImageToolkit::factory($this->getImagePath ());
      if ($mode == true) {
         $mode = 'crop';
      }
      else {
         $mode = 'ratio';
      }
      $this->thumbToolkit->setDestSize ($dst_w, $dst_h, $mode);

      $final = luxbum::getThumbImage ($this->dir, $this->file, $dst_w, $dst_h);
      if (!is_file ($final)) {
         files::createDir ($this->thumbDir);
         $this->thumbToolkit->createThumb ($final);
      }
      return $final;
   }
    
   /**
    * Check if it is necessary to generate a preview of the image.
    * The preview is generated only if :
    * - the image size in bytes is greater than the configurated threshold
    * - the image dimensions are smaller than the original image 
    * @access private
    * @return boolean true/false Generate the preview / or not
    */
   function _needPreview ($imageToolkit) {
      if ($this->getSize() < MIN_SIZE_FOR_PREVIEW * 1024) {
         return false;
      }

      // Si image de départ plus petite, on ne redimentione pas la photo
      if ($imageToolkit->destBiggerThanFrom()) {
         return false;
      }

      return true;
   }

   /**
    * Generate the preview and returns the file path to it.
    * @return String File path to the generated preview.
    */
   function generatePreview ($dst_w = 650, $dst_h = 485, $mode) {
      $this->previewToolkit = ImageToolkit::factory($this->getImagePath ());
      if ($mode == true) {
         $mode = 'crop';
      }
      else {
         $mode = 'ratio';
      }
      $this->previewToolkit->setDestSize ($dst_w, $dst_h, $mode);

      // If the generation is not needed, returns the file path to the original image
      if ($this->_needPreview($this->previewToolkit) == false) {
         return $this->getImagePath ();
      }

      $final = luxbum::getPreviewImage($this->dir, $this->file, $dst_w, $dst_h);
      
      // Generate the preview is the preview is not yet generated
      if (!is_file ($final)) {
         files::createDir ($this->previewDir);
         $this->previewToolkit->createThumb ($final);
      }
      
      return $final;
   }

   /**
    * Retourne la chaine de taille de la vignette pour la balise &gt;img&lt;
    * @return String Taille de la vignette pour la balise &gt;img&lt;
    */
   function getThumbResizeSize () {
      if ($this->thumbToolkit == null) {
         return '';
         return imagetoolkit::getImageDimensions($this->getAsThumb ());
      }
      return sprintf ('width="%s" height="%s"',
				         $this->thumbToolkit->getImageDestWidth(),
				         $this->thumbToolkit->getImageDestHeight());
   }

   /**
    * Retourne la chaine de taille de l'aper�u pour la balise &gt;img&lt;
    * @return String Taille de l'aper�u pour la balise &gt;img&lt;
    */
   function getPreviewResizeSize () {
      if ($this->previewToolkit == null) {
         return '';
         return imagetoolkit::getImageDimensions($this->getAsPreview ());
      }
      return sprintf ('width="%s" height="%s"',
                        $this->previewToolkit->getImageDestWidth(),
                        $this->previewToolkit->getImageDestHeight());
   }


   /**-----------------------------------------------------------------------**/
   /** Fonctions pr le cache des images */
   /**-----------------------------------------------------------------------**/

   /**
    * Delete the image, it's cache and it's comments
    * @return Boolean
    */
   function delete () {
      $this->clearCache ();
      commentaire::deletePhoto ($this->dir, $this->file);
      return files::deleteFile (luxbum::getFsPath ($this->dir) . $this->file);
   }

   /**
    * Delete the image cache
    */
   function clearCache () {
      $this->clearThumbCache ();
      $this->clearPreviewCache ();
   }

   /**
    * Delete the thumb cache. The function fetch all images in the thumb dir to 
    * find images which matches because it is possible to have many thumbs with many sizes.
    * @access private
    */
   function DateTimeclearThumbCache () {

      if ($fd = opendir ($this->thumbDir)) {
         while ($current_file = readdir ($fd)) {
            if ($current_file[0] != '.'
                && !is_dir ($this->thumbDir.$current_file) 
                && eregi ('^.*(' . $this->file . ')$', $current_file)){
               files::deleteFile ($this->thumbDir.$current_file);
            }
         }
         closedir ($fd);
      }
   }

   /**
    * Delete the preview cache
    * @access private
    */
   function clearPreviewCache () {
      files::deleteFile($this->previewDir . $this->file);
   }


   /**-----------------------------------------------------------------------**/
   /** Fonctions d'informations META */
   /**-----------------------------------------------------------------------**/

   /**
    * Initialise les informations META de la photo
    */
   function metaInit () {
      $this->imageMeta = new ImageMeta($this->getImagePath());
      $this->imageMeta->getMeta();
   }

   /**
    * Retourne ok si les informations META existent
    * @return boolean Infomations META existent
    */
   function hasMeta() {
      return $this->imageMeta->hasMeta();
   }

   function getMeta() {
      return $this->imageMeta;
   }
   
   function getLinkFullImage() {
      return link::full($this->dir, $this->file);
   }
}

?>