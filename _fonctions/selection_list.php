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
if (ereg ('^/selection_list-([0-9]+)\.html$', $_SERVER['QUERY_STRING'], $argv) ) {
   $page_courante = $argv[1];
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
// Page modelixe
$page = new ModeliXe('vignette.mxt');
$page->SetModeliXe();
definir_titre ($page, 'Voici votre sélection ('.$_SESSION['luxbum_selection_size'].') : ');
remplir_style ($page);
$page->MxText ('nom_dossier', 'Ma sélection');
$page->WithMxPath('liste', 'relative');


//on balle toute la selection dans un tableau contigu
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

$first_ok = false;
// Parcours des vignettes
for ($i = ($page_courante-1) * LIMIT_THUMB_PAGE  ; 
     $i < ($page_courante)   * LIMIT_THUMB_PAGE && $i < $_SESSION['luxbum_selection_size'] ; 
     $i++) {
   $name = $tab_selection[$i]['img'];
   $dir = $tab_selection[$i]['dir'];
   $file = new luxBumImage($dir, $name);
   $title = luxbum::niceName($tab_selection[$i]['dir'] .' - ' .$tab_selection[$i]['img']);

   if ($first_ok == false) {
	   $dir_defaut = $dir;
      $img_defaut = $name;
      $first_ok = true;
   }
   $page->MxAttribut ('styleCol', VIGNETTE_STYLE);
   $page->MxText     ('num_photo',($i+1).' / '.$_SESSION['luxbum_selection_size']);
   $page->MxAttribut ('vignette', $file->getThumbLink());
   $page->MxAttribut ('alt',      $title);
   $page->MxAttribut ('title',    $title);
   $page->MxUrl      ('lien',     lien_apercu ($dir, $name, $page_courante));
	$page->MxAttribut ('style', 'view_photo');
   $page->MxBloc ('', 'loop');
}

$page->WithMxPath ('', 'relative');


//----------------
// Affichage par page
$link = lien_selection("%d");
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