<?php
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
//include (CONF_FIR.'config_manager.php');
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
$session_timeout = 5 * 60; // TimeOut de 5minutes


if ($logued == 1) {
   if ((time() - $_SESSION['last_access'] ) > $session_timeout) {
      $page = new ModeliXe ('login.mxt');
      $page->SetModeliXe ();
      $_SESSION = array ();
      session_destroy ();
      $page->MxAttribut ('action', ADMIN_FILE.'?p=login');
      $page->MxAttribut ('message_id', 'message_ko');
      $page->MxText ('message', 'TIMEOUT: Vous �tes d�sormais d�connect�.');
   }

   else if ($_SERVER['REMOTE_ADDR'] != $_SESSION['ipaddr']) {
      $page = new ModeliXe ('login.mxt');
      $page->SetModeliXe ();
      $_SESSION = array ();
      session_destroy ();
      $page->MxAttribut ('action', ADMIN_FILE.'?p=login');
      $page->MxAttribut ('message_id', 'message_ko');
      $page->MxText ('message', 'IP: Nan mais tu r�ve. Tu es d�sormais d�connect�.');
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

      if (MANAGER_USERNAME == $username && MANAGER_PASSWORD == md5 ($password)) {
         $_SESSION['logued'] = true;
         $_SESSION['last_access']=time();
         $_SESSION['ipaddr'] = $_SERVER['REMOTE_ADDR']; 
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