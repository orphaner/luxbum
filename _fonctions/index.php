<?php

include (FONCTIONS_DIR.'luxbum.class.php');


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
$nuxIndex = new  luxBumIndex ();
$nuxIndex->addAllGallery ();
$nuxIndex->gallerySort ();

// Galerie vide
if ($nuxIndex->getGalleryCount () == 0) {
   $page->MxBloc ('dossiers', 'modify', STRUCTURE_DIR.'index_vide.mxt');
}

// Affichage de l'index des galeries
else {
   $cpt = 1;
   $loop = 0;

   // Parcours des galeries
   while (list (,$gallery) = each ($nuxIndex->galleryList)) {
      $niceName = $gallery->getNiceName ();
      $name     = $gallery->getName ();
      $count    = $gallery->getCount ();
      $taille   = $gallery->getNiceSize ();
      $thumb    = $gallery->getThumbPath ();

      $page->MxText     ('dossiers.col'.$cpt.'.nom',      $niceName);
      $page->MxAttribut ('dossiers.col'.$cpt.'.alt',      $niceName);
      $page->MxAttribut ('dossiers.col'.$cpt.'.title',    $niceName);
      $page->MxAttribut ('dossiers.col'.$cpt.'.title2',   $niceName);
      $page->MxAttribut ('dossiers.col'.$cpt.'.apercu',   $thumb);
      $page->MxUrl      ('dossiers.col'.$cpt.'.lien',     lien_vignette (0, $name));
      if (SHOW_SLIDESHOW == 'on') {
         $page->MxText     ('dossiers.col'.$cpt.'.slideshow.slideshow',lien_slideshow ($name));
      }
      else {
         $page->MxBloc ('dossiers.col'.$cpt.'.slideshow', 'delete');
      }
      $page->MxText     ('dossiers.col'.$cpt.'.nb_photo', $count);
      $page->MxText     ('dossiers.col'.$cpt.'.taille',   $taille);

      $cpt++;
      $loop++;
      if ($loop%3 == 0) {
         $page->MxBloc ('dossiers', 'loop');
         $cpt = 1;
      }
   }

   // On vide les blocs vides
   while ($cpt <= 3) {
      $page->MxBloc ('dossiers.col'.$cpt, 'modify',' ');
      $cpt++;
   }

   // Loop si 3 blocs
   if ($loop%3 != 0) {
      $page->MxBloc ('dossiers', 'loop');
   }
}



$page->MxWrite();

?>