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
   
   function luxBumIndex ($dir) {
      parent::RecordSet2();
      $this->dir = $dir;
      $this->_loadSort ();
   }
   
   
   /**-----------------------------------------------------------------------**/
   /** Getter et setter */
   /**-----------------------------------------------------------------------**/
   /**
    * Retourne le nombre de galeries
    */
   function getGalleryCount () {
      return count ($this->galleryList);
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
            $this->arrayList = $this->_sortGalleryArray($this->arrayList, $this->sortType, $this->sortOrder);
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
    * Enregistre les préférences de tri dans un fichier de format :
    * sortType\n
    * sortOrder\n
    * imgX en pos 1\n
    * imgX en pos n\n
    */
   function saveSort () {
      $list = $this->_sortGalleryArray ($this->galleryList, 'manuel', 'asc');
      //print_r($list);
      files::deleteFile (luxbum::getFsPath($this->dir).ORDER_FILE, 'a');
      if ($fd = fopen (luxbum::getFsPath($this->dir).ORDER_FILE, 'a')) {
         fputs ($fd, $this->sortType."\n");
         fputs ($fd, $this->sortOrder."\n");
         for ($i = 0 ; $i < count ($list) ; $i++) {
            $gallery = $list[$i];
            $name = $gallery->getName();
            fputs($fd, "$name\n");
         }
         fclose($fd);
      }
   }
   
   /**
    * Charge l'ordre des photos
    * @access private
    */
   function _loadSort () {
      if (is_file(luxbum::getFsPath($this->dir).ORDER_FILE)) {
         $fd = fopen (luxbum::getFsPath($this->dir).ORDER_FILE, 'r+');
         $sortType = trim(fgets ($fd));
         $sortOrder = trim(fgets ($fd));
         $this->setSortType($sortType);
         $this->setSortOrder($sortOrder);
         $position = 0;
         while ($galleryName = trim(fgets($fd))) {
            $this->sortList[$galleryName] = $position;
            $position++;
         }
         //print_r($this->sortList);
         fclose ($fd);
      }
   }
}
?>