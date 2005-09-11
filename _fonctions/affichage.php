<?php

  //------------------------------------------------------------------------------
  // Include
  //------------------------------------------------------------------------------
include (FONCTIONS_DIR.'luxbum.class.php');
function verif_photo ($dir, $img) {
   if (!is_file (luxbum::getImage ($dir, $img))) {
      return false;
   }
   return true;
}


//------------------------------------------------------------------------------
// Parsing des paramètres
//------------------------------------------------------------------------------
// Méthode rewritée
if (USE_REWRITE == 'on') {
   if (!isset($_GET['photo']) || !isset($_GET['d']) || !isset($_GET['page']) ) {
      exit('manque des paramètres ON');
   }

   $file          = $_GET['photo'];
   $dir           = $_GET['d'];
   $page_courante = $_GET['page'];
}
// Méthode non rewritée
else  {
   if (ereg ('^/affichage-([0-9]+)-(.+)-(.+)\.html$', $_SERVER['QUERY_STRING'], $argv) ) {
      $page_courante = $argv[1];
      $dir           = $argv[2];
      $file          = $argv[3];
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
else if (!verif_photo ($dir, $file)) {
   exit ('nom de la photo incorrect !!');
}


//------------------------------------------------------------------------------
// Init
//------------------------------------------------------------------------------

// Page modelixe
$page = new ModeliXe('affichage.mxt');
$page->SetModeliXe();
remplir_style ($page);



//------------------------------------------------------------------------------
// Code principal
//------------------------------------------------------------------------------

$luxAff = new luxBumImage ($dir, $file);


//----------------
// Affichage de la photo
$page->MxAttribut ('photo', $luxAff->getAsPreview ());
$page->MxAttribut ('alt',   $luxAff->getImageName ());
$page->MxUrl      ('lien',  $luxAff->getImagePath ());


if ($luxAff -> findDescription () == true) {
   if ($luxAff -> getDate() != '') {
      $dateDesc = 'Le '.$luxAff -> getDate();

      if ($luxAff -> getDescription () != '' && $luxAff -> getDescription () != "\n") {
         $dateDesc .= ' - '. ucfirst ($luxAff -> getDescription ());
      }
   }
   else if ($luxAff -> getDescription () != '') {
      $dateDesc = ucfirst ($luxAff -> getDescription ());
   }

   $page->MxText ('desc', $dateDesc);
}

$page->MxAttribut ('title',  luxbum::niceName ($luxAff -> getImageName()) .' - '
                   . ucfirst ($luxAff -> getDescription ()));


//----------------
// Liens quand la page est hors de sa frame
$lien_redirect = lien_vignette_redirect ($page_courante, $dir, $luxAff->getImageName ());
$page->MxText ('redirect_script',   $lien_redirect);
$page->MxUrl  ('redirect_noscript', $lien_redirect);



//----------------
// Liens suivants et précédents

$nuxThumb = new luxBumGalleryList ($dir);
$nuxThumb->addAllImages ();
$galleryCount = $nuxThumb->getCount ();
$imageIndex = $nuxThumb->getImageIndex ($file);

$newBack = false;
$newForward = false;
if ($imageIndex % LIMIT_THUMB_PAGE == 0) {
   $newBack = true;
}

if ($imageIndex % LIMIT_THUMB_PAGE == LIMIT_THUMB_PAGE - 1) {
   $newForward = true;
}

$page_courante++;

// Une seule image dans la gallerie
if ($galleryCount == 1) {
   $page->MxBloc ('back', 'delete');
   $page->MxBloc ('forward', 'delete');
}

// Première image
else if ($imageIndex == 0) {
   $page->MxBloc ('back', 'delete');
   $page->MxUrl ('forward.lien', lien_apercu ($dir, $nuxThumb->list[$imageIndex+1]->getImageName (), $page_courante));
}

// Dernière image
else if ($imageIndex == $galleryCount-1) {
   if ($newBack == true) {
      $page->MxUrl ('back.lien',  lien_vignette_redirect ($page_courante-2, $dir, $nuxThumb->list[$imageIndex-1]->getImageName ()));
      $page->MxAttribut ('back.target', '_parent');
   }
   else {
      $page->MxUrl ('back.lien', lien_apercu ($dir, $nuxThumb->list[$imageIndex-1]->getImageName (), $page_courante));
      $page->MxAttribut ('back.target', '_self');
   }
   $page->MxBloc ('forward', 'delete');
}

// Les deux liens
else {
   if ($newBack == true) {
      $page->MxUrl ('back.lien',  lien_vignette_redirect ($page_courante-2, $dir, $nuxThumb->list[$imageIndex-1]->getImageName ()));
      $page->MxAttribut ('back.target', '_parent');
   }
   else {
      $page->MxUrl ('back.lien', lien_apercu ($dir, $nuxThumb->list[$imageIndex-1]->getImageName (), $page_courante));
      $page->MxAttribut ('back.target', '_self');
   }


   if ($newForward == true) {
      $page->MxUrl ('forward.lien',  lien_vignette ($page_courante, $dir));
      $page->MxAttribut ('forward.target', '_parent');
   }
   else {
      $page->MxUrl ('forward.lien', lien_apercu ($dir, $nuxThumb->list[$imageIndex+1]->getImageName (), $page_courante));
      $page->MxAttribut ('forward.target', '_self');
   }
}



//----------------
// Affichage de la page
$page->MxWrite();

?>