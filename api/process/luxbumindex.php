<?php 


//==============================================================================
// Classe luxBumIndex : Index de toutes les galeries
//==============================================================================

/**
 * @package process
 * Classe contenant l'index de toutes les galeries
 */
class luxBumIndex extends Recordset2
{
//   var $galleryList = array ();
   var $sortList = array();
   var $sortType;
   var $sortOrder;
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
    * Affecte le type du tri de l'index
    * @param String $sortType Type du tri de l'index
    */
   function setSortType ($sortType) {
      $this->sortType = $sortType;
   }
   
   /**
    * Retourne le type de tri de l'index
    * @return String Type du tri de l'index
    */
   function getSortType () {
      return $this->sortType;
   }
   
   /**
    * Affecte le sens du tri (asc / desc)
    * @param String $sortOrder Sens du tri
    */
   function setSortOrder ($sortOrder) {
      $this->sortOrder = $sortOrder;
   }
   
   /**
    * Retourne le sens du tri
    * @return String Sens du tri
    */
   function getSortOrder () {
      return $this->sortOrder;
   }

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
   /**
    * Sort Image Array will sort an array of Images based on the given key. The
    * key should correspond to any of the Image fields that can be sorted. If the
    * given key turns out to be NULL for an image, we default to the filename.
    *
    * @access private
    * @param images The array to be sorted.
    * @param sortType le type de tri : manuel, date, description
    * @param
    * @return A new array of sorted images.
    */
   function _sortGalleryArray($galleryList, $sortType, $sortOrder) {
      $newImageArray = array();
      $newImageArrayFailed = array();
      $realkey = null;
      $i = 0;
          
      //echo "sortType:$sortType - $sortOrder - ";
      foreach ($galleryList as $gallery) {
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
            $newImageArrayFailed[$gallery->getName()] = $gallery;
         }
         else {
            // Suffixe avec le nom de la galerie au cas où 
            // il y aurait des clés identiques !!! 
            // (ce qui arrive souvent, même size|count, ordre non défini)
            $realkey .= '_'.$gallery->getName();
            $newImageArray[$realkey] = $gallery;
         }
         $i++;
      }
      //print_r ($newImageArray);
          
      // Now natcase sort the array based on the keys 
      uksort ($newImageArray, "strnatcasecmp");
      uksort ($newImageArrayFailed, "strnatcasecmp");
      
      // Inverse l'ordre si ordre décroissant
      if ($sortOrder == 'desc') {
         $newImageArray = array_reverse ($newImageArray);
         $newImageArrayFailed = array_reverse ($newImageArrayFailed);
      }
      
      // Return a new array with just the values
      $newImageArray = array_values($newImageArray);
      $newImageArrayFailed = array_values($newImageArrayFailed);
      return array_merge($newImageArray, $newImageArrayFailed);
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