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
// Variables
//------------------------------------------------------------------------------
$str_critere = ADMIN_FILE. '?p=tri_galerie&amp;d='.$dir;



//------------------------------------------------------------------------------
// Objet de la galerie
//------------------------------------------------------------------------------
$nuxThumb = new luxBumGallery ($dir);
$nuxThumb->addAllImages ();
$galleryCount = $nuxThumb->getCount ();

// Validation du formulaire
if (isset($_POST['sortableListsSubmitted'])) {
   
   // Parsing de l'ordre retourné
   parse_str($_POST['imageOrder'], $inputArray);
   $inputArray = $inputArray['images'];
   $inputArray = array_flip ($inputArray);
   
   // On affecte l'ordre dans la liste des images
   for ($i=0 ; $i < $galleryCount ; $i++) {
      $name = $nuxThumb->list[$i]->getImageName();
      $nuxThumb->list[$i]->setSortPosition($inputArray[$name]);
   } 
   
   // Save
   $nuxThumb->setSortType('manuel');
   $nuxThumb->setSortOrder('asc');
   $nuxThumb->saveSort();

   if (isset($_POST['retourEdition']) && $_POST['retourEdition']=='on') {
      header('location: '.ADMIN_FILE.'?p=galerie&d='.$dir.'&page=0');
   }
   
   // Recrée la galerie pr que l'ordre soit bon ici :)
   unset($nuxThumb);
   $nuxThumb = new luxBumGallery ($dir);
   $nuxThumb->addAllImages ();
}

//------------------------------------------------------------------------------
// Init
//------------------------------------------------------------------------------
// Page modelixe
definir_titre ($page, 'Galerie : '.$dir.' - LuxBum Manager');
$page->MxAttribut ('class_galeries', 'actif');
$page->MxBloc ('main', 'modify', ADMIN_STRUCTURE_DIR.'tri_galerie.mxt');
$page->WithMxPath ('main', 'relative');
$page->MxAttribut('actionTri', $str_critere);
$page->MxUrl('retourGalerie', ADMIN_FILE.'?p=galerie&amp;d='.$dir.'&amp;page=0');
$page->MxText('galerie', $dir);


for ($i=0 ; $i < $galleryCount ; $i++) {
   $page->MxImage ('images.image', $nuxThumb->list[$i]->getThumbLink(), 
                   $nuxThumb->list[$i]->getImageName(), 'id="id_'.$nuxThumb->list[$i]->getImageName().'"');
   $page->MxBloc('images', 'loop');
}

?>