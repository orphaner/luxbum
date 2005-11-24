<?php
// if (ereg ('free.fr', $_SERVER["HTTP_HOST"]) &&  !@is_dir ('sessions')) {
//    @mkdir ('sessions');
// }
session_start();

if (isset($_SESSION['logued']) && $_SESSION['logued'] == true) {
   $logued = true;
}
else {
   $logued = false;
}

// Pr d�finir le titre des pages (un peu concon)
function definir_titre (&$page, $titre_page) {
   $page->MxText('titre_page', $titre_page);
}

//------------------------------------------------------------------------------
// Includes
//------------------------------------------------------------------------------
include ('common.php');
include (CONF_DIR.'config_manager.php');


//------------------------------------------------------------------------------
// Constantes
//------------------------------------------------------------------------------
define('MX_TEMPLATE_PATH', ADMIN_STRUCTURE_DIR); //Pr�cise le r�pertoire de template par d�faut.


//------------------------------------------------------------------------------
// Parsing des param�tres
//------------------------------------------------------------------------------
$action = '';
if (isset ($_GET['action'])) {
   $action = $_GET['action'];
}

$p = '';
if (isset ($_GET['p'])) {
   $p = $_GET['p'];
}
$session_timeout = 30 * 60; // TimeOut de 10 minutes

define ('AUTH_METHOD', 'dotclear'); // luxbum ou dotclear
define ('DOTCLEAR_PATH', '../dotclear/'); // slash final doit �tre l�
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
      
      // V�rif user enregistr�
      $sql_req = "SELECT COUNT(*) FROM ".DB_PREFIX."user "
         ."WHERE user_id='".$this->user."' AND user_pwd='".(md5($this->pass))."'";
      $sql_nb = $mysql->DbCount ($sql_req);

      if ($sql_nb == 1) {

         // V�rif admin ou non
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
   if (isset ($_SESSION['is_admin'])) {
      return $_SESSION['is_admin'];
   }
   return false;
}

if ($logued == 1) {

   /* Time out */
   if ((time() - $_SESSION['last_access'] ) > $session_timeout) {
      $page = new ModeliXe ('login.mxt');
      $page->SetModeliXe ();
      $_SESSION = array ();
      session_destroy ();
      $page->MxAttribut ('action', ADMIN_FILE.'?p=login');
      $page->MxAttribut ('message_id', 'message_ko');
      $page->MxText ('message', 'TIMEOUT: Vous �tes d�sormais d�connect�.');
   }

   // D�connexion
   else if ($p == 'logout') {
      $page = new ModeliXe ('login.mxt');
      $page->SetModeliXe ();
      $_SESSION = array ();
      session_destroy ();
      $page->MxAttribut ('action', ADMIN_FILE.'?p=login');
      $page->MxAttribut ('message_id', 'message_ok');
      $page->MxText ('message', 'Vous �tes d�sormais d�connect�.');
   }
                
   // Inclusion du module d'admin
   else {
      $page = new ModeliXe ('header.mxt');
      $page->SetModeliXe();
      if (!isAdmin ()) {
         $page->MxBloc ('isadmin', 'delete');
      }

      $pages = array(
         'liste_galeries',
         'galerie',
         'parametres',
         'outils'
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

else if ($logued == false) {

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
         include (FONCTIONS_DIR.'mysql.inc.php');
         $auth = new authDotclear ($username, $password);
      }
      else {
         $auth = new auth ($username, $password);
      }

      if ($auth->checkUser () == true) {
         $_SESSION['logued'] = true;
         $_SESSION['last_access']=time();
         $_SESSION['ipaddr'] = $_SERVER['REMOTE_ADDR'];
         $_SESSION['is_admin'] = $auth->isAdmin ();
         header ('location:manager.php');
      }
      else {
         $page->MxAttribut ('message_id', 'message_ko');
         $page->MxText ('message', 'Erreur. <br /> Essayez � nouveau :');
         $page->MxAttribut ('username_value', $username);
         $page->MxAttribut ('password_value', $password);
      }
   }
   else {
      $page->MxAttribut ('message_id', 'message_ok');
      $page->MxText ('message', 'Vous devez remplir les informations ci dessous pour acc�der � la zone d\'administration.');
   }
}


$page->MxWrite ();

?>