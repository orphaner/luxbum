<?php 

  /**
   * Structure représentant une galerie + les vignettes à afficher
   */
class luxBumGalleryList extends luxBumGallery
{
   var $list = array ();


   /**
    * 
    */
   function luxBumGalleryList ($dir) {
      $this->luxBumGallery ($this->protectDir ($dir), '');
   }

   /**
    * 
    */
   function getDescriptions () {

      $desc = array ();

      if (is_file ($this->getDirPath ($this->dir).DESCRIPTION_FILE)) {
         $fd = fopen ($this->getDirPath ($this->dir).DESCRIPTION_FILE, 'r+');
         while ($line = fgets ($fd))  {
            if ( ereg ('^.*\|.*\|.*$', $line)) {
               list ($imgName, $imgDescription) = explode ('|', $line, 2);
               $desc [$imgName] = $imgDescription;
               unset ($tab);
            }
         }
         fclose ($fd);
      }
      return $desc;
   }


   /**
    * 
    */
   function addAllImages () {

      $dir_fd = opendir ($this->getDirPath ($this->dir));
      $desc = $this->getDescriptions ();

      while ($current_file = readdir ($dir_fd)) {
         if ($current_file[0] != '.' 
             && !is_dir ($this->getImage ($this->dir, $current_file) )
             && eregi ('^.*(' . ALLOWED_FORMAT . ')$', $current_file)) {

            $this->list[$current_file] = new luxBumImage ($this->dir, $current_file);
            if (array_key_exists ($current_file, $desc)) {
               list ($imgDate, $imgDescription) = explode ("|", $desc[$current_file], 2);
               $this->list[$current_file]->setAllDescription ($imgDescription, $imgDate);
            }
         }
      }
      closedir ($dir_fd);
      sort ($this->list);
   }


   /**
    * 
    */
   function createOrMajDescriptionFile () {

      $desc = array ();

      // Recherche de la description dans toutes les descriptions
      if (is_file ($this->getDirPath ($this->dir) . DESCRIPTION_FILE)) {
         $fd = fopen ($this->getDirPath ($this->dir) . DESCRIPTION_FILE, 'r');
         while ($line = fgets ($fd)) {
            if (ereg ('^.*\|.*\|.*$', $line)) {
               $tab = explode ('|', $line, 2);
               $desc[] = $tab[0];
               unset ($tab);
            }
         }
      }

      $fd = fopen ($this->getDirPath ($this->dir) . DESCRIPTION_FILE, 'a');
      while (list (,$img) = each ($this->list)) {
         $name = $img->getImageName ();
         if (!in_array ($name, $desc)) {
            fputs ($fd, "$name||\n");
         }
      }
      fclose ($fd);
   }

   /**
    * 
    */
   function updateDescriptionFile () {
      files::deleteFile ($this->getDirPath ($this->dir) . DESCRIPTION_FILE, 'a');
      $fd = fopen ($this->getDirPath ($this->dir) . DESCRIPTION_FILE, 'a');

      for ($i = 0 ; $i < $this->getCount () ; $i++) {
         $img = $this->list[$i];
         $name = $img->getImageName ();
         $description = $img->getDescription ();
         $date = $img->getDate ();
         fputs ($fd, "$name|$date|$description\n");
      }

      fclose ($fd);
   }

   /**
    * 
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

   /**
    * 
    */
   function clearCache () {

      reset ($this->list);
      while (list (,$img) = each ($this->list)) {
         $img->clearCache ();
      }
   }

   /**
    *
    */
   function clearThumbCache () {

      reset ($this->list);
      while (list (,$img) = each ($this->list)) {
         $img->clearThumbCache ();
      }
   }

   /**
    *
    */
   function clearPreviewCache () {

      reset ($this->list);
      while (list (,$img) = each ($this->list)){
         $img->clearPreviewCache ();
      }
   }
}

?>