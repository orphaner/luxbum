<?php
/*
 * Created on 11 févr. 2006
 *
 */

//------------------------------------------------------------------------------
// Includes
//------------------------------------------------------------------------------
include (FONCTIONS_DIR.'luxbum.class.php');


//------------------------------------------------------------------------------
// Variables
//------------------------------------------------------------------------------
$str_critere = ADMIN_FILE. '?p=tri_index';



//------------------------------------------------------------------------------
// Objet de la galerie
//------------------------------------------------------------------------------
$nuxIndex = new  luxBumIndex ();
$nuxIndex->addAllGallery (0);

// Validation du formulaire
if (isset($_POST['sortableListsSubmitted'])) {
   
   // Parsing de l'ordre retourné
   parse_str($_POST['galleryOrder'], $inputArray);
   $inputArray = $inputArray['galeries'];
   $inputArray = array_flip ($inputArray);

   // On affecte l'ordre dans la liste des images
   for ($i=0 ; $i < $nuxIndex->getGalleryCount() ; $i++) {
      $name = $nuxIndex->galleryList[$i]->getName();
      $nuxIndex->galleryList[$i]->setSortPosition($inputArray[$name]);
   } 
   
   // Save
   $nuxIndex->setSortType('manuel');
   $nuxIndex->setSortOrder('asc');
   $nuxIndex->saveSort();

   if (isset($_POST['retourEdition']) && $_POST['retourEdition']=='on') {
      header('location: '.ADMIN_FILE);
   }
   
   // Recrée la galerie pr que l'ordre soit bon ici :)
   unset($nuxIndex);
   $nuxIndex = new  luxBumIndex ();
   $nuxIndex->addAllGallery (0);
}

//------------------------------------------------------------------------------
// Init
//------------------------------------------------------------------------------
// Page modelixe
definir_titre ($page, 'Tri de l\'index des galeries - LuxBum Manager');
$page->MxAttribut ('class_galeries', 'actif');
$page->MxBloc ('main', 'modify', ADMIN_STRUCTURE_DIR.'tri_index.mxt');
$page->WithMxPath ('main', 'relative');
$page->MxAttribut('actionTri', $str_critere);


// Parcours des galeries
$cpt = 1;
$loop = 0;
while (list (,$gallery) = each ($nuxIndex->galleryList)) {
   if ($gallery->getCount () > 0) {
      $thumb    = $gallery->getIndexLink();
   }
   else {
      $thumb = '_images/manager/vide.png';
   }
   $page->MxImage ('galeries.image', $thumb);
   $page->MxText('galeries.nom', $gallery->getNiceName());
   $page->MxText('galeries.nb_photo', $gallery->getCount());
   $page->MxText('galeries.taille', $gallery->getNiceSize());
   $page->MxAttribut('galeries.id', 'id_'.$gallery->getName());
   $page->MxBloc ('galeries', 'loop');
}
?>