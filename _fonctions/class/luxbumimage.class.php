<?php

include (LIB_DIR.'exifer/exif.php');
include_once (FONCTIONS_DIR.'mysql.inc.php');

define ('NOT_SET', 'Not Set');

//==============================================================================
// Classe luxBumImage : Fonctions pour les générations de miniatures
//==============================================================================

/**
 *
 */
class luxBumImage extends luxBum
{
   var $dir;
   var $img;
   var $thumbDir;
   var $previewDir;


   var $description = NULL;
   var $date = NULL;

   var $thumbToolkit = NULL;
   var $previewToolkit = NULL;
   
   var $previewImagePath;
   
   var $sortPosition = 0;


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
      $this->thumbDir = $this->getThumbPath ($this->dir);
      $this->previewDir = $this->getPreviewPath ($this->dir);
      $this->setAllDescription ('', '');
      $this->previewImagePath = $this->getPreviewImage ($this->dir, $this->img);
      //echo "<strong>$this->dir</strong> : <em>!! $this->photoDir !!</em><br/>";
   }
   
   /**
    * Retourne le chemin de l'image d'aperçu
    * @return String Chemin de l'image d'aperçu
    */
   function getPreviewImagePath () {
      return $this->previewImagePath;
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
      return $this->getImage ($this->dir, $this->img);
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
      if (is_file ($this->getDirPath ($this->getImageDir()).DESCRIPTION_FILE)) {
         $fd = fopen ($this->getDirPath ($this->getImageDir()).DESCRIPTION_FILE, 'r+');
         while (!$trouve && $line = fgets ($fd)) {
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
      if (USE_REWRITE == 'on') {
         $prefix = 'image/';
      }
      else {
         $prefix = 'image.php?';
      }
      return $prefix.THUMB_DIR.'-'.$this->dir.'-'.$this->img;
   }
   
   /**
    * Retourne le lien de l'aperçu de l'image vers le script qui génére l'image
    * @return Lien de l'aperçu de l'image vers le script de génération
    */
   function getPreviewLink () {
      if (USE_REWRITE == 'on') {
         $prefix = 'image/';
      }
      else {
         $prefix = 'image.php?';
      }
      return $prefix.PREVIEW_DIR.'-'.$this->dir.'-'.$this->img;
   }

   /**
    * Génére la vignette de l'image et retourne le chemin vers l'image générée
    * @return String Chemin vers la vignette générée.
    */
   function getAsThumb ($dst_w = 85, $dst_h = 85) {
      $this->thumbToolkit = new imagetoolkit ($this->getImagePath ());
      $this->thumbToolkit->setDestSize ($dst_w, $dst_h);

      $final = $this->getThumbImage ($this->dir, $this->img, $dst_w, $dst_h);
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
      $this->previewToolkit = new imagetoolkit ($this->getImagePath ());
      $this->previewToolkit->setDestSize ($dst_w, $dst_h);
      
      // Si pas d'aperçu on retourne l'image originale
      if ($this->_needPreview() == false) {
         return $this->getImagePath ();
      }

      // Génération de preview
      if (!is_file ($this->previewImagePath)) {
         files::createDir ($this->previewDir);
         $this->previewToolkit->createThumb ($this->previewImagePath);
      }
      return $this->previewImagePath;
   }

   /**
    * Retourne la chaine de taille de la vignette pour la balise &gt;img&lt;
    * @return String Taille de la vignette pour la balise &gt;img&lt;
    */
   function getThumbResizeSize () {
      if ($this->thumbToolkit == null) {
         return '';
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
      return files::deleteFile ($this->getDirPath ($this->dir) . $this->img);
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
    * Retourne le nombre de commentaires actifs de la photos
    * @return nombre de commentaires actifs
    */
   function getNbComment () {
      global $mysql;
      $query = sprintf ("SELECT count(*) FROM ".DBL_PREFIX."commentaire "
         ."WHERE galerie_comment=%s AND photo_comment=%s AND pub_comment=%s",
         $mysql->escapeString($this->dir),
         $mysql->escapeString($this->img),
         $mysql->escapeSet(1));
      return $mysql->DbCount ($query);
   }

   /**-----------------------------------------------------------------------**/
   /** Fonctions d'informations exif */
   /**-----------------------------------------------------------------------**/
   var $exifResult;


   /**
    * @access private
    */
   function &reduceExif ($exifvalue) {
      $vals = split ("/",$exifvalue);
      if (count ($vals) == 2){
         $exposure = round ($vals[0]/$vals[1],2);
         if ($exposure < 1) {
            $exposure = '1/'.round ($vals[1]/$vals[0],0);
         }
      }
      else {
         $exposure = round ($vals[0]/$vals[1], 2);
      }

      return $exposure;
   }

   /**
    * @access private
    */
   function pullout ($str){
      $str = stripslashes($str);
      $str = UTF8_decode($str);
      return $str;
   }

   /**
    * Initialise les informations EXIF de la photo
    */
   function exifInit () {
      $verbose = 0;
      $this->exifResult = read_exif_data_raw ($this->getImagePath (), $verbose); 
   }

   /**
    * Retourne ok si les informations EXIF existent
    * @return boolean Infomations EXIF existent
    */
   function exifExists () {
      if (array_key_exists ('SubIFD', $this->exifResult) && 
          array_key_exists ('IFD0', $this->exifResult)) {
         return true;
      }
      return false;
   }

   /**
    * Retourne la valeur ISO
    * @return String Valeur ISO
    */
   function getExifISO () {
      if (array_key_exists ('ISOSpeedRatings', $this->exifResult['SubIFD'])) {
         return $this->pullout ($this->exifResult['SubIFD']['ISOSpeedRatings']);
      }
      return NOT_SET;
   }
   
   /**
    * Retourne le modèle de l'appareil photo
    * @return Modèle de l'appareil photo
    */
   function getExifCameraModel () {
      if (array_key_exists ('Model', $this->exifResult['IFD0'])) {
         return trim ($this->exifResult['IFD0']['Model']);
      }
      return NOT_SET;
   }

   /**
    * Retourne la marque de l'appareil photo
    * @return String Marque de l'appareil photo
    */
   function getExifCameraMaker () {
      if (array_key_exists ('Make', $this->exifResult['IFD0'])) {
         return trim ($this->exifResult['IFD0']['Make']);
      }
      return NOT_SET;
   }

   /**
    * Retourne la distance focale
    * @return String Distance focale
    */
   function getExifFocalLength () {
      if (array_key_exists ('FocalLength', $this->exifResult['SubIFD'])) {
         return $this->exifResult['SubIFD']['FocalLength'];
      }
      return NOT_SET;
   }

   /**
    * Retourne si le flash c'est déclenché ou non
    * @return Boolean Flash déclenché ou non
    */
   function getExifFlash () {
      if (array_key_exists ('Flash', $this->exifResult['SubIFD'])) {
         $flash = $this->exifResult['SubIFD']['Flash'];
         if ($flash == 'No Flash') {
            $flash = 'Pas enclenché';
         }
         return $flash;
      }
      return NOT_SET;
   }

   /**
    * Retourne la date à laquelle la photo a été prise
    * @return Date de prise de la photo
    */
   function getExifCaptureDate () {
      if (array_key_exists ('DateTimeOriginal', $this->exifResult['SubIFD'])) {
         return $this->exifResult['SubIFD']['DateTimeOriginal'];
      }
      return NOT_SET;
   }

   /**
    * Retourne le temps d'ouverture
    * @return String Temps d'ouverture
    */
   function getExifAperture () {
      if (array_key_exists ('FNumber', $this->exifResult['SubIFD'])) {
         return $this->exifResult['SubIFD']['FNumber'];
      }
      return NOT_SET;
   }

   /**
    * Retourne le temps d'exposition
    * @return String Temps d'exposition
    */
   function getExifExposureTime () {
      if (array_key_exists ('ExposureTime', $this->exifResult['SubIFD'])) {
         $exposure = $this->exifResult['SubIFD']['ExposureTime'];
         if ($exposure != '') {
            $exposure = $this->reduceExif ($exposure);
            $exposure = $exposure.' sec';
         }
         return $exposure;
      }
      return NOT_SET;
   }
}

?>