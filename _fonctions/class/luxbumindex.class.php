<?php 

  //==============================================================================
  // Classe luxBumIndex : Index de toutes les galeries
  //==============================================================================

  /**
   * Classe contenant l'index de toutes les galeries
   */
class luxBumIndex extends luxBum
{
   var $galleryList = array ();


   /**-----------------------------------------------------------------------**/
   /* Remplissage de la liste des galeries */
   /**-----------------------------------------------------------------------**/

   /**
    * Ajoute une galerie
    * @param name le nom de la galerie
    * @param preview le chemin vers une vignette de visualisation
    */
   function addGallery ($name) {
      $this->galleryList[$name] = new luxBumGallery ($name);
   }

   /**
    * Remplit la liste de toutes les galeries
    */
   function addAllGallery ($minImage = 1) {

      if (!is_dir (PHOTOS_DIR)) {
         return ;
      }

      /* Lecture de tous les dossiers de photos */
      $dir_fd = opendir (PHOTOS_DIR);
      $i = 0;

      while ($current_dir = readdir ($dir_fd)) {

         /* On chope ts les dossiers */
         if ($current_dir[0] != '.' && is_dir ($this->getDirPath ($current_dir))) {
            $trouve = false;
            $apercu_fd = opendir ($this->getDirPath ($current_dir));

            while (!$trouve && $current_file = readdir ($apercu_fd)) {
               if ($current_file[0] != '.' 
                   && !is_dir ($this->getImage ($current_dir, $current_file)) 
                   && eregi ('^.*(' . ALLOWED_FORMAT . ')$', $current_file)) {
                  $trouve = true;
               }
            }
            closedir ($apercu_fd);

            if ($trouve == true || ($minImage == 0 && $trouve == false)) {
               $this->addGallery ($current_dir);
            }
         }
         $i++;
      }

      closedir ($dir_fd);
   }

   /**
    * Trie les galeries.
    * Fonction créé pour éventuellement avoir des tris selon critères (date, taille, ...)
    */
   function gallerySort () {
      asort ($this->galleryList);
   }

   /**
    * Retourne le nombre de galeries
    */
   function getGalleryCount () {
      return count ($this->galleryList);
   }
}
?>