<?php
/*
 * Created on 14 févr. 2006
 * @author Nicolas LASSALLE
 * 
 */
 
//------------------------------------------------------------------------------
// Includes
//------------------------------------------------------------------------------
include (FONCTIONS_DIR.'luxbum.class.php');


//------------------------------------------------------------------------------
// Paramètres
//------------------------------------------------------------------------------

if (!isset($_GET['d'])) {
   exit ('manque des paramètres');
}

$dir = $_GET['d'];


// Vérif du dossier
if ($dir == '') {
   $page->MxBloc ('main', 'modify', '<span class="erreur">ERREUR: Il faut choisir un dossier !!</span>');
   $page->MxWrite ();
   exit (0);
}

else if (!verif_dir ($dir) || !is_dir (luxbum::getDirPath ($dir))) {
   $page->MxBloc ('main', 'modify', '<span class="erreur">ERREUR: Nom de dossier incorrect !!</span>');
   $page->MxWrite ();
   exit (0);
}
else if (!files::isWritable (luxbum::getDirPath ($dir))) {
   $page->MxBloc ('main', 'modify', '<span class="erreur">ERREUR: Le dossier choisit doit être accessible en écriture !!</span>');
   $page->MxWrite ();
   exit (0);
}


//------------------------------------------------------------------------------
// Objet de la galerie
//------------------------------------------------------------------------------
$nuxThumb = new luxBumGallery($dir);
$nuxThumb->addAllImages ();


//------------------------------------------------------------------------------
// Init
//------------------------------------------------------------------------------
// Page modelixe
definir_titre ($page, 'Galerie : '.$dir.' - LuxBum Manager');
$page->MxAttribut ('class_galeries', 'actif');
$page->MxBloc ('main', 'modify', ADMIN_STRUCTURE_DIR.'generer_cache.mxt');
$page->WithMxPath ('main', 'relative');
$page->MxText('galerie', $dir);
$page->MxUrl('retourGalerie', ADMIN_FILE.'?p=galerie&amp;d='.$dir.'&amp;page=0');

$galleryCount = $nuxThumb->getCount ();

for ($i=0 ; $i < $galleryCount ; $i++) {
      //$file = $nuxThumb->list[$i];
   $page->MxImage ('vignettes.image', $nuxThumb->list[$i]->getThumbLink());
   $page->MxImage ('apercus.image', $nuxThumb->list[$i]->getPreviewLink());
   $page->MxBloc('vignettes', 'loop');
   $page->MxBloc('apercus', 'loop');
}


?>