<?php

include_once (FONCTIONS_DIR.'luxbum.class.php');
include_once (FONCTIONS_DIR.'utils/formulaires.php');

$f = '';
if (isset($_GET['f'])) {
   $f = $_GET['f'];
}

function tri_select () {
   return array ('type=nom&order=asc'    => 'Nom (croissant)',
                 'type=nom&order=desc'   => 'Nom (décroissant)',
                 'type=count&order=asc'  => 'Nombre de photos (croissant)',
                 'type=count&order=desc' => 'Nombre de photos (décroissant)',
                 'type=size&order=asc'   => 'Taille (croissant)',
                 'type=size&order=desc'  => 'Taille (décroissant)',
                 'type=manuel&order=asc' => 'Manuel');
}


//------------------------------------------------------------------------------
// Init
//------------------------------------------------------------------------------
$str_critere = ADMIN_FILE.'?p=liste_galeries';

// Création de l'objet contenant l'index
$nuxIndex = new  luxBumIndex ('');
$nuxIndex->addAllGallery (0);


// Page modelixe
definir_titre ($page, 'Liste des galeries - LuxBum Manager');
$page->MxAttribut ('class_galeries', 'actif');
$page->MxBloc ('main', 'modify', ADMIN_STRUCTURE_DIR.'liste_galeries.mxt');
$page->WithMxPath ('main', 'relative');
$page->MxUrl ('action_vider_cache', $str_critere.'&amp;f=vider_cache');
$page->MxAttribut ('action_ajout_galerie', $str_critere.'&amp;f=ajout_galerie');

// Tri
if (isset ($_POST['sortby'])) {
   $selectedSortBy = $_POST['sortby'];
}
else {
   $selectedSortBy = 'type='.$nuxIndex->getSortType().'&order='.$nuxIndex->getSortOrder();
}
$page->MxSelect('tri', 'sortby', $selectedSortBy, tri_select());
$page->MxAttribut('action_tri', $str_critere.'&amp;f=tri');
$page->MxUrl ('triUrl', ADMIN_FILE.'?p=tri_index');



// Titre et h1 de la page 
// $page->MxText ('nom_galerie', NOM_GALERIE);
// definir_titre ($page, NOM_GALERIE);



//------------------------------------------------------------------------------
// Les actions
//------------------------------------------------------------------------------

// Vider le cache de toutes les galeries
if ($f == 'vider_cache') {
   while (list (,$gallery) = each ($nuxIndex->galleryList)) {
      $name     = $gallery->getName ();
      $toDel = new luxBumGallery($name);
      $toDel->addAllImages ();
      $toDel->clearCache ();
      unset ($todel);
   }

   reset ($nuxIndex->galleryList);

   $page->MxText ('message', 'Le cache de toutes les galeries a été vidé');
}

// Créer une nouvelle galerie
else if ($f == 'ajout_galerie') {
   $err_vide = false;
   $ajout_galerie = '';
   get_post ('ajout_galerie', $ajout_galerie);
   verif_non_vide ('ajout_galerie', $ajout_galerie);

   if ($err_vide == false) {
      $path = $nuxIndex->getDirPath ($ajout_galerie);
      if (!verif_dir ($ajout_galerie)) {
         $page->MxAttribut ('val_ajout_galerie', unprotege_input ($ajout_galerie));
         $page->MxText ('err_ajout_galerie', 'Seul Les caractères alphanumériques et "_" sont autorisés');
      }
      else if (is_dir ($path)) {
         $page->MxAttribut ('val_ajout_galerie', unprotege_input ($ajout_galerie));
         $page->MxText ('err_ajout_galerie', 'La galerie '.unprotege_input ($ajout_galerie).' existe déjà. ' .
               'Veuillez choisir un autre nom.');
      }
      else {
         if (files::createDir ($path)) {
            $page->MxText ('message', 'La galerie '.unprotege_input ($ajout_galerie).' a bien été créée. ' .
                  '<br /> Vous pouvez maintenant y rajouter vos photos.');
            unset ($nuxIndex);
            $nuxIndex = new  luxBumIndex ();
            $nuxIndex->addAllGallery (0);
         }
         else {
            $page->MxText ('message', '<span class="erreur">La galerie '.unprotege_input ($ajout_galerie).' n\'a pas été créée. <br /> ' .
                  'Le répertoire '.PHOTOS_DIR.' n\'est probablement pas accessible en écriture.</span>');
         }
      }
   }
}

// Modifie une galerie
else if ($f == 'modifier_galerie') {
   $err_modifier_galerie = '';
   $modifier_galerie = '';
   $dir = '';

   if (isset ($_GET['d'])) {
      $dir = $_GET['d'];

      if (isset ($_POST['modifier_galerie'])) {
         $modifier_galerie = $_POST['modifier_galerie'];
      }

      if ($modifier_galerie == '') {
         $err_modifier_galerie = 'Champ vide !!';
      }
      else if (!ereg ('^([a-zA-Z0-9_]){1,}$', $modifier_galerie)) {
         $err_modifier_galerie = 'Seul Les caractères alphanumériques et "_" sont autorisés';
      }
      else if (!is_dir ($nuxIndex->getDirPath ($dir))) {
         $err_modifier_galerie = 'Erreur !!';
      }
      else {
         $galRename = new luxBumGallery ($dir);
         if ($galRename->rename($modifier_galerie) == false) {
            $err_modifier_galerie = 'Nom déjà utilisé !!';
         }
         else {
            unset ($nuxIndex);
            $nuxIndex = new  luxBumIndex ();
            $nuxIndex->addAllGallery (0);
            $page->MxText ('message', 'La galerie '.unprotege_input ($dir).' a ' .
                  'bien été renommée en '.unprotege_input ($modifier_galerie));
         }
      }
   }
}

// Supprime une galerie
else if ($f == 'del') {
   if (isset ($_GET['d'])) {
      $dir = $_GET['d'];
      if (!verif_dir ($dir)) {
         $page->MxText ('message', '<span class="erreur">Nom de dossier incorrect pour la suppression !!</span>');
      }
      else {
         $dirName = $nuxIndex->getDirPath ($dir);
         if (!files::isDeletable ($dirName)) {
            $page->MxText ('message', '<span class="erreur">Droits insuffisants pour supprimer le dossier !!</span>');
         }
         else {
            luxBumGallery::delete($dir);
            unset ($nuxIndex);
            $nuxIndex = new  luxBumIndex ();
            $nuxIndex->addAllGallery (0);
            $page->MxText ('message', 'La galerie '.unprotege_input ($dirName).' a bien été supprimée');
         }
      }
   }
}

// Choix du tri
else if ($f == 'tri') {
   if (isset($_POST['sortby'])) {
      parse_str($_POST['sortby'], $order);
      $nuxIndex->setSortType($order['type']);
      $nuxIndex->setSortOrder($order['order']);
      $nuxIndex->saveSort();

      unset ($nuxIndex);
      $nuxIndex = new  luxBumIndex ();
      $nuxIndex->addAllGallery (0);
   }
}


//------------------------------------------------------------------------------
// Code principal
//------------------------------------------------------------------------------


// Galerie vide
if ($nuxIndex->getGalleryCount () == 0) {
   $page->MxBloc ('liste', 'modify', STRUCTURE_DIR.'index_vide.mxt');
}

// Affichage de l'index des galeries
else {
   $page->WithMxPath ('liste', 'relative');

   // Parcours des galeries
   while (list (,$gallery) = each ($nuxIndex->galleryList)) {
      $niceName = $gallery->getNiceName ();
      $name     = $gallery->getName ();
      $count    = $gallery->getCount ();
      $taille   = $gallery->getNiceSize ();

      if ($gallery->getCount () > 0) {
         $thumb    = $gallery->getIndexLink();
      }
      else {
         $thumb = '_images/manager/vide.png';
      }

      $page->MxText ('nb_photo', $count);
      $page->MxText ('taille', $taille);
      $page->MxAttribut ('galerie_id', $name);
      $page->MxText ('nom', $niceName);
      $page->MxAttribut ('alt', $niceName);
      $page->MxAttribut ('apercu', $thumb);
      $page->MxUrl ('lien', ADMIN_FILE.'?p=galerie&amp;d='.$name.'&amp;page=0');

      $page->MxUrl ('del', "javascript:if(window.confirm('La suppression de cette galerie entrainera la suppression de tous les commentaires qui lui sont associés. Etes-vous sûr de vouloir supprimer cette galerie ?')) window.location='".$str_critere.'&amp;d='.$name.'&amp;f=del\';');

      $page->MxAttribut ('action_modifier_galerie',  $str_critere.'&amp;d='.$name.'&amp;f=modifier_galerie#'.$name);
      $page->MxAttribut ('id_id', 'mg_'.$name);
      $page->MxAttribut ('for_id', 'mg_'.$name);

      if ($f == 'modifier_galerie' && $dir == $name && $err_modifier_galerie != '') {
         $page->MxAttribut ('val_modifier_galerie', $modifier_galerie);
         $page->MxText ('err_modifier_galerie', $err_modifier_galerie);
      }
      else {
         $page->MxAttribut ('val_modifier_galerie', $name);
      }

      $page->MxBloc ('', 'loop');
   }
}

?>