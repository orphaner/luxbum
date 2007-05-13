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
   var $list = array();

   /**
    * Enter description here...
    *
    * @var integer
    */
   var $count = 0;
      
   /**
    * Enter description here...
    *
    * @return Selection the instance of the current selection
    */
   function getInstance() {
      //unset($_SESSION['lbSelection']);
      if (!isset($_SESSION['lbSelection'])) {
         return new Selection();
      }
      return $_SESSION['lbSelection'];
   }

   /**
    * Enter description here...
    *
    * @param string $dir
    * @param string $img
    */
   function addFile($dir, $file) {
      if(!isSet($this->list[$dir])){
         $this->list[$dir] = array();
      }
      $this->list[$dir][$file] = 1;
      $this->count++;
   }

   /**
    * Enter description here...
    *
    * @param string $dir
    * @param string $img
    */
   function removeFile($dir, $file) {
      unset($this->list[$dir][$file]);
      $this->count--;
   }
   
   /**
    * Enter description here...
    *
    * @param string $dir
    * @param string $img
    */
   function exists($dir, $file) {
      return isset($this->list[$dir][$file]);
   }
    
   /**
    * Enter description here...
    *
    * @return array
    */
   function toArray() {
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
   function getCount() {
      return $this->getCount();
   }
   
   /**
    * Enter description here...
    * @static
    */
   function saveSelection($selection) {
      $_SESSION['lbSelection'] = $selection;
   }
}

?>