<?php



include (PHOTOS_DIR.'p.php');
include_once(FONCTIONS_DIR.'class/files.php');

class Pass {
   var $dir;
   var $user;
   var $pass;

   function Pass($dir, $user, $pass) {
      $this->dir = $dir;
      $this->user = $user;
      $this->pass = sha1($pass);
   }

   function isLoginOK ($user, $pass) {
      return (($user == $this->user) && (sha1($pass) == $this->pass));
   }


   /**
    * @return
    */
   function getDir () {
      return $this->dir;
   }

   /**
    * @param String dir
    */
   function setDir ($dir) {
      $this->dir = $dir;
   }

   /**
    * @return
    */
   function getUser () {
      return $this->user;
   }

   /**
    * @param String user
    */
   function setUser ($user) {
      $this->user = $user;
   }

   /**
    * @param X pass
    */
   function setPass ($pass) {
      $this->pass = $pass;
   }
}


class PrivateManager
{

   var $list = array();


   function PrivateManager () {
      $this->load();
   }

   /**
    *
    */
   function &getInstance() {
      static $instance;
      if (!$instance) {
         $instance = new Private();
      }
      return $instance;
   }

   /**
    *
    */
   function load() {
      if (is_file (PHOTOS_DIR.PASS_FILE)) {
         $dede = implode ("", @file (PHOTOS_DIR.PASS_FILE));
         $this = unserialize ($dede);
      }
   }

   /**
    *
    */
   function save() {
      $passContent = serialize($this);
      files::deleteFile(PHOTOS_DIR.PASS_FILE);
      files::writeFile(PHOTOS_DIR.PASS_FILE, $passContent);
   }

   /**
    *  Java startsWith equivalent.
    *  @param[in] $what Searched prefix.
    *  @param[in] $where String which possibly starts with the prefix.
    *  @return TRUE if $where starts with $what, FALSE otherwise.
    */
   function startsWith ($what, $where) {
      if (strpos($where, $what) === 0) {
         return true;
      }
      return false;
   }

   /**
    * @param String dir
    */
   function isPrivate($dir) {
      reset($this->list);
      while (list($key, $val) = each($this->list)) {
         if ($this->startsWith($key, $dir)) {
            return true;
         }
      }
      return false;
   }

   /**
    * @param String dir
    * @param String user
    * @param String pass
    */
   function addPrivateGallery($dir, $user, $pass) {
      $aPass = new Pass($dir, $user, $pass);
      $this->list[$dir] = $aPass;
   }

   /**
    * @param String dir
    */
   function removePrivateGallery($dir) {
      if (array_key_exists($dir, $this->list)) {
         unset($this->list[$dir]);
      }
   }

   /**
    * @param String dir
    * @param String user
    * @param String pass
    */
   function updatePrivateGallery($dir, $user, $pass) {
      if (array_key_exists($dir, $this->list)) {
         $this->list[$dir]->setPass($pass);
         $this->list[$dir]->setUser($user);
      }
   }

   /**
    * @param String oldDir
    * @param String newDir
    */
   function renamePrivateGallery($oldDir, $newDir) {
      if (array_key_exists($oldDir, $this->list)) {
         $this->list[$newDir] = $this->list[$oldDir];
         unset($this->list[$oldDir]);
         $this->list[$newDir]->setDir($newDir);
      }
   }

   /**
    * 
    */
   function resetAll() {
      unset($this->list);
   }
}


?>