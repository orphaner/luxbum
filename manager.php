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
include ('_conf/config_manager.php');


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



if ($logued == 1) {

   switch ($p) {

      // D�connexion
      case 'logout':
         // Page modelixe
         $page = new ModeliXe ('login.mxt');
         $page->SetModeliXe();
         $_SESSION['logued'] = false;
         $page->MxAttribut ('action', ADMIN_FILE.'?p=login');
         $page->MxAttribut ('message_id', 'message_ok');
         $page->MxText ('message', 'Vous �tes d�sormais d�connect�.');
         break;
                
         // Inclusion du module d'admin
      default:

         // Page modelixe
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
         $count = count($pages);
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
         break;
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