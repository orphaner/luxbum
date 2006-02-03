<?php

if (SHOW_SELECTION == 'off') {
   exit ('Sélection désactivée.');
}

//------------------------------------------------------------------------------
// Include
//------------------------------------------------------------------------------
include (FONCTIONS_DIR.'luxbum.class.php');
include (FONCTIONS_DIR.'utils/aff_page.inc.php');

//------------------------------------------------------------------------------
// Parsing des paramètres
//------------------------------------------------------------------------------
$page_courante = 0;
// Méthode rewritée
if (USE_REWRITE == 'on') {
   if (isset($_GET['page'])) {
       $page_courante = $_GET['page'];
	}
}
// Méthode non rewritée
else  {
   if (ereg ('^/selection_list-([0-9]+)\.html$', $_SERVER['QUERY_STRING'], $argv) ) {
      $page_courante = $argv[1];
   }
}
$page_courante++;


// Vérif que la page est bonne
if (!isset($_SESSION['luxbum_selection_size'])) {
   header('Location: '.INDEX_FILE);
}
else if ($_SESSION['luxbum_selection_size']==0) {
   header('Location: '.INDEX_FILE);
}
else if (ceil ($_SESSION['luxbum_selection_size'] / LIMIT_THUMB_PAGE) < $page_courante) {
   header('Location: '.INDEX_FILE);
}




//------------------------------------------------------------------------------
// Init
//------------------------------------------------------------------------------
// Variables


// Page modelixe
$page = new ModeliXe('vignette.mxt');
$page->SetModeliXe();
//$niceDir = ucfirst (luxBum::niceName ($dir));
definir_titre ($page, 'Voici votre sélection ('.$_SESSION['luxbum_selection_size'].') : ');
remplir_style ($page);
$page->MxText ('nom_dossier', 'Ma sélection');



//------------------------------------------------------------------------------
// Code principal
//------------------------------------------------------------------------------
$page->MxBloc ('liste', 'modify', STRUCTURE_DIR.$template);
$page->WithMxPath('liste', 'relative');


//on balle toutes la selection dans un tableau contigu
$tab_selection = array();
$i = 0;

foreach($_SESSION['luxbum'] as $d=>$s){
	foreach($_SESSION['luxbum'][$d] as $img=>$ok){
		$tab_selection[$i]['dir'] = $d;
		$tab_selection[$i]['img'] = $img;
		$i++;
	}
}

//----------------
// Affichage des vignettes
$i = 0;
$cpt = 1;
$loop = 0;

$first_ok = false;
// Parcours des vignettes
for ($i = ($page_courante-1) * LIMIT_THUMB_PAGE  ; 
     $i < ($page_courante)   * LIMIT_THUMB_PAGE && $i < $_SESSION['luxbum_selection_size'] ; 
     $i++) {
   $name = $tab_selection[$i]['img'];//$name     = $file->getImageName ();
   $dir = $tab_selection[$i]['dir'];
   //$file     = $tab_selection;//$nuxThumb->list[$i];
   $file = new luxBumImage($dir, $name);
   $title = $tab_selection[$i]['dir'] .' - ' .$tab_selection[$i]['img'];//$title    = $niceName . ' - ' . ucfirst ($file->getDescription ());

   if ($first_ok == false) {
	  $dir_defaut = $dir;
      $img_defaut = $name;
      $first_ok = true;
   }
   $page->MxText     ('num_photo'.$cpt,              ($i+1).' / '.$_SESSION['luxbum_selection_size']);
   $page->MxAttribut ('view_photo'.$cpt.'.vignette', $file->getAsThumb (IMG_W, IMG_H));
   $page->MxAttribut ('view_photo'.$cpt.'.alt',      $title);
   $page->MxAttribut ('view_photo'.$cpt.'.title',    $title);
   $page->MxUrl      ('view_photo'.$cpt.'.lien',     lien_apercu ($dir, $name, $page_courante));
   
   //@start upd dark 1.1 : changement du style dans les vignettes si photo selectionnee
   //@implique : ajout style "view_photo_selected" dans css et attribut "style" dans page modeliXe
   if(isSet($_SESSION['luxbum'][$dir][$name])){
	 $page->MxAttribut('view_photo'.$cpt.'.style', 'view_photo_selected');
   }else{
	 $page->MxAttribut('view_photo'.$cpt.'.style', 'view_photo');
   }
   //@end upd dark 1.1
  
	$page->MxAttribut('view_photo'.$cpt.'.style', 'view_photo');
   $cpt++;
   $loop++;
   if ($loop % NB_COL == 0) {
      $page->MxBloc ('', 'loop');
      $cpt = 1;
   }
}


// On vide les blocs vides
while ($cpt <= NB_COL) {
   $page->MxBloc ('view_photo'.$cpt, 'modify',' ');
   $cpt++;
}

// Loop si 3 blocs
if ($loop % NB_COL != 0) {
   $page->MxBloc ('', 'loop');
}

$page->WithMxPath ('', 'relative');


//----------------
// Affichage par page
$link = lien_selection("%d"); //lien_vignette ("%d", $dir);
$start = $page_courante * LIMIT_THUMB_PAGE;
$AffPage = aff_page2 ($_SESSION['luxbum_selection_size'], $page_courante-1, LIMIT_THUMB_PAGE, $start, $link);
$page->MxText ('aff_page', $AffPage);


//----------------
// Photo par défaut
$page->MxAttribut ('affichage', lien_apercu ($dir_defaut, $img_defaut, $page_courante));


//----------------
// Affichage de la page
$page->MxWrite();

?>