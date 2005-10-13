<?php

  //------------------------------------------------------------------------------
  // Init
  //------------------------------------------------------------------------------
  // Page modelixe
definir_titre ($page, 'Outils : Diagnostic - LuxBum Manager');
$page->MxAttribut ('class_outils', 'actif');
$page->MxBloc ('main', 'modify', ADMIN_STRUCTURE_DIR.'outils/diagnostic.mxt');
$page->WithMxPath ('main', 'relative');


//------------------------------------------------------------------------------
// Includes
//------------------------------------------------------------------------------
include (FONCTIONS_DIR.'luxbum.class.php');
include (CONF_DIR.'version.php');


//------------------------------------------------------------------------------
// Fcontions
//------------------------------------------------------------------------------
function performVerifFile ($elt, &$page) {
   if (is_link ($elt)) {
      $elt = readlink ($elt);
   }

   if (is_dir ($elt)) {
      $type = 'répertoire';
   }
   else {
      $type = 'fichier';
   }

   if (is_file ($elt) || is_dir ($elt)) {
      if (files::isWritable ($elt)) {
         $page->MxImage ('perms.img_check', '_images/manager/check_on.png');
         $page->MxText ('perms.check', 'Le '.$type.' <strong>'.$elt.'</strong> est accessible en écriture');
      }
      else {
         $page->MxImage ('perms.img_check', '_images/manager/check_off.png');
         $page->MxText ('perms.check', 'Le '.$type.' <strong>'.$elt.'</strong> n\'est pas accessible en écriture');
      }
   }
   else {
      $page->MxImage ('perms.img_check', '_images/manager/check_off.png');
      $page->MxText ('perms.check', '<strong>'.$elt.'</strong> n\'existe pas');
   }
   $page->MxBloc ('perms', 'loop');
}

$page->MxText ('luxbum_version', $luxbum_version);

$page->MxText ('php_version', phpversion ());
$page->MxText ('web_server', $_SERVER['SERVER_SOFTWARE']);
$page->MxText ('gd_version', imagetoolkit::gdVersionExact ());



performVerifFile (PHOTOS_DIR, $page);
performVerifFile (CONF_DIR.'config.php', $page);
performVerifFile (CONF_DIR.'config_manager.php', $page);



// Création de l'objet contenant l'index
$nuxIndex = new  luxBumIndex ();
$nuxIndex->addAllGallery (0);
$nuxIndex->gallerySort ();


while (list (,$gallery) = each ($nuxIndex->galleryList)) {
   $name     = $gallery->getNiceName ();
   $count    = $gallery->getCount ();
   $taille   = $gallery->getNiceSize ();

   $page->MxText ('galeries.nom', $name);
   $page->MxText ('galeries.nb_photo', $count);
   $page->MxText ('galeries.taille', $taille);
   $page->MxBloc ('galeries', 'loop');
}

// echo "<b>Total disk space : </b>".round(disk_total_space(".")/1000000000,5)." Go<br>"; 
// echo "<b>Free disk space : </b>".round(disk_free_space(".")/1000000000,5)." Go<br><br><br></center>";
?>