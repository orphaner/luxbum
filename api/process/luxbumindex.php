<?php 


  //==============================================================================
  // Classe luxBumIndex : Index de toutes les galeries
  //==============================================================================

  /**
   * @package process
   * Classe contenant l'index de toutes les galeries
   */
class luxBumIndex extends SortableRecordset
{
//   var $galleryList = array ();
   var $dir;
   var $selfGallery;
   
   function luxBumIndex ($dir) {
      parent::RecordSet2();
      $this->dir = $dir;
      $this->_loadSort ();
      $this->selfGallery = new luxBumGallery($dir);
   }
   
   
   /**-----------------------------------------------------------------------**/
   /** Index Gallery itslef */
   /**-----------------------------------------------------------------------**/
   function getSelfGallery() {
      return $this->selfGallery;
   }
   function isSelfGallery() {
      return $this->selfGallery->getCount() > 0;
   }


   /**-----------------------------------------------------------------------**/
   /** Getter et setter */
   /**-----------------------------------------------------------------------**/
   /**
    * Retourne le nombre de galeries
    */
   function getGalleryCount () {
      return $this->getIntRowCount();
   }

   /**
    * Retourne le dossier de la galerie
    */
   function getDir () {
      return $this->dir;
   }


   /**-----------------------------------------------------------------------**/
   /** Remplissage de la liste des galeries */
   /**-----------------------------------------------------------------------**/

   /**
    * Ajoute une galerie
    * @param String $name le nom de la galerie
    * @param int $sortPosition Position de la galerie dans l'index
    */
   function addGallery ($name) {
      $galleryName = files::addTailSlash($this->dir).$name;
      $galleryTemp = new luxBumGallery($galleryName);
      $galleryTemp->addSubGalleries();

      if ($galleryTemp->getCount() > 0 
          || $galleryTemp->hasSubGallery()) {
         
         // On affecte l'ordre
         if (array_key_exists ($name, $this->sortList)) {
            $galleryTemp->setSortPosition($this->sortList[$name]);
         }

         $this->addToList($galleryTemp);
      }
   }

   

   /**
    * Remplit la liste de toutes les galeries
    * @param boolean $minImage Au moins une image ou non dans la galerie
    */
   function addAllGallery ($minImage = 1) {

      $this->_loadSort();
      
      // Lecture de tous les dossiers de photos 
      if (/*(is_dir($this->dir) || is_link($this->dir)) &&*/ $dir_fd = opendir (luxbum::getFsPath ($this->dir))) {
   
         while ($current_dir = readdir ($dir_fd)) {
            
            // Lecture de tous les dossiers
            if ($current_dir[0] != '.' && is_dir (luxbum::getFsPath ($this->dir, $current_dir))
                && $current_dir != files::removeTailSlash(THUMB_DIR)
                && $current_dir != files::removeTailSlash(PREVIEW_DIR)) {

               $this->addGallery ($current_dir);
            }
         }
         closedir ($dir_fd);
         if (count ($this->arrayList) > 0) {
            $this->arrayList = $this->sortRecordset($this->arrayList, $this->sortType, $this->sortOrder);
         }
      }
   }
   
   /**-----------------------------------------------------------------------**/
   /** Fonctions de tri */
   /**-----------------------------------------------------------------------**/
   function getSortRealKey($gallery, $sortType=null) {
      if ($sortType == null) {
         $sortType = $this->sortType;
      }

      switch ($sortType) {
         case 'manuel':
            $realkey = $gallery->getSortPosition();
            break;
         case 'count':
            $realkey = $gallery->getCount();
            break;
         case 'size':
            $realkey = $gallery->getSize();
            break;
         default:
            $realkey = $gallery->getName();
      }
      $realkey = trim($realkey);
      if ($realkey == null || $realkey == '') {
         $realkey = $gallery->getName();
      }
      else {
         // Suffixe avec le nom de la galerie au cas où 
         // il y aurait des clés identiques !!! 
         // (ce qui arrive souvent, même size|count, ordre non défini)
         $realkey .= '_'.$gallery->getName();
      }
      return $realkey;
   }
   
   /**
    *
    */
   function getOrderFilePath() {
      return luxbum::getFsPath($this->dir).ORDER_FILE;
   }
}
?>