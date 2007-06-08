<?php

class CommonGallery extends inc_SortableRecordset {
   /**
    * Enter description here...
    *
    * @access private
    * @var unknown_type
    */
   protected $name;
   protected $preview;
   
   protected $flvCount = 0;
   protected $imageCount = 0;
   protected $totalCount = 0;
   
   protected $flvSize = 0;
   protected $imageSize = 0;
   protected $totalSize = 0;

   
   /**
    * Set the gallery name
    * @param string $name Gallery name
    */
   function setName ($name) {
      $this->name = $name;
   }

   /**
    * Return the gallery name
    * @return String GAllery name
    */
   function getName () {
      return $this->name;
   }

   /**
    * Retourne le "beau" nom de la galerie
    * @return String Beau nom de la galerie
    */
   function getNiceName () {
      return luxbum::niceName ($this->name);
   }

   /**
    * Retourne l'image par d�faut de la galerie
    * @return String Chemin vers l'image par d�faut de la galerie
    */
   function getPreview () {
      return $this->preview;
   }

   /**
    * Retourne la taille en octets des photos de la galerie
    * @return int Taille en octets des photos de la galerie
    */
   function getImageSize () {
      return $this->imageSize;
   }

   /**
    * Retourne la taille en octets des photos de la galerie
    * @return int Taille en octets des photos de la galerie
    */
   function getFlvSize () {
      return $this->flvSize;
   }

   /**
    * Retourne la taille en octets des photos de la galerie
    * @return int Taille en octets des photos de la galerie
    */
   function getTotalSize () {
      return $this->totalSize;
   }

   /**
    * Retourne la taille affichable des photos de la galerie
    * @return int Taille affichable des photos de la galerie
    */
   function getImageNiceSize () {
      return luxbum::niceSize ($this->imageSize);
   }

   /**
    * Retourne la taille affichable des photos de la galerie
    * @return int Taille affichable des photos de la galerie
    */
   function getFlvNiceSize () {
      return luxbum::niceSize ($this->flvSize);
   }

   /**
    * Retourne la taille affichable des photos de la galerie
    * @return int Taille affichable des photos de la galerie
    */
   function getTotalNiceSize () {
      return luxbum::niceSize ($this->totalSize);
   }

   /**
    * Retourne le nombre de photos de la galerie
    * @return int Nombre de photos de la galerie
    */
   function getImageCount () {
      return $this->imageCount;
   }

   /**
    * Retourne le nombre de vidéos de la galerie
    * @return int Nombre de vidéos de la galerie
    */
   function getFlvCount () {
      return $this->flvCount;
   }

   /**
    * Retourne le nombre de vidéos de la galerie
    * @return int Nombre de vidéos de la galerie
    */
   function getTotalCount () {
      return $this->totalCount;
   }

   /**
    * Affecte la position de la galerie dans l'index
    * @param String $sortPosition Position de la galerie dans l'index
    */
   function setSortPosition ($sortPosition) {
      $this->sortPosition = $sortPosition;
   }
   
   /**
    * Retourne la position de la galerie dans l'index
    * @return String Position de la galerie dans l'index
    */
   function getSortPosition () {
      return $this->sortPosition;
   }
   
   /**
    * Retourne la position d'une image $imgName dans la galerie
    * @param String $imgName l'image dont on cherche la position
    * @return la position de cette image dans la galerie
    */
   function getImageIndex ($imgName) {
      $index = 0;
      $trouve = false;

      $this->reset();
      while (!$trouve && list (,$img) = each ($this->arrayList)) {
         $name = $img->getFile();
         if ($name == $imgName) {
            $trouve = true;
         }
         else {
            $index++;
         }
      }
      $this->reset();
      if (!$trouve) {
         return -1;
      }
      return $index;
   }

   
   /**-----------------------------------------------------------------------**/
   /** UI Functions */
   /**-----------------------------------------------------------------------**/
   /**
    * @return string the link url to consult the gallery
    */
   public function getLinkConsult() {
      return link::gallery($this->dir, $this->f()->getFile());
   }
   
   /**
    * @return string the link url to consult the gallery
    */
   public function getLinkGallery() {
      return link::gallery($this->dir, $this->f()->getFile());
   }
   
   /**
    * @return string the link url to consult the gallery
    */
   public function getLinkDisplay() {
      return link::display($this->dir, $this->f()->getFile());
   }
   
   /**
    * @return string the link url to consult the gallery as a slideshow
    */
   public function getLinkSlideshow() {
      return link::slideshow($this->dir, $this->f()->getFile());
   }

   /**
    * @return string the link url to consult the sub galleries of the gallery
    */
   public function getLinkSubGallery() {
      return link::subGallery($this->dir);
   }
   
   /**
    * @return string the link url to the login form of a private gallery
    */
   public function getLinkPrivate() {
      return link::privateGallery($this->dir);
   }
   
   /**
    * @return string the link url to consult the previous file in a gallery page
    */
   public function getLinkPreviousGallery() {
      if ($this->isFirst()) {
         return '';
      }
      $this->move($this->getDefaultIndex());
      $this->movePrev();
      $file = $this->f();
      return link::gallery($file->getDir(), $file->getFile());
   }
   
   /**
    * @return string the link url to consult the next file in a gallery page
    */
   public function getLinkNextGallery() {
      if ($this->isLast()) {
         return '';
      }
      $this->move($this->getDefaultIndex());
      $this->moveNext();
      $file = $this->f();
      return link::gallery($file->getDir(), $file->getFile());
   }   
   
   /**
    * @return string the link url to consult the previous file in a gallery page
    */
   public function getLinkPreviousDisplay() {
      if ($this->isFirst()) {
         return '';
      }
      $this->move($this->getDefaultIndex());
      $this->movePrev();
      $file = $this->f();
      return link::display($file->getDir(), $file->getFile());
   }
   
   /**
    * @return string the link url to consult the next file in a gallery page
    */
   public function getLinkNextDisplay() {
      if ($this->isLast()) {
         return '';
      }
      $this->move($this->getDefaultIndex());
      $this->moveNext();
      $file = $this->f();
      return link::display($file->getDir(), $file->getFile());
   }
   
   /**
    * Retourne le lien de la vignette de l'image vers le script qui génére
    * l'image
    * @return Lien de la vignette de l'image vers le script de génération
    */
   protected function getIndexLink () {

      $this->_completeDefaultImage ();      

      // La galerie contient des photos
      return link::index($this->dir, $this->preview);
   }
   
   public function getDefaultImage($return = false) {

      // private gallery image
      if ($this->isPrivate() && !$this->isUnlocked()) {
         return Pluf::f('color_theme_path').'/images/folder_locked.png';
      }

      // Sub gallery image
      if ($this->hasSubGallery() && $this->getTotalCount() == 0) {
         return Pluf::f('color_theme_path').'/images/folder_image.png';
      }

      // Video gallery
      if ($this->getImageCount() == 0 && $this->getFlvCount() > 0) {
         return Pluf::f('color_theme_path').'/images/folder_video.png';
      }

      // default gallery image
	  return $this->getIndexLink();
   }
}

?>