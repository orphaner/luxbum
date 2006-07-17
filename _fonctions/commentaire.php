<?php

  //------------------------------------------------------------------------------
  // Include
  //------------------------------------------------------------------------------
include (FONCTIONS_DIR.'luxbum.class.php');


//------------------------------------------------------------------------------
// Parsing des paramètres
//------------------------------------------------------------------------------
if (!isset($_GET['photo']) || !isset($_GET['d'])) {
   exit('manque des paramètres ON');
}
$file          = $_GET['photo'];
$dir           = $_GET['d'];

if (SHOW_COMMENTAIRE == 'off') {
   exit ('Les commentaires ne sont pas activés !');
}
else if (!verif::dir ($dir)) {
   exit ('nom de dossier incorrect !!');
}
//else if (!is_dir (luxbum::getDirPath ($dir))) {
//   exit ('dossier incorrect !!');
//}
else if (!verif::photo ($dir, $file)) {
   exit ('nom de la photo incorrect !!');
}

//------------------------------------------------------------------------------
// Init
//------------------------------------------------------------------------------
$page = new ModeliXe ('commentaire.mxt');
$page->SetModeliXe();
remplir_style ($page);
definir_titre ($page, 'Commentaires');
$mysql = new MysqlInc (DBL_HOST, DBL_LOGIN, DBL_PASSWORD, DBL_NAME);
$mysql->DbConnect ();


//------------------------------------------------------------------------------
// Affichage du formulaire d'ajout de commentaire
//------------------------------------------------------------------------------
$page->WithMxPath ('', 'relative');
$com = new Commentaire ();
if (isset ($_GET['valid']) && $_GET['valid'] == 1) {
   $com->fillFromPost ();

   if ($com->isValidForm ()) {
      $com->setGalerie ($dir);
      $com->setPhoto ($file);
      $com->insertRow ();
   }
   else {
      $page->MxAttribut ('val_auteur', unprotege_input($com->getAuteur()));
      $page->MxAttribut ('val_site', $com->getSite());
      $page->MxAttribut ('val_email', $com->getEmail());
      $page->MxText ('val_content',  unprotege_input($com->getContent()));

      $page->MxText ('err_auteur', $com->getErreur ('auteur'));
      $page->MxText ('err_site', $com->getErreur ('site'));
      $page->MxText ('err_email', $com->getErreur ('email'));
      $page->MxText ('err_content', $com->getErreur ('content'));
   }
}
else {
   $page->MxText ('val_content', '');
}

$page->MxAttribut ('action', INDEX_FILE.'?p=comment&d='.$dir.'&photo='.$file.'&valid=1');



//------------------------------------------------------------------------------
// Affichage des commentaires
//------------------------------------------------------------------------------
$lux = new luxBumImage ($dir, $file);

// Compte le nombre de commentaires
if ($lux->getNbComment() > 0) {

   // Sélection des commenaires
   $sql = "SELECT id_comment, date_comment, auteur_comment, email_comment, site_comment, content_comment "
      ."FROM ".DBL_PREFIX."commentaire "
      ."WHERE galerie_comment='$dir' AND photo_comment='$file' AND pub_comment='1'";
   $res = $mysql->DbQuery ($sql);
   $page->WithMxPath ('comments', 'relative');

   // Affichage des commentaires
   while ($row = $mysql->DbNextRow ($res)) {
      //print_r ($row);
      $page->MxText('date', $row['date_comment']);
      $page->MxText('auteur', $row['auteur_comment']);
      $page->MxText('content', nl2br ($row['content_comment']));
      if ($row['email_comment'] == '') {
         $page->MxBloc('email', 'delete');
      }
      else {
         $page->MxUrl ('email.lien', 'mailto:'.$row['email_comment']);
      }
      if ($row['site_comment'] == '') {
         $page->MxBloc('site', 'delete');
      }
      else {
         $page->MxUrl ('site.lien', $row['site_comment']);
      }
      $page->MxBloc ('', 'loop');
   }
}
else {
   $page->MxBloc ('comments', 'modify', 'Pas de commentaires');
}




$page->MxWrite ();
$mysql->DbClose();

?>