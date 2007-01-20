<?php



//==============================================================================
// Classe luxBumImage : Fonctions pour les générations de miniatures
//==============================================================================

/**
 * @package process
 */
class luxBumImage
{
   var $dir;
   var $img;
   var $thumbDir;
   var $previewDir;


   var $description = NULL;
   var $date = NULL;

   var $thumbToolkit = NULL;
   var $previewToolkit = NULL;

   var $sortPosition = '';

   var $listComments = NULL;


   /**
    * Constructeur par défaut
    * @param String $dir le nom de la galerie
    * @param String $img le nom de l'image
    */
   function luxBumImage ($dir, $img) {
      $this->dir = $dir;
      $list = split('/', $dir);
      $this->name = $list[count($list) - 1];

      $this->img = $img;
      $this->thumbDir = luxbum::getThumbPath ($this->dir);
      $this->previewDir = luxbum::getPreviewPath ($this->dir);
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
    * Retourne le nom de l'image
    * @return String Nom de l'image
    */
   function getImageName () {
      return $this->img;
   }

   /**
    * Retourne le chemin complet de l'image
    * @return String Chemin complet de l'image
    */
   function getImagePath () {
      return luxbum::getImage ($this->dir, $this->img);
   }

   /**
    * Retourne la description de l'image
    * @return String Description de l'image
    */
   function getDescription () {
      return $this->description;
   }

   /**
    * Retourne la date de l'image
    * @return String Date de l'image
    */
   function getDate () {
      return $this->date;
   }

   /**
    * Affecte la description de l'image
    * @param String $description Description de l'image
    */
   function setDescription ($description) {
      $this->description = $description;
   }

   /**
    * Affecte la date de l'image
    * @param String $date Date de l'image
    */
   function setDate ($date) {
      $this->date = $date;
   }

   /**
    * Affecte la date et la description de l'image
    * @param String $description Description de l'image
    * @param String $date Date de l'image
    */
   function setAllDescription ($description, $date) {
      $this->setDescription ($description);
      $this->setDate ($date);
   }

   /**
    * Retourne true/false si la date et la description sont vide
    * @return Boolean true/false si la date et la description sont vide
    */
   function issetDescription () {
      if ($this->description == '' && $this->date == '') {
         return false;
      }
      return true;
   }

   /**
    * Retourne la taille en octets de l'image
    * @return int Taille en octets de l'image
    */
   function getSize () {
      return filesize ($this->getImagePath ());
   }
   
   /**
    * Retourne la date et la description sous un format affichable
    * @return String Date et descrition sous format affichable
    */
   function getDateDesc () {
      $dateDesc = '&nbsp;';
      
      // Date
      if ($this -> getDate() != '') {
         list ($jour, $mois, $annee) = explode ('/', $this -> getDate());
         setlocale (LC_TIME, 'fr_FR');
         $timeStamp = mktime (0, 0, 0, $mois, $jour, $annee);
         $dateDesc = 'Le '.strftime (DATE_FORMAT,  $timeStamp);
    
         // date + description
         if ($this -> getDescription () != '') {
            $dateDesc .= ' - '. ucfirst ($this -> getDescription ());
         }
      }
   
      // Que description
      else if ($this -> getDescription () != '') {
         $dateDesc = ucfirst ($this -> getDescription ());
      }
      return $dateDesc;
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
   
   /**
    * Affecte l'ordre manuel de tri
    * @param int $sortOrder Ordre de tri
    */
   function setSortPosition ($sortPosition) {
      $this->sortPosition = $sortPosition;
   }
   
   /**
    * Retourne l'ordre manuel de tri
    * @return int Ordre manuel de tri
    */
   function getSortPosition () {
      return $this->sortPosition;
   }

   /**-----------------------------------------------------------------------**/
   /** Fonctions des descriptions d'images */
   /**-----------------------------------------------------------------------**/

   /**
    * Recherche et affecte la date/description de l'image dans le fichier des
    * descriptions.
    * @return boolean true/false Date/description trouvés ou non
    */
   function findDescription () {

      // Une description est déjà rentrée, pas besoin de chercher !
      if ($this->description != '' || $this->date != '') {
         return true;
      }

      $desc = array ();
      $trouve = false;

      // Recherche de la description dans toutes les descriptions
      if (is_file (luxbum::getFsPath ($this->getImageDir()).DESCRIPTION_FILE)) {
         $fd = fopen (luxbum::getFsPath ($this->getImageDir()).DESCRIPTION_FILE, 'r+');
         while (!$trouve && $line = fgets ($fd)) {
            $line = trim($line);
            if (ereg ('^.*\|.*\|.*$', $line)) {
               $tab = explode ('|', $line, 2);
               $desc[$tab[0]] = $tab[1];
               if ($tab[0] == $this->getImageName ()) {
                  $trouve = true;
               }
               unset ($tab);
            }
         }
      }

      // Si on a trouvé la description, on met à jour les champs
      if (isset ($desc[$this->getImageName ()])) {
         $tab = explode ('|', $desc[$this->getImageName ()]);
         $this->setdate ($tab[0]);
         $this->setdescription ($tab[1]);
      }

      return $trouve;
   }


   /**-----------------------------------------------------------------------**/
   /** Fonctions pour créer les thumbs / preview */
   /**-----------------------------------------------------------------------**/
   
   /**
    * Retourne le lien de la vignette de l'image vers le script qui génére
    * l'image
    * @return Lien de la vignette de l'image vers le script de génération
    */
   function getThumbLink () {
      return link::thumb($this->dir, $this->img);
   }
   
   /**
    * Retourne le lien de l'aperçu de l'image vers le script qui génére l'image
    * @return Lien de l'aperçu de l'image vers le script de génération
    */
   function getPreviewLink () {
      return link::preview($this->dir, $this->img);
   }

   /**
    * Génére la vignette de l'image et retourne le chemin vers l'image générée
    * @return String Chemin vers la vignette générée.
    */
   function getAsThumb ($dst_w = 85, $dst_h = 85) {
      $this->thumbToolkit = processFactory::imageToolkit($this->getImagePath ());
      $this->thumbToolkit->setDestSize ($dst_w, $dst_h);

      $final = luxbum::getThumbImage ($this->dir, $this->img, $dst_w, $dst_h);
      if (!is_file ($final)) {
         files::createDir ($this->thumbDir);
         $this->thumbToolkit->createThumb ($final);
      }
      return $final;
   }
   
   /**
    * Cette fonction détermine si oui ou non il faut générer un aperçu.
    * L'aperçu est généré seulement si la taille en octets de l'image est
    * supérieur au seuil fixé.
    * @access private
    * @return boolean true/false Génére l'aperçu ou non
    */
   function _needPreview () {
      if ($this->getSize() < MIN_SIZE_FOR_PREVIEW * 1024) {
         return false;
      }
      return true;
   }

   /**
    * Génére l'aperçu de l'image et retourne le chemin vers l'image générée
    * @return String Chemin vers l'aperçu généré.
    */
   function getAsPreview ($dst_w = 650, $dst_h = 485) {
      $this->previewToolkit = processFactory::imageToolkit($this->getImagePath ());
      $this->previewToolkit->setDestSize ($dst_w, $dst_h);
      
      // Si pas d'aperçu on retourne l'image originale
      if ($this->_needPreview() == false) {
         return $this->getImagePath ();
      }

      $final = luxbum::getPreviewImage ($this->dir, $this->img, $dst_w, $dst_h);
      // Génération de preview
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
    * Retourne la chaine de taille de l'aperçu pour la balise &gt;img&lt;
    * @return String Taille de l'aperçu pour la balise &gt;img&lt;
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
    * Supprime la photo ainsi que tout son cache et les commentaires associés.
    * @return Boolean
    */
   function delete () {
      $this->clearCache ();
      commentaire::deletePhoto ($this->dir, $this->img);
      return files::deleteFile (luxbum::getFsPath ($this->dir) . $this->img);
   }


   /**
    * Supprime le cache de l'image
    */
   function clearCache () {
      $this->clearThumbCache ();
      $this->clearPreviewCache ();
   }

   /**
    * Supprime le cache des aperçus
    */
   function clearThumbCache () {

      if ($fd = opendir ($this->thumbDir)) {
         while ($current_file = readdir ($fd)) {
            if ($current_file[0] != '.' 
                && !is_dir ($this->thumbDir.$current_file) 
                && eregi ('^.*(' . $this->img . ')$', $current_file)){
               files::deleteFile ($this->thumbDir.$current_file);
            }
         }
         closedir ($fd);
      }
   }

   /**
    * Supprime le cache des vignettes
    */
   function clearPreviewCache () {
      files::deleteFile ($this->previewDir . $this->img);
   }




   /**-----------------------------------------------------------------------**/
   /** Fonctions pour les commentaires */
   /**-----------------------------------------------------------------------**/
   /**
    * Charge les commentaires de la photo
    */
   function lazyLoadComments() {
      if ($this->listComments == NULL) {
         $serialFile = luxbum::getCommentFilePath($this->dir, $this->img);
         if (is_file ($serialFile)) {
            $instanceSerial = implode ("", @file ($serialFile));
            $this->listComments = unserialize ($instanceSerial);
         }
         else {
            $this->listComments = new Recordset2();
         }
      }
      return $this->listComments;
   }

   /**
    *
    */
   function saveComment ($comment) {
      $list = $this->lazyLoadComments();
      $list->addToList($comment);
      $passContent = serialize($list);

      $serialFile = luxbum::getCommentFilePath($this->dir, $this->img);
      $serialDir = luxbum::getCommentPath($this->dir);
      files::createDir($serialDir);
      files::deleteFile($serialFile);
      files::writeFile($serialFile, $passContent);

      $this->listComments = false;
   }


   /**
    * Retourne le nombre de commentaires actifs de la photos
    * @return nombre de commentaires actifs
    */
   function getNbComment () {
      $list = $this->lazyLoadComments();
      return $list->getIntRowCount();
   }

   /**-----------------------------------------------------------------------**/
   /** Fonctions d'informations exif */
   /**-----------------------------------------------------------------------**/
   var $imageMeta;

   /**
    * Initialise les informations EXIF de la photo
    */
   function metaInit () {
      $this->imageMeta = new ImageMeta($this->getImagePath());
      $this->imageMeta->getMeta();
   }

   /**
    * Retourne ok si les informations EXIF existent
    * @return boolean Infomations EXIF existent
    */
   function hasMeta() {
      return $this->imageMeta->hasMeta();
   }

   function getMeta() {
      return $this->imageMeta->getProperties();
   }
}

?>