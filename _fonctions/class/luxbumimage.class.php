<?php

include (LIB_DIR.'exifer/exif.php');
include_once (FONCTIONS_DIR.'mysql.inc.php');

define ('NOT_SET', __('Not Set'));

//==============================================================================
// Classe luxBumImage : Fonctions pour les g�n�rations de miniatures
//==============================================================================

/**
 *
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

   var $sortPosition = 0;


   /**
    * Constructeur par d�faut
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
    * @return boolean true/false Date/description trouv�s ou non
    */
   function findDescription () {

      // Une description est d�j� rentr�e, pas besoin de chercher !
      if ($this->description != '' || $this->date != '') {
         return true;
      }

      $desc = array ();
      $trouve = false;

      // Recherche de la description dans toutes les descriptions
      if (is_file (luxbum::getFsPath ($this->getImageDir()).DESCRIPTION_FILE)) {
         $fd = fopen (luxbum::getFsPath ($this->getImageDir()).DESCRIPTION_FILE, 'r+');
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

      // Si on a trouv� la description, on met � jour les champs
      if (isset ($desc[$this->getImageName ()])) {
         $tab = explode ('|', $desc[$this->getImageName ()]);
         $this->setdate ($tab[0]);
         $this->setdescription ($tab[1]);
      }

      return $trouve;
   }


   /**-----------------------------------------------------------------------**/
   /** Fonctions pour cr�er les thumbs / preview */
   /**-----------------------------------------------------------------------**/
   
   /**
    * Retourne le lien de la vignette de l'image vers le script qui g�n�re
    * l'image
    * @return Lien de la vignette de l'image vers le script de g�n�ration
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
    * Retourne le lien de l'aper�u de l'image vers le script qui g�n�re l'image
    * @return Lien de l'aper�u de l'image vers le script de g�n�ration
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
    * G�n�re la vignette de l'image et retourne le chemin vers l'image g�n�r�e
    * @return String Chemin vers la vignette g�n�r�e.
    */
   function getAsThumb ($dst_w = 85, $dst_h = 85) {
      $this->thumbToolkit = new imagetoolkit ($this->getImagePath ());
      $this->thumbToolkit->setDestSize ($dst_w, $dst_h);

      $final = luxbum::getThumbImage ($this->dir, $this->img, $dst_w, $dst_h);
      if (!is_file ($final)) {
         files::createDir ($this->thumbDir);
         $this->thumbToolkit->createThumb ($final);
      }
      return $final;
   }
   
   /**
    * Cette fonction d�termine si oui ou non il faut g�n�rer un aper�u.
    * L'aper�u est g�n�r� seulement si la taille en octets de l'image est
    * sup�rieur au seuil fix�.
    * @access private
    * @return boolean true/false G�n�re l'aper�u ou non
    */
   function _needPreview () {
      if ($this->getSize() < MIN_SIZE_FOR_PREVIEW * 1024) {
         return false;
      }
      return true;
   }

   /**
    * G�n�re l'aper�u de l'image et retourne le chemin vers l'image g�n�r�e
    * @return String Chemin vers l'aper�u g�n�r�.
    */
   function getAsPreview ($dst_w = 650, $dst_h = 485) {
      $this->previewToolkit = new imagetoolkit ($this->getImagePath ());
      $this->previewToolkit->setDestSize ($dst_w, $dst_h);
      
      // Si pas d'aper�u on retourne l'image originale
      if ($this->_needPreview() == false) {
         return $this->getImagePath ();
      }

      $final = luxbum::getPreviewImage ($this->dir, $this->img, $dst_w, $dst_h);
      // G�n�ration de preview
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
    * Supprime la photo ainsi que tout son cache et les commentaires associ�s.
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
    * Supprime le cache des aper�us
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
      if ($mysql != null) {
         $query = sprintf ("SELECT count(*) FROM ".DBL_PREFIX."commentaire "
                           ."WHERE galerie_comment=%s AND photo_comment=%s AND pub_comment=%s",
                           $mysql->escapeString($this->dir),
                           $mysql->escapeString($this->img),
                           $mysql->escapeSet(1));
         return $mysql->DbCount ($query);
      }
      return 0;
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
      if (array_key_exists('SubIFD', $this->exifResult)
            && array_key_exists ('ISOSpeedRatings', $this->exifResult['SubIFD'])) {
         return $this->pullout ($this->exifResult['SubIFD']['ISOSpeedRatings']);
      }
      return NOT_SET;
   }
   
   /**
    * Retourne le mod�le de l'appareil photo
    * @return Mod�le de l'appareil photo
    */
   function getExifCameraModel () {
      if (array_key_exists('IFD0', $this->exifResult)
            && array_key_exists ('Model', $this->exifResult['IFD0'])) {
         return trim ($this->exifResult['IFD0']['Model']);
      }
      return NOT_SET;
   }

   /**
    * Retourne la marque de l'appareil photo
    * @return String Marque de l'appareil photo
    */
   function getExifCameraMaker () {
      if (array_key_exists('IFD0', $this->exifResult)
            && array_key_exists ('Make', $this->exifResult['IFD0'])) {
         return trim ($this->exifResult['IFD0']['Make']);
      }
      return NOT_SET;
   }

   /**
    * Retourne la distance focale
    * @return String Distance focale
    */
   function getExifFocalLength () {
      if (array_key_exists('SubIFD', $this->exifResult)
            && array_key_exists ('FocalLength', $this->exifResult['SubIFD'])) {
         return $this->exifResult['SubIFD']['FocalLength'];
      }
      return NOT_SET;
   }

   /**
    * Retourne si le flash c'est d�clench� ou non
    * @return Boolean Flash d�clench� ou non
    */
   function getExifFlash () {
      if (array_key_exists('SubIFD', $this->exifResult)
            && array_key_exists ('Flash', $this->exifResult['SubIFD'])) {
         $flash = $this->exifResult['SubIFD']['Flash'];
         if ($flash == 'No Flash') {
            $flash = __('Flash did not fire');
         }
         return $flash;
      }
      return NOT_SET;
   }

   /**
    * Retourne la date � laquelle la photo a �t� prise
    * @return Date de prise de la photo
    */
   function getExifCaptureDate () {
      if (array_key_exists('SubIFD', $this->exifResult)
            && array_key_exists ('DateTimeOriginal', $this->exifResult['SubIFD'])) {
         return $this->exifResult['SubIFD']['DateTimeOriginal'];
      }
      return NOT_SET;
   }

   /**
    * Retourne le temps d'ouverture
    * @return String Temps d'ouverture
    */
   function getExifAperture () {
      if (array_key_exists('SubIFD', $this->exifResult)
            && array_key_exists ('FNumber', $this->exifResult['SubIFD'])) {
         return $this->exifResult['SubIFD']['FNumber'];
      }
      return NOT_SET;
   }

   /**
    * Retourne le temps d'exposition
    * @return String Temps d'exposition
    */
   function getExifExposureTime () {
      if (array_key_exists('SubIFD', $this->exifResult)
            && array_key_exists ('ExposureTime', $this->exifResult['SubIFD'])) {
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