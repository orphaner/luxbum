<?php
/*
 * Created on 19 janv. 2006
 *
 */
 
include (FONCTIONS_DIR.'luxbum.class.php');

//------------------------------------------------------------------------------
// Parsing des paramètres
//------------------------------------------------------------------------------
// Méthode rewritée
if (USE_REWRITE == 'on') {
   if (!isset($_GET['d'])) {
      exit('manque des paramètres ON');
   }

   $dir           = $_GET['d'];
}
// Méthode non rewritée
else  {
   if (ereg ('^/slideshow-(.+)\.html$', $_SERVER['QUERY_STRING'], $argv) ) {
      $dir           = $argv[1];
   }
   else  {
      exit ('manque des paramètres OFF');
   }
}

if (!verif_dir ($dir)) {
   exit ('nom de dossier incorrect !!');
}
else if (!is_dir (luxbum::getDirPath ($dir))) {
   exit ('dossier incorrect !!');
}

//------------------------------------------------------------------------------
// Init
//------------------------------------------------------------------------------

// Page modelixe
$page = new ModeliXe('slideshow.mxt');
$page->SetModeliXe();
$page->MxText ('defaultspeed', 4);
$page->MxText ('slide_width', '650px');
$page->MxText ('slide_height_full', '510px');
$page->MxText ('slide_height', '485px');

$nuxThumb = new luxBumGalleryList ($dir);
$nuxThumb->addAllImages ();
$galleryCount = $nuxThumb->getCount ();


for ($i = 0 ; $i < $nuxThumb->getCount() ; $i++) {
   $file = $nuxThumb->list[$i];
   $page->MxText ('photosSRC.i', $i);
   $page->MxText ('photosSRC.photo', $file->getAsPreview());
   $page->MxBloc ('photosSRC', 'loop');
}


$page->MxWrite();
?>