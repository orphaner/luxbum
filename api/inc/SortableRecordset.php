<?php

/**
 * @package inc
 */
class inc_SortableRecordset extends inc_Recordset {

   var $sortList = array();
   var $sortType;
   var $sortOrder;

   /**
    * Set the sort clause
    * @param String $sortType Sort clause
    */
   function setSortType ($sortType) {
      $this->sortType = $sortType;
   }
    
   /**
    * Return the sort clause
    * @return String Sort clause
    */
   function getSortType () {
      return $this->sortType;
   }
    
   /**
    * Set the sort order (asc / desc)
    * @param String $sortOrder Sort order
    */
   function setSortOrder ($sortOrder) {
      $this->sortOrder = $sortOrder;
   }
    
   /**
    * Revert the sort order
    * @return String Sort order (asc or desc)
    */
   function getSortOrder () {
      return $this->sortOrder;
   }



   /**
    * Sort Image Array will sort an array of Images based on the given key. The
    * key should correspond to any of the Image fields that can be sorted. If the
    * given key turns out to be NULL for an image, we default to the filename.
    *
    * @access private
    * @param images The array to be sorted.
    * @param sortType le type de tri : manuel, date, description
    * @param sortOrder
    * @return A new array of sorted images.
    */
   function sortRecordset($galleryList, $sortType, $sortOrder) {
      $newImageArray = array();
      $newImageArrayFailed = array();
      $realkey = null;
      $i = 0;

      //echo "sortType:$sortType - $sortOrder - ";
      foreach ($galleryList as $gallery) {
         $realkey = $this->getSortRealKey($gallery, $sortType);
         $newImageArray[$realkey] = $gallery;
         $i++;
      }
      //print_r ($newImageArray);

      // Now natcase sort the array based on the keys
      uksort ($newImageArray, "strnatcasecmp");
      uksort ($newImageArrayFailed, "strnatcasecmp");

      // Revert the sort order if necessary
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
    * Save the sort properties in a file who respect this format :
    * sortType\n
    * sortOrder\n
    * imgX en pos 1\n
    * imgX en pos n\n
    */
   function saveSort () {
      $list = $this->sortRecordset($this->arrayList, $this->sortType, $this->sortOrder);
      files::deleteFile ($this->getOrderFilePath(), 'a');
      if ($fd = fopen ($this->getOrderFilePath(), 'a')) {
         fputs ($fd, $this->sortType."\n");
         fputs ($fd, $this->sortOrder."\n");
         for ($i = 0 ; $i < count ($list) ; $i++) {
            $img = $list[$i];
            $name = $img->getFile();
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
      if (is_file ($this->getOrderFilePath())) {
         $fd = fopen ($this->getOrderFilePath(), 'r+');
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
}

?>