<?php 

include (LIB_DIR.'exifer/exif.php');

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


   var $description;
   var $date;



   function luxBumImage ($dir, $img) {
      $this->dir = $dir;
      $this->img = $img;
      $this->thumbDir = $this->getThumbPath ($dir);
      $this->previewDir = $this->getPreviewPath ($dir);
      $this->setAllDescription ('', '');
   }

   function getImageDir () {
      return $this->dir;
   }

   function getImageName () {
      return $this->img;
   }

   function getImagePath () {
      return $this->getImage ($this->dir, $this->img);
   }

   function getDescription () {
      return $this->description;
   }

   function getDate () {
      return $this->date;
   }

   function setDescription ($description) {
      $this->description = $description;
   }

   function setDate ($date) {
      $this->date = $date;
   }

   function setAllDescription ($description, $date) {
      $this->setDescription ($description);
      $this->setDate ($date);
   }

   function issetDescription () {
      if ($this->description == '' && $this->date == '') {
         return false;
      }
      return true;
   }


   /**-----------------------------------------------------------------------**/
   /** Fonctions des descriptions d'images */
   /**-----------------------------------------------------------------------**/

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
            if ( ereg ('^.*\|.*\|.*$', $line)) {
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

   function getAsThumb ($dst_w = 85, $dst_h = 85) {
      $final = $this->getThumbImage ($this->dir, $this->img, $dst_w, $dst_h);
      if (!is_file ($final)) {
         files::createDir ($this->thumbDir);
         imagetoolkit::createThumb ($this->getImagePath (), $final, $dst_w, $dst_h);
      }
      return $final;
   }

   function getAsPreview ($dst_w = 650, $dst_h = 485) {
      $final = $this->getPreviewImage ($this->dir, $this->img);
      if (!is_file ($final)) {
         files::createDir ($this->previewDir);
         imagetoolkit::createThumb ($this->getImagePath (), $final, $dst_w, $dst_h);
      }
      return $final;
   }


   /**-----------------------------------------------------------------------**/
   /** Fonctions pr le cache des images */
   /**-----------------------------------------------------------------------**/

   /**
    * 
    */
   function delete () {
      $this->clearCache ();
      return files::deleteFile ($this->getDirPath ($this->dir) . $this->img);
   }


   /**
    * 
    */
   function clearCache () {
      $this->clearThumbCache ();
      $this->clearPreviewCache ();
   }

   /**
    *
    */
   function clearThumbCache () {

      $fd = opendir ($this->thumbDir);

      while ($current_file = readdir ($fd)) {
         if ($current_file[0] != '.' 
             && !is_dir ($this->thumbDir.$current_file) 
             && eregi ('^.*(' . $this->img . ')$', $current_file)){
            files::deleteFile ($this->thumbDir.$current_file);
         }
      }
      closedir ($fd);
   }

   /**
    *
    */
   function clearPreviewCache () {
      files::deleteFile ($this->previewDir . $this->img);
   }


   /**-----------------------------------------------------------------------**/
   /** Fonctions d'informations exif */
   /**-----------------------------------------------------------------------**/
   var $exifResult;
   var $notSet = 'Not Set';


   /**
    *
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
    *
    */
   function pullout ($str){
      $str = stripslashes($str);
      $str = UTF8_decode($str);
      return $str;
   }

   /**
    *
    */
   function exifInit () {
      $verbose = 0;
      $this->exifResult = read_exif_data_raw ($this->getImagePath (), $verbose); 
   }

   /**
    *
    */
   function exifExists () {
      if (array_key_exists ('SubIFD', $this->exifResult) && 
          array_key_exists ('IFD0', $this->exifResult)) {
         return true;
      }
      return false;
   }

   /**
    *
    */
   function getExifISO () {
      if (array_key_exists ('ISOSpeedRatings', $this->exifResult['SubIFD'])) {
         return $this->pullout ($this->exifResult['SubIFD']['ISOSpeedRatings']);
      }
      return $this->notSet;
   }
   /**
    *
    */
   function getExifCameraModel () {
      if (array_key_exists ('Model', $this->exifResult['IFD0'])) {
         return trim ($this->exifResult['IFD0']['Model']);
      }
      return $this->notSet;
   }

   /**
    *
    */
   function getExifCameraMaker () {
      if (array_key_exists ('Make', $this->exifResult['IFD0'])) {
         return trim ($this->exifResult['IFD0']['Make']);
      }
      return $this->notSet;
   }

   /**
    *
    */
   function getExifFocalLength () {
      if (array_key_exists ('FocalLength', $this->exifResult['SubIFD'])) {
         return $this->exifResult['SubIFD']['FocalLength'];
      }
      return $this->notSet;
   }

   /**
    *
    */
   function getExifFlash () {
      if (array_key_exists ('Flash', $this->exifResult['SubIFD'])) {
         $flash = $this->exifResult['SubIFD']['Flash'];
         if ($flash == 'No Flash') {
            $flash = 'Pas enclenché';
         }
         return $flash;
      }
      return $this->notSet;
   }

   /**
    *
    */
   function getExifCaptureDate () {
      if (array_key_exists ('DateTimeOriginal', $this->exifResult['SubIFD'])) {
         return $this->exifResult['SubIFD']['DateTimeOriginal'];
      }
      return $this->notSet;
   }

   /**
    *
    */
   function getExifAperture () {
      if (array_key_exists ('FNumber', $this->exifResult['SubIFD'])) {
         return $this->exifResult['SubIFD']['FNumber'];
      }
      return $this->notSet;
   }

   /**
    *
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
      return $this->notSet;
   }
}

?>