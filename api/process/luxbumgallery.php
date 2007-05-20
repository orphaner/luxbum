<?php



  //==============================================================================
  // Classe luxBumGallery : une galerie
  //==============================================================================

  /**
   * @package process
   * Class representing a gallery
   */
class luxBumGallery extends CommonGallery
{
   /**-----------------------------------------------------------------------**/
   /** Champs de la classe */
   /**-----------------------------------------------------------------------**/
   var $dir;
   
   var $sortPosition = '';
   var $private = false;
   var $privateExact = false;
   var $listSubGallery = array();
   var $dirPath;


   /**-----------------------------------------------------------------------**/
   /** Constructeur de classe */
   /**-----------------------------------------------------------------------**/
   /**
    * Default constructor
    * @param String $dir Dossier de la galerie
    */
   function luxBumGallery ($dir, $preview = '') {
      parent::__construct();
      $this->preview = $preview;

      $this->dir = files::addTailSlash($dir);
      $this->dirPath = files::addTailSlash(luxbum::getFsPath($dir));
      $list = split('/', $dir);
      $this->name = $list[count($list) - 1];

      $this->addAllImages();
      $this->_completeInfos ();
      $this->_loadPrivate();
   }

   /**
    * Return a luxbumGallery instance 
    */
   function getInstance($dir, $preview='') {
      return new luxBumGallery($dir, $preview);
   }

   /**
    * Fill in information about the gallery :
    * - image number
    * - images size in byte
    * - flv video number
    * - flv video size in byte
    * - private : private gallery or no
    * @access private
    */
   function _completeInfos () {
      if ($fd = dir ($this->dirPath)) {
         while ($current_file = $fd->read ()) {
            if (files::isPhotoFile($this->dir, $current_file)) {
               $theFile = luxbum::getFilePath($this->dir, $current_file);
               $this->imageSize += filesize ($theFile);
               $this->imageCount++; 
            }
            else if (files::isFlvFile($this->dir, $current_file)) {
               $theFile = luxbum::getFilePath($this->dir, $current_file);
               $this->flvSize += filesize ($theFile);
               $this->flvCount++; 
            }
         }
         $fd->close();
      }
      $this->totalSize = $this->flvSize + $this->imageSize;
      $this->totalCount = $this->flvCount + $this->imageCount;
   }

   /**
    *
    */
   function _loadPrivate() {
      $priv = PrivateManager::getInstance();
      $this->private = $priv->isPrivate($this->dir);
      $this->privateExact = $priv->isPrivateExact($this->dir);
   }


   /**-----------------------------------------------------------------------**/
   /** Getter et setter */
   /**-----------------------------------------------------------------------**/

   /**
    * 
    */
   function setDir($dir) {
      $this->dir = $dir;
   }

   /**
    * Retourne le chemin de la galerie
    * @return String Chemin de la galerie
    */
   function getDir () {
      return $this->dir;
   }

   /**
    * Retourne si la galerie est privée ou non
    * @return Boolean Galerie privée ou non (true / false)
    */
   function isPrivate() {
      return $this->private;
   }

   /**
    * Retourne si la galerie est la première galerie privée ou non dans l'arborescence
    * @return Boolean Galerie privée ou non (true / false)
    */
   function isPrivateExact() {
      return $this->privateExact;
   }

   /**
    * 
    */
   function isUnlocked() {
      $privateManager =& PrivateManager::getInstance();
      return $privateManager->isUnlocked($this->dir);
   }
   
   function isPrivateAndLocked($current = true) {
      return $this->isPrivate() && !$this->isUnlocked();
   }

   /**-----------------------------------------------------------------------**/
   /** Descriptions / dates */
   /**-----------------------------------------------------------------------**/
   /**
    * Lit le fichier des dates/descriptions et retourne un tableau
    * correspondant par rapport au nom de fichier.
    * 
    * @return array cl�: nomPhoto / valeur: date|description
    */
   function getDescriptions () {
      $desc = array ();

      if (is_file ($this->dirPath.DESCRIPTION_FILE)) {
         $fd = @fopen ($this->dirPath.DESCRIPTION_FILE, 'r+');
         if ($fd === false) {
            throw new Exception("Unable to open file: ".$this->dirPath.DESCRIPTION_FILE);
         }
         while ($line = trim(fgets($fd)))  {
            if ( ereg ('^.*\|.*\|.*$', $line)) {
               list ($imgName, $imgDescription) = explode ('|', $line, 2);
               $desc [$imgName] = $imgDescription;
            }
         }
         fclose ($fd);
      }
      return $desc;
   }
   
   /**
    * Cr�e ou met � jour le fichier des descriptions des photos
    * 
    */
   function createOrMajDescriptionFile () {
      $desc = array ();

      // Search images who are in the description file
      if (is_file ($this->dirPath . DESCRIPTION_FILE)) {
         if ($fd = fopen ($this->dirPath . DESCRIPTION_FILE, 'r')) {
            while ($line = fgets ($fd)) {
               if (ereg ('^.*\|.*\|.*$', $line)) {
                  list($desc[]) = explode ('|', $line, 2);
               }
            }
            fclose($fd);
         }
      }

      // On ajoute les images non présentes dans le fichier de description
      if ($fd = fopen ($this->dirPath . DESCRIPTION_FILE, 'a')) {
         reset ($this->arrayList);
         while (list (,$img) = each ($this->arrayList)) {
            $name = $img->getFile();
            if (!in_array ($name, $desc)) {
               fputs ($fd, "$name||\n");
            }
         }
         fclose ($fd);
      }
   }

   /**
    * �crit les nouvelles descriptions/dates dans le fichier de description
    */
   function updateDescriptionFile () {
      files::deleteFile ($this->dirPath . DESCRIPTION_FILE, 'a');
      
      if ($fd = fopen ($this->dirPath . DESCRIPTION_FILE, 'a')) {
         for ($i = 0 ; $i < $this->getTotalCount () ; $i++) {
            $img = $this->list[$i];
            $name = $img->getFile ();
            $description = $img->getDescription ();
            $date = $img->getDate ();
            fputs ($fd, "$name|$date|$description\n");
         }
         fclose ($fd);
      }
   }
   
   /**-----------------------------------------------------------------------**/
   /** Fonctions des sous galeries */
   /**-----------------------------------------------------------------------**/

   /**
    *
    */
   function isSubGallery ($current_file) {
      if (!is_dir($this->dirPath.$current_file)) {
         return false;
      }
      if ($current_file[0] == '.') {
         return false;
      }
      if ($current_file == files::removeTailSlash(THUMB_DIR)
          || $current_file == files::removeTailSlash(PREVIEW_DIR)) {
         return false;
      }
      if (files::isPhotoFile($this->dir, $current_file)) {
         return false;
      }
      return true;
   }

   /**
    *
    */
   function hasSubGallery() {
      return count($this->listSubGallery) > 0;
   }
   
   /**
    *
    */
   function addSubGalleries() {
      // Ouverture du dossier des photos
      if ($dir_fd = opendir ($this->dirPath)) {

         // Parcours des photos du dossiers
         while ($current_file = readdir ($dir_fd)) {
            if ($this->isSubGallery($current_file)) {
               $this->listSubGallery[] = $this->dir.$current_file;
            }
         }
      }
      closedir($dir_fd);
   }


   /**-----------------------------------------------------------------------**/
   /** Ajout des images */
   /**-----------------------------------------------------------------------**/
   /**
    * Add all images to the gallery, then sort them.
    * They have all their date / description correctly filled in.
    */
   function addAllImages () {

      // Ouverture du dossier des photos
      if ($dir_fd = opendir ($this->dirPath)) {
        
         // Fetch all images, their descriptions and their orders
         $desc = $this->getDescriptions ();
         $this->_loadSort();
    
         // Parcours des photos du dossiers
         while ($current_file = readdir ($dir_fd)) {
            
            // Add an image file
            if (files::isPhotoFile ($this->dir, $current_file)) {
               $imageTemp = new luxBumImage ($this->dir, $current_file);
                
               // On affecte les dates / descriptions
               if (array_key_exists ($current_file, $desc)) {
                  list ($imgDate, $imgDescription) = explode ("|", $desc[$current_file], 2);
                  $imageTemp->setAllDescription ($imgDescription, $imgDate);
               }
                
               // On affecte l'ordre
               if (array_key_exists ($current_file, $this->sortList)) {
                  $imageTemp->setSortPosition($this->sortList[$current_file]);
               }
               $this->addToList($imageTemp);
            }
            
            // Add a flash video file
            else if (files::isFlvFile($this->dir, $current_file)) {
               $object = new LuxbumFlv($this->dir, $current_file);
               $this->addToList($object);
            }
         }
         closedir ($dir_fd);
      }
      
      if (count ($this->arrayList) > 0) {
         $this->arrayList = $this->sortRecordset($this->arrayList, $this->sortType, $this->sortOrder);
      }
   }
   
   
   /**-----------------------------------------------------------------------**/
   /** Fonctions de tri */
   /**-----------------------------------------------------------------------**/
   /**
    * @param String $file
    * @param String $sortType
    */
   function getSortRealKey($file, $sortType=null) {
      if ($sortType == null) {
         $sortType = $this->sortType;
      }

      switch ($sortType) {
         case 'manuel':
            $realkey = $file->getSortPosition();
            break;
         case 'date':
            $realkey = $file->getDate();
            break;
         case 'description':
            $realkey = $file->getDescription();
            break;
         default:
            $realkey = $file->getFile();
      }
      $realkey = trim($realkey);
      if ($realkey == null || $realkey == '') {
         $realkey = $file->getFile();
      }
      else {
         // Add a suffix to the key with the image name in case there are identical keys.
         // It often appends : same date or description, order not defined
         $realkey .= '_'.$file->getFile();
      }
      return $realkey;
   }

   /**
    *
    */
   function getOrderFilePath() {
      return $this->dirPath . ORDER_FILE;
   }

   /**-----------------------------------------------------------------------**/
   /** Default image */
   /**-----------------------------------------------------------------------**/
   /**
    * Search for the default image
    * @access private
    */
   function _completeDefaultImage () {
      $default = '';

      // On est dans une sous galerie sans images
      if ($this->getTotalCount() == 0) {
         $this->preview = '';
      }
      
      if ($this->getImageCount() == 0 && $this->getFlvCount() > 0) {
         
      }

      // Search for an user defined image
      if (is_file (luxbum::getFilePath($this->dir, DEFAULT_INDEX_FILE))) {
         $fd = fopen (luxbum::getFilePath($this->dir, DEFAULT_INDEX_FILE), 'r+');
         $line = fgets ($fd);
         
         if ( files::isFile($this->dir, $line)) {
            $default = $line;
         }
         fclose ($fd);
      }

      // Search the first image of the gallery
      if ($default == '') {
         $trouve = false;
         $apercu_fd = opendir ($this->dirPath);

         while (!$trouve && $current_file = readdir ($apercu_fd)) {
            if ($current_file[0] != '.' 
                && !is_dir (luxbum::getImage ($this->dir, $current_file)) 
                && eregi ('^.*(' . ALLOWED_FORMAT . ')$', $current_file)) {
               $default = $current_file;
               $trouve = true;
            }
         }
         closedir ($apercu_fd);
      }

      $this->preview = $default;
   }

   /**
    * Set an user defined image
    * @param String $filename Filename of the user defined image
    * @return boolean 
    */
   function setNewDefaultImage ($filename) {
      $theFile = $this->dirPath . $filename;
      if (!is_file ($theFile)) {
         return false;
      }
      files::writeFile ($this->dirPath . DEFAULT_INDEX_FILE, $filename);
      return true;
   }
   
   
   /**-----------------------------------------------------------------------**/
   /** Rename / delete functions */
   /**-----------------------------------------------------------------------**/
   /**
    * Rename the current gallery
    * @param String $newName New name of the gallery
    * @return boolean Rename success (true) or failed (false)
    */
   function rename ($newName) {
      if (files::renameDir ($this->dirPath, luxbum::getFsPath ($newName))) {
         commentaire::renameGalerie ($this->dir, $newName);
         $this->setName($newName);
         return true;
      }
      return false;
   }
   
   /**
    * Delete a gallery 
    * @static 
    * @param String $galleryName Gallery name to delete
    */
   function delete ($galleryName) {
      files::deltree (luxbum::getFsPath ($galleryName));
      commentaire::deleteGalerie ($galleryName);
   }
   
   /**
    * Delete the gallery cache.
    */
   function clearCache () {
      $this->reset();
      while (list (,$img) = each ($this->arrayList)) {
         $img->clearCache ();
      }
   }
   
   function __toString() {
      echo "luxbumgallery::__toString();";
   }
}

?>