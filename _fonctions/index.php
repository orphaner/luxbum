<?php

include (FONCTIONS_DIR.'luxbum.class.php');

//------------------------------------------------------------------------------
// Parsing des paramètres
//------------------------------------------------------------------------------
if (ereg ('^/ssgal-(.*)\.html$', $_SERVER['QUERY_STRING'], $argv) ) {
   $photoDir = $argv[1];
}
else {
   exit ('manque des paramètres.');
}

//------------------------------------------------------------------------------
// Init
//------------------------------------------------------------------------------

// Page modelixe
$page = new ModeliXe('index.mxt');
$page->SetModeliXe();

// Titre et h1 de la page 
$page->MxText ('nom_galerie', NOM_GALERIE);
definir_titre ($page, NOM_GALERIE);
remplir_style ($page);


//------------------------------------------------------------------------------
// Code principal
//------------------------------------------------------------------------------

// Création de l'objet contenant l'index
$nuxIndex = new  luxBumIndex ($photoDir);
$nuxIndex->addAllGallery ();

// Galerie vide
if ($nuxIndex->getGalleryCount () == 0) {
   $page->MxBloc ('dossiers', 'modify', 'Il n\'y a aucune galerie à consulter.');
}

// Affichage de l'index des galeries
else {
   $page->WithMxPath('dossiers', 'relative');
   
   // Parcours des galeries
   while (list (,$gallery) = each ($nuxIndex->galleryList)) {

      $niceName = $gallery->getNiceName ();
      $name     = $gallery->getName ();

      $page->MxText     ('nom',      $niceName);
      $page->MxAttribut ('alt',      $niceName);
      $page->MxAttribut ('title',    $niceName);
      $page->MxAttribut ('title2',   $niceName);
      $page->MxAttribut ('apercu',   $gallery->getIndexLink());
      $page->MxUrl      ('lien',     lien_vignette (0, $name));
      if (SHOW_SLIDESHOW == 'on') {
         $page->MxText     ('slideshow.slideshow',lien_slideshow ($name));
      }
      else {
         $page->MxBloc ('slideshow', 'delete');
      }
      $page->MxText     ('nb_photo', $gallery->getCount ());
      $page->MxText     ('taille',   $gallery->getNiceSize ());

      // Lien pour afficher la page de sous galerie
      if ($gallery->hasSubGallery()) {echo "ssgal:".$gallery->getName()."<br>";
         $page->MxUrl('ssgalerie.lien', lien_sous_galerie($gallery->getName()));
      }
      else {
         $page->MxBloc('ssgalerie','delete');
      }
      
      $page->MxBloc ('', 'loop');
   }
}


$page->MxWrite();

?>