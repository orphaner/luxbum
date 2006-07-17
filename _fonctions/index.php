<?php

include (FONCTIONS_DIR.'luxbum.class.php');

//------------------------------------------------------------------------------
// Parsing des paramètres
//------------------------------------------------------------------------------
if (ereg ('^/ssgal-(.*)\.html$', $_SERVER['QUERY_STRING'], $argv) ) {
   $photoDir = files::removeTailSlash($argv[1]);
   verif::isDir($photoDir);
}
else {
   $photoDir = '';
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
   $page->MxBloc ('dossiers', 'modify', 
                  'Il n\'y a aucune galerie à consulter.');
}

// Affichage de l'index des galeries
else {

   // Affichage de la barre de navigation dans les galeries récursives
   if ($photoDir == '') {
      $page->MxBloc ('menunav', 'delete');
   }
   else {
      $list = split('/', $photoDir);
      $concat = '';
      for ($i = 0 ; $i < count($list) ; $i++) {
         $concat = $concat.$list[$i].'/';
         $page->MxText('menunav.nav.nom_dossier', $list[$i]);
         $page->MxUrl('menunav.nav.lien',  lien_sous_galerie($concat));
         $page->MxBloc('menunav.nav', 'loop');
      }
   }




   $page->WithMxPath('dossiers', 'relative');
  
   // Parcours des galeries
   while (list (,$gallery) = each ($nuxIndex->galleryList)) {

      $niceName = $gallery->getNiceName ();
      $name     = $gallery->getName ();
      $dir      = $gallery->getDir();

      $page->MxText     ('nom',      $niceName);
      $page->MxAttribut ('alt',      $niceName);
      $page->MxAttribut ('title',    $niceName);
      $page->MxAttribut ('apercu',   $gallery->getIndexLink());

      if ($gallery->getCount () == 0) {
         $page->MxBloc ('slideshow', 'delete');
         $page->MxBloc ('consulter', 'delete');
         $page->MxBloc ('infos', 'delete');
      }
      else {
         $page->MxAttribut ('consulter.title2',   $niceName);
         $page->MxUrl      ('consulter.lien',     lien_vignette (0, $dir));
         $page->MxUrl      ('lien',     lien_vignette (0, $dir));

         if (SHOW_SLIDESHOW == 'on') {
            $page->MxText     ('slideshow.slideshow',lien_slideshow ($dir));
         }
         else {
            $page->MxBloc ('slideshow', 'delete');
         }

         $page->MxText     ('infos.nb_photo', $gallery->getCount ());
         $page->MxText     ('infos.taille',   $gallery->getNiceSize ());
      }

      // Lien pour afficher la page de sous galerie
      if ($gallery->hasSubGallery()) {
         $page->MxUrl('ssgalerie.lien', lien_sous_galerie($gallery->getName()));
         $page->MxUrl('lien',lien_sous_galerie($gallery->getName()));
      }
      else {
         $page->MxBloc('ssgalerie','delete');
      }
      
      $page->MxBloc ('', 'loop');
   }
}


$page->MxWrite();

?>