<?php

define('TYPE_IMAGE_FILE', 1);
define('TYPE_FLV_FILE', 2);

/**
 * 
 * @abstract
 */
class CommonFile {
   var $type;
   
   var $dir;
   var $file;

   var $description = NULL;
   var $date = NULL;

   var $sortPosition = '';

   var $listComments = NULL;
   
   function getType() {
      return $this->type;
   }

   function getDir() {
      return $this->dir;
   }

   function getFile() {
      return $this->file;
   }

   /**
    * Retourne le chemin complet de l'image
    * @return String Chemin complet de l'image
    */
   function getFilePath() {
      return luxbum::getFilePath($this->dir, $this->file);
   }

   /**
    * Retourne la description de l'image
    * @return String Description de l'image
    */
   function getDescription() {
      return $this->description;
   }

   /**
    * Retourne la date de l'image
    * @return String Date de l'image
    */
   function getDate() {
      return $this->date;
   }

   /**
    * Affecte la description de l'image
    * @param String $description Description de l'image
    */
   function setDescription($description) {
      $this->description = $description;
   }

   /**
    * Affecte la date de l'image
    * @param String $date Date de l'image
    */
   function setDate($date) {
      $this->date = $date;
   }

   /**
    * Affecte la date et la description de l'image
    * @param String $description Description de l'image
    * @param String $date Date de l'image
    */
   function setAllDescription($description, $date) {
      $this->setDescription($description);
      $this->setDate($date);
   }

   /**
    * Retourne true/false si la date et la description sont vide
    * @return Boolean true/false si la date et la description sont vide
    */
   function issetDescription() {
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
      return filesize($this->getFilePath());
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

   /**-----------------------------------------------------------------------**/
   /** Fonctions des descriptions d'images */
   /**-----------------------------------------------------------------------**/

   /**
    * Recherche et affecte la date/description de l'image dans le fichier des
    * descriptions.
    * @return boolean true/false Date/description trouv�s ou non
    */
   function findDescription () {

      // The description is already filled in
      if ($this->description != '' || $this->date != '') {
         return true;
      }

      $desc = array ();
      $trouve = false;

      // Recherche de la description dans toutes les descriptions
      if (is_file(luxbum::getFsPath($this->getDir()).DESCRIPTION_FILE)) {
         $fd = fopen(luxbum::getFsPath($this->getDir()).DESCRIPTION_FILE, 'r+');
         while (!$trouve && $line = fgets($fd)) {
            $line = trim($line);
            
            if (ereg ('^.*\|.*\|.*$', $line)) {
               
               $tab = explode('|', $line, 2);
               $desc[$tab[0]] = $tab[1];
               
               if ($tab[0] == $this->getFile()) {
                  $trouve = true;
               }
               unset($tab);
            }
         }
      }

      // Si on a trouv� la description, on met � jour les champs
      if (isset($desc[$this->getFile()])) {
         $tab = explode('|', $desc[$this->getFile()]);
         $this->setDate($tab[0]);
         $this->setDescription($tab[1]);
      }

      return $trouve;
   }
   
   /**-----------------------------------------------------------------------**/
   /** Fonctions pour les commentaires */
   /**-----------------------------------------------------------------------**/
   /**
    * Charge les commentaires de la photo
    */
   function lazyLoadComments() {
      if ($this->listComments == NULL) {
         $serialFile = luxbum::getCommentFilePath($this->dir, $this->file);
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

      $serialFile = luxbum::getCommentFilePath($this->dir, $this->file);
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
}
?>