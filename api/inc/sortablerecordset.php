<?php

  /**
   * @package inc
   */

class SortableRecordset extends Recordset2 {

   var $sortList = array();
   var $sortType;
   var $sortOrder;

   /**
    * Affecte le type du tri de l'index
    * @param String $sortType Type du tri de l'index
    */
   function setSortType ($sortType) {
      $this->sortType = $sortType;
   }
   
   /**
    * Retourne le type de tri de l'index
    * @return String Type du tri de l'index
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
  }

?>
