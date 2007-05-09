<?php



  //==============================================================================
  // Classe luxBumGallery : une galerie
  //==============================================================================

  /**
   * @package process
   * Structure repr�sentant une galerie
   */
class luxBumGallery extends SortableRecordset
{
   /**-----------------------------------------------------------------------**/
   /** Champs de la classe */
   /**-----------------------------------------------------------------------**/
   var $name, $dir;
   var $preview;
   
   var $flvCount = 0;
   var $imageCount = 0;
   var $totalCount = 0;
   
   var $flvSize = 0;
   var $imageSize = 0;
   var $totalSize = 0;
   
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
      parent::Recordset2();
      $this->preview = $preview;

      $this->dir = $dir;
      $this->dirPath = luxbum::getFsPath($dir);
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
    * Remplit les informations suivantes sur la galerie :
    * - nombre d'images
    * - taille des images en octets
    * - private : galerie priv�e ou non
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
    * Affecte le nom de la galerie
    * @param string $name Nom de la galerie
    */
   function setName ($name) {
      $this->name = $this->dir = $name;
   }

   /**
    * Retourne le nom de la galerie
    * @return String Nom de la galerie
    */
   function getName () {
      return $this->name;
   }

   /**
    * Retourne le chemin de la galerie
    * @return String Chemin de la galerie
    */
   function getDir () {
      return $this->dir;
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

   function isUnlocked() {
      $privateManager =& PrivateManager::getInstance();
      return $privateManager->isUnlocked($this->dir);
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
         $fd = fopen ($this->dirPath.DESCRIPTION_FILE, 'r+');
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
      if (!is_dir ($this->dirPath.$current_file)) {
         return false;
      }
      if ($current_file[0] == '.') {
         return false;
      }
      if ($current_file == files::removeTailSlash(THUMB_DIR)
          || $current_file == files::removeTailSlash(PREVIEW_DIR)) {
         return false;
      }
      if (files::isPhotoFile ($this->dir, $current_file)) {
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
    * Ajoute toute les images � la liste $this->list
    * Les images ont leur date / descriptions correctement remplies.
    * Les images sont tri�es suivant les options.
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
   /** Fonctions de tri */
   /**-----------------------------------------------------------------------**/
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
         // Suffixe avec le nom de l'image au cas o� 
         // il y aurait des cl�s identiques !!! 
         // (ce qui arrive souvent, m�me date|description, ordre non d�fini)
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
   /** Image par d�faut */
   /**-----------------------------------------------------------------------**/
   /**
    * Recherche l'image par d�faut !
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


      /* Recherche d'une image par d�faut d�finie par l'utilisateur */
      if (is_file ($this->dirPath . DEFAULT_INDEX_FILE)) {
         $fd = fopen ($this->dirPath . DEFAULT_INDEX_FILE, 'r+');
         $line = fgets ($fd);
               
         if ( is_file ($this->dirPath . $line )) {
            $default = $line;
         }
         fclose ($fd);
      }

      /* On cherche la premi�re image du dossier pr faire un aper�u */
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
    * Retourne le lien de la vignette de l'image vers le script qui g�n�re
    * l'image
    * @return Lien de la vignette de l'image vers le script de g�n�ration
    */
   function getIndexLink () {

      $this->_completeDefaultImage ();      

      // La galerie contient des photos
      return link::index($this->dir, $this->preview);
   }

   /**
    * Ecrit une nouvelle image par d�faut
    * @param String $img Nom de la nouvelle image par d�faut
    * @return boolean 
    */
   function setNewDefaultImage ($img) {
      $theFile = $this->dirPath . $img;
      if (!is_file ($theFile)) {
         return false;
      }
      files::writeFile ($this->dirPath . DEFAULT_INDEX_FILE, $img);
      return true;
   }
   
   
   /**-----------------------------------------------------------------------**/
   /** Fonctions de renomage / suppression */
   /**-----------------------------------------------------------------------**/
   /**
    * Renome la galerie courante
    * @param String $newName Nouveau nom de la galerie
    * @return boolean Renomage OK ou KO
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
    * Efface une galerie. M�thode statique
    * @param String $dirName Nom de la galerie � supprimer.
    */
   function delete ($dirName) {
      files::deltree (luxbum::getFsPath ($dirName));
      commentaire::deleteGalerie ($dirName);
   }
   
   /**
    * Supprime le cache de la galerie
    */
   function clearCache () {
      reset ($this->list);
      while (list (,$img) = each ($this->list)) {
         $img->clearCache ();
      }
   }

   /**
    * Supprime les vignettes cach�es de la galerie
    */
   function clearThumbCache () {
      reset ($this->list);
      while (list (,$img) = each ($this->list)) {
         $img->clearThumbCache ();
      }
   }

   /**
    * Supprime les aper�us cach�s de la galerie
    */
   function clearPreviewCache () {
      reset ($this->list);
      while (list (,$img) = each ($this->list)){
         $img->clearPreviewCache ();
      }
   }
}

?>