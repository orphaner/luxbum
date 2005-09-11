<?php 

  //==============================================================================
  // Classe luxBumGallery : une galerie
  //==============================================================================

  /**
   * Structure représentant une galerie
   */
class luxBumGallery extends luxBum
{
   var $name, $dir;
   var $preview;
   var $count;
   var $size;

   /**
    * 
    */
   function luxBumGallery ($name, $preview = '') {
      $this->name = $this->dir = $name;
      $this->preview = $preview;
      $this->size = 0;
      $this->count = 0; 
      $this->completeInfos ();
      $this->completeDefaultImage ();
   }

   /**
    * 
    */
   function getName () {
      return $this->name;
   }

   /**
    * 
    */
   function getNiceName () {
      return $this->niceName ($this->name);
   }

   /**
    * 
    */
   function getPreview () {
      return $this->preview;
   }

   /**
    * 
    */
   function getSize () {
      return $this->size;
   }

   /**
    * 
    */
   function getNiceSize () {
      return $this->niceSize ($this->size);
   }

   /**
    * 
    */
   function getCount () {
      return $this->count;
   }

   /**
    * 
    */
   function completeInfos () {
      $fd = dir ($this->getDirPath ($this->dir));

      while ($current_file = $fd->read ()) {
         $the_file = $this->getImage ($this->dir, $current_file);

         if ($current_file[0] != '.' 
             && !is_dir ($the_file) 
             && eregi ('^.*(' . ALLOWED_FORMAT . ')$', $current_file) ) {
            $this->size += filesize ($the_file);
            $this->count++; 
         }
      }
      $fd->close();
   }

   /**
    * Recherche l'image par défaut !
    */
   function completeDefaultImage () {

      $default = '';

      /* Recherche d'une image par défaut définie par l'utilisateur */
      if (is_file ($this->getDirPath ($this->dir) . DEFAULT_INDEX_FILE)) {
         $fd = fopen ($this->getDirPath ($this->dir) . DEFAULT_INDEX_FILE, 'r+');
         $line = fgets ($fd);
               
         if ( is_file ($this->getDirPath ($this->dir) . $line )) {
            $default = $line;
         }
         fclose ($fd);
      }

      /* On cherche la première image du dossier pr faire un aperçu */
      if ($default == '') {
         $trouve = false;
         $apercu_fd = opendir ($this->getDirPath ($this->dir));

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

      $this->preview = $default;
   }

   /**
    * Retourne le chemin vers l'image par défaut. 
    * Celle ci est créée si elle n'existe pas.
    */
   function getThumbPath () {
      $nuxImage = new luxBumImage ($this->name, $this->preview);
      return $nuxImage->getAsThumb ();
   }

   /**
    * Ecrit une nouvelle image par défaut
    */
   function setNewDefaultImage ($img) {
      $theFile = $this->getDirPath ($this->dir) . $img;
      if (!is_file ($theFile)) {
         return false;
      }
      files::writeFile ($this->getDirPath ($this->dir) . DEFAULT_INDEX_FILE, $img);
      return true;
   }
}

?>