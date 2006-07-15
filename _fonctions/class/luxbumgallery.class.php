<?php

  //==============================================================================
  // Classe luxBumGallery : une galerie
  //==============================================================================

  /**
   * Structure représentant une galerie
   */
class luxBumGallery extends luxBum
{
   /**-----------------------------------------------------------------------**/
   /** Champs de la classe */
   /**-----------------------------------------------------------------------**/
   var $name, $dir;
   var $preview;
   var $count;
   var $size;
   var $sortType;
   var $sortOrder;
   var $list = array ();
   var $sortList = array();
   var $sortPosition = 0;


   /**-----------------------------------------------------------------------**/
   /** Constructeur de classe */
   /**-----------------------------------------------------------------------**/
   /**
    * Constructeur par défaut
    * @param String $dir Dossier de la galerie
    */
   function luxBumGallery ($dir, $preview = '') {
      $this->preview = $preview;
      $this->size = 0;
      $this->count = 0; 

      $this->dir = $dir;
      $list = split('/', $dir);
      $this->name = $list[count($list) - 1];
      //echo "<strong>$this->dir</strong> : <em>!! $this->name !!</em><br/>";


      $this->_completeInfos ();
      $this->_completeDefaultImage ();
   }

   /**
    * Remplit les informations suivantes sur la galerie :
    * - nombre d'images
    * - taille des images en octets
    * @access private
    */
   function _completeInfos () {
      if ($fd = dir ($this->getFsPath($this->dir))) {
         while ($current_file = $fd->read ()) {
            if (files::isPhotoFile($this->dir, $current_file)) {
               $the_file = $this->getImage ($this->dir, $current_file);
               $this->size += filesize ($the_file);
               $this->count++; 
            }
         }
         $fd->close();
      }
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
      return $this->niceName ($this->name);
   }

   /**
    * Retourne l'image par défaut de la galerie
    * @return String Chemin vers l'image par défaut de la galerie
    */
   function getPreview () {
      return $this->preview;
   }

   /**
    * Retourne la taille en octets des photos de la galerie
    * @return int Taille en octets des photos de la galerie
    */
   function getSize () {
      return $this->size;
   }

   /**
    * Retourne la taille affichable des photos de la galerie
    * @return int Taille affichable des photos de la galerie
    */
   function getNiceSize () {
      return $this->niceSize ($this->size);
   }

   /**
    * Retourne le nombre de photos de la galerie
    * @return int Nombre de photos de la galerie
    */
   function getCount () {
      return $this->count;
   }
   
   /**
    * Affecte le type du tri de la galerie
    * @param String $sortType Type du tri de la galerie
    */
   function setSortType ($sortType) {
      $this->sortType = $sortType;
   }
   
   /**
    * Retourne le type de tri de la galerie
    * @return String Type du tri de la galerie
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

   /**-----------------------------------------------------------------------**/
   /** Descriptions / dates */
   /**-----------------------------------------------------------------------**/
   /**
    * Lit le fichier des dates/descriptions et retourne un tableau
    * correspondant par rapport au nom de fichier.
    * 
    * @return array clé: nomPhoto / valeur: date|description
    */
   function getDescriptions () {
      $desc = array ();

      if (is_file ($this->getDirPath ($this->dir).DESCRIPTION_FILE)) {
         $fd = fopen ($this->getDirPath ($this->dir).DESCRIPTION_FILE, 'r+');
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
    * Crée ou met à jour le fichier des descriptions des photos
    * 
    */
   function createOrMajDescriptionFile () {
      $desc = array ();

      // Recherche des images présentes dans le fichier de description
      if (is_file ($this->getDirPath ($this->dir) . DESCRIPTION_FILE)) {
         if ($fd = fopen ($this->getDirPath ($this->dir) . DESCRIPTION_FILE, 'r')) {
            while ($line = fgets ($fd)) {
               if (ereg ('^.*\|.*\|.*$', $line)) {
                  list($desc[]) = explode ('|', $line, 2);
               }
            }
            fclose($fd);
         }
      }

      // On ajoute les images non présentes dans le fichier de description
      if ($fd = fopen ($this->getDirPath ($this->dir) . DESCRIPTION_FILE, 'a')) {
         reset ($this->list);
         while (list (,$img) = each ($this->list)) {
            $name = $img->getImageName ();
            if (!in_array ($name, $desc)) {
               fputs ($fd, "$name||\n");
            }
         }
         fclose ($fd);
      }
   }

   /**
    * Écrit les nouvelles descriptions/dates dans le fichier de description
    */
   function updateDescriptionFile () {
      files::deleteFile ($this->getDirPath ($this->dir) . DESCRIPTION_FILE, 'a');
      
      if ($fd = fopen ($this->getDirPath ($this->dir) . DESCRIPTION_FILE, 'a')) {
         for ($i = 0 ; $i < $this->getCount () ; $i++) {
            $img = $this->list[$i];
            $name = $img->getImageName ();
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

   var $listSubGallery = array();

   function isSubGallery ($current_file) {
      if (!is_dir ($this->getFsPath($this->dir).$current_file)) {
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

   function hasSubGallery() {
      return count($this->listSubGallery) > 0;
   }
   
   function addSubGalleries() {
      // Ouverture du dossier des photos
      //echo "AjoutSSGAL [path]: ".$this->getFsPath ($this->dir)."<br>";
      if ($dir_fd = opendir ($this->getFsPath ($this->dir))) {

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
    * Ajoute toute les images à la liste $this->list
    * Les images ont leur date / descriptions correctement remplies.
    * Les images sont triées suivant les options.
    */
   function addAllImages () {
      $list = array ();

      // Ouverture du dossier des photos
      if ($dir_fd = opendir ($this->getDirPath ($this->dir))) {
        
         // Récupération des descriptions et de l'ordre
         $desc = $this->getDescriptions ();
         $this->_loadSort();
    
         // Parcours des photos du dossiers
         while ($current_file = readdir ($dir_fd)) {
            if (files::isPhotoFile ($this->dir, $current_file)) {
               $list[$current_file] = new luxBumImage ($this->dir, $current_file);
                
               // On affecte les dates / descriptions
               if (array_key_exists ($current_file, $desc)) {
                  list ($imgDate, $imgDescription) = explode ("|", $desc[$current_file], 2);
                  $list[$current_file]->setAllDescription ($imgDescription, $imgDate);
               }
                
               // On affecte l'ordre
               if (array_key_exists ($current_file, $this->sortList)) {
                  $list[$current_file]->setSortPosition($this->sortList[$current_file]);
               }
            }
         }
         closedir ($dir_fd);
      }
      
      if (count ($list) > 0) {
         $this->list = $this->_sortImageArray ($list, $this->sortType, $this->sortOrder);
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

      reset ($this->list);
      while (!$trouve && list (,$img) = each ($this->list)) {
         $name = $img->getImageName ();
         if ($name == $imgName) {
            $trouve = true;
         }
         else {
            $index++;
         }
      }

      if (!$trouve) {
         return -1;
      }
      return $index;
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
   function _sortImageArray($images, $sortType, $sortOrder) {
      $newImageArray = array();
      $newImageArrayFailed = array();
      $realkey = null;
      $i = 0;
          
      //echo "sortType:$sortType - $sortOrder - ";
      foreach ($images as $image) {
         switch ($sortType) {
            case 'manuel':
               $realkey = $image->getSortPosition();
               break;
            case 'date':
               $realkey = $image->getDate();
               break;
            case 'description':
               $realkey = $image->getDescription();
               break;
            default:
               $realkey = $image->getImageName();
         }
         $realkey = trim($realkey);
         if ($realkey == null || $realkey == '') {
            $newImageArrayFailed[$image->getImageName()] = $image;
         }
         else {
            // Suffixe avec le nom de l'image au cas où 
            // il y aurait des clés identiques !!! 
            // (ce qui arrive souvent, même date|description, ordre non défini)
            $realkey .= '_'.$image->getImageName();
            $newImageArray[$realkey] = $image;
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
      $list = $this->_sortImageArray ($this->list, 'manuel', 'asc');
      //print_r($list);
      files::deleteFile ($this->getDirPath ($this->dir) . ORDER_FILE, 'a');
      if ($fd = fopen ($this->getDirPath ($this->dir).ORDER_FILE, 'a')) {
         fputs ($fd, $this->sortType."\n");
         fputs ($fd, $this->sortOrder."\n");
         for ($i = 0 ; $i < count ($list) ; $i++) {
            $img = $list[$i];
            $name = $img->getImageName ();
            fputs($fd, "$name\n");
         }
         fclose ($fd);
      }
   }
   
   /**
    * Charge l'ordre des photos
    * @access private
    */
   function _loadSort () {
      if (is_file ($this->getDirPath ($this->dir).ORDER_FILE)) {
         $fd = fopen ($this->getDirPath ($this->dir).ORDER_FILE, 'r+');
         $sortType = trim(fgets ($fd));
         $sortOrder = trim(fgets ($fd));
         $this->setSortType($sortType);
         $this->setSortOrder($sortOrder);
         $position = 0;
         while ($imageName = trim(fgets($fd))) {
            $this->sortList[$imageName] = $position;
            $position++;
         }
         fclose ($fd);
      }
   }

   /**-----------------------------------------------------------------------**/
   /** Image par défaut */
   /**-----------------------------------------------------------------------**/
   /**
    * Recherche l'image par défaut !
    * @access private
    */
   function _completeDefaultImage () {
      $default = '';
      //echo "CompleteDefaultImage ".$this->getFsPath ($this->dir)."<br>";

      /* Recherche d'une image par défaut définie par l'utilisateur */
      if (is_file ($this->getFsPath ($this->dir) . DEFAULT_INDEX_FILE)) {
         $fd = fopen ($this->getFsPath ($this->dir) . DEFAULT_INDEX_FILE, 'r+');
         $line = fgets ($fd);
               
         if ( is_file ($this->getFsPath ($this->dir) . $line )) {
            $default = $line;
         }
         fclose ($fd);
      }

      /* On cherche la première image du dossier pr faire un aperçu */
      if ($default == '') {
         $trouve = false;
         $apercu_fd = opendir ($this->getFsPath ($this->dir));

         while (!$trouve && $current_file = readdir ($apercu_fd)) {
            if ($current_file[0] != '.' 
                && !is_dir ($this->getImage ($this->dir, $current_file)) 
                && eregi ('^.*(' . ALLOWED_FORMAT . ')$', $current_file)) {
               $default = $current_file;
               $trouve = true;
            }
         }
         closedir ($apercu_fd);
      }

      //echo "<br>DEFAULT: $default <br>";

      $this->preview = $default;
   }

   /**
    * Retourne le chemin vers l'image par défaut. 
    * Celle ci est créée si elle n'existe pas.
    */
   //function getThumbPath () {
      //$nuxImage = new luxBumImage ($this->getDirPath ($this->dir), $this->preview);
      //return $nuxImage->getAsThumb ();
   // return "dede";
   //}
   
   /**
    * Retourne le lien de la vignette de l'image vers le script qui génére
    * l'image
    * @return Lien de la vignette de l'image vers le script de génération
    */
   function getIndexLink () {
      if (USE_REWRITE == 'on') {
         $prefix = 'image/';
      }
      else {
         $prefix = 'image.php?';
      }
      return $prefix.'index-'.($this->dir).'-'.$this->preview;
   }

   /**
    * Ecrit une nouvelle image par défaut
    * @param String $img Nom de la nouvelle image par défaut
    * @return boolean 
    */
   function setNewDefaultImage ($img) {
      $theFile = $this->getDirPath ($this->dir) . $img;
      if (!is_file ($theFile)) {
         return false;
      }
      files::writeFile ($this->getDirPath ($this->dir) . DEFAULT_INDEX_FILE, $img);
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
      if (files::renameDir (luxbum::getDirPath ($this->dir), luxbum::getDirPath ($newName))) {
         commentaire::renameGalerie ($this->dir, $newName);
         $this->setName($newName);
         return true;
      }
      return false;
   }
   
   /**
    * Efface une galerie. Méthode statique
    * @param String $dirName Nom de la galerie à supprimer.
    */
   function delete ($dirName) {
      files::deltree (luxbum::getDirPath ($dirName));
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
    * Supprime les vignettes cachées de la galerie
    */
   function clearThumbCache () {
      reset ($this->list);
      while (list (,$img) = each ($this->list)) {
         $img->clearThumbCache ();
      }
   }

   /**
    * Supprime les aperçus cachés de la galerie
    */
   function clearPreviewCache () {
      reset ($this->list);
      while (list (,$img) = each ($this->list)){
         $img->clearPreviewCache ();
      }
   }
}

?>