<?php
// if (ereg ('free.fr', $_SERVER["HTTP_HOST"]) &&  !@is_dir ('sessions')) {
//    @mkdir ('sessions');
// }
DEFINE ('PREFIX_SESSION',sha1($_SERVER['SCRIPT_FILENAME']).'_');
//session_id(md5($_SERVER['SCRIPT_FILENAME']));
session_start();
//echo $_REQUEST["PHPSESSID"].'<br>';
//echo md5($_SERVER['SCRIPT_FILENAME']);
if (isset($_SESSION[PREFIX_SESSION.'logued']) && $_SESSION[PREFIX_SESSION.'logued'] == true) {
   //if ($_SESSION['urlscript'] == $_SERVER['SCRIPT_FILENAME']) {
      $logued_check = true;
   /*}
   else {
      $logued_check = false;
   }*/
}
else {
   $logued_check = false;
}

// Pr définir le titre des pages (un peu concon)
function definir_titre (&$page, $titre_page) {
   $page->MxText('titre_page', $titre_page);
}

//------------------------------------------------------------------------------
// Includes
//------------------------------------------------------------------------------
include_once ('common.php');
include_once (CONF_DIR.'config_manager.php');
include_once (FONCTIONS_DIR.'mysql.inc.php');
$mysql = new MysqlInc (DB_HOST, DB_LOGIN, DB_PASSWORD, DB_NAME);


//------------------------------------------------------------------------------
// Constantes
//------------------------------------------------------------------------------
define('MX_TEMPLATE_PATH', ADMIN_STRUCTURE_DIR); //Précise le répertoire de template par défaut.


//------------------------------------------------------------------------------
// Parsing des paramètres
//------------------------------------------------------------------------------
$action = '';
if (isset ($_GET['action'])) {
   $action = $_GET['action'];
}

$p = '';
if (isset ($_GET['p'])) {
   $p = $_GET['p'];
}
$session_timeout = 30 * 60; // TimeOut de 30 minutes

define ('AUTH_METHOD', 'luxbum'); // luxbum ou dotclear
define ('DOTCLEAR_PATH', '../dotclear/'); // slash final doit être là
class auth {
   var $user;
   var $pass;
   var $level;
   
   function auth ($user, $pass) {
      $this->user = $user;
      $this->pass = $pass;
   }

   function checkUser () {
      $this->level = 9;
      return (MANAGER_USERNAME == $this->user && MANAGER_PASSWORD == md5 ($this->pass));
   }

   function isAdmin () {
      return $this->level == 9;
   }
}
class authDotclear extends auth {
   function checkUser () {
      $mysql = new MysqlInc (DB_HOST, DB_USER, DB_PASS, DB_DBASE);
      $mysql->DbConnect ();
      
      // Vérif user enregistré
      $sql_req = "SELECT COUNT(*) FROM ".DB_PREFIX."user "
         ."WHERE user_id='".$this->user."' AND user_pwd='".(md5($this->pass))."'";
      $sql_nb = $mysql->DbCount ($sql_req);

      if ($sql_nb == 1) {

         // Vérif admin ou non
         $sql_req = "SELECT user_level FROM ".DB_PREFIX."user "
            ."WHERE user_id='".$this->user."'";
         $mysql->DbQuery ($sql_req);
         $row = $mysql->DbNextRow ();
         $this->level = $row['user_level'];
      }

      $mysql->DbClose ();
      return ($sql_nb == 1);
   }
}

function isAdmin () {
   if (isset ($_SESSION[PREFIX_SESSION.'is_admin'])) {
      return $_SESSION[PREFIX_SESSION.'is_admin'];
   }
   return false;
}

function logout ($message, $ok) {
   $page = new ModeliXe ('login.mxt');
   $page->SetModeliXe ();
   while (list ($key,) = each($_SESSION)) {
      if (strpos ($key, PREFIX_SESSION)===0) {
         $_SESSION[$key] = '';
         unset($_SESSION['$key']);
      }
   }
   //$_SESSION = array ();
   //session_destroy ();
   $page->MxAttribut ('action', ADMIN_FILE.'?p=login');
   $page->MxAttribut ('message_id', 'message_'.$ok);
   $page->MxText ('message', $message);
   return $page;
}

if ($logued_check == true) {

   /* Time out */
   if ((time() - $_SESSION[PREFIX_SESSION.'last_access'] ) > $session_timeout) {
      $page = logout ('TIMEOUT: Vous êtes désormais déconnecté.', 'ko');
   }

   // Déconnexion
   else if ($p == 'logout') {
      $page = logout ('Vous êtes désormais déconnecté.', 'ok');
   }
       
   // Inclusion du module d'admin
   else {
      // Dernier accès de la page
      $_SESSION[PREFIX_SESSION.'last_access']=time();
      
      // Connection à la base de données
      if (SHOW_COMMENTAIRE == 'on' || (SHOW_COMMENTAIRE == 'off' && $mysql->testDbConnect())) {
         $mysql->DbConnect();
      }
         
      // Création de la page modelixe
      $page = new ModeliXe ('header.mxt');
      $page->SetModeliXe();
      if (!isAdmin ()) {
         $page->MxBloc ('isadmin', 'delete');
      }

      $pages = array(
         'liste_galeries',
         'galerie',
         'parametres',
         'outils',
         'commentaires',
         'cache'
         );
      $trouve = false;
      $i = 0;
      $count = count ($pages);
      while (!$trouve && $i != $count) {
         if ($p == $pages[$i]) {
            include ADMIN_DIR.'/'.$p.'.php';
            $trouve = true;
         }
         $i++;
      }
      if ($trouve == false) {
         include ADMIN_DIR.'liste_galeries.php';
      }
   }
}

else if ($logued_check == false) {

   // Formulaire de connexion
   $page = new ModeliXe('login.mxt');
   $page->SetModeliXe();
   $page->MxAttribut('action', ADMIN_FILE.'?p=login');

   if ($p == 'login') {
      $username = '';
      if (isset ($_POST['username'])) {
         $username = $_POST['username'];
      }
      $password = '';
      if (isset ($_POST['password'])) {
         $password = $_POST['password'];
      }

      if (AUTH_METHOD == 'dotclear') {
         include (DOTCLEAR_PATH.'conf/config.php');
         $auth = new authDotclear ($username, $password);
      }
      else {
         $auth = new auth ($username, $password);
      }

      if ($auth->checkUser () == true) {
         $_SESSION[PREFIX_SESSION.'logued'] = true;
         $_SESSION[PREFIX_SESSION.'last_access']=time();
         $_SESSION[PREFIX_SESSION.'ipaddr'] = $_SERVER['REMOTE_ADDR'];
         $_SESSION[PREFIX_SESSION.'urlscript'] = $_SERVER['SCRIPT_FILENAME'];
         $_SESSION[PREFIX_SESSION.'is_admin'] = $auth->isAdmin ();
         header ('location:manager.php');
      }
      else {
         $page->MxAttribut ('message_id', 'message_ko');
         $page->MxText ('message', 'Erreur. <br /> Essayez à nouveau :');
         $page->MxAttribut ('username_value', $username);
         $page->MxAttribut ('password_value', $password);
      }
   }
   else {
      $page->MxAttribut ('message_id', 'message_ok');
      $page->MxText ('message', 'Vous devez remplir les informations ci dessous pour accéder à la zone d\'administration.');
   }
}

if ($mysql) {
   $mysql->DbClose();
}
$page->MxWrite ();

?>