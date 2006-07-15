<?php
/*
 * Created on 19 janv. 2006
 *
 */
 
include (FONCTIONS_DIR.'luxbum.class.php');

//------------------------------------------------------------------------------
// Parsing des paramètres
//------------------------------------------------------------------------------
if (ereg ('^/slideshow-(.+)\.html$', $_SERVER['QUERY_STRING'], $argv) ) {
   $dir = $argv[1];
}
else  {
   exit ('manque des paramètres OFF');
}


if (SHOW_SLIDESHOW == 'off') {
   exit ('Les diaporamas ne sont pas activés');
}
else if (!verif_dir ($dir)) {
   exit ('nom de dossier incorrect !!');
}
// else if (!is_dir (luxbum::getDirPath ($dir))) {
//    exit ('dossier incorrect !!');
// }

//------------------------------------------------------------------------------
// Init
//------------------------------------------------------------------------------

// Page modelixe
$page = new ModeliXe('slideshow.mxt');
$page->SetModeliXe();
$page->MxText ('photosDir', PHOTOS_DIR);
$page->MxText ('dir', $dir);
$page->MxText ('defaultspeed', SLIDESHOW_TIME);
$page->MxAttribut ('defaultspeed', SLIDESHOW_TIME);
if (SLIDESHOW_FADING == 'on') {
   $page->MxText ('fading', 'true');
   $page->MxAttribut ('fading', 'checked');
}
else {
   $page->MxText ('fading', 'false');
   $page->MxAttribut ('fading', '');
}
$page->MxText ('slide_width', '650px');
$page->MxText ('slide_height_full', '510px');
$page->MxText ('slide_height', '485px');

$nuxThumb = new luxBumGallery($dir);
$nuxThumb->addAllImages ();
$galleryCount = $nuxThumb->getCount ();


for ($i = 0 ; $i < $nuxThumb->getCount() ; $i++) {
   $file = $nuxThumb->list[$i];
   $page->MxText ('photosSRC.i', $i);
   $page->MxText('photosSRC.photo', $file->getPreviewLink());
   $page->MxBloc ('photosSRC', 'loop');
}


$page->MxWrite();
?>