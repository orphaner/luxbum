<?php

/*
# ***** BEGIN LICENSE BLOCK *****
#
# The Original Code is DotClear Weblog.
#
# The Initial Developer of the Original Code is
# Olivier Meunier.
# Portions created by the Initial Developer are Copyright (C) 2003
# the Initial Developer. All Rights Reserved.
#
# Contributor(s):
#    Loïc d'Anterroches
*/

  /** 
   * @package inc
   */
class inc_Recordset implements  Iterator 
{
   var $arrayField = array();

   var $arrayList = array(); // tableau contenant la liste
   var $intIndex; //index pour parcourir les enregistrements
   private $intRowCount=0; // nombre d'enregistrements
   var $endOfPage=-1;
   var $startOfPage=-1;
   var $defaultIndex=-1;


   function inc_Recordset() {
      $this->intIndex = 0;
   }

   function setStartOfPage ($startOfPage) {
      $this->startOfPage = $startOfPage;
      $this->move($startOfPage);
   }

   function setEndOfPage ($endOfPage) {
      $this->endOfPage = $endOfPage;
   }

   function setDefaultIndex($defaultIndex) {
      $this->defaultIndex = $defaultIndex;
   }

   function getDefaultIndex() {
      return $this->defaultIndex;
   }

   function setField($key, $data) {
      $this->arrayField[$key] = $data;
   }

   function getIntIndex() {
      return $this->intIndex;
   }

   function getIntRowCount() {
      return $this->intRowCount;
   }

   function fg($key) {
      if (array_key_exists($key, $this->arrayField)) {
         return $this->arrayField[$key];
      }
      return false;
   }

   function addToList($val) {
      $this->arrayList[] = $val;
      return $this->intRowCount++;
   }

   /**
    * Get the current row of data.
    * @return CommonFile
    */
   function f() {
      //if (/*!$this->EOP() && */!$this->BOF() && !$this->EOF()) {
         return $this->arrayList[$this->intIndex];
      //}
      //return false;
   }

   function getDefault () {
      if ($this->defaultIndex == -1) {
         return false;
      }
      if (!array_key_exists($this->defaultIndex, $this->arrayList)) {
         return false;
      }
      return $this->arrayList[$this->defaultIndex];
   }

   function moveStart() {
      $this->intIndex = 0;
      return true;
   }

   function moveStartPage() {
      if ($this->startOfPage == -1) {
         $this->intIndex = 0;
      }
      else {
         $this->intIndex = $this->startOfPage;
      }
      return true;
   }

   function moveEnd() {
      $this->intIndex = ($this->intRowCount-1);
      return true;
   }

   function moveNext() {
      if (!empty($this->arrayList) && !$this->EOF()) {
         $this->intIndex++;
         return true;
      }
      else {
         return false;
      }
   }

   function movePrev() {
      if (!empty($this->arrayList) && $this->intIndex > 0) {
         $this->intIndex--;
         return true;
      }
      else {
         return false;
      }
   }

   function move($index) {
      if (!empty($this->arrayList)
          && $this->intIndex >= 0
          && $index < $this->intRowCount) {
         $this->intIndex = $index;
         return true;
      }
      else {
         return false;
      }
   }

   function isFirst () {
      return ($this->defaultIndex == 0);
   }
   function isLast () {
      return ($this->defaultIndex+1 == $this->intRowCount);
   }

   function BOF() {
      return ($this->intIndex == -1 || $this->intRowCount == 0);
   }

   function EOF() {
      return ($this->intIndex == $this->intRowCount);
   }

   function EOP() {
      return ($this->EOF() || $this->intIndex == $this->endOfPage);
   }

   function isEmpty() {
      return ($this->intRowCount == 0);
   }

   function reset() {
      reset ($this->arrayList);
   }
   
   
    function current () {
       return $this->f();
    }
 	function key () {
 	   return $this->intIndex;
 	}
 	function next () {
 	   $this->intIndex++;
 	   //$this->moveNext();
 	   return $this->f();
 	}
 	function rewind () {
 	   $this->moveStartPage();
 	}
 	function valid () {
 	   return !($this->intIndex == $this->intRowCount || $this->intIndex == $this->endOfPage);//$this->EOP();
 	}
}

?>