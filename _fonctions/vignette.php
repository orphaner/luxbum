<?php

// Indique si il faut chercher une image par défaut ou non
$first_ok = false;


//------------------------------------------------------------------------------
// Include
//------------------------------------------------------------------------------
include (FONCTIONS_DIR.'luxbum.class.php');
include (FONCTIONS_DIR.'utils/aff_page.inc.php');


//------------------------------------------------------------------------------
// Parsing des paramètres
//------------------------------------------------------------------------------
if (ereg ('^/vignette-([0-9]+)-(.*)-(.*)\.html$', $_SERVER['QUERY_STRING'], $argv) ) {
   $page_courante = $argv[1];
   $dir           = $argv[2];
   $img_defaut    = $argv[3];
   $first_ok = true;
}
else if (ereg ('^/vignette-([0-9]+)-(.*)\.html$', $_SERVER['QUERY_STRING'], $argv) ) {
   $page_courante = $argv[1];
   $dir           = $argv[2];
}
else {
   exit ('manque des paramètres.');
}

$page_courante++;

if (!verif::dir ($dir)) {
   exit ('nom de dossier incorrect !!');
}
// else if (!is_dir (luxbum::getDirPath ($dir))) {
//    exit ('dossier incorrect !!');
// }

// Vérif que la page est bonne
$theGallery = new luxBumGallery($dir);
$theGallery->addAllImages ();
$galleryCount = $theGallery->getCount ();

if (ceil ($galleryCount / LIMIT_THUMB_PAGE) < $page_courante) {
   exit ('page incorrecte !!');
}



//------------------------------------------------------------------------------
// Init
//------------------------------------------------------------------------------
// Variables


// Page modelixe
$page = new ModeliXe('vignette.mxt');
$page->SetModeliXe();
$niceDir = ucfirst (luxBum::niceName ($dir));
definir_titre ($page, $niceDir. ' - '. NOM_GALERIE);
remplir_style ($page);
$page->MxText ('nom_dossier', $niceDir);



//------------------------------------------------------------------------------
// Code principal
//------------------------------------------------------------------------------
$page->WithMxPath('liste', 'relative');

$theGallery->createOrMajDescriptionFile ();
$theGallery->getDescriptions ();


//----------------
// Affichage des vignettes
$i = 0;


// Parcours des vignettes
for ($i = ($page_courante-1) * LIMIT_THUMB_PAGE  ; 
     $i < ($page_courante)   * LIMIT_THUMB_PAGE && $i < $galleryCount ; 
     $i++) {
   $file     = $theGallery->list[$i];
   $name     = $file->getImageName ();
   $niceName = luxbum::niceName ($name);
   $title    = $niceName . ' - ' . ucfirst ($file->getDescription ());

   if ($first_ok == false) {
      $img_defaut = $name;
      $first_ok = true;
   }

   $page->MxAttribut ('styleCol', VIGNETTE_STYLE);
   $page->MxText     ('num_photo',($i+1).' / '.$galleryCount);
   $page->MxAttribut ('vignette', $file->getThumbLink());
   $page->MxAttribut ('alt',      $title);
   $page->MxAttribut ('title',    $title);
   $page->MxUrl      ('lien',     lien_apercu ($dir, $name, $page_courante));

   //@start upd dark 1.1 : changement du style dans les vignettes si photo selectionnee
   //@implique : ajout style "view_photo_selected" dans css et attribut "style" dans page modeliXe
   if (isSet($_SESSION['luxbum'][$dir][$name])) {
      $page->MxAttribut ('style', 'view_photo_selected');
   }
   else {
      $page->MxAttribut ('style', 'view_photo');
   }
   //@end upd dark 1.1
   $page->MxBloc ('', 'loop');
}


$page->WithMxPath ('', 'relative');


//----------------
// Affichage par page
$link = lien_vignette ("%d", $dir);
$start = $page_courante * LIMIT_THUMB_PAGE;
$AffPage = aff_page2 ($galleryCount, $page_courante-1, LIMIT_THUMB_PAGE, $start, $link);
$page->MxText ('aff_page', $AffPage);


//----------------
// Photo par défaut
$page->MxAttribut ('affichage', lien_apercu ($dir, $img_defaut, $page_courante));


//----------------
// Affichage de la page
$page->MxWrite();

?>