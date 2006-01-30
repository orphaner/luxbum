<?php


if (SHOW_SELECTION == 'off') {
   exit ('Sélection désactivée.');
}

  /** dark a la barabarian**/

function getScriptURL () {
   $url = '';
   if ($_SERVER['SERVER_PORT'] == 80) {
      $url .= 'http://';
   }
   elseif ($_SERVER['SERVER_PORT'] == 443) {
      $url .= 'https://';
   }
   $url .= $_SERVER['SERVER_NAME'];

   $path = split ($_SERVER['REQUEST_URI'], '/');
   for ($i=0 ; $i < count ($path)-1 ; $i++) {
      $url .= '/'.$path[$i];
   }
   $url .= '/luxbum-0.4.4_upd_dark/';
   return $url;
}

//------------------------------------------------------------------------------
// Parsing des paramètres
//------------------------------------------------------------------------------
// Méthode rewritée
if (USE_REWRITE == 'on') {
   if (!isset($_GET['page']) || !isset($_GET['d'])) {
      exit ('manque des paramètres');
   }
   if( $_GET['p'] == 'select' ){
      $select = true;
   }else{
      $select = false;
   }
   $page_courante = $_GET['page'];
   $dir           = $_GET['d'];
   $img           = $_GET['imgd'];
}
// Méthode non rewritée
else  {
   if (ereg ('^/select-([0-9]+)-(.*)-(.*)\.html$', $_SERVER['QUERY_STRING'], $argv) ) {
      $page_courante = $argv[1];
      $dir           = $argv[2];
      $img                   = $argv[3];
      $select            = true;
   }
   else if (ereg ('^/unselect-([0-9]+)-(.*)-(.*)\.html$', $_SERVER['QUERY_STRING'], $argv) ) {
      $page_courante = $argv[1];
      $dir           = $argv[2];
      $img                   = $argv[3];
      $select            = false;
   }
   else {
      exit ('manque des paramètres.');
   }
}

if ($select == true){
   if(!isSet($_SESSION['luxbum'])){
      $_SESSION['luxbum'] = array();
   }
   if(!isSet($_SESSION['luxbum'][$dir])){
      $_SESSION['luxbum'][$dir] = array();
   }
   $_SESSION['luxbum'][$dir][$img] = 1;
   if(!isSet($_SESSION['luxbum_selection_size'])){
      $_SESSION['luxbum_selection_size'] = 1;
   }
   else{
      $_SESSION['luxbum_selection_size'] ++;
   }
}
else{
   unset($_SESSION['luxbum'][$dir][$img]);
   $_SESSION['luxbum_selection_size'] --;
}

//echo lien_apercu($dir, $img, ($page_courante+1));
//echo getScriptURL();
header('Location: '.lien_vignette_redirect($page_courante, $dir, $img));
//header('Location: '.INDEX_FILE.lien_apercu($dir, $img, ($page_courante+1)));

//header('Location: http://127.0.0.1/luxbum-0.4.4_upd_dark/index.php?/affichage-0-test-Photo_058.jpg.html');
?>