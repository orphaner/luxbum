<?php

//------------------------------------------------------------------------------
// Includes
//------------------------------------------------------------------------------
define ('API_DIR', '_fonctions/');
define ('CONF_DIR', '../conf/');
define ('LIB_DIR', '_librairies/');
define ('INDEX_FILE', 'index.php');
define ('ADMIN_FILE', 'manager.php');
define ('PHOTOS_DIR', 'photos/');
define ('THUMB_DIR', 'vignette/');
define ('COMMENT_DIR', '.comment/');
define ('PREVIEW_DIR', 'apercu/');
define ('DESCRIPTION_FILE', 'description.txt');
define ('ORDER_FILE', 'ordre.txt');
define ('DEFAULT_INDEX_FILE', 'defaut.txt');
define ('ALLOWED_FORMAT', 'jpg|jpeg|png|gif');
define ('PASS_FILE', 'pass.php');
define ('LOCALE_DIR', 'locales/');
define ('TEMPLATE_DIR', 'templates/');
define ('ADMIN_STRUCTURE_DIR', '_structure_manager/');
define ('ADMIN_DIR', '_fonctions_manager/');

include('../api/inc/recordset.php');
include('../api/inc/sortablerecordset.php');
include('../api/inc/l10n.php');

include('../api/process/processFactory.php');
include('../api/process/luxbumgallery.php');
include('../api/process/luxbumindex.php');
include('../api/process/private.php');
include('../api/process/commentaire.php');
include('../api/process/luxbumimage.php');

include('../api/inc/paginator.php');
include('../api/inc/imagetoolkit.php');
include('../api/inc/imagetoolkit.imagemagick.php');
include('../api/inc/imagetoolkit.gd.php');
include('../api/inc/aff_page.inc.php');
include('../api/inc/formulaires.php');
include('../api/inc/files.php');
include('../api/inc/verif.php');
include('../api/inc/image.meta.php');
include('../api/inc/zip.php');
include('../api/inc/upload.php');
include('../api/inc/dispatcher.php');

include('../api/ui/lib.frontend.php');
include('../api/ui/views.php');
include('../api/ui/link.php');
include('../api/process/luxbum.php');

include_once (CONF_DIR.'config_manager.php');
include_once (CONF_DIR.'config_auth.php');
include_once (LIB_DIR.'ModeliXe.php');
//$mysql = new MysqlInc (DBL_HOST, DBL_LOGIN, DBL_PASSWORD, DBL_NAME);


//------------------------------------------------------------------------------
// Constantes
//------------------------------------------------------------------------------
// Précise le répertoire de template par défaut.
define('MX_TEMPLATE_PATH', ADMIN_STRUCTURE_DIR); 

// Le préfixe des variables de session
define('PREFIX_SESSION',sha1($_SERVER['SCRIPT_FILENAME'].MANAGER_PASSWORD).'_');


session_start();
if (isset($_SESSION[PREFIX_SESSION.'logued']) && $_SESSION[PREFIX_SESSION.'logued'] == true) {
      $logued_check = true;
}
else {
   $logued_check = false;
}

// Pr définir le titre des pages (un peu concon)
function definir_titre (&$page, $titre_page) {
   $page->MxText('titre_page', $titre_page);
}


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

/**
 * Classe d'authentification Luxbum
 */
class auth {
   var $user;
   var $pass;
   var $level;
   
   /**
    * Constructeur par défaut
    * @param string $user Le nom d'utilisateur
    * @param string $pass Le mot de passe
    */
   function auth ($user, $pass) {
      $this->user = $user;
      $this->pass = $pass;
   }

   /**
    * Vérifie que l'utilisateur est correct et défini son niveau d'accès
    * @return boolean true / false : correct / incorrect
    */
   function checkUser () {
      $this->level = 9;
      return (MANAGER_USERNAME == $this->user && MANAGER_PASSWORD == md5 ($this->pass));
   }

   /**
    * Est ce que l'utilsateur courant est administrateur ?
    * @return boolean true / false : admin / pas admin
    */
   function isAdmin () {
      return $this->level == 9;
   }
}
/**
 * Classe d'authenfitication par dotclear
 * Surcharge la fonction checkUser() de la classe auth
 */
class authDotclear extends auth {
   function checkUser () {
      $mysqlDC = new MysqlInc (DB_HOST, DB_USER, DB_PASS, DB_DBASE);
      $mysqlDC->DbConnect ();
      
      // Vérif user enregistré
      $sql_req = "SELECT COUNT(*) FROM ".DB_PREFIX."user "
         ."WHERE user_id='".$this->user."' AND user_pwd='".(md5($this->pass))."'";
      $sql_nb = $mysqlDC->DbCount ($sql_req);

      if ($sql_nb == 1) {

         // Vérif admin ou non
         $sql_req = "SELECT user_level FROM ".DB_PREFIX."user "
            ."WHERE user_id='".$this->user."'";
         $res = $mysqlDC->DbQuery ($sql_req);
         $row = $mysqlDC->DbNextRow ($res);
         $this->level = $row['user_level'];
      }

      $mysqlDC->DbClose ();
      return ($sql_nb == 1);
   }
}

/**
 * Est  ce que l'utilsateur courant est administrateur ?
 * @return boolean true / false : admin / pas admin
 */
function isAdmin () {
   if (isset ($_SESSION[PREFIX_SESSION.'is_admin'])) {
      return $_SESSION[PREFIX_SESSION.'is_admin'];
   }
   return false;
}

/**
 * Déconnection de la zone d'administration
 */
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


// Si l'utilisateur est connecté
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
         'cache',
         'tri_galerie',
         'tri_index',
         'generer_cache'
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

// Si l'utilisateur est déconnecté
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


$page->MxWrite ();

?>