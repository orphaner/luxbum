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
// Méthode rewritée
if (USE_REWRITE == 'on') {
   if (!isset($_GET['page']) || !isset($_GET['d'])) {
      exit ('manque des paramètres');
   }
   $page_courante = $_GET['page'];
   $dir           = $_GET['d'];
   

   if (isset ($_GET['imgd'])) {
      $img_defaut = $_GET['imgd'];
      $first_ok = true;
   }
}
// Méthode non rewritée
else  {
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
}
$page_courante++;

if (!verif_dir ($dir)) {
   exit ('nom de dossier incorrect !!');
}
else if (!is_dir (luxbum::getDirPath ($dir))) {
   exit ('dossier incorrect !!');
}

// Vérif que la page est bonne
$nuxThumb = new luxBumGalleryList ($dir);
$nuxThumb->addAllImages ();
$galleryCount = $nuxThumb->getCount ();

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
$page->MxBloc ('liste', 'modify', STRUCTURE_DIR.$template);
$page->WithMxPath('liste', 'relative');

$nuxThumb->createOrMajDescriptionFile ();
$nuxThumb->getDescriptions ();


//----------------
// Affichage des vignettes
$i = 0;
$cpt = 1;
$loop = 0;


// Parcours des vignettes
for ($i = ($page_courante-1) * LIMIT_THUMB_PAGE  ; 
     $i < ($page_courante)   * LIMIT_THUMB_PAGE && $i < $galleryCount ; 
     $i++) {
   $file     = $nuxThumb->list[$i];
   $name     = $file->getImageName ();
   $niceName = luxbum::niceName ($name);
   $title    = $niceName . ' - ' . ucfirst ($file->getDescription ());

   if ($first_ok == false) {
      $img_defaut = $name;
      $first_ok = true;
   }

   $page->MxText     ('num_photo'.$cpt,              ($i+1).' / '.$galleryCount);
   $page->MxAttribut ('view_photo'.$cpt.'.vignette', $file->getAsThumb (IMG_W, IMG_H));
   $page->MxAttribut ('view_photo'.$cpt.'.alt',      $title);
   $page->MxAttribut ('view_photo'.$cpt.'.title',    $title);
   $page->MxUrl      ('view_photo'.$cpt.'.lien',     lien_apercu ($dir, $name, $page_courante));

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