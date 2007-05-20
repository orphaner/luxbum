<?php

/**
 * 
 */
class Selection {
    
   /**
    * Enter description here...
    *
    * @var array
    */
   private $list = array();

   /**
    * Enter description here...
    *
    * @var integer
    */
   private $count = 0;
      
   /**
    * Singleton to create a new selection or reuse the existing selection.
    *
    * @return Selection the instance of the current selection
    */
   public function getInstance() {
      if (!isset($_SESSION['lbSelection'])) {
         return new Selection();
      }
      return $_SESSION['lbSelection'];
   }

   /**
    * Add a file to the current selection
    *
    * @param String $dir
    * @param String $img
    */
   public function addFile($dir, $file) {
      if(!isSet($this->list[$dir])){
         $this->list[$dir] = array();
      }
      $this->list[$dir][$file] = 1;
      $this->count++;
   }

   /**
    * Remove a file from the current selection
    *
    * @param String $dir
    * @param String $img
    */
   public function removeFile($dir, $file) {
      unset($this->list[$dir][$file]);
      $this->count--;
   }
   
   /**
    * Check if a file is in the current selection
    *
    * @param String $dir
    * @param String $img
    * @return boolean true if a file is in the current selection ; false otherwise
    */
   public function exists($dir, $file) {
      return isset($this->list[$dir][$file]);
   }
    
   /**
    * Returns the current selection into an array.
    *
    * @return array
    */
   public function toArray() {
      $t = array();
      $i = 0;
      foreach($this->list as $dir => $s){
         foreach($this->list[$dir] as $file => $ok){
            $t[$i]['dir']  = $dir;
            $t[$i]['file'] = $file;
            $i++;
         }
      }
      return $t;
   }
   
   /**
    * Number of object into the selection
    *
    * @return integer
    */
   public function getCount() {
      return $this->count;
   }
   
   /**
    * Enter description here...
    * @static
    * @param Selection $selection
    */
   static public function saveSelection($selection) {
      $_SESSION['lbSelection'] = $selection;
   }
   
   /**
    * Delete the entire selection
    * @static
    * @param Selection $selection
    */
   static public function deleteSelection() {
      $_SESSION['lbSelection'] = null;
   }
}

?>