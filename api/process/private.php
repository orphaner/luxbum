<?php


  /**
   * @package process
   */
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
      $this->pass = sha1($pass);
   }
}

class PassPost  {
   var $login;
   var $password;
   private $posted = false;

   function getLogin() {
      return $this->login;
   }
   function getPassword(){
      return $this->password;
   }
   function setLogin($login) {
      $this->login = $login;
   }
   function setPassword($password) {
      $this->password = $password;
   }
   var $errors = array ();

   function PassPost() {
   }

   function fillFromPost() {
      $this->posted = true;
      
      // login, mandatory
      if (isset ($_POST['login']) && $_POST['login'] != '') {
         $this->setLogin (protege_input ($_POST['login']));
      }
      else {
         $this->errors['login'] = _('Champ vide !!!');
      }

      // password, mandatory
      if (isset ($_POST['password']) && $_POST['password'] != '') {
         $this->setPassword (protege_input ($_POST['password']));
      }
      else {
         $this->errors['password'] = _('Champ vide !!!');
      }
   }

   /**
    * 
    */
   function getError($champ) {
      if (array_key_exists ($champ, $this->errors)) {
         return $this->errors[$champ];
      }
      return '';
   }

   /**
    * 
    */
   function isValidForm () {
      return (count ($this->errors) == 0);
   }
   
   /**
    * 
    */
   function getPosted() {
      return $this->posted;
   }
}


  /**
   * @package process
   */
class PrivateManager
{

   var $list = array();


   function PrivateManager () {
   }

   /**
    * Singleton permettant de charger un PrivateManager
    */
   function &getInstance() {
      static $instance;
      if (!$instance) {
         if (is_file (PHOTOS_DIR.PASS_FILE)) {
            $instanceSerial = implode ("", @file (PHOTOS_DIR.PASS_FILE));
            $instance = unserialize ($instanceSerial);
         }
         else {
            $instance = new PrivateManager();
         }
      }
      return $instance;
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
      $dir = files::removeTailSlash($dir);
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
    */
   function isPrivateExact($dir) {
      $dir = files::removeTailSlash($dir);
      reset($this->list);
      while (list($key, $val) = each($this->list)) {
         if ($key == $dir) {
            return true;
         }
      }
      return false;
   }

   /**
    * @param String dir
    */
   function getPrivateEntry($dir) {
      $dir = files::removeTailSlash($dir);
      reset($this->list);
      while (list($key, $val) = each($this->list)) {
         if ($this->startsWith($key, $dir)) {
            return $key;
         }
      }
      return null;
   }

   /**
    * @param String dir
    */
   function isUnlocked($dir) {
      $dir = files::removeTailSlash($dir);
      // A manager is loggued in
      if (isset($_SESSION['manager']) && $_SESSION['manager']) {
         return true;
      }
      
      $key = $this->getPrivateEntry($dir);
      // The gallery is not private, so it is unlocked
      if ($key == null) {
         return true;
      }
      // The gallery is unlocked
      if (isset($_SESSION['Private'][$key]) && $_SESSION['Private'][$key] == true){
         return true;
      }
      return false;
   }

   /**
    * @param String dir
    */
   function isLocked($dir) {
      $dir = files::removeTailSlash($dir);
      return !$this->isUnlocked($dir);
   }

   /**
    * @param String dir
    */
   function unlockDir($dir, $passForm) {
      $dir = files::removeTailSlash($dir);
      $key = $this->getPrivateEntry($dir);
      if ($key == null) {
         return true;
      }
      $pass = $this->list[$key];
      if ($pass->isLoginOk($passForm->getLogin(), $passForm->getPassword())) {
         $_SESSION['Private'][$key] = true;
         return true;
      }
      return false;
   }

   /**
    * @static
    */
   function isLockedStatic($dir) {
      $dir = files::removeTailSlash($dir);
      $privateManager =& PrivateManager::getInstance();
      return $privateManager->isLocked($dir);
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
      $dir = files::removeTailSlash($dir);
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
      $dir = files::removeTailSlash($dir);
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
      $oldDir = files::removeTailSlash($oldDir);
      $newDir = files::removeTailSlash($newDir);
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