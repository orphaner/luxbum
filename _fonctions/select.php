<?php


if (SHOW_SELECTION == 'off') {
   exit ('Sélection désactivée.');
}


//------------------------------------------------------------------------------
// Parsing des paramètres
//------------------------------------------------------------------------------

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

header('Location: '.lien_vignette_redirect($page_courante, $dir, $img));
?>